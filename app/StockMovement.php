<?php

namespace App;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'outlet_id',
        'inventory_item_id',
        'user_id',
        'type',
        'before_quantity',
        'quantity',
        'after_quantity',
        'reference_type',
        'reference_id',
        'notes',
    ];

    protected $casts = [
        'before_quantity' => 'float',
        'quantity' => 'float',
        'after_quantity' => 'float',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
