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
        $outletId = $this->resolveOutletId($request);
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

        $outletId = $this->resolveOutletId($request);
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

        $heldOrder->delete();

        return $this->successResponse(null, 'Pesanan ditahan berhasil dihapus/dipanggil');
    }

    protected function resolveOutletId(Request $request)
    {
        if ($request->has('current_outlet_id')) {
            return $request->get('current_outlet_id');
        }
        if ($request->has('outlet_id')) {
            $val = $request->input('outlet_id');
            $outlet = Outlet::where('id', $val)->orWhere('uuid', $val)->first();
            return $outlet ? $outlet->id : null;
        }
        $user = Auth::user();
        $defaultOutlet = $user ? $user->defaultOutlet() : null;
        return $defaultOutlet ? $defaultOutlet->id : null;
    }
}
