<?php

namespace App;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasUuid;

    protected $fillable = [
        'uuid',
        'code',
        'name',
        'slug',
        'logo',
        'email',
        'phone',
        'address',
        'currency',
        'timezone',
        'tax_percentage',
        'tax_mode',
        'tax_enabled',
        'service_charge_percentage',
        'service_charge_enabled',
        'trial_used',
        'is_active',
    ];

    protected $casts = [
        'tax_percentage' => 'float',
        'tax_enabled' => 'boolean',
        'service_charge_percentage' => 'float',
        'service_charge_enabled' => 'boolean',
        'trial_used' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function outlets()
    {
        return $this->hasMany(Outlet::class, 'tenant_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'tenant_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'tenant_id');
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class, 'tenant_id')
            ->whereIn('status', [Subscription::STATUS_TRIAL, Subscription::STATUS_ACTIVE, Subscription::STATUS_GRACE_PERIOD])
            ->latest('id');
    }

    public function isFrozen()
    {
        $sub = $this->activeSubscription;
        if (!$sub) {
            // Check if there is an explicit frozen subscription
            return $this->subscriptions()->where('status', Subscription::STATUS_FROZEN)->exists();
        }
        return $sub->status === Subscription::STATUS_FROZEN;
    }

    public function hasFeature($featureKey)
    {
        $sub = $this->activeSubscription;
        if (!$sub || !$sub->plan) {
            return false;
        }

        $features = $sub->plan->features;
        if (is_string($features)) {
            $features = json_decode($features, true) ?: [];
        }

        if (is_array($features)) {
            // Can be array of keys ['pos_core', 'inventory_basic'] or map ['pos_core' => true]
            if (isset($features[$featureKey])) {
                return (bool) $features[$featureKey];
            }
            return in_array($featureKey, $features, true);
        }

        return false;
    }
}
