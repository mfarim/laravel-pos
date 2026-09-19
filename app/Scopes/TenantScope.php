<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Flag to disable tenant scoping during certain internal operations.
     *
     * @var bool
     */
    protected static $disabled = false;

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (self::$disabled) {
            return;
        }

        $tenantId = self::resolveCurrentTenantId();

        if ($tenantId !== null) {
            $builder->where($model->getTable() . '.tenant_id', $tenantId);
        }
    }

    /**
     * Resolve the current tenant ID from request, session, or authenticated user.
     *
     * @return int|null
     */
    public static function resolveCurrentTenantId()
    {
        // 1. From request attribute (set by EnsureTenantScope middleware)
        if (app()->bound('request') && request()->has('current_tenant_id')) {
            return request()->get('current_tenant_id');
        }

        // 2. From authenticated user
        if (Auth::check()) {
            $user = Auth::user();
            if (!empty($user->is_superadmin)) {
                return null; // Superadmins see all records
            }
            if (!empty($user->tenant_id)) {
                return $user->tenant_id;
            }
        }

        // 3. From global config if set
        if (config()->has('app.current_tenant_id')) {
            return config('app.current_tenant_id');
        }

        return null;
    }

    /**
     * Execute a callback with tenant scope disabled.
     *
     * @param  callable  $callback
     * @return mixed
     */
    public static function withoutTenantScope(callable $callback)
    {
        self::$disabled = true;
        try {
            return $callback();
        } finally {
            self::$disabled = false;
        }
    }
}
