<?php

namespace App\Http\Controllers\Api\V1;

use App\InventoryItem;
use App\InventoryStock;
use App\Outlet;
use App\PaymentMethod;
use App\PosSession;
use App\Product;
use App\ProductVariant;
use App\StockMovement;
use App\Transaction;
use App\TransactionItem;
use App\TransactionPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransactionController extends ApiController
{
    /**
     * Calculate cart totals (subtotal, taxes, discounts, grand total).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items'             => 'required|array|min:1',
            'items.*.quantity'  => 'required|numeric|min:0.01',
            'items.*.unit_price'=> 'required|numeric|min:0',
            'discount_amount'   => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal', 422, $validator->errors());
        }

        $outletId = $this->resolveOutletId($request);
        $outlet = $outletId ? Outlet::find($outletId) : null;
        $taxRate = $outlet ? $outlet->effective_tax_rate : 11.00;

        $subtotal = 0;
        foreach ($request->input('items') as $item) {
            $qty = (float) $item['quantity'];
            $price = (float) $item['unit_price'];
            $itemDiscount = isset($item['discount_amount']) ? (float) $item['discount_amount'] : 0;
            $subtotal += ($qty * $price) - $itemDiscount;
        }

        $orderDiscount = (float) $request->input('discount_amount', 0);
        $taxableAmount = max(0, $subtotal - $orderDiscount);
        $taxAmount = round($taxableAmount * ($taxRate / 100), 2);
        $grandTotal = round($taxableAmount + $taxAmount, 2);

        return $this->successResponse([
            'subtotal'        => (float) $subtotal,
            'discount_amount' => (float) $orderDiscount,
            'tax_percentage'  => (float) $taxRate,
            'tax_amount'      => (float) $taxAmount,
            'grand_total'     => (float) $grandTotal,
        ], 'Kalkulasi keranjang berhasil');
    }

    /**
     * Checkout and create transaction with stock deduction.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items'                      => 'required|array|min:1',
            'items.*.product_id'         => 'required',
            'items.*.quantity'           => 'required|numeric|min:0.01',
            'items.*.unit_price'         => 'required|numeric|min:0',
            'payments'                   => 'required|array|min:1',
            'payments.*.payment_method_id' => 'required',
            'payments.*.amount'          => 'required|numeric|min:0.01',
            'customer_name'              => 'nullable|string',
            'notes'                      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi data checkout gagal', 422, $validator->errors());
        }

        $outletId = $this->resolveOutletId($request);
        if (!$outletId) {
            return $this->errorResponse('Outlet ID diperlukan', 400);
        }

        $outlet = Outlet::find($outletId);
        if (!$outlet) {
            return $this->errorResponse('Outlet tidak ditemukan', 404);
        }

        // Must have an open shift session
        $session = PosSession::where('outlet_id', $outletId)->where('status', 'open')->latest('id')->first();
        if (!$session) {
            return $this->errorResponse('Tidak ada shift kasir yang sedang aktif. Harap buka shift terlebih dahulu.', 400);
        }

        $user = Auth::user();
        $itemsData = $request->input('items');
        $paymentsData = $request->input('payments');

        // Calculate totals
        $subtotal = 0;
        foreach ($itemsData as $item) {
            $qty = (float) $item['quantity'];
            $price = (float) $item['unit_price'];
            $itemDiscount = isset($item['discount_amount']) ? (float) $item['discount_amount'] : 0;
            $subtotal += ($qty * $price) - $itemDiscount;
        }

        $orderDiscount = (float) $request->input('discount_amount', 0);
        $taxRate = $outlet->effective_tax_rate;
        $taxable = max(0, $subtotal - $orderDiscount);
        $taxAmount = round($taxable * ($taxRate / 100), 2);
        $grandTotal = round($taxable + $taxAmount, 2);

        $totalPayment = 0;
        foreach ($paymentsData as $p) {
            $totalPayment += (float) $p['amount'];
        }

        if ($totalPayment < $grandTotal) {
            return $this->errorResponse(sprintf('Total pembayaran (Rp %s) kurang dari total belanja (Rp %s)', number_format($totalPayment), number_format($grandTotal)), 400);
        }

        $changeAmount = max(0, $totalPayment - $grandTotal);

        // Generate Transaction Number: OUT01-YYYYMMDD-0001
        $todayStr = Carbon::now()->format('Ymd');
        $count = Transaction::where('outlet_id', $outletId)->whereDate('created_at', Carbon::today())->count() + 1;
        $trxNumber = sprintf('%s-%s-%04d', $outlet->code, $todayStr, $count);

        $transaction = null;

        DB::transaction(function () use (
            &$transaction, $user, $outlet, $session, $trxNumber, $itemsData,
            $paymentsData, $subtotal, $orderDiscount, $taxAmount, $grandTotal,
            $totalPayment, $changeAmount, $taxRate, $request
        ) {
            $transaction = Transaction::create([
                'tenant_id'             => $user->tenant_id,
                'outlet_id'             => $outlet->id,
                'pos_session_id'        => $session->id,
                'user_id'               => $user->id,
                'customer_name'         => $request->input('customer_name'),
                'customer_phone'        => $request->input('customer_phone'),
                'transaction_number'    => $trxNumber,
                'type'                  => 'sale',
                'subtotal'              => $subtotal,
                'discount_amount'       => $orderDiscount,
                'tax_percentage'        => $taxRate,
                'tax_amount'            => $taxAmount,
                'service_charge_amount' => 0,
                'grand_total'           => $grandTotal,
                'payment_amount'        => $totalPayment,
                'change_amount'         => $changeAmount,
                'notes'                 => $request->input('notes'),
                'status'                => 'completed',
                'completed_at'          => Carbon::now(),
            ]);

            // Save line items and deduct stock
            foreach ($itemsData as $item) {
                $product = Product::find($item['product_id']);
                $variantId = isset($item['variant_id']) ? $item['variant_id'] : null;
                $variant = $variantId ? ProductVariant::find($variantId) : null;

                $itemName = $product ? $product->name : 'Item';
                if ($variant) {
                    $itemName .= ' (' . $variant->name . ')';
                }

                $qty = (float) $item['quantity'];
                $unitPrice = (float) $item['unit_price'];
                $costPrice = $product ? (float) $product->cost_price : 0;
                $itemDiscount = isset($item['discount_amount']) ? (float) $item['discount_amount'] : 0;
                $lineSubtotal = ($qty * $unitPrice) - $itemDiscount;

                TransactionItem::create([
                    'transaction_id'     => $transaction->id,
                    'product_id'         => $product ? $product->id : null,
                    'product_variant_id' => $variant ? $variant->id : null,
                    'inventory_item_id'  => $product ? $product->inventory_item_id : null,
                    'item_name'          => $itemName,
                    'item_sku'           => $product ? $product->sku : null,
                    'quantity'           => $qty,
                    'unit_price'         => $unitPrice,
                    'cost_price'         => $costPrice,
                    'discount_amount'    => $itemDiscount,
                    'subtotal'           => $lineSubtotal,
                    'notes'              => isset($item['notes']) ? $item['notes'] : null,
                ]);

                // Deduct stock if product tracks stock
                if ($product && $product->track_stock && $product->inventory_item_id) {
                    $this->deductStock($product->inventory_item_id, $outlet->id, $qty, $transaction, $user->id);
                }
            }

            // Save payments
            foreach ($paymentsData as $p) {
                TransactionPayment::create([
                    'transaction_id'    => $transaction->id,
                    'payment_method_id' => $p['payment_method_id'],
                    'amount'            => (float) $p['amount'],
                    'reference_number'  => isset($p['reference_number']) ? $p['reference_number'] : null,
                ]);
            }
        });

        $transaction->load(['items', 'payments.paymentMethod', 'outlet', 'user']);

        return $this->successResponse($transaction, 'Transaksi berhasil disimpan', 201);
    }

    /**
     * List transactions for current outlet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $outletId = $this->resolveOutletId($request);
        $query = Transaction::where('status', 'completed')
            ->with(['items', 'payments.paymentMethod', 'user'])
            ->latest('id');

        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }

        if ($request->has('date')) {
            $query->whereDate('completed_at', $request->input('date'));
        }

        if ($request->has('pos_session_id')) {
            $query->where('pos_session_id', $request->input('pos_session_id'));
        }

        $transactions = $query->paginate($request->input('per_page', 20));

        return $this->successResponse($transactions, 'Riwayat transaksi berhasil diambil');
    }

    /**
     * Get transaction detail (receipt view).
     *
     * @param  string|int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $trx = Transaction::where(function ($q) use ($id) {
            $q->where('id', $id)->orWhere('uuid', $id);
        })->with(['items', 'payments.paymentMethod', 'outlet', 'user', 'session'])->first();

        if (!$trx) {
            return $this->errorResponse('Transaksi tidak ditemukan', 404);
        }

        return $this->successResponse($trx, 'Detail transaksi berhasil diambil');
    }

    /**
     * Void a transaction and restore inventory stock.
     *
     * @param  string|int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function voidTransaction($id, Request $request)
    {
        $trx = Transaction::where(function ($q) use ($id) {
            $q->where('id', $id)->orWhere('uuid', $id);
        })->with('items')->first();

        if (!$trx) {
            return $this->errorResponse('Transaksi tidak ditemukan', 404);
        }

        if ($trx->status === 'voided') {
            return $this->errorResponse('Transaksi ini sudah dibatalkan/void sebelumnya', 400);
        }

        DB::transaction(function () use ($trx, $request) {
            $trx->status = 'voided';
            $trx->notes = ($trx->notes ? $trx->notes . ' | ' : '') . 'Void reason: ' . $request->input('reason', 'Pembatalan kasir');
            $trx->save();

            // Restore inventory stock
            $user = Auth::user();
            foreach ($trx->items as $item) {
                if ($item->inventory_item_id) {
                    $stock = InventoryStock::firstOrCreate([
                        'tenant_id'         => $trx->tenant_id,
                        'outlet_id'         => $trx->outlet_id,
                        'inventory_item_id' => $item->inventory_item_id,
                    ], ['quantity' => 0]);

                    $before = (float) $stock->quantity;
                    $after = $before + (float) $item->quantity;
                    $stock->quantity = $after;
                    $stock->save();

                    StockMovement::create([
                        'tenant_id'         => $trx->tenant_id,
                        'outlet_id'         => $trx->outlet_id,
                        'inventory_item_id' => $item->inventory_item_id,
                        'user_id'           => $user ? $user->id : null,
                        'type'              => 'adjustment',
                        'before_quantity'   => $before,
                        'quantity'          => (float) $item->quantity,
                        'after_quantity'    => $after,
                        'reference_type'    => 'TransactionVoid',
                        'reference_id'      => $trx->transaction_number,
                        'notes'             => 'Restored from voided transaction',
                    ]);
                }
            }
        });

        return $this->successResponse($trx, 'Transaksi berhasil dibatalkan (void)');
    }

    /**
     * Helper to deduct stock for a sold item.
     */
    protected function deductStock($inventoryItemId, $outletId, $qty, $transaction, $userId)
    {
        $stock = InventoryStock::firstOrCreate([
            'tenant_id'         => $transaction->tenant_id,
            'outlet_id'         => $outletId,
            'inventory_item_id' => $inventoryItemId,
        ], ['quantity' => 0]);

        $before = (float) $stock->quantity;
        $after = $before - (float) $qty;
        $stock->quantity = $after;
        $stock->save();

        StockMovement::create([
            'tenant_id'         => $transaction->tenant_id,
            'outlet_id'         => $outletId,
            'inventory_item_id' => $inventoryItemId,
            'user_id'           => $userId,
            'type'              => 'sale',
            'before_quantity'   => $before,
            'quantity'          => -$qty,
            'after_quantity'    => $after,
            'reference_type'    => 'Transaction',
            'reference_id'      => $transaction->transaction_number,
            'notes'             => 'Penjualan POS',
        ]);
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
