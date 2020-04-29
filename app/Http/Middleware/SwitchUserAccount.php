<?php

namespace App\Http\Middleware;

use Closure;
use Socialite;
use Illuminate\Support\Facades\Auth;


class SwitchUserAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $authUser = Auth::user();
        if (! is_null($authUser)) {
            $accounts = $authUser->accounts();

            switch ($request->route()->getName()) {

                case 'spotify.vue': // try to connect to Spotify account
                    if(! is_null($accounts->spotify_id)) {
                        return $this->trySwitching($request, $next, $authUser, 'spotify');
                    }
                    break;

                case 'deezer.vue': // try to connect to Spotify account
                    if(! is_null($accounts->deezer_id)) {
                        return $this->trySwitching($request, $next, $authUser, 'deezer');
                    }
                    break;
            }
        }
        
        return $next($request);
    }

    public function trySwitching($request, Closure $next, $authUser, $driver)
    {
        if ($authUser->provider != $driver) {
            $user = $authUser->account($driver);
            if (isset($user)) {
                Auth::loginUsingId($user->id);
                $socialiteUser = Socialite::driver($driver)->userFromToken($user->access_token);
                // dd(Auth::user(), $authUser, $user, $socialiteUser);
            }
        }

        return $next($request);
    }
}
