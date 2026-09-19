<?php

namespace App;

use App\Traits\BelongsToTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class PosSession extends Model
{
    use HasUuid, BelongsToTenant;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'outlet_id',
        'user_id',
        'session_number',
        'opening_cash',
        'closing_cash',
        'expected_cash',
        'cash_difference',
        'opening_notes',
        'closing_notes',
        'opened_at',
        'closed_at',
        'closed_by',
        'status',
    ];

    protected $casts = [
        'opening_cash' => 'float',
        'closing_cash' => 'float',
        'expected_cash' => 'float',
        'cash_difference' => 'float',
    ];

    protected $dates = [
        'opened_at',
        'closed_at',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function closedByUser()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'pos_session_id');
    }

    public function heldOrders()
    {
        return $this->hasMany(HeldOrder::class, 'pos_session_id');
    }

    public function isOpen()
    {
        return $this->status === 'open';
    }
}
