<?php

namespace App\Http\Controllers\Api\V1;

use App\InventoryItem;
use App\InventoryStock;
use App\Outlet;
use App\PaymentMethod;
use App\PosSession;
use App\Product;
use App\StockMovement;
use App\Transaction;
use App\TransactionItem;
use App\TransactionPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MobileSyncController extends ApiController
{
    /**
     * Sync offline transactions captured by mobile app.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncTransactions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transactions' => 'required|array|min:1',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal', 422, $validator->errors());
        }

        $outletId = $this->resolveAuthorizedOutletId($request);
        if (!$outletId) {
            return $this->errorResponse('Outlet ID diperlukan', 400);
        }

        $outlet = Outlet::find($outletId);
        if (!$outlet) {
            return $this->errorResponse('Outlet tidak ditemukan', 404);
        }

        $user = Auth::user();
        $synced = [];
        $errors = [];

        foreach ($request->input('transactions') as $idx => $tData) {
            $offlineId = isset($tData['offline_id']) ? $tData['offline_id'] : 'offline_' . $idx;

            // Check if already synced via notes or reference
            $existing = Transaction::where('tenant_id', $user->tenant_id)
                ->where('outlet_id', $outlet->id)
                ->where('notes', 'like', "%offline_id:{$offlineId}%")
                ->first();

            if ($existing) {
                $synced[] = [
                    'offline_id'         => $offlineId,
                    'status'             => 'already_synced',
                    'transaction_number' => $existing->transaction_number,
                    'id'                 => $existing->id,
                ];
                continue;
            }

            try {
                $trx = null;
                DB::transaction(function () use (&$trx, $user, $outlet, $tData, $offlineId) {
                    $todayStr = Carbon::now()->format('Ymd');
                    $count = Transaction::where('outlet_id', $outlet->id)->whereDate('created_at', Carbon::today())->count() + 1;
                    $trxNumber = sprintf('%s-%s-%04d', $outlet->code, $todayStr, $count);

                    $items = isset($tData['items']) ? $tData['items'] : [];
                    $payments = isset($tData['payments']) ? $tData['payments'] : [];

                    $subtotal = 0;
                    foreach ($items as $item) {
                        $qty = (float) $item['quantity'];
                        $price = (float) $item['unit_price'];
                        $disc = isset($item['discount_amount']) ? (float) $item['discount_amount'] : 0;
                        $subtotal += ($qty * $price) - $disc;
                    }

                    $orderDiscount = isset($tData['discount_amount']) ? (float) $tData['discount_amount'] : 0;
                    $taxRate = $outlet->effective_tax_rate;
                    $taxable = max(0, $subtotal - $orderDiscount);
                    $taxAmount = round($taxable * ($taxRate / 100), 2);
                    $grandTotal = round($taxable + $taxAmount, 2);

                    $totalPayment = 0;
                    foreach ($payments as $p) {
                        $totalPayment += (float) $p['amount'];
                    }
                    $change = max(0, $totalPayment - $grandTotal);

                    $trx = Transaction::create([
                        'tenant_id'          => $user->tenant_id,
                        'outlet_id'          => $outlet->id,
                        'user_id'            => $user->id,
                        'customer_name'      => isset($tData['customer_name']) ? $tData['customer_name'] : null,
                        'transaction_number' => $trxNumber,
                        'type'               => 'sale',
                        'subtotal'           => $subtotal,
                        'discount_amount'    => $orderDiscount,
                        'tax_percentage'     => $taxRate,
                        'tax_amount'         => $taxAmount,
                        'grand_total'        => $grandTotal,
                        'payment_amount'     => $totalPayment,
                        'change_amount'      => $change,
                        'notes'              => (isset($tData['notes']) ? $tData['notes'] . ' | ' : '') . "offline_id:{$offlineId}",
                        'status'             => 'completed',
                        'completed_at'       => isset($tData['completed_at']) ? Carbon::parse($tData['completed_at']) : Carbon::now(),
                    ]);

                    foreach ($items as $item) {
                        $product = Product::find($item['product_id']);
                        $qty = (float) $item['quantity'];
                        $price = (float) $item['unit_price'];
                        $disc = isset($item['discount_amount']) ? (float) $item['discount_amount'] : 0;

                        TransactionItem::create([
                            'transaction_id'    => $trx->id,
                            'product_id'        => $product ? $product->id : null,
                            'inventory_item_id' => $product ? $product->inventory_item_id : null,
                            'item_name'         => $product ? $product->name : 'Item',
                            'item_sku'          => $product ? $product->sku : null,
                            'quantity'          => $qty,
                            'unit_price'        => $price,
                            'cost_price'        => $product ? (float) $product->cost_price : 0,
                            'discount_amount'   => $disc,
                            'subtotal'          => ($qty * $price) - $disc,
                        ]);

                        if ($product && $product->track_stock && $product->inventory_item_id) {
                            $stock = InventoryStock::firstOrCreate([
                                'tenant_id'         => $user->tenant_id,
                                'outlet_id'         => $outlet->id,
                                'inventory_item_id' => $product->inventory_item_id,
                            ], ['quantity' => 0]);

                            $before = (float) $stock->quantity;
                            $after = $before - $qty;
                            $stock->quantity = $after;
                            $stock->save();

                            StockMovement::create([
                                'tenant_id'         => $user->tenant_id,
                                'outlet_id'         => $outlet->id,
                                'inventory_item_id' => $product->inventory_item_id,
                                'user_id'           => $user->id,
                                'type'              => 'sale',
                                'before_quantity'   => $before,
                                'quantity'          => -$qty,
                                'after_quantity'    => $after,
                                'reference_type'    => 'TransactionSync',
                                'reference_id'      => $trxNumber,
                            ]);
                        }
                    }

                    foreach ($payments as $p) {
                        TransactionPayment::create([
                            'transaction_id'    => $trx->id,
                            'payment_method_id' => $p['payment_method_id'],
                            'amount'            => (float) $p['amount'],
                            'reference_number'  => isset($p['reference_number']) ? $p['reference_number'] : null,
                        ]);
                    }
                });

                $synced[] = [
                    'offline_id'         => $offlineId,
                    'status'             => 'synced',
                    'transaction_number' => $trx->transaction_number,
                    'id'                 => $trx->id,
                ];
            } catch (\Exception $e) {
                $errors[] = [
                    'offline_id' => $offlineId,
                    'error'      => $e->getMessage(),
                ];
            }
        }

        return $this->successResponse([
            'synced' => $synced,
            'errors' => $errors,
        ], 'Sinkronisasi transaksi offline selesai diproses');
    }
}
