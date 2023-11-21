<?php

namespace App\Http\Controllers;

use App\Helpers\SsoUpHelper;
use Illuminate\Http\Request;
use App\Http\Middleware\SsoUpMiddleware;

/**
 * Created by Erlang Parasu 2021.
 */
class SsoUpController extends Controller
{
    public function handleToken(Request $request)
    {
        
       
        if (! config('sso-up.enabled')) {
           
            return redirect()->route('login');
        }
        
        logger('SsoUpController_handleToken:', [
            'headers' => getallheaders(),
            '_POST' => $_POST,
            '_GET' => $_GET,
            '_REQUEST' => $_REQUEST,
            '_FILES' => $_FILES,
            'request_ip' => $request->ip(),
            'request_ips' => $request->ips(),
        ]);

        // CHECK NEW TOKEN
       
        try {
            //dd($request->all());
            validator($request->all(), [
                'token' => 'required|string:min:10',
                'username' => 'required|string|min:1',
            ])->validate();

            $token = $request->query('token');
            $username = $request->query('username');
           
            $helper = new SsoUpHelper(config('sso-up'));
            if ($helper->isLoggedIn($token, $username)) {
                session()->put('sso-up-data', [
                    'token' => $token,
                    'username' => $username,
                    'is_logged_in' => $helper->isLoggedIn($token, $username),
                    'login_at' => now()->format('Y-m-d H:i:s'),
                ]);
                logger('SsoUpController_handleToken:', ['sso_login_success', $username]);

                return response()->redirectToRoute('login.redirect');
            }
        } catch (\Throwable $th) {
            // throw $th;
        }
        
        // CHECK OLD SESSION
        
        try {
            
            $old = session()->get('sso-up-data');

            $token = $old['token'];
            $username = $old['username'];
           
            $helper = new SsoUpHelper(config('sso-up'));
            if ($helper->isLoggedIn($token, $username)) {
                return response()->redirectToRoute('login.redirect');
            }

            SsoUpMiddleware::trySessionSsoLogout();
        } catch (\Throwable $th) {
            // throw $th;
        }
       // dd("testing");

        //GO TO SSO LOGIN PAGE
        //dd(config('app.url'));
        $helper = new SsoUpHelper(config('sso-up'));
        $sso_login_url = $helper->getLoginUrl(config('app.url'));

        return response()->redirectTo($sso_login_url);
    }
}
