<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginStoreRequest;

class AuthCommandServices
{
    public function login(LoginStoreRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return true;
        }

        return false;
    }
}
