<?php

namespace App\Services\UserSso;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserSsoQueryServices
{
    // ? List Role User Asset (Sementara) :
    // ? Staff Asset => id_role = 33
    // ? Direktur Asset => id_role = 34
    // ? User Asset => id_role = 35

    public function getUserSso(Request $request)
    {
        $jwt_token = $_COOKIE[config('app.jwt_cookie_name')];

        $sso_siska_url = config('app.sso_siska_url') . '/api/users';

        $response_sso_siska = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Bearer ' . $jwt_token,
        ])->get($sso_siska_url, [
            'fields' => 'name,guid,email',
            'search' => 'name:' . $request->keyword,
            'roles' => [33, 34, 36],
            'page' => 1,
            'perPage' => 20,
        ]);

        return $response_sso_siska['data']['nodes'];
    }

    public function getDataUserByRoleId(Request $request, $role)
    {
        $sso_login = config('app.sso_login');
        $user = User::query()->where('role', $role)->first();
        if ($sso_login) {
            $sso_siska = config('app.sso_siska');
            if ($sso_siska) {
                $jwt_token = $_COOKIE[config('app.jwt_cookie_name')];

                $sso_siska_url = config('app.sso_siska_url') . '/api/users';

                $response_sso_siska = Http::withHeaders([
                    'accept' => 'application/json',
                    'Authorization' => 'Bearer ' . $jwt_token,
                ])->get($sso_siska_url, [
                    'fields' => 'name,guid,email',
                    'search' => 'name:' . $request->keyword,
                    'roles' => [$role],
                    'page' => 1,
                    'perPage' => 20,
                ]);
                $user = $response_sso_siska['data']['nodes'];
            }
        }

        return $user;
    }

    public function getUserByGuid(string $guid)
    {
        $jwt_token = $_COOKIE[config('app.jwt_cookie_name')];

        $siska_url = config('app.siska_url') . '/api/pegawai';

        $response_sso_siska = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Bearer ' . $jwt_token,
        ])->get($siska_url, [
            'fields' => 'nama,guid,email,no_induk,no_hp,token_user',
            'search' => 'token_user:' . $guid,
        ]);

        // dd($response_sso_siska->json());

        return $response_sso_siska['data']['nodes'];
    }

    public function getDataUnit()
    {
        $access_token = \Session::get('access_token');
        $ldap_url = config('app.sso_ldap_url') . '/api/unit/all';

        $response_ldap = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token,
        ])->get($ldap_url);

        return $response_ldap['data'];
    }

    public function getDataPosition()
    {
        $access_token = \Session::get('access_token');
        $ldap_url = config('app.sso_ldap_url') . '/api/position/all';
        $response_ldap = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token,
        ])->get($ldap_url);

        return $response_ldap['data'];
    }

    public function getDataPositionById(string $id)
    {
        $access_token = \Session::get('access_token');
        $ldap_url = config('app.sso_ldap_url') . '/api/position/' . $id;
        $response_ldap = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token,
        ])->get($ldap_url);

        return $response_ldap['data'];
    }

    public function getPositionByGuid(string $guid_position)
    {
        $access_token = \Session::get('access_token');
        $ldap_url = config('app.sso_ldap_url') . '/api/user/' . $guid_position . '/position';
        $response_ldap = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token,
        ])->get($ldap_url);

        return $response_ldap['data'];
    }

    public function getUnitByGuid(string $guid_position)
    {
        $access_token = \Session::get('access_token');
        $ldap_url = config('app.sso_ldap_url') . '/api/position-unit/member/position/' . $guid_position . '/unit';
        $response_ldap = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token,
        ])->get($ldap_url);

        return $response_ldap['data'];
    }

    public function getUnitById(string $id)
    {
        $access_token = \Session::get('access_token');
        $ldap_url = config('app.sso_ldap_url') . '/api/unit/' . $id;
        $response_ldap = Http::withHeaders([
            'accept' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token,
        ])->get($ldap_url);

        return $response_ldap['data'];
    }

    public function getUserPositionByUsernameSSO(string $username)
    {
        $masayu_url = config('app.masayu_url') . '/Employees/byUsername?username=' . $username;

        $response_masayu = Http::withHeaders([
            'accept' => 'application/json',
        ])->get($masayu_url);

        if ($response_masayu->json()['error']) {
            return [];
        }

        return $response_masayu->json()['data']['employee'][0]['positions'] ?? [];
    }
}
