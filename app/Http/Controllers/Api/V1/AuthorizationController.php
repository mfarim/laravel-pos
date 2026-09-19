<?php

namespace App\Http\Controllers\Api\V1;

use App\AuthorizationLog;
use App\AuthorizationSetting;
use App\Outlet;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthorizationController extends ApiController
{
    /**
     * Verify manager/supervisor PIN for override actions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyPin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pin'         => 'required|string',
            'action_type' => 'required|in:void,refund,discount,price_override',
            'amount'      => 'nullable|numeric',
            'reason'      => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal', 422, $validator->errors());
        }

        $currentUser = Auth::user();
        $pin = $request->input('pin');

        // Look for any supervisor/manager/owner in the same tenant who has this PIN
        $managers = User::where('tenant_id', $currentUser->tenant_id)->get();
        $authorizer = null;

        foreach ($managers as $user) {
            // Check if user has role pemilik or manager or is superadmin
            $isEligible = $user->isSuperAdmin() || $user->hasRole('pemilik') || $user->hasRole('admin') || $user->hasRole('manager');
            if ($isEligible && !empty($user->pin) && Hash::check($pin, $user->pin)) {
                $authorizer = $user;
                break;
            }
        }

        $outletId = $request->get('current_outlet_id');
        if (!$outletId) {
            $def = $currentUser->defaultOutlet();
            $outletId = $def ? $def->id : null;
        }

        if (!$authorizer) {
            // Log denied attempt
            if ($outletId) {
                AuthorizationLog::create([
                    'tenant_id'        => $currentUser->tenant_id,
                    'outlet_id'        => $outletId,
                    'requested_by'     => $currentUser->id,
                    'authorized_by'    => null,
                    'action_type'      => $request->input('action_type'),
                    'status'           => 'denied',
                    'amount'           => $request->input('amount'),
                    'reason'           => 'Invalid PIN entered',
                ]);
            }

            return $this->errorResponse('PIN Otorisasi Supervisor tidak valid.', 403);
        }

        // Log approved authorization
        $log = null;
        if ($outletId) {
            $log = AuthorizationLog::create([
                'tenant_id'        => $currentUser->tenant_id,
                'outlet_id'        => $outletId,
                'requested_by'     => $currentUser->id,
                'authorized_by'    => $authorizer->id,
                'action_type'      => $request->input('action_type'),
                'status'           => 'approved',
                'amount'           => $request->input('amount'),
                'reason'           => $request->input('reason', 'Supervisor approval'),
            ]);
        }

        return $this->successResponse([
            'approved'      => true,
            'authorizer'    => [
                'id'   => $authorizer->id,
                'name' => $authorizer->name,
            ],
            'auth_token'    => $log ? $log->uuid : null,
        ], 'Otorisasi supervisor berhasil disetujui');
    }
}
