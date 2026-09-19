<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class BlockFrozenWrite
{
    /**
     * Block modifying requests if tenant account is frozen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && !$user->isSuperAdmin() && $user->tenant && $user->tenant->isFrozen()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akun merchant Anda berstatus Frozen (Read-only). Transaksi tidak dapat diproses.',
                    'subscription_status' => 'frozen',
                ], 403);
            }
            return redirect()->back()
                ->with('error', 'Akun dibekukan. Tidak dapat memodifikasi data.');
        }

        return $next($request);
    }
}
