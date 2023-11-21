<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\SsoHelpers;
use Illuminate\Http\Request;

class SsoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // // dd(\Session::get('access_token'));
        // // dd(\Session::get('user'));
        // $sso_login = config('app.sso_login');
        // if (! $sso_login) {
        //     return $next($request);
        // }

        // $token = SsoHelpers::checkTokenIsValid($request);

        // if ($token) {
        //     return $next($request);
        // }
        // return redirect()->route('sso.redirect');
    }
}
