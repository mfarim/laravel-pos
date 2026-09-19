<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

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
}
