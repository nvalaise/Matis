<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIsSpotify
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
        if(! Auth::check()) {
            return response()->json(['code' => 403, 'message' => 'Your are not logged. Please get connected first with your account.'], 403);
        }
        if( is_null(Auth::user()->has('spotify')) ) {
            return response()->json(['code' => 403, 'message' => 'Your have not been connected with Spotify yet.'], 403);
        }

        return $next($request);
    }
}
