<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Services\SSO\SSOServices;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\SsoUpMiddleware;

class SsoController extends Controller
{
    protected $ssoServices;

    public function __construct(SSOServices $ssoServices)
    {
        $this->ssoServices = $ssoServices;
    }

    public function redirectSso(Request $request)
    {
        return redirect($this->ssoServices->redirectSso($request));
    }

    public function callback(Request $request)
    {
        try {
            $response = $this->ssoServices->generateTokenFromSso($request);

            if ($response) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('sso.redirect');

            //code...
        } catch (\Throwable $th) {
            // throw $th;
            return response()->json([
                'message' => 'error',
                'data' => $th->getMessage(),
            ], 500);
        }
    }

    public function logoutSso(Request $request)
    {
        if (config('app.sso_login') || config('app.sso_siska')) {
            $response = $this->ssoServices->logoutSso($request);

            if ($response) {
                return redirect('/');
            }
        } else {
            Auth::logout();
            SsoUpMiddleware::trySessionSsoLogout();
            $request->session()->flush();
            $request->session()->regenerate();
            return redirect('/');
        }

        return redirect()->route('admin.dashboard');
    }
}
