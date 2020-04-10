<?php


namespace App\Classes;


use Illuminate\Support\Str;
use Auth;

class ApiToken
{
    public static function getToken($device_token)
    {
        $token = Str::random(60);

        Auth::user()->forceFill([
            'api_token' => hash('sha256', $token),
            'device_token' => $device_token,
        ])->save();
        Auth::guard('api')->user();
        return $token;
    }
}
