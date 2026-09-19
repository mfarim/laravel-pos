<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

use App\Scopes\MemberScope;


class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable;

    use HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'name', 'username', 'email', 'phone', 'password',
        'tenant_id', 'pin', 'api_token', 'is_superadmin', 'locale',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'pin', 'api_token',
    ];

    protected $casts = [
        'is_superadmin' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function outlets()
    {
        return $this->belongsToMany(Outlet::class, 'user_outlets', 'user_id', 'outlet_id')
            ->withPivot('is_default')
            ->withTimestamps();
    }

    public function defaultOutlet()
    {
        return $this->outlets()->wherePivot('is_default', true)->first()
            ?: $this->outlets()->first();
    }

    public function isSuperAdmin()
    {
        return (bool) $this->is_superadmin;
    }

    public function hasOutlet($outletId)
    {
        if ($this->isSuperAdmin()) {
            return true;
        }
        return $this->outlets()->where('outlets.id', $outletId)->exists();
    }
}
