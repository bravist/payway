<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Providers\Auth\SignTokenGuard;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        Auth::extend('signToken', function ($app, $name, array $config) {
            $guard = new SignTokenGuard(
                $app['auth']->createUserProvider($config['provider'] ?? null),
                $app['request'],
                'api_token',
                'secret'
            );
                
            $app->refresh('request', $guard, 'setRequest');
                
            return $guard;
        });

//         //Pssport routes
//         Passport::routes();

//         //access_token expired times
//         Passport::tokensExpireIn(now()->addSeconds(config('auth.passport.access_token_expired_seconds')));

//         //refresh_token expired times
//         Passport::refreshTokensExpireIn(now()->addSeconds(config('auth.passport.refresh_token_expired_seconds')));
    }
}
