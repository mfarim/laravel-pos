<?php

namespace App\Http\Controllers\Api\V1;

use App\PaymentMethod;

class PaymentMethodController extends ApiController
{
    /**
     * List payment methods available for the tenant.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $methods = PaymentMethod::where('is_active', true)->get();

        return $this->successResponse($methods, 'Daftar metode pembayaran berhasil diambil');
    }
}
