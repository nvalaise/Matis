<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIsDeezer
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
            return response()->json(['code' => 403, 'message' => 'Your are not logged. Please get connected with one of the applications.'], 403);
        }
        if( is_null(Auth::user()->has('deezer')) ) {
            return response()->json(['code' => 403, 'message' => 'Your have not been connected with Deezer yet.'], 403);
        }

        //dd(Auth::user()->has('deezer'), Auth::user(), Auth::user()->accessToken);
        return $next($request);
    }
}
