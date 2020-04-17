<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

use App\Extensions\DeezerUserProvider;
use App\Services\Auth\DeezerGuard;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    { 
        $this->registerPolicies();

        // add custom user provider
        Auth::provider('deezer', function($app, array $config) {            
            return new DeezerUserProvider($app->make($config['model']));
        });  

       // add custom guard
        Auth::extend('deezer', function ($app, $name, array $config) {
        
          return new DeezerGuard(Auth::createUserProvider($config['provider']), $app->make('request'));
        });
    }
}
