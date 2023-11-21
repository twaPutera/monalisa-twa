<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param mixed $role
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (Auth::user()->is_active == '0') {
            return abort(403, 'Unauthorized action');
        }

        if (Auth::user()->role != null) {
            $roles = explode('|', $role);
            if (in_array(Auth::user()->role, $roles)) {
                return $next($request);
            }
            return abort(403, 'Unauthorized action');
        }
    }
}
