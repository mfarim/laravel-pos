<?php

namespace App\Http\Controllers\Api\V1;

use App\HeldOrder;
use App\Outlet;
use App\PosSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HeldOrderController extends ApiController
{
    /**
     * List held orders for current outlet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $outletId = $this->resolveAuthorizedOutletId($request);
        $query = HeldOrder::latest('id');

        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }

        $heldOrders = $query->get();

        return $this->successResponse($heldOrders, 'Daftar pesanan ditahan berhasil diambil');
    }

    /**
     * Save/hold current cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items'         => 'required|array|min:1',
            'customer_name' => 'nullable|string',
            'subtotal'      => 'required|numeric|min:0',
            'grand_total'   => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal', 422, $validator->errors());
        }

        $outletId = $this->resolveAuthorizedOutletId($request);
        $session = PosSession::where('outlet_id', $outletId)->where('status', 'open')->latest('id')->first();
        $user = Auth::user();

        $count = HeldOrder::where('outlet_id', $outletId)->whereDate('created_at', Carbon::today())->count() + 1;
        $holdNumber = sprintf('HOLD-%03d', $count);

        $heldOrder = HeldOrder::create([
            'tenant_id'       => $user->tenant_id,
            'outlet_id'       => $outletId,
            'pos_session_id'  => $session ? $session->id : null,
            'user_id'         => $user->id,
            'hold_number'     => $holdNumber,
            'customer_name'   => $request->input('customer_name'),
            'items'           => $request->input('items'),
            'subtotal'        => (float) $request->input('subtotal'),
            'discount_amount' => (float) $request->input('discount_amount', 0),
            'tax_amount'      => (float) $request->input('tax_amount', 0),
            'grand_total'     => (float) $request->input('grand_total'),
            'notes'           => $request->input('notes'),
        ]);

        return $this->successResponse($heldOrder, 'Pesanan berhasil ditahan', 201);
    }

    /**
     * Delete/recall a held order.
     *
     * @param  string|int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $heldOrder = HeldOrder::where(function ($q) use ($id) {
            $q->where('id', $id)->orWhere('uuid', $id);
        })->first();

        if (!$heldOrder) {
            return $this->errorResponse('Pesanan ditahan tidak ditemukan', 404);
        }

        $user = Auth::user();
        if ($user && !$user->isSuperAdmin() && !$user->hasOutlet($heldOrder->outlet_id)) {
            return $this->errorResponse('Akses terhadap pesanan cabang ini ditolak.', 403);
        }

        $heldOrder->delete();

        return $this->successResponse(null, 'Pesanan ditahan berhasil dihapus/dipanggil');
    }

    protected function resolveOutletId(Request $request)
    {
        return $this->resolveAuthorizedOutletId($request);
    }
}
