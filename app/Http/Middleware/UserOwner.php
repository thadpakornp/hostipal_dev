<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class UserOwner
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
        if(Auth::user()->type == 'Owner') {
            return $next($request);
        }
        return redirect()->back()->with(['error'=> 'ไม่อนุญาตให้เข้าถึง']);
    }
}
