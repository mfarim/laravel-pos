<?php

namespace App;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class TransactionPayment extends Model
{
    use HasUuid;

    protected $fillable = [
        'uuid',
        'transaction_id',
        'payment_method_id',
        'amount',
        'reference_number',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }
}
