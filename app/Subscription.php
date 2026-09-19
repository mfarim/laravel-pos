<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    // Status constants for PHP 7.4
    const STATUS_TRIAL        = 'trial';
    const STATUS_ACTIVE       = 'active';
    const STATUS_GRACE_PERIOD = 'grace_period';
    const STATUS_FROZEN       = 'frozen';
    const STATUS_CANCELLED    = 'cancelled';
    const STATUS_EXPIRED      = 'expired';

    protected $fillable = [
        'tenant_id',
        'subscription_plan_id',
        'billing_cycle',
        'status',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'cancelled_at',
        'cancellation_reason',
    ];

    protected $dates = [
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'cancelled_at',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function invoices()
    {
        return $this->hasMany(SubscriptionInvoice::class, 'subscription_id');
    }

    public function canUseSystem()
    {
        return in_array($this->status, [
            self::STATUS_TRIAL,
            self::STATUS_ACTIVE,
            self::STATUS_GRACE_PERIOD,
        ], true);
    }
}
