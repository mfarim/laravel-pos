<?php

namespace App\Http\Middleware;

use App\Outlet;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateWithApiToken
{
    /**
     * Handle an incoming request for Mobile REST API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (empty($token)) {
            // Check query string fallback for testing
            $token = $request->query('api_token');
        }

        if (empty($token)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthenticated. Token Bearer diperlukan.',
            ], 401);
        }

        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Token API tidak valid atau telah kedaluwarsa.',
            ], 401);
        }

        Auth::setUser($user);

        // Merge tenant ID
        if ($user->tenant_id) {
            $request->merge(['current_tenant_id' => $user->tenant_id]);
        }

        // Handle X-Outlet-Id header for outlet-scoped operations
        $outletHeader = $request->header('X-Outlet-Id');
        if ($outletHeader) {
            // Can be outlet integer ID or UUID
            $outlet = Outlet::where(function ($q) use ($outletHeader) {
                $q->where('id', $outletHeader)->orWhere('uuid', $outletHeader);
            })->first();

            if ($outlet) {
                if (!$user->isSuperAdmin() && !$user->hasOutlet($outlet->id)) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Anda tidak memiliki izin untuk mengakses outlet ini.',
                    ], 403);
                }
                $request->merge(['current_outlet_id' => $outlet->id, 'current_outlet' => $outlet]);
            }
        }

        return $next($request);
    }
}
