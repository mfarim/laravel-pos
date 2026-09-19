<?php

namespace App;

use App\Traits\BelongsToTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasUuid, BelongsToTenant;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'outlet_id',
        'pos_session_id',
        'user_id',
        'customer_name',
        'customer_phone',
        'transaction_number',
        'type',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'service_charge_amount',
        'rounding',
        'grand_total',
        'payment_amount',
        'change_amount',
        'tax_percentage',
        'notes',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'tax_amount' => 'float',
        'service_charge_amount' => 'float',
        'rounding' => 'float',
        'grand_total' => 'float',
        'payment_amount' => 'float',
        'change_amount' => 'float',
        'tax_percentage' => 'float',
    ];

    protected $dates = [
        'completed_at',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function session()
    {
        return $this->belongsTo(PosSession::class, 'pos_session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class, 'transaction_id');
    }

    public function payments()
    {
        return $this->hasMany(TransactionPayment::class, 'transaction_id');
    }
}
