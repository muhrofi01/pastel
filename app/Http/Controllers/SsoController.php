<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JKD\SSO\Client\Provider\Keycloak;

class SsoController extends Controller
{
    /**
     * Buat instance SSO Provider
     * (AMAN & bisa dipakai ulang)
     */
    protected function provider(): Keycloak
    {
        return new Keycloak([
            'authServerUrl'         => 'https://sso.bps.go.id',
            'realm'                 => 'pegawai-bps',
            'clientId'              => env('SSO_CLIENT_ID'),
            'clientSecret'          => env('SSO_CLIENT_SECRET'),
            'redirectUri'           => 'http://localhost:8000/post-sso'
        ]);
    }

    /**
     * Redirect user ke halaman SSO
     */
    public function redirect()
    {
        $provider = $this->provider();

        return redirect()->to(
            $provider->getAuthorizationUrl()
        );
    }

    public function ssologin()
    {
        $provider = $this->provider();
        
        if (!isset($_GET['code'])) {
            
            // Untuk mendapatkan authorization code
            $authUrl = $provider->getAuthorizationUrl();
            $_SESSION['oauth2state'] = $provider->getState();
            header('Location: '.$authUrl);
            exit;
            
        // Mengecek state yang disimpan saat ini untuk memitigasi serangan CSRF
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        
        }
    }

    public function postssologin(Request $request)
    {
        $code = $request->query('code');

        if (! $code) {
            abort(400, 'Authorization code tidak ditemukan.');
        }

        $provider = $this->provider();

        $token = $provider->getAccessToken('authorization_code', [
            'code' => $_GET['code'],
        ]);

        $ssoUser = $provider->getResourceOwner($token);
        $email   = $ssoUser->getEmail();

        if (! $email) {
            abort(400, 'Email tidak ditemukan dari SSO.');
        }

        $user = User::where('email', $email)->first();

        if (! $user) {
            abort(403, 'Email tidak terdaftar di sistem.');
        }

        Auth::login($user);

        return redirect()->to(
            filament()->getPanel('pegawai')->getUrl()
        );
    }
}