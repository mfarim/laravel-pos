<?php

namespace App\Http\Controllers\Api\V1;

use App\Outlet;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends ApiController
{
    /**
     * Login using email/username and password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login'    => 'required|string', // can be email or username
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal', 422, $validator->errors());
        }

        $login = $request->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = User::where($field, $login)->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return $this->errorResponse('Kredensial login tidak cocok.', 401);
        }

        // Generate or refresh API token (Sanctum / Bearer compatible)
        $token = bin2hex(random_bytes(30));
        $user->api_token = $token;
        $user->save();

        return $this->formatAuthResponse($user, $token);
    }

    /**
     * Fast login for cashiers using 6-digit PIN and Outlet ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pinLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'outlet_id' => 'required',
            'pin'       => 'required|string|min:4|max:8',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal', 422, $validator->errors());
        }

        $outletParam = $request->input('outlet_id');
        $outlet = Outlet::where('id', $outletParam)->orWhere('uuid', $outletParam)->first();

        if (!$outlet) {
            return $this->errorResponse('Outlet tidak ditemukan', 404);
        }

        // Search users attached to this outlet with matching PIN
        $pin = $request->input('pin');
        $matchedUser = null;

        $users = $outlet->users()->get();
        foreach ($users as $candidate) {
            if (!empty($candidate->pin) && Hash::check($pin, $candidate->pin)) {
                $matchedUser = $candidate;
                break;
            }
        }

        if (!$matchedUser) {
            return $this->errorResponse('PIN salah atau tidak ditemukan kasir yang cocok di outlet ini.', 401);
        }

        $token = bin2hex(random_bytes(30));
        $matchedUser->api_token = $token;
        $matchedUser->save();

        return $this->formatAuthResponse($matchedUser, $token, $outlet);
    }

    /**
     * Get authenticated user profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = Auth::user();
        $tenant = $user->tenant;
        $subscription = $tenant ? $tenant->activeSubscription : null;

        return $this->successResponse([
            'user' => [
                'id'       => $user->id,
                'uuid'     => $user->uuid,
                'name'     => $user->name,
                'username' => $user->username,
                'email'    => $user->email,
                'phone'    => $user->phone,
                'roles'    => $user->roles->pluck('name'),
                'is_superadmin' => $user->isSuperAdmin(),
            ],
            'tenant' => $tenant ? [
                'id'       => $tenant->id,
                'uuid'     => $tenant->uuid,
                'name'     => $tenant->name,
                'slug'     => $tenant->slug,
                'currency' => $tenant->currency,
                'timezone' => $tenant->timezone,
                'tax_percentage' => $tenant->tax_percentage,
                'subscription_status' => $subscription ? $subscription->status : 'none',
                'plan_name' => ($subscription && $subscription->plan) ? $subscription->plan->name : 'None',
            ] : null,
            'outlets' => $user->outlets()->get(['outlets.id', 'outlets.uuid', 'outlets.name', 'outlets.code', 'user_outlets.is_default']),
        ], 'Profil pengguna berhasil diambil');
    }

    /**
     * Logout and revoke API token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $user = Auth::user();
        if ($user) {
            $user->api_token = null;
            $user->save();
        }

        return $this->successResponse(null, 'Logout berhasil');
    }

    /**
     * Helper to structure user and token payload.
     */
    protected function formatAuthResponse($user, $token, $selectedOutlet = null)
    {
        $tenant = $user->tenant;
        $outlets = $user->outlets()->get(['outlets.id', 'outlets.uuid', 'outlets.name', 'outlets.code', 'user_outlets.is_default']);
        $currentOutlet = $selectedOutlet ?: ($user->defaultOutlet() ?: $outlets->first());

        return $this->successResponse([
            'token'      => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id'       => $user->id,
                'uuid'     => $user->uuid,
                'name'     => $user->name,
                'username' => $user->username,
                'email'    => $user->email,
                'phone'    => $user->phone,
                'roles'    => $user->roles->pluck('name'),
                'is_superadmin' => $user->isSuperAdmin(),
            ],
            'tenant' => $tenant ? [
                'id'       => $tenant->id,
                'uuid'     => $tenant->uuid,
                'name'     => $tenant->name,
                'slug'     => $tenant->slug,
                'currency' => $tenant->currency,
                'timezone' => $tenant->timezone,
                'tax_percentage' => $tenant->tax_percentage,
            ] : null,
            'outlets'        => $outlets,
            'current_outlet' => $currentOutlet ? [
                'id'   => $currentOutlet->id,
                'uuid' => $currentOutlet->uuid,
                'code' => $currentOutlet->code,
                'name' => $currentOutlet->name,
            ] : null,
        ], 'Autentikasi berhasil');
    }
}
