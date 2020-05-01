<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Extensions\DeezerProvider;
use App\Extensions\SpotifyProvider;

use App\Extensions\DiscogsProvider;
use App\Extensions\DiscogsServer;

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
        $this->bootDiscogsSocialite();
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

    private function bootDiscogsSocialite()
    {
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'discogs',
            function ($app) use ($socialite) {
                $config = $app['config']['services.discogs'];
                return new DiscogsProvider(
                    $this->app['request'], new DiscogsServer($config, null)
                );
            }
        );
    }
}

