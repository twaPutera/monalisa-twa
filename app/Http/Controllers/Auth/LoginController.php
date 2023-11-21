<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\AuthCommandServices;
use App\Http\Requests\Auth\LoginStoreRequest;

class LoginController extends Controller
{
    protected $authCommandServices;

    public function __construct(AuthCommandServices $authCommandServices)
    {
        $this->authCommandServices = $authCommandServices;
    }

    public function loginForm()
    {
        //dd("wahyu");
        return view('pages.auth.login');
    }


    public function loginStore(LoginStoreRequest $request)
    {

        
        if ($this->authCommandServices->login($request)) {
            
            return redirect()->route('login.redirect');
        }

        return redirect()->back()->with('error', 'Email or password is incorrect');
    }

    public function redirect()
    {
        //dd('request');
        $user = auth()->user();

        if ($user->role == 'user') {
            return redirect()->route('user.dashboard.index');
        }
        return redirect()->route('admin.dashboard');
    }
}
