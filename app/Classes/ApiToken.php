<?php


namespace App\Classes;


use Illuminate\Support\Str;
use Auth;
use App\Models\User;

class ApiToken
{
    public static function getToken($userid, $device_token)
    {
        $token = Str::random(250);

        User::find($userid)->forceFill([
            'api_token' => hash('sha256', $token),
            'device_token' => $device_token,
        ])->save();

        return $token;
    }
}
