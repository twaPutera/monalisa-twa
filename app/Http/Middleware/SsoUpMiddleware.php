<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Helpers\SsoUpHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Created by Erlang Parasu 2021.
 */
class SsoUpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param mixed $custom_guard
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $custom_guard)
    {
        if ($request->has('message')) {
            if ('not registered url' == $request->query('message')) {
                self::trySessionSsoLogout();
                logger('SsoUpMiddleware_handle:', ['not registered url']);

                return response()->redirectTo('/?err=sso-not-registered-url');
            }
        }

        if (config('sso-up.enabled')) {
            $helper = new SsoUpHelper(config('sso-up'));
            $sso_login_url = $helper->getLoginUrl(config('app.url'));

            try {
                $old = session()->get('sso-up-data');

                $token = $old['token'];
                $username = $old['username'];

                if ($helper->isLoggedIn($token, $username)) {
                    try {
                        $temp_user = User::query()
                            ->select(['id'])
                            ->where('username_sso', $username)
                            ->where('is_active', '1')
                            ->first();
                        if (null != $temp_user) {
                            if (null != Auth::guard('web')->user()) {
                                if (Auth::guard('web')->user()->id == $temp_user->id) {
                                    return $next($request);
                                }
                            } else {
                                if (Auth::guard('web')->loginUsingId($temp_user->id)) {
                                    return $next($request);
                                }
                            }
                        }
                    } catch (\Throwable $th) {
                        // throw $th;
                        logger('try_catch:', ['SsoUpMiddleware_handle:', 'web guard', $th->getMessage()]);
                    }

                    // Remove session because SSO username unknown

                    self::trySessionSsoLogout();
                    logger('try_catch:', ['SsoUpMiddleware_handle:', 'sso username unknown']);

                    return response()->redirectToRoute('login', [
                        'err' => 'sso_username-unknown',
                        'time' => now()->format('YmdHis'),
                    ]);
                }

                self::trySessionSsoLogout();

                return response()->redirectTo($sso_login_url);
            } catch (\Throwable $th) {
                // throw $th;
                logger('try_catch:', ['SsoUpMiddleware_handle:', 'helper', $th->getMessage()]);
                report($th);

                return response()->redirectTo($sso_login_url);
            }
        }

        return $next($request);
    }

    public static function trySessionSsoLogout()
    {
        try {
            $old = session()->get('sso-up-data');

            $token = $old['token'];
            $username = $old['username'];

            $helper = new SsoUpHelper(config('sso-up'));
            $helper->logout($token, $username);
        } catch (\Throwable $th) {
            // throw $th;
        }

        session()->put('sso-up-data', null);
    }

    public static function checkSSO()
    {
        if (config('sso-up.enabled')) {
            $helper = new SsoUpHelper(config('sso-up'));
            $sso_login_url = $helper->getLoginUrl(config('app.url'));

            try {
                $old = session()->get('sso-up-data');

                $token = $old['token'];
                $username = $old['username'];

                if ($helper->isLoggedIn($token, $username)) {
                    try {
                        $temp_user = User::query()
                            ->select(['id'])
                            ->where('username_sso', $username)
                            ->where('is_active', '1')
                            ->first();
                        if (null != $temp_user) {
                            if (null != Auth::guard('web')->user()) {
                                if (Auth::guard('web')->user()->id == $temp_user->id) {
                                    return response()->redirectToRoute('login.redirect');
                                }
                            } else {
                                if (Auth::guard('web')->loginUsingId($temp_user->id)) {
                                    return response()->json([$temp_user]);
                                    return response()->redirectToRoute('login.redirect');
                                }
                            }
                        }
                    } catch (\Throwable $th) {
                        // throw $th;
                        logger('try_catch:', ['SsoUpMiddleware_handle:', 'web guard', $th->getMessage()]);
                    }

                    // Remove session because SSO username unknown

                    self::trySessionSsoLogout();
                    logger('try_catch:', ['SsoUpMiddleware_handle:', 'sso username unknown']);

                    // TODO: show message Error.
                }

                self::trySessionSsoLogout();
            } catch (\Throwable $th) {
                // throw $th;
                logger('try_catch:', ['SsoUpMiddleware_handle:', 'helper', $th->getMessage()]);
                report($th);
                // Do nothing.
            }
        }

        return null;
    }
}
