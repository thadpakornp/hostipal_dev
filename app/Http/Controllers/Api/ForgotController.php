<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

class ForgotController extends Controller
{
    use SendsPasswordResetEmails;

    public function __invoke(Request $request)
    {
        $this->validateEmail($request);
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? response()->json(['code' => '200', 'data' => 'ส่งลิ้งก์เปลี่ยนรหัส่านทางอีเมลที่ลงทะเบียนแล้ว'])
            : response()->json(['code' => '101', 'data' => 'ไม่สามารถรีเซ็นรหัสผ่านได้']);
    }
}
