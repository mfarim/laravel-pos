<?php

namespace App;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class AuthorizationSetting extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'require_auth_void',
        'require_auth_refund',
        'require_auth_discount',
        'discount_threshold_percent',
        'require_auth_price_override',
    ];

    protected $casts = [
        'require_auth_void' => 'boolean',
        'require_auth_refund' => 'boolean',
        'require_auth_discount' => 'boolean',
        'discount_threshold_percent' => 'float',
        'require_auth_price_override' => 'boolean',
    ];
}
