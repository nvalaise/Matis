<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Extensions\DeezerProvider;
use App\Extensions\SpotifyProvider;

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
        $this->bootSpotifySocialite();
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

    private function bootSpotifySocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'spotify',
            function ($app) use ($socialite) {
                $config = $app['config']['services.spotify'];
                return $socialite->buildProvider(SpotifyProvider::class, $config);
            }
        );
    }
}
