<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class EnsureTenantScope
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Super admins have global access
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        $tenantId = $user->tenant_id;

        // If no direct tenant_id on user, attempt to retrieve from assigned outlet
        if (!$tenantId) {
            $defaultOutlet = $user->defaultOutlet();
            if ($defaultOutlet) {
                $tenantId = $defaultOutlet->tenant_id;
                $user->update(['tenant_id' => $tenantId]);
            }
        }

        if (!$tenantId) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akun Anda belum terasosiasi dengan tenant/merchant.',
                ], 403);
            }
            abort(403, 'Akun Anda belum terasosiasi dengan tenant/merchant.');
        }

        // Store tenant ID in internal request attributes (not in public input parameters)
        $request->attributes->set('current_tenant_id', $tenantId);

        // Share current tenant to all Blade views
        if (function_exists('view')) {
            view()->share('currentTenant', $user->tenant);
        }

        return $next($request);
    }
}
