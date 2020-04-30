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
            $driver = $request->segment(2);
            $accounts = $authUser->accounts();
            switch ($driver) {

                case 'spotify': // try to connect to Spotify account
                    if(! is_null($accounts->spotify_id)) {
                        return $this->trySwitching($request, $next, $authUser, $driver);
                    }
                    break;

                case 'deezer': // try to connect to Spotify account
                    if(! is_null($accounts->deezer_id)) {
                        return $this->trySwitching($request, $next, $authUser, $driver);
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
                try {
                    $socialiteUser = Socialite::driver($driver)->userFromToken($user->access_token);
                    Auth::logout();
                    Auth::loginUsingId($user->id);
                } catch (\GuzzleHttp\Exception\ClientException $e) {
                    return response()->json(['code' => 403, 'message' => 'Can\'t connect your account with '.ucfirst($driver).'. Try to authenticate again.'], 403);
                } catch (\Exception $e) {
                    return response()->json(['code' => 403, 'message' => 'Can\'t connect your account with '.ucfirst($driver).'. Try to authenticate again.'], 403);
                }

            }
        }



        return $next($request);
    }
}
