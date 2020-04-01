<?php


namespace App\Classes;


use Illuminate\Support\Str;
use Auth;

class ApiToken
{
    public static function getToken()
    {
        $token = Str::random(60);

        Auth::user()->forceFill([
            'api_token' => hash('sha256', $token),
        ])->save();
        Auth::guard('api')->user();
        return $token;
    }
}
