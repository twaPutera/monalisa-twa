<?php

namespace App\Providers;

use Firebase\JWT\JWT;
use App\Helpers\SsoHelpers;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\View\View as ViewView;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        JWT::$leeway = 5;
        // To redirect access for ngrok server
        $ngrok_status = env('NGROK_STATUS') != null ? env('NGROK_STATUS') : false;
        if ($ngrok_status == true) {
            URL::forceScheme('https');
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.is_force_https')) {
            URL::forceScheme('https');
        }

        view::composer('*', function (ViewView $view) {
            $user = SsoHelpers::getUserLogin();
            $view->with('user', $user);
        });

        Validator::extend('alpha_spaces', function ($attribute, $value) {
            // This will only accept alpha and spaces.
            // If you want to accept hyphens use: /^[\pL\s-]+$/u.
            return preg_match('/^\S*$/u', $value);
        });
    }
}
