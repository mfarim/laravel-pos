<?php

namespace App;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'name',
        'symbol',
        'is_base_unit',
    ];

    protected $casts = [
        'is_base_unit' => 'boolean',
    ];

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class, 'unit_id');
    }
}
