<?php

namespace App\Http\Controllers\Api;

use App\Helpers\CreateUser;
use App\Http\Controllers\Controller;
use App\Http\Resources\Prefix;
use App\Models\PrefixModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function prefix(){
        return response()->json([
            'code' => '200',
            'data' => Prefix::collection(PrefixModel::all())
        ]);
    }

    public function stored(Request $request){
        $validator = Validator::make($request->all(), [
            'prefix_id' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required','string','max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
            'phone' => ['required','string','max:10'],
            'agree-terms' => ['required'],
            'file-input' => ['required','mimes:jpeg,bmp,png','max:10240']
        ],[
            'prefix_id.required' => 'โปรดเลือกคำนำหน้า',
            'name.required' => 'กรุณาระบุชื่อ',
            'name.string' => 'รูปแบบข้อมูลของชื่อไม่ถูกต้อง',
            'name.max' => 'ชื่อยาวเกินไป',
            'surname.required' => 'กรุณาระบุนามสกุล',
            'surname.string' => 'รูปแบบข้อมูลของนามสกุลไม่ถูกต้อง',
            'surname.max' => 'นามสกุลยาวเกินไป',
            'password.required' => 'กรุณาระบุรหัสผ่าน',
            'password.string' => 'รูปแบบรหัสผ่านไม่ถูกต้อง',
            'password.min' => 'รหัสผ่านต้องมากกว่า 8 ตัวอักษร',
            'password.confirmed' => 'รหัสผ่านไม่ตรงกัน',
            'email.required' => 'กรุณาระบุอีเมลผู้ใช้งาน',
            'email.string' => 'กรุณากรอกอีเมล์',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.max' => 'อีเมลยาวเกินไป',
            'email.unique' => 'อีเมลนี้มีในระบบแล้ว',
            'password_confirmation.required' => 'กรุณาระบุรหัสผ่านอีกครั้ง',
            'phone.required' => 'กรุณาระบุเบอร์โทรติดต่อ',
            'phone.max' => 'เบอร์โทรติดต่อยาวเกินไป',
            'agree-terms.required' => 'กรุณายอมรับเงื่อนไขการใช้บริการ',
            'file-input.required' => 'กรุณาระบุรูปภาพ',
            'file-input.mimes' => 'ประเภทรูปภาพไม่ถูกต้อง',
            'file-input.max' => 'รูปภาพใหญ่เกินไป ไม่ควรเกิน 10M',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => '100',
                'data' => $validator->messages()->first()
            ]);
        }
        if(CreateUser::created($request->all())){
            $code = '200';
            $description = 'ลงทะเบียนเรียบร้อยแล้ว';
        } else {
            $code = '101';
            $description = 'ไม่สามารถลงทะเบียนได้';
        }
        return response()->json([
            'code' => $code,
            'data' => $description
        ]);
    }
}
