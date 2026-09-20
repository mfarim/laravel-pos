<?php

namespace App\Http\Controllers\Api\V1;

use App\Outlet;
use App\PosSession;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SessionController extends ApiController
{
    /**
     * Get the active shift session for current outlet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function current(Request $request)
    {
        $outletId = $this->resolveAuthorizedOutletId($request);
        if (!$outletId) {
            return $this->errorResponse('Outlet ID diperlukan (sediakan header X-Outlet-Id atau parameter outlet_id)', 400);
        }

        $session = PosSession::where('outlet_id', $outletId)
            ->where('status', 'open')
            ->with(['user', 'outlet'])
            ->latest('id')
            ->first();

        if (!$session) {
            return $this->successResponse([
                'has_active_session' => false,
                'session'            => null,
            ], 'Tidak ada sesi shift kasir yang sedang aktif');
        }

        // Calculate current real-time sales within this session
        $salesSummary = $this->calculateSessionSummary($session);

        return $this->successResponse([
            'has_active_session' => true,
            'session'            => $session,
            'summary'            => $salesSummary,
        ], 'Sesi kasir aktif ditemukan');
    }

    /**
     * Open a new cashier shift session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function open(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'opening_cash'  => 'required|numeric|min:0',
            'opening_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal', 422, $validator->errors());
        }

        $outletId = $this->resolveAuthorizedOutletId($request);
        if (!$outletId) {
            return $this->errorResponse('Outlet ID diperlukan', 400);
        }

        // Ensure there is no open shift already running in this outlet
        $existing = PosSession::where('outlet_id', $outletId)->where('status', 'open')->first();
        if ($existing) {
            return $this->errorResponse('Masih terdapat sesi shift aktif di outlet ini. Harap tutup shift sebelumnya terlebih dahulu.', 400, [
                'active_session' => $existing,
            ]);
        }

        $user = Auth::user();
        $dateStr = Carbon::now()->format('Ymd');
        $count = PosSession::where('outlet_id', $outletId)->whereDate('opened_at', Carbon::today())->count() + 1;
        $sessionNumber = sprintf('SHIFT-%s-%03d', $dateStr, $count);

        $session = PosSession::create([
            'tenant_id'      => $user->tenant_id,
            'outlet_id'      => $outletId,
            'user_id'        => $user->id,
            'session_number' => $sessionNumber,
            'opening_cash'   => (float) $request->input('opening_cash'),
            'opening_notes'  => $request->input('opening_notes'),
            'opened_at'      => Carbon::now(),
            'status'         => 'open',
        ]);

        return $this->successResponse($session, 'Shift kasir berhasil dibuka', 201);
    }

    /**
     * Close an active shift session and reconcile cash drawer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function close(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'closing_cash'  => 'required|numeric|min:0',
            'closing_notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal', 422, $validator->errors());
        }

        $outletId = $this->resolveAuthorizedOutletId($request);
        $session = PosSession::where('outlet_id', $outletId)->where('status', 'open')->latest('id')->first();

        if (!$session) {
            return $this->errorResponse('Tidak ada sesi shift aktif untuk ditutup', 404);
        }

        $user = Auth::user();
        $summary = $this->calculateSessionSummary($session);

        $closingCashActual = (float) $request->input('closing_cash');
        $expectedCash = $summary['expected_cash'];
        $discrepancy = $closingCashActual - $expectedCash;

        $session->update([
            'closing_cash'    => $closingCashActual,
            'expected_cash'   => $expectedCash,
            'cash_difference' => $discrepancy,
            'closing_notes'   => $request->input('closing_notes'),
            'closed_at'       => Carbon::now(),
            'closed_by'       => $user->id,
            'status'          => 'closed',
        ]);

        return $this->successResponse([
            'session' => $session,
            'report'  => $summary,
        ], 'Shift kasir berhasil ditutup');
    }

    /**
     * Get report for a specific shift session.
     *
     * @param  string|int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function report($id)
    {
        $session = PosSession::where(function ($q) use ($id) {
            $q->where('id', $id)->orWhere('uuid', $id);
        })->with(['user', 'outlet', 'closedByUser'])->first();

        if (!$session) {
            return $this->errorResponse('Sesi shift tidak ditemukan', 404);
        }

        $user = Auth::user();
        if ($user && !$user->isSuperAdmin() && !$user->hasOutlet($session->outlet_id)) {
            return $this->errorResponse('Akses terhadap laporan shift cabang ini ditolak.', 403);
        }

        $summary = $this->calculateSessionSummary($session);

        return $this->successResponse([
            'session' => $session,
            'summary' => $summary,
        ], 'Laporan shift berhasil diambil');
    }

    /**
     * Helper to calculate transaction summary within a session.
     */
    protected function calculateSessionSummary(PosSession $session)
    {
        $transactions = Transaction::where('pos_session_id', $session->id)
            ->where('status', 'completed')
            ->with('payments.paymentMethod')
            ->get();

        $totalSales = $transactions->sum('grand_total');
        $totalTax = $transactions->sum('tax_amount');
        $totalDiscount = $transactions->sum('discount_amount');

        $cashSales = 0;
        $nonCashSales = 0;
        $paymentBreakdown = [];

        foreach ($transactions as $trx) {
            foreach ($trx->payments as $pay) {
                $methodCode = $pay->paymentMethod ? $pay->paymentMethod->code : 'other';
                $isCash = $pay->paymentMethod ? $pay->paymentMethod->is_cash : false;

                if (!isset($paymentBreakdown[$methodCode])) {
                    $paymentBreakdown[$methodCode] = [
                        'name'   => $pay->paymentMethod ? $pay->paymentMethod->name : 'Lainnya',
                        'amount' => 0,
                    ];
                }
                $paymentBreakdown[$methodCode]['amount'] += (float) $pay->amount;

                if ($isCash || $methodCode === 'cash') {
                    $cashSales += (float) $pay->amount;
                } else {
                    $nonCashSales += (float) $pay->amount;
                }
            }
        }

        $openingCash = (float) $session->opening_cash;
        $expectedCash = $openingCash + $cashSales;

        return [
            'total_transactions' => $transactions->count(),
            'total_sales'        => (float) $totalSales,
            'total_tax'          => (float) $totalTax,
            'total_discount'     => (float) $totalDiscount,
            'opening_cash'       => $openingCash,
            'cash_sales'         => $cashSales,
            'non_cash_sales'     => $nonCashSales,
            'expected_cash'      => $expectedCash,
            'payment_breakdown'  => array_values($paymentBreakdown),
        ];
    }

    protected function resolveOutletId(Request $request)
    {
        return $this->resolveAuthorizedOutletId($request);
    }
}
