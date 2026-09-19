<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionInvoice extends Model
{
    protected $fillable = [
        'tenant_id',
        'subscription_id',
        'invoice_number',
        'amount',
        'status',
        'payment_method',
        'payment_reference',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    protected $dates = [
        'paid_at',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}
