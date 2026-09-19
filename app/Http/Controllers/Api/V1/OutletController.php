<?php

namespace App\Http\Controllers\Api\V1;

use App\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OutletController extends ApiController
{
    /**
     * List outlets accessible to the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isSuperAdmin()) {
            $outlets = Outlet::where('is_active', true)->get();
        } else {
            $outlets = $user->outlets()->where('is_active', true)->get();
        }

        return $this->successResponse($outlets, 'Daftar outlet berhasil diambil');
    }

    /**
     * Get outlet details.
     *
     * @param  string|int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = Auth::user();

        $outlet = Outlet::where(function ($q) use ($id) {
            $q->where('id', $id)->orWhere('uuid', $id);
        })->first();

        if (!$outlet) {
            return $this->errorResponse('Outlet tidak ditemukan', 404);
        }

        if (!$user->isSuperAdmin() && !$user->hasOutlet($outlet->id)) {
            return $this->errorResponse('Akses ke outlet ini ditolak', 403);
        }

        return $this->successResponse([
            'id'                        => $outlet->id,
            'uuid'                      => $outlet->uuid,
            'code'                      => $outlet->code,
            'name'                      => $outlet->name,
            'address'                   => $outlet->address,
            'city'                      => $outlet->city,
            'phone'                     => $outlet->phone,
            'email'                     => $outlet->email,
            'opening_time'              => $outlet->opening_time,
            'closing_time'              => $outlet->closing_time,
            'tax_percentage'            => $outlet->effective_tax_rate,
            'service_charge_percentage' => (float) ($outlet->service_charge_percentage ?: 0),
            'receipt_header'            => $outlet->receipt_header,
            'receipt_footer'            => $outlet->receipt_footer,
            'receipt_show_logo'         => $outlet->receipt_show_logo,
            'has_active_session'        => $outlet->activeSession()->exists(),
        ], 'Detail outlet berhasil diambil');
    }
}
