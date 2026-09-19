<?php

namespace App;

use App\Traits\BelongsToTenant;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class AuthorizationLog extends Model
{
    use HasUuid, BelongsToTenant;

    protected $fillable = [
        'uuid',
        'tenant_id',
        'outlet_id',
        'requested_by',
        'authorized_by',
        'action_type',
        'status',
        'reference_number',
        'amount',
        'reason',
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function requestedByUser()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function authorizedByUser()
    {
        return $this->belongsTo(User::class, 'authorized_by');
    }
}
