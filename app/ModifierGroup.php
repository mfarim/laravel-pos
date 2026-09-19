<?php

namespace App;

use App\Traits\BelongsToTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class ModifierGroup extends Model
{
    use HasUuid, BelongsToTenant;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'name',
        'is_required',
        'min_selections',
        'max_selections',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'min_selections' => 'integer',
        'max_selections' => 'integer',
    ];

    public function modifiers()
    {
        return $this->hasMany(Modifier::class, 'modifier_group_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_modifier_groups', 'modifier_group_id', 'product_id');
    }
}
