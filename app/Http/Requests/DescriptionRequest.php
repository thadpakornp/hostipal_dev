<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DescriptionRequest extends FormRequest
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
            'files.*' => ['mimes:png,jpeg,gif,jpg,bmp,doc,docx,xls,xlsx,csv,pdf,mp3,mp4,mov,mpg,mpeg,avi,mpga,wav']
        ];
    }

    public function messages()
    {
        return [
            'files.*.mimes' => 'นามสกุลไฟล์นี้ไม่ได้รับอนุญาต'
        ];
    }
}
