<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManageRequest extends FormRequest
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
            'name' => ['required'],
            'phone' => ['required','max:10'],
            'address' => ['required'],
            'province' => ['required'],
            'country' => ['required'],
            'district' => ['required'],
            'code' => ['required'],
            'g_location_lat' => ['required'],
            'g_location_long' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'กรุณาระบุชื่อสถานพยาบาล',
            'phone.required' => 'กรุณาระบุเบอร์โทรติดต่อ',
            'phone.max' => 'เบอร์โทรติดต่อไม่ถูกต้อง',
            'address.required' => 'กรุณาระบุที่อยู่',
            'province.required' => 'กรุณาเลือกจังหวัด',
            'country.required' => 'กรุณาเลือกอำเภอ/เขต',
            'district.required' => 'กรุณาเลือกตำบล/แขวง',
            'code.required' => 'กรุณาระบุรหัสไปรษณีย์',
            'g_location_lat.required' => 'กรุณาระบุสถานที่ตั้งสถานพยาบาลบนแผนที่',
            'g_location_long.required' => 'กรุณาระบุสถานที่ตั้งสถานพยาบาลบนแผนที่',
        ];
    }
}
