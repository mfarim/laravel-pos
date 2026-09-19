<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckSubscriptionFeature
{
    /**
     * Handle an incoming request and check if tenant has the required feature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $feature
     * @return mixed
     */
    public function handle($request, Closure $next, $feature)
    {
        $user = Auth::user();

        if (!$user || $user->isSuperAdmin()) {
            return $next($request);
        }

        $tenant = $user->tenant;

        if (!$tenant || !$tenant->hasFeature($feature)) {
            $featureNames = [
                'pos_core' => 'Core POS',
                'multi_outlet' => 'Multi Cabang / Outlet',
                'inventory_basic' => 'Inventaris Dasar',
                'inventory_advanced' => 'Inventaris Lanjutan',
                'recipe_bom' => 'Resep & BOM Bahan Baku',
                'stock_transfer' => 'Transfer Stok Antar Cabang',
                'manager_authorization' => 'Otorisasi PIN Supervisor',
                'qr_order' => 'Self-Order QR Meja',
                'kds' => 'Kitchen Display System (KDS)',
                'api_access' => 'REST API Mobile / Eksternal',
            ];

            $label = isset($featureNames[$feature]) ? $featureNames[$feature] : ucfirst(str_replace('_', ' ', $feature));

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Fitur ({$label}) tidak tersedia pada paket langganan Anda saat ini.",
                    'required_feature' => $feature,
                ], 403);
            }

            return redirect()->back()
                ->with('warning', "Fitur ({$label}) membutuhkan paket langganan yang lebih tinggi.");
        }

        return $next($request);
    }
}
