<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use File;
use App\Classes\Resize;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getProfile(Request $request)
    {
        if (Auth::user()->type == 'User') {
            if ($request->input('id')) {
                if (Auth::user()->id != $request->input('id')) {
                    return response()->json([
                        'code' => '107',
                        'data' => 'ไม่อนุญาตให้เข้าถึงข้อมูล'
                    ]);
                }
            }
        }
        if (Auth::user()->type == 'Admin') {
            if ($request->input('id')) {
                if (User::find($request->input('id'))->type == 'Owner') {
                    return response()->json([
                        'code' => '107',
                        'data' => 'ไม่อนุญาตให้เข้าถึงข้อมูล'
                    ]);
                }
                if (User::find($request->input('id'))->office_id != Auth::user()->office_id) {
                    return response()->json([
                        'code' => '107',
                        'data' => 'ไม่อนุญาตให้เข้าถึงข้อมูล'
                    ]);
                }
            }
        }

        if ($request->input('id') == null || $request->input('id') == '') {
            $user = UserResource::make(User::find(Auth::user()->id));
        } else {
            $user = UserResource::make(User::find($request->input('id')));
        }

        if (empty($user)) {
            return response()->json([
                'code' => '200',
                'data' => 'ไม่พบข้อมูลในระบบ'
            ]);
        } else {
            return response()->json([
                'code' => '200',
                'data' => $user
            ]);
        }
    }

    public function getUser()
    {
        if (Auth::user()->type == 'User') {
            return response()->json([
                'code' => '107',
                'data' => 'ไม่อนุญาตให้เข้าถึงข้อมูล'
            ]);
        }
        $users = UserResource::collection(User::where('id', '<>', Auth::user()->id)->whereNull('deleted_at')->GetAll());
        if (empty($users)) {
            return response()->json([
                'code' => '200',
                'data' => 'ไม่พบข้อมูลในระบบ'
            ]);
        } else {

            return response()->json([
                'code' => '200',
                'data' => $users
            ]);
        }
    }

    public function updated(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'prefix_id' => ['required'],
                'name' => ['required', 'string', 'max:255'],
                'surname' => ['required', 'string', 'max:255'],
                'phone' => ['required', 'string', 'max:10'],
            ],
            [
                'prefix_id.required' => 'โปรดเลือกคำนำหน้า',
                'name.required' => 'กรุณาระบุชื่อ',
                'name.string' => 'รูปแบบข้อมูลของชื่อไม่ถูกต้อง',
                'name.max' => 'ชื่อยาวเกินไป',
                'surname.required' => 'กรุณาระบุนามสกุล',
                'surname.string' => 'รูปแบบข้อมูลของนามสกุลไม่ถูกต้อง',
                'surname.max' => 'นามสกุลยาวเกินไป',
                'phone.required' => 'กรุณาระบุเบอร์โทรติดต่อ',
                'phone.max' => 'เบอร์โทรติดต่อยาวเกินไป',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'code' => '100',
                'data' => $validator->messages()->first()
            ]);
        }

        $user = User::find(Auth::user()->id);
        $user->prefix_id = $request->input('prefix_id');
        $user->name = $request->input('name');
        $user->surname = $request->input('surname');
        $user->phone = $request->input('phone');

        if ($request->hasFile('profile')) {
            if ($user->profile != NULL || $user->profile != '') {
                File::delete(public_path('assets/img/profiles/' . $user->profile));
                File::delete(public_path('assets/img/avatars/' . $user->profile));
            }

            $profile = time() . '.' . $request->file('profile')->extension();
            $request->file('profile')->move(public_path('assets/img/profiles'), $profile);
            Resize::Profile($profile);
            $user->profile = $profile;
        }

        if ($user->save()) {
            $code = '200';
            $description = 'แก้ไขข้อมูลเรียบร้อยแล้ว';
        } else {
            $code = '106';
            $description = 'ไม่สามารถแก้ไขข้อมูลได้';
        }
        return response()->json([
            'code' => $code,
            'data' => $description
        ]);
    }

    public function password(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if (empty($user)) {
            return response()->json([
                'code' => '101',
                'data' => 'ไม่พบข้อมูล'
            ]);
        }
        if (!password_verify($request->input('oldPassword'), $user->password)) {
            return response()->json([
                'code' => '101',
                'data' => 'รหัสผ่านเดิมไม่ถูกต้อง'
            ]);
        }
        if ($request->input('newPassword1') != $request->input('newPassword2')) {
            return response()->json([
                'code' => '101',
                'data' => 'รหัสผ่านใหม่ 2 ช่องไม่ตรงกัน'
            ]);
        }
        if (password_verify($request->input('newPassword1'), $user->password)) {
            return response()->json([
                'code' => '101',
                'data' => 'ไม่สามารถเปลี่ยนรหัสผ่านที่เป็นรหัสเดิมได้'
            ]);
        }
        $user->password = Hash::make($request->input('newPassword1'));
        if (!$user->save()) {
            return response()->json([
                'code' => '101',
                'data' => 'เปลี่ยนรหัสผ่านล้มเหลว'
            ]);
        }
        return response()->json([
            'code' => '200',
            'data' => 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว'
        ]);
    }
}
