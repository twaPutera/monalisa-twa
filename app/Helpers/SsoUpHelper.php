<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

/**
 * Created by Erlang Parasu 2021.
 */
class SsoUpHelper
{
    private $config = null;

    const BASE_URL_PROD = 'https://sso.universitaspertamina.ac.id';
    const BASE_URL_TEST = 'https://sso-dev.universitaspertamina.ac.id';

    public function __construct($config)
    {
        $this->config = $config;
    }

    public function getBaseUrl()
    {
        $config = $this->config;
        if ('production' == ($config['mode'] ?? 'dev')) {
            return self::BASE_URL_PROD;
        }
        return self::BASE_URL_TEST;
    }

    public function getLoginUrl($redirect_url)
    {
        $str_query = http_build_query([
            'redirect_url' => $redirect_url,
        ]);

        $url = $this->getBaseUrl().'/sso-login?'.$str_query;

        return $url;
    }

    public function isLoggedIn($token, $username)
    {
        try {
            $url = $this->getBaseUrl().'/sso-check?token='.$token.'&username='.$username.'';
            $response = Http::get($url);
            $sso_check = $response->body();

            $is_logged_in = '1' == $sso_check;

            // logger('SsoUpHelper_isLoggedIn', [
            //     $is_logged_in,
            //     $username,
            // ]);

            return $is_logged_in;
        } catch (\Throwable $th) {
            // throw $th;
        }

        return false;
    }

    public function logout($token, $username)
    {
        try {
            $url = $this->getBaseUrl().'/sso-logout?token='.$token.'&username='.$username.'';
            $response = Http::get($url);
            $sso_check = $response->body();

            logger('SsoUpHelper_logout', [
                $username,
            ]);

            return true;
        } catch (\Throwable $th) {
            // throw $th;
        }

        return false;
    }
}
