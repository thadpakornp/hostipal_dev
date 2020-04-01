<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class UserStatus
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
        if(Auth::user()->status != 'Active') {
            Auth::logout();
            return redirect()->route('backend.index');
        }
        return $next($request);
    }
}
