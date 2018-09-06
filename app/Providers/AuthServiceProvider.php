<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

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

        //Pssport routes
        Passport::routes();

        //access_token expired times
        Passport::tokensExpireIn(now()->addSeconds(config('auth.passport.access_token_expired_seconds')));

        //refresh_token expired times
        Passport::refreshTokensExpireIn(now()->addSeconds(config('auth.passport.refresh_token_expired_seconds')));
    }
}
