<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Session;
use App\Classes\Resize;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class RegisterController2 extends Controller
{
    public function index() {
        return view('auth.register2');
    }

    public function posted(Request $request){
        $validator = Validator::make($request->all(), [
            'prefix_id' => ['required'],
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required','string','max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
            'phone' => ['required','string','max:10'],
            'office_id' => ['required'],
            'agree-terms' => ['required'],
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
            'office_id.required' => 'กรุณาระบุสถานพยาบาล',
            'agree-terms.required' => 'กรุณายอมรับเงื่อนไขการใช้บริการ',
        ]);

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->back();
        }
        
        $num_user = User::where('type','Owner')->count();

        $user_insert['prefix_id'] = $request->input('prefix_id');
        $user_insert['name'] = $request->input('name');
        $user_insert['surname'] = $request->input('surname');
        $user_insert['phone'] = $request->input('phone');
        $user_insert['office_id'] = $request->input('office_id');
        $user_insert['email'] = $request->input('email');
        $user_insert['password'] = Hash::make($request->input('password'));
        $user_insert['register_at'] = Carbon::now();
        $user_insert['profile'] = 'avatar1.jpg';

        if($num_user == 0) {
            $user_insert['type'] = 'Owner';
            $user_insert['status'] = 'Active';
        } else {
            $user_insert['type'] = 'User';
            $user_insert['status'] = 'Disabled';
        }
        $user = User::create($user_insert);

        if($user) {
             return '<br/><br/><br/><center><h1>ดำเนินการบันทึกข้อมูลแล้ว <br/>กรุณารอดำเนินการ <br/>โดยผู้ดูแลระบบของท่าน</h></center>';
        }
        return '<br/><br/><br/><center><h1><font color="red">เกิดข้อผิดพลาด กรุณาลองใหม่</font></h></center>';
    }
}
