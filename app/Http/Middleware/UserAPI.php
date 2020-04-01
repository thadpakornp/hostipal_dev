<?php

namespace App\Http\Middleware;

use Closure;

class UserAPI
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
        $header = $request->header('Accept');
        if ($header == '*/*') {
            return response()->json([
                'code' => '108',
                'data' => [
                    'description' => 'Incorrect header'
                ]
            ]);
        }
        if ($header != 'application/json'){
            return response()->json([
                'code' => '109',
                'data' => [
                    'description' => 'Incorrect header type'
                ]
            ]);
        }
        return $next($request);
    }
}
