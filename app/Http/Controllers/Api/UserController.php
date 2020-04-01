<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;

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
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ], [
            'id.required' => 'กรุณาระบุไอดีที่ต้องการแก้ไข'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'code' => '100',
                'data' => $validator->messages()->first()
            ]);
        }

        if(Auth::user()->type == 'User'){
            if(Auth::user()->id != $request->input('id')){
                return response()->json([
                    'code' => '107',
                    'data' => 'ไม่อนุญาตให้แก้ไขข้อมูล'
                ]);
            }
        }

        if(Auth::user()->type == 'Admin'){
            $user_required = User::find($request->input('id'));
            if($user_required->type == 'Owner'){
                return response()->json([
                    'code' => '107',
                    'data' => 'ไม่อนุญาตให้แก้ไขข้อมูล'
                ]);
            }
        }

        if (User::find($request->input('id'))->update($request->all())) {
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
}
