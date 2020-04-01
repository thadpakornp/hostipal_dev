<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChartRequest extends FormRequest
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
            'id_card' => ['required', 'numeric'],
            'hbd' => ['required', 'date'],
            'address' => ['required']
        ];
    }

    public function messages()
    {
        return [
            'prefix_id.required' => 'กรุณาเลือกคำนำหน้า',
            'name.required' => 'กรุณาระบุชื่อ',
            'surname.required' => 'กรุณาระบุนามสกุล',
            'id_card.required' => 'กรุณาระบุหมายเลขบัตรประชาชน',
            'id_card.numeric' => 'หมายเลขบัตรประชาชนประกอบด้วยตัวเองเท่านั้น',
            'hbd.required' => 'กรุณาระบุวันเกือนปี เกิด',
            'hbd.date' => 'รูปแบบวันเกิดไม่ถูกต้อง',
            'address.required' => 'กรุณาระบุที่อยู่'
        ];
    }
}
