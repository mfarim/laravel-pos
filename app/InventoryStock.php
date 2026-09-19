<?php

namespace App;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class InventoryStock extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'outlet_id',
        'inventory_item_id',
        'quantity',
        'reserved_quantity',
    ];

    protected $casts = [
        'quantity' => 'float',
        'reserved_quantity' => 'float',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
