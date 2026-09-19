<?php

namespace App;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Modifier extends Model
{
    use HasUuid;

    protected $fillable = [
        'uuid',
        'modifier_group_id',
        'name',
        'price',
        'inventory_item_id',
    ];

    protected $casts = [
        'price' => 'float',
    ];

    public function group()
    {
        return $this->belongsTo(ModifierGroup::class, 'modifier_group_id');
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }
}
