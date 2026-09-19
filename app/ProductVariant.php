<?php

namespace App;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasUuid;

    protected $fillable = [
        'uuid',
        'product_id',
        'sku',
        'barcode',
        'name',
        'price_adjustment',
        'inventory_item_id',
        'is_active',
    ];

    protected $casts = [
        'price_adjustment' => 'float',
        'is_active' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
