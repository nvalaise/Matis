<?php

namespace App\Http\Middleware;

use Closure;
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
        $user = Auth::user();
            //dd($user);

        if (! is_null($user)) {
            $accounts = $user->accounts();

            switch ($request->route()->getName()) {
                case 'spotify.vue': // try to connect to Spotify account
                    if(! is_null($accounts->spotify_id)) {
                        Auth::logout(); 
                        Auth::loginUsingId($accounts->spotify_id);
                        return $next($request);
                    }
                    break;
                case 'deezer.vue': // try to connect to Spotify account
                    if(! is_null($accounts->deezer_id)) {
                        Auth::logout(); 
                        Auth::loginUsingId($accounts->deezer_id);
                        return $next($request);
                    }
                    break;
            }
        }
        
        return $next($request);
    }
}
