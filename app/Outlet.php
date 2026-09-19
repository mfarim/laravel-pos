<?php

namespace App;

use App\Traits\BelongsToTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasUuid, BelongsToTenant;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'code',
        'name',
        'address',
        'city',
        'phone',
        'email',
        'opening_time',
        'closing_time',
        'tax_percentage',
        'service_charge_percentage',
        'receipt_header',
        'receipt_footer',
        'receipt_show_logo',
        'is_active',
    ];

    protected $casts = [
        'tax_percentage' => 'float',
        'service_charge_percentage' => 'float',
        'receipt_show_logo' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_outlets', 'outlet_id', 'user_id')
            ->withPivot('is_default')
            ->withTimestamps();
    }

    public function sessions()
    {
        return $this->hasMany(PosSession::class, 'outlet_id');
    }

    public function activeSession()
    {
        return $this->hasOne(PosSession::class, 'outlet_id')
            ->where('status', 'open')
            ->latest('id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'outlet_id');
    }

    public function stocks()
    {
        return $this->hasMany(InventoryStock::class, 'outlet_id');
    }

    /**
     * Get effective tax percentage (outlet override or tenant default).
     */
    public function getEffectiveTaxRateAttribute()
    {
        if ($this->tax_percentage !== null) {
            return (float) $this->tax_percentage;
        }
        return $this->tenant ? (float) $this->tenant->tax_percentage : 11.00;
    }
}
