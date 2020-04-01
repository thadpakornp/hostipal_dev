<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'prefix_id' => ['required'],
            'name' => ['required'],
            'surname' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'phone' => ['required','max:10'],
            'type' => ['required'],
            'file-input' => ['required','mimes:jpeg,bmp,png','max:10240']
        ];
    }

    public function messages()
    {
        return [
            'prefix_id.required' => 'กรุณาเลือกคำนำหน้า',
            'name.required' => 'กรุณาป้อนชื่อ',
            'surname.required' => 'กรุณาป้อนนามสกุล',
            'email.required' => 'กรุณาป้อนอีเมลใช้งาน',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้มีในระบบแล้ว',
            'phone.required' => 'กรุณาป้อนเบอร์โทรติดต่อ',
            'phone.max' => 'รูปแบบเบอร์โทรติดต่อไม่ถูกต้อง',
            'type.required' => 'กรุณาเลือกประเภทผู้ใช้งาน',
            'file-input.required' => 'กรุณาระบุรูปภาพ',
            'file-input.mimes' => 'ประเภทรูปภาพไม่ถูกต้อง',
            'file-input.max' => 'รูปภาพใหญ่เกินไป ไม่ควรเกิน 10M',
        ];
    }
}
