<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'phone' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'prefix_id.required' => 'กรุณาเลือกคำนำหน้าชื่อ',
            'name.required' => 'กรุณาระบุชื่อ',
            'surname.required' => 'กรุณาระบุนามสกุล',
            'phone.required' => 'กรุณาระบุเบอร์โทรติดต่อ'
        ];
    }
}
