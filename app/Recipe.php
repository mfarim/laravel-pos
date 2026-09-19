<?php

namespace App;

use App\Traits\BelongsToTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasUuid, BelongsToTenant;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'product_id',
        'product_variant_id',
        'name',
        'yield_quantity',
    ];

    protected $casts = [
        'yield_quantity' => 'float',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function items()
    {
        return $this->hasMany(RecipeItem::class, 'recipe_id');
    }
}
