<?php

namespace App;

use App\Traits\BelongsToTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasUuid, BelongsToTenant;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'category_id',
        'unit_id',
        'sku',
        'name',
        'cost_price',
        'minimum_stock',
        'is_active',
    ];

    protected $casts = [
        'cost_price' => 'float',
        'minimum_stock' => 'float',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(InventoryCategory::class, 'category_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function stocks()
    {
        return $this->hasMany(InventoryStock::class, 'inventory_item_id');
    }

    public function stockForOutlet($outletId)
    {
        return $this->hasOne(InventoryStock::class, 'inventory_item_id')
            ->where('outlet_id', $outletId);
    }
}
