<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Extensions\DeezerProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootDeezerSocialite();
    }

    private function bootDeezerSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'deezer',
            function ($app) use ($socialite) {
                $config = $app['config']['services.deezer'];
                return $socialite->buildProvider(DeezerProvider::class, $config);
            }
        );
    }
}
