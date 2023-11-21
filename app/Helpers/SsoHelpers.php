<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SsoHelpers
{
    public static function generateNewAccessToken($code)
    {
        $response = Http::asForm()->post(config('app.sso_ldap_url') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('app.sso_client_id'),
            'client_secret' => config('app.sso_client_secret'),
            'redirect_uri' => config('app.sso_client_callback'),
            'code' => $code,
        ]);

        \Session::put('access_token', $response['access_token']);
        \Session::put('refresh_token', $response['refresh_token']);

        \Cookie::queue(config('app.access_token_cookie_name'), $response['access_token'], (60 * 60 * 24));

        return $response;
    }

    public static function refreshTokenAccess(Request $request, $refresh_token)
    {
        try {
            $response = Http::asForm()->post(config('app.sso_ldap_url') . '/oauth/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refresh_token,
                'client_id' => config('app.sso_client_id'),
                'client_secret' => config('app.sso_client_secret'),
                'code' => $request->code,
            ]);

            \Session::put('access_token', $response['access_token']);
            \Session::put('refresh_token', $response['refresh_token']);

            \Cookie::queue(config('app.access_token_cookie_name'), $response['access_token'], (60 * 60 * 24));

            return $response['access_token'];
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }

    public static function generateNewJwtToken($access_token)
    {
        $sso_siska_url = config('app.sso_siska_url') . '/api/auth/authorization-token';

        $response_sso_siska = Http::post($sso_siska_url, [
            'access_token' => $access_token,
        ]);

        $response_sso_siska = json_decode($response_sso_siska->body(), true);
        \Session::put('jwt_token', $response_sso_siska['data']['jwt']);
        setcookie(config('app.jwt_cookie_name'), $response_sso_siska['data']['jwt'], time() + (60 * 60 * 24), '/');

        return $response_sso_siska;
    }

    public static function decodeJwtToken($jwt_token)
    {
        $response = [];
        $jwt_key = config('app.jwt_secret');
        try {
            $decoded = JWT::decode($jwt_token, new Key($jwt_key, 'HS256'));
            $response['success'] = true;
            $response['data'] = $decoded;

            \Session::put('user', $decoded);
        } catch (\Throwable $th) {
            // throw $th;
            $response['success'] = false;
            $response['data'] = $th->getMessage();
        }

        return $response;
    }

    public static function checkAccessTokenIsValid($access_token)
    {
        $response = [];
        $jwt_key = config('app.sso_public_key');

        try {
            $decoded = JWT::decode($access_token, new Key($jwt_key, 'RS256'));
            $response['success'] = true;
            $response['data'] = $decoded;
        } catch (\Throwable $th) {
            throw $th;
            $response['success'] = false;
            $response['data'] = $th->getMessage();
        }

        return $response;
    }

    public static function checkTokenIsValid(Request $request)
    {
        $access_token = \Session::get('access_token', null);
        if (isset($access_token)) {
            $access_token_decoded = self::checkAccessTokenIsValid($access_token);
            if (! $access_token_decoded['success']) {
                $refresh_token = \Session::get('refresh_token', null);
                if (isset($refresh_token)) {
                    $access_token = self::refreshTokenAccess($request, $refresh_token);
                    if (! $access_token) {
                        return false;
                    }
                }
            }
            $jwt_token = \Session::get('jwt_token', null);
            if (! isset($jwt_token)) {
                $jwt_token = self::generateNewJwtToken($access_token);
            }
            $jwt_token_decoded = self::decodeJwtToken($jwt_token);
            if (! $jwt_token_decoded['success']) {
                $jwt_token = self::generateNewJwtToken($access_token);
            }
            return true;
        }

        return false;
    }

    public static function getUserLogin()
    {
        $sso_login = config('app.sso_login');
        $user = auth()->user();
        if ($sso_login) {
            $sso_siska = config('app.sso_siska');
            if ($sso_siska) {
                $user = \Session::get('user', null);
            }
        }

        return $user;
    }
}
