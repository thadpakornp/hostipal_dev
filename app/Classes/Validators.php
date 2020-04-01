<?php


namespace App\Classes;


use Illuminate\Support\Facades\Validator;
use Auth;
use Session;

class Validators
{
    public static function emails($request,$email = NULL){
        if($request['email']){
            if($email == NULL){
                $email = Auth::user()->email;
            } else {
                $email = $email;
            }
            if($email != $request['email']) {
                $validator = Validator::make($request, [
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
                ], [
                    'email.required' => 'กรุณาระบุอีเมลผู้ใช้งาน',
                    'email.string' => 'กรุณากรอกอีเมล์',
                    'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
                    'email.max' => 'อีเมลยาวเกินไป',
                    'email.unique' => 'อีเมลนี้มีในระบบแล้ว'
                ]);
                if ($validator->fails()) {
                    Session::flash('error', $validator->messages()->first());
                    return false;
                }
                return true;
            }
            return true;
        }
        return true;
    }

    public static function files($request){
        if($request['file-input']){
            $validator = Validator::make($request,[
                'file-input' => ['required','mimes:jpeg,bmp,png','max:10240']
            ],[
                'file-input.required' => 'กรุณาระบุรูปภาพ',
                'file-input.mimes' => 'ประเภทรูปภาพไม่ถูกต้อง',
                'file-input.max' => 'รูปภาพใหญ่เกินไป ไม่ควรเกิน 10M'
            ]);
            if ($validator->fails()) {
                Session::flash('error', $validator->messages()->first());
                return false;
            }
            return true;
        }
        return true;
    }
}
