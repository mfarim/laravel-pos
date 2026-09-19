<?php

namespace App;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasUuid;

    protected $fillable = [
        'uuid',
        'transaction_id',
        'product_id',
        'product_variant_id',
        'inventory_item_id',
        'item_name',
        'item_sku',
        'quantity',
        'unit_price',
        'cost_price',
        'discount_amount',
        'subtotal',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'float',
        'unit_price' => 'float',
        'cost_price' => 'float',
        'discount_amount' => 'float',
        'subtotal' => 'float',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
