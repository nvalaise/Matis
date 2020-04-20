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
        if(Auth::guard('deezer')->check()) {
            return $next($request);
        }

        else {
            return response()->json(['code' => 403, 'message' => 'Your are not logged with your Deezer account.'], 403);
        }
    }
}
