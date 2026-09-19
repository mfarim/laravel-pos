<?php

namespace App;

use App\Traits\BelongsToTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasUuid, BelongsToTenant;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'category_id',
        'inventory_item_id',
        'sku',
        'barcode',
        'name',
        'slug',
        'description',
        'image',
        'base_price',
        'cost_price',
        'product_type',
        'track_stock',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'base_price' => 'float',
        'cost_price' => 'float',
        'track_stock' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function modifierGroups()
    {
        return $this->belongsToMany(ModifierGroup::class, 'product_modifier_groups', 'product_id', 'modifier_group_id');
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class, 'product_id');
    }
}
