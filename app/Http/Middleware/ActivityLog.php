<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\ActivityLog as ActivityLogModel;

class ActivityLog
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
        ActivityLogModel::create([
            'user_id' => Auth::user()->id,
            'domain' => $request->getHost(),
            'url' => $request->url(),
            'path' => implode('/', $request->segments()),
            'description' => json_encode($request->all()),
            'method' => $request->method(),
            'client_ip'  => $request->ip(),
            'guard' => Auth::getDefaultDriver(),
            'protocol'   => $request->getProtocolVersion(),
            'user_agent' => $request->userAgent(),
            'session_id' => session()->getId()
        ]);
        return $next($request);
    }
}
