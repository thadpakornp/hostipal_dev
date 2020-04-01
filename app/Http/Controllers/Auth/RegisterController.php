<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\CreateUser;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
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
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return CreateUser::created($data);
    }
}
