<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckSubscriptionStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $mode
     * @return mixed
     */
    public function handle($request, Closure $next, $mode = 'read')
    {
        $user = Auth::user();

        if (!$user || $user->isSuperAdmin() || !$user->tenant) {
            return $next($request);
        }

        $tenant = $user->tenant;
        $subscription = $tenant->activeSubscription;

        // No active or trial subscription
        if (!$subscription) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Tidak ada paket langganan aktif. Silakan berlangganan untuk melanjutkan.',
                    'subscription_status' => 'inactive',
                ], 403);
            }
            return redirect()->route('subscription.index')
                ->with('warning', 'Silakan aktifkan paket langganan Anda terlebih dahulu.');
        }

        // Account is frozen (read-only mode)
        if ($tenant->isFrozen() && ($mode === 'write' || in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']))) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Akun merchant Anda berstatus Frozen (Read-only). Silakan perpanjang langganan untuk transaksi.',
                    'subscription_status' => 'frozen',
                ], 403);
            }
            return redirect()->back()
                ->with('error', 'Akun merchant Anda berstatus Frozen. Anda hanya dapat melihat data.');
        }

        if (!$subscription->canUseSystem()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Masa aktif langganan Anda telah berakhir. Silakan perpanjang.',
                    'subscription_status' => 'expired',
                ], 403);
            }
            return redirect()->route('subscription.index')
                ->with('warning', 'Masa aktif langganan Anda telah berakhir.');
        }

        return $next($request);
    }
}
