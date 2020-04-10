<?php

namespace App\Http\Controllers\Api;

use App\Classes\ApiToken;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required']
        ], [
            'email.required' => 'กรุณาระบุอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'password.required' => 'กรุณาระบุรหัสผ่าน'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => '100',
                'data' => $validator->messages()->first()
            ]);
        }
        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'code' => '101',
                'data' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'
            ]);
        }
        if (Auth::user()->type == 'User') {
            if (Auth::user()->chart_id != NULL && Auth::user()->status != 'Active') {
                return response()->json([
                    'code' => '101',
                    'data' => 'อีเมลนี้ไม่พร้อมใช้งาน'
                ]);
            }
        }
        if (Auth::user()->deleted_at != NULL) {
            return response()->json([
                'code' => '101',
                'data' => 'ไม่พบข้อมูลในระบบ'
            ]);
        }
        return response()->json([
            'code' => '200',
            'data' => ApiToken::getToken($request->input('device_token'))
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();
        if ($user) {
            $user->api_token = null;
            $user->device_token = null;
            $user->save();
            Auth::logout();
        }
        return response()->json([
            'code' => '200',
            'data' => 'ออกจากระบบ'
        ]);
    }
}
