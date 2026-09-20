<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    /**
     * Return a standardized JSON success response.
     *
     * @param  mixed   $data
     * @param  string  $message
     * @param  int     $code
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data = null, $message = 'Success', $code = 200)
    {
        $response = [
            'status'  => 'success',
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    /**
     * Return a standardized JSON error response.
     *
     * @param  string  $message
     * @param  int     $code
     * @param  mixed   $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message = 'Error', $code = 400, $errors = null)
    {
        $response = [
            'status'  => 'error',
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Resolve and verify that the authenticated user is authorized for the requested outlet.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return int|null
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function resolveAuthorizedOutletId(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        $rawOutlet = $request->header('X-Outlet-Id')
            ?: ($request->input('outlet_id') ?: $request->input('current_outlet_id'));

        if ($rawOutlet) {
            $outlet = Outlet::where(function ($q) use ($rawOutlet) {
                $q->where('id', $rawOutlet)->orWhere('uuid', $rawOutlet);
            })->first();

            if (!$outlet) {
                abort(response()->json([
                    'status'  => 'error',
                    'message' => 'Outlet tidak ditemukan.',
                ], 404));
            }

            if (!$user->isSuperAdmin() && !$user->hasOutlet($outlet->id)) {
                abort(response()->json([
                    'status'  => 'error',
                    'message' => 'Akses ditolak: Anda tidak memiliki penugasan otorisasi pada outlet/cabang ini.',
                ], 403));
            }

            return $outlet->id;
        }

        $defaultOutlet = $user->defaultOutlet();
        return $defaultOutlet ? $defaultOutlet->id : null;
    }
}
