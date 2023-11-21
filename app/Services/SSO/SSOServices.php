<?php

namespace App\Services\SSO;

use App\Helpers\SsoHelpers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SSOServices
{
    public function redirectSso(Request $request)
    {
        $request->session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'client_id' => config('app.sso_client_id'),
            'redirect_uri' => config('app.sso_client_callback'),
            'response_type' => 'code',
            'scope' => '',
            'state' => $state,
        ]);

        $url = config('app.sso_ldap_url') . '/oauth/authorize?' . $query;

        return $url;
    }

    public function generateTokenFromSso(Request $request)
    {
        $state = $request->session()->pull('state');

        $token_ldap = SsoHelpers::generateNewAccessToken($request->code);

        $jwt_token = SsoHelpers::generateNewJwtToken($token_ldap['access_token']);

        $jwt_token = $jwt_token['data']['jwt'];

        $jwt_decoded = SsoHelpers::decodeJwtToken($jwt_token);
        \Session::put('user', $jwt_decoded['data']);

        if ($jwt_decoded['success']) {
            return true;
        }

        return false;
    }

    public function logoutSso(Request $request)
    {
        $request->session()->forget('access_token');
        $request->session()->forget('refresh_token');
        $request->session()->forget('jwt_token');
        $request->session()->forget('user');

        $request->session()->flush();

        \Cookie::forget(config('app.access_token_cookie_name'));
        \Cookie::forget(config('app.jwt_cookie_name'));

        return true;
    }
}
