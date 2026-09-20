<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\User;
use App\Tenant;
use App\Outlet;
use App\Subscription;
use App\SubscriptionPlan;
use App\Role;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth or handle instant one-click demo login.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectAuthenticatedUser(Auth::user());
        }

        $clientId = config('services.google.client_id');
        $redirectUri = config('services.google.redirect');

        // If credentials are not configured or demo mode is explicitly requested, provide instant login
        if (empty($clientId) || $request->has('demo')) {
            $demoData = [
                'id' => 'google_demo_' . substr(md5(microtime()), 0, 12),
                'email' => $request->input('email', 'merchant.google@gmail.com'),
                'name' => $request->input('name', 'Google Demo Merchant'),
                'avatar' => 'https://ui-avatars.com/api/?name=Google+Merchant&background=4285F4&color=fff&size=128',
            ];

            return $this->provisionAndLoginGoogleUser($demoData, true);
        }

        $state = Str::random(40);
        $request->session()->put('google_oauth_state', $state);

        $params = [
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'state' => $state,
            'prompt' => 'select_account',
            'access_type' => 'online',
        ];

        return redirect('https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params));
    }

    /**
     * Handle the callback from Google OAuth.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectAuthenticatedUser(Auth::user());
        }

        if ($request->has('error')) {
            return redirect()->route('login')->with('error', 'Login Google dibatalkan: ' . $request->get('error'));
        }

        $code = $request->get('code');
        if (empty($code)) {
            return redirect()->route('login')->with('error', 'Otorisasi Google gagal: Authorization code tidak ditemukan.');
        }

        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');
        $redirectUri = config('services.google.redirect');

        // Exchange authorization code for access token via cURL
        $tokenResponse = $this->exchangeCodeForToken($code, $clientId, $clientSecret, $redirectUri);
        if (!$tokenResponse || !isset($tokenResponse['access_token'])) {
            return redirect()->route('login')->with('error', 'Gagal memverifikasi token Google. Pastikan kredensial Google OAuth valid.');
        }

        // Fetch user profile from Google UserInfo endpoint
        $userInfo = $this->fetchGoogleUserInfo($tokenResponse['access_token']);
        if (!$userInfo || !isset($userInfo['email'])) {
            return redirect()->route('login')->with('error', 'Gagal mengambil data profil dari akun Google Anda.');
        }

        $googleData = [
            'id' => isset($userInfo['id']) ? $userInfo['id'] : (isset($userInfo['sub']) ? $userInfo['sub'] : null),
            'email' => $userInfo['email'],
            'name' => isset($userInfo['name']) ? $userInfo['name'] : 'Pengguna Google',
            'avatar' => isset($userInfo['picture']) ? $userInfo['picture'] : null,
        ];

        return $this->provisionAndLoginGoogleUser($googleData, false);
    }

    /**
     * Provision or locate the user and log them in.
     *
     * @param array $data
     * @param bool $isDemo
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function provisionAndLoginGoogleUser(array $data, $isDemo = false)
    {
        $email = $data['email'];
        $user = User::where('email', $email)->orWhere('google_id', $data['id'])->first();

        if ($user) {
            // Update existing user with Google details if missing
            $updates = [];
            if (empty($user->google_id) && !empty($data['id'])) {
                $updates['google_id'] = $data['id'];
            }
            if (!empty($data['avatar']) && empty($user->avatar)) {
                $updates['avatar'] = $data['avatar'];
            }
            if (!empty($updates)) {
                $user->update($updates);
            }
        } else {
            // Provision new Tenant and Store Owner User
            $user = DB::transaction(function () use ($data) {
                // 1. Create Tenant
                $tenantName = 'Toko ' . $data['name'];
                $tenantSlug = 'toko-' . Str::slug($data['name']) . '-' . strtolower(Str::random(4));
                $tenant = Tenant::create([
                    'name' => $tenantName,
                    'slug' => $tenantSlug,
                    'email' => $data['email'],
                    'status' => 'active',
                ]);

                // 2. Attach Free Forever Subscription Plan
                $freePlan = SubscriptionPlan::where('slug', 'free')->first();
                if (!$freePlan) {
                    $freePlan = SubscriptionPlan::first();
                }

                if ($freePlan) {
                    Subscription::create([
                        'tenant_id' => $tenant->id,
                        'plan_id' => $freePlan->id,
                        'status' => 'active',
                        'starts_at' => Carbon::now(),
                        'ends_at' => Carbon::now()->addYears(10), // Gratis selamanya
                        'trial_ends_at' => Carbon::now()->addYears(10),
                    ]);
                }

                // 3. Create Default Outlet for the new tenant
                $outlet = Outlet::create([
                    'tenant_id' => $tenant->id,
                    'name' => 'Outlet Utama',
                    'code' => 'OUT-01',
                    'is_active' => true,
                ]);

                // 4. Generate unique username
                $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9_]/', '', explode('@', $data['email'])[0]));
                if (empty($baseUsername)) {
                    $baseUsername = 'merchant';
                }
                $username = $baseUsername;
                $counter = 1;
                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . '_' . rand(100, 9999);
                    $counter++;
                    if ($counter > 10) {
                        $username = $baseUsername . '_' . time();
                        break;
                    }
                }

                // 5. Create User
                $newUser = User::create([
                    'uuid' => (string) Str::uuid(),
                    'tenant_id' => $tenant->id,
                    'name' => $data['name'],
                    'username' => $username,
                    'email' => $data['email'],
                    'google_id' => $data['id'],
                    'avatar' => $data['avatar'],
                    'password' => bcrypt(Str::random(32)),
                    'pin' => bcrypt('123456'), // Default supervisor PIN 123456
                    'api_token' => 'usr_' . Str::random(50),
                    'is_superadmin' => false,
                    'locale' => 'id',
                ]);

                // 6. Assign role 'pemilik' (Store Owner)
                $pemilikRole = Role::where('name', 'pemilik')->first();
                if ($pemilikRole) {
                    $newUser->attachRole($pemilikRole);
                }

                // 7. Associate user with the default outlet
                $newUser->outlets()->attach($outlet->id, ['is_default' => true]);

                return $newUser;
            });
        }

        Auth::login($user, true);

        $welcomeMessage = $isDemo
            ? 'Selamat datang! Anda masuk melalui Akun Google (Mode Cepat Gratis). Semua fitur SaaS POS aktif!'
            : 'Selamat datang, ' . $user->name . '! Akun SaaS POS Anda aktif dan 100% Gratis 1 tahun dengan Google.';

        return $this->redirectAuthenticatedUser($user)->with('success_message', $welcomeMessage);
    }

    /**
     * Redirect authenticated user based on role.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectAuthenticatedUser($user)
    {
        if ($user->hasRole('pemilik') || $user->is_superadmin) {
            return redirect('/pemilik')->with('berhasil_login', 'Welcome');
        }

        return redirect('/penjaga')->with('berhasil_login', 'Welcome');
    }

    /**
     * Exchange code for token via HTTP POST.
     *
     * @param string $code
     * @param string $clientId
     * @param string $clientSecret
     * @param string $redirectUri
     * @return array|null
     */
    protected function exchangeCodeForToken($code, $clientId, $clientSecret, $redirectUri)
    {
        $ch = curl_init('https://oauth2.googleapis.com/token');
        $payload = http_build_query([
            'code' => $code,
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code',
        ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            return json_decode($response, true);
        }

        return null;
    }

    /**
     * Fetch user profile from Google.
     *
     * @param string $accessToken
     * @return array|null
     */
    protected function fetchGoogleUserInfo($accessToken)
    {
        $ch = curl_init('https://www.googleapis.com/oauth2/v2/userinfo');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Accept: application/json',
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200 && $response) {
            return json_decode($response, true);
        }

        return null;
    }
}
