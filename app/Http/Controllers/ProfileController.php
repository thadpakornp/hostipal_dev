<?php

namespace App\Http\Controllers;

use App\Classes\Resize;
use App\Classes\Validators;
use App\Helpers\FormatThai;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\UserRequest;
use App\Models\OfficeModel;
use App\Models\User;
use App\Notifications\RegisterNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Session;
use Illuminate\Support\Facades\Validator;
use Auth;
use File;
use Illuminate\Support\Str;

class ProfileController extends Controller {

    public function __construct() {
        $this->middleware(['user.status'])->except('updated');
    }

    public function index() {
        $users = User::where('id', '<>', Auth::user()->id)->whereNull('deleted_at')->Gets(['id', 'prefix_id', 'name', 'email', 'surname', 'status', 'office_id', 'profile']);
        $offices = OfficeModel::whereNull('deleted_at')->get(['id', 'name']);
        return view('users', compact('users', 'offices'));
    }

    public function create() {
        $offices = OfficeModel::whereNull('deleted_at')->Type()->get(['id', 'name']);
        return view('users_form', compact('offices'));
    }

    public function created(UserRequest $request) {
        $str_password = Str::random(8);
        $user_insert['prefix_id'] = $request->input('prefix_id');
        $user_insert['name'] = $request->input('name');
        $user_insert['surname'] = $request->input('surname');
        $user_insert['phone'] = $request->input('phone');
        $user_insert['email'] = $request->input('email');
        $user_insert['password'] = Hash::make($str_password);
        $user_insert['register_at'] = Carbon::now();
        $user_insert['type'] = $request->input('type');
        $user_insert['office_id'] = $request->input('office_id');
        $user_insert['status'] = 'Active';

        if (Auth::user()->type != "Owner" || $request->input('type') != 'Owner') {
            $validator = Validator::make($request->all(), [
                        'office_id' => ['required'],
                            ], [
                        'office_id.required' => 'กรุณาเลือดสังกัดสถานพยาบาล',
            ]);

            if ($validator->fails()) {
                Session::flash('error', $validator->messages()->first());
                return redirect()->back();
            }
        }

        $user = User::create($user_insert);
        if ($user) {
            $profile = time() . '.' . $request->file('file-input')->getClientOriginalExtension();
            $request->file('file-input')->move(public_path('assets/img/profiles'), $profile);
            Resize::Profile($profile);
            $user_update = User::find($user->id);
            $user_update->profile = $profile;
            $user_update->save();

            //Send password to mail
            $user->notify(new RegisterNotification($str_password));
            return redirect()->route('backend.users.index')->with(['success' => 'ระบบส่งรหัสผ่านไปยัง ' . $user->email]);
        } else {
            return redirect()->route('backend.users.index')->with(['error' => 'ไม่สามารถสร้างผู้ใช้งานได้']);
        }
    }

    public function edit($id) {
        $user = User::find(decrypt($id), ['id', 'prefix_id', 'name', 'email', 'surname', 'status', 'office_id', 'profile', 'type', 'phone']);
        $offices = OfficeModel::whereNull('deleted_at')->get(['id', 'name']);
        if (empty($user)) {
            return redirect()->back()->with(['error' => 'ไม่พบข้อมูลผู้ใช้งานดังกล่าว']);
        }
        return view('users_edit', compact('user', 'offices'));
    }

    public function edited(Request $request) {
        $user = User::find(decrypt($request->input('id')));
        if (empty($user)) {
            return redirect()->back()->with(['error' => 'ไม่พบข้อมูลผู้ใช้งานดังกล่าว']);
        }

        $validator = Validator::make($request->all(), [
                    'prefix_id' => ['required'],
                    'name' => ['required'],
                    'surname' => ['required'],
                    'phone' => ['required', 'max:10'],
                    'type' => ['required'],
                    'status' => ['required']
                        ], [
                    'prefix_id.required' => 'กรุณาเลือกคำนำหน้าชื่อ',
                    'name.required' => 'กรุณาระบุชื่อ',
                    'surname.required' => 'กรุณาระบุนามสกุล',
                    'phone.required' => 'กรุณาระบุเบอร์โทรติดต่อ',
                    'phone.max' => 'รูปแบบเบอร์โทรติดต่อไม่ถูกต้อง',
                    'type.required' => 'กรุณาเลือกประเภทผู้ใช้งาน',
                    'status.required' => 'กรุณาเลือกสถานะผู้ใช้งาน'
        ]);

        if (Auth::user()->type != "Owner" || $request->input('type') != 'Owner') {
            $validator = Validator::make($request->all(), [
                        'office_id' => ['required'],
                            ], [
                        'office_id.required' => 'กรุณาเลือดสังกัดสถานพยาบาล',
            ]);
        }

        if ($validator->fails()) {
            Session::flash('error', $validator->messages()->first());
            return redirect()->back();
        }

        if ($request->input('email')) {
            if (!Validators::emails($request->all(), $user->email)) {
                return redirect()->back();
            } else {
                $user_update['email'] = $request->input('email');
            }
        }

        if ($request->hasFile('file-input')) {
            if (!Validators::files($request->all())) {
                return redirect()->back();
            } else {
                $profile = time() . '.' . $request->file('file-input')->extension();
                $request->file('file-input')->move(public_path('assets/img/profiles'), $profile);
                Resize::Profile($profile);
                $user_update['profile'] = $profile;

                if ($user->profile != NULL || $user->profile != '') {
                    File::delete(public_path('assets/img/profiles/' . $user->profile));
                    File::delete(public_path('assets/img/avatars/' . $user->profile));
                }
            }
        }
        $user_update['prefix_id'] = $request->input('prefix_id');
        $user_update['name'] = $request->input('name');
        $user_update['surname'] = $request->input('surname');
        $user_update['phone'] = $request->input('phone');
        $user_update['type'] = $request->input('type');
        $user_update['office_id'] = $request->input('office_id');
        $user_update['status'] = $request->input('status');
        if ($user->update($user_update)) {
            Session::flash('success', 'เปลี่ยนแปลงข้อมูลเรียบร้อยแล้ว');
        } else {
            Session::flash('error', 'ไม่สามารถแก้ไขข้อมูลได้');
        }
        return redirect()->route('backend.users.index');
    }

    public function updated(ProfileRequest $request) {
        $user = User::find(Auth::user()->id);

        if ($request->input('email')) {
            if (!Validators::emails($request->all(), Auth::user()->email)) {
                return redirect()->back();
            } else {
                $data['email'] = $request->input('email');
            }
        }

        if ($request->input('password') || $request->input('password_confirmation')) {
            $validator = Validator::make($request->all(), [
                        'password' => ['required', 'string', 'min:8', 'confirmed'],
                        'password_confirmation' => ['required', 'string', 'min:8']
                            ], [
                        'password.required' => 'กรุณาระบุรหัสผ่าน',
                        'password.string' => 'รูปแบบรหัสผ่านไม่ถูกต้อง',
                        'password.min' => 'รหัสผ่านต้องมากกว่า 8 ตัวอักษร',
                        'password.confirmed' => 'รหัสผ่านไม่ตรงกัน',
                        'password_confirmation.required' => 'กรุณาระบุรหัสผ่านอีกครั้ง'
            ]);
            if ($validator->fails()) {
                Session::flash('error', $validator->messages()->first());
                return redirect()->back();
            }
            $data['password'] = Hash::make($request->input('password'));
        }
        if ($request->hasFile('file-input')) {
            if (!Validators::files($request->all())) {
                return redirect()->back();
            } else {
                $profile = time() . '.' . $request->file('file-input')->extension();
                $request->file('file-input')->move(public_path('assets/img/profiles'), $profile);
                Resize::Profile($profile);
                $data['profile'] = $profile;

                if ($user->profile != NULL || $user->profile != '') {
                    File::delete(public_path('assets/img/profiles/' . $user->profile));
                    File::delete(public_path('assets/img/avatars/' . $user->profile));
                }
            }
        }

        $data['prefix_id'] = $request->input('prefix_id');
        $data['name'] = $request->input('name');
        $data['surname'] = $request->input('surname');
        $data['phone'] = $request->input('phone');
        if (Auth::user()->type == 'Owner' || Auth::user()->type == 'Admin') {
            $data['office_id'] = $request->input('hospital');
        }

        if ($user->update($data)) {
            Session::flash('success', 'เปลี่ยนแปลงข้อมูลเรียบร้อยแล้ว');
        } else {
            Session::flash('error', 'ไม่สามารถดำเนินการได้');
        }
        return redirect()->back();
    }

    public function destroy(Request $request) {
        $user = User::find(decrypt($request->input('id')));
        if (empty($user)) {
            return response()->json(['error' => 'ไม่พบข้อมูลที่ต้องการลบ'], 401);
        }
        $user->deleted_at = Carbon::now();
        if (!$user->save()) {
            return response()->json(['error' => 'ไม่สามารถลบได้'], 401);
        }
    }

    public function details(Request $request) {
        $models = '';

        $user = User::find(decrypt($request->input('id')), ['id', 'prefix_id', 'name', 'email', 'surname', 'status', 'office_id', 'profile', 'type', 'phone', 'register_at']);
        if (empty($user)) {
            return response()->json(['error' => 'ไม่สามารถโหลดข้อมูลได้'], 401);
        }
        if ($user->status == 'Active') {
            $status = '<span class="label label-success">ใช้งาน</span>';
        } else {
            $status = '<span class="label label-danger">รออนุมัติ</span>';
        }
        if ($user->type == 'Owner') {
            $users = '<span class="label label-success">ผู้ดูแลระบบสูงสุด</span>';
        } elseif ($user->type == 'Admin') {
            $users = '<span class="label label-danger">ผู้ดูแลสถานพยาบาล</span>';
        } else {
            $users = '<span class="label label-warning">ผู้ใช้งาน</span>';
        }
        if (isset($user->office->name)) {
            $office_name = $user->office->name;
        } else {
            $office_name = null;
        }
        if (isset($user->office->phone)) {
            $office_phone = FormatThai::PhoneThai($user->office->phone);
        } else {
            $office_phone = null;
        }
        if (isset($user->office->address)) {
            $office_address = $user->office->address;
        } else {
            $office_address = null;
        }
        if (isset($user->office->district)) {
            $office_district = $user->office->district;
        } else {
            $office_district = null;
        }
        if (isset($user->office->country)) {
            $office_country = $user->office->country;
        } else {
            $office_country = null;
        }
        if (isset($user->office->province)) {
            $office_province = $user->office->province;
        } else {
            $office_province = null;
        }
        if (isset($user->office->code)) {
            $office_code = $user->office->code;
        } else {
            $office_code = null;
        }
        if (isset($user->office->g_location_lat)) {
            $office_g_location_lat = $user->office->g_location_lat;
        } else {
            $office_g_location_lat = null;
        }
        if (isset($user->office->g_location_long)) {
            $office_g_location_long = $user->office->g_location_long;
        } else {
            $office_g_location_long = null;
        }
        $request_approve = '<div class="row">
                                            <div class="col-sm-6">
                                                <a class="block block-link-hover3 text-center"
                                                   style="border-style: solid;"
                                                   href="#" onclick="approve(this);" data-id="' . encrypt($user->id) . '" data-name="' . $user->prefix->name . ' ' . $user->name . ' ' . $user->surname . '">
                                                    <div class="block-content block-content-full">
                                                        <div class="h1 font-w700"><i class="fa fa-yoast"></i></div>
                                                    </div>
                                                    <div
                                                        class="block-content block-content-full block-content-mini bg-gray-lighter text-success font-w600">
                                                        อนุมัติ
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-sm-6">
                                                <a class="block block-link-hover3 text-center"
                                                   style="border-style: solid;"
                                                   href="#" onclick="rejected(this);" data-id="' . encrypt($user->id) . '" data-name="' . $user->prefix->name . ' ' . $user->name . ' ' . $user->surname . '">
                                                    <div class="block-content block-content-full">
                                                        <div class="h1 font-w700 text-danger"><i
                                                                class="fa fa-window-close-o"></i></div>
                                                    </div>
                                                    <div
                                                        class="block-content block-content-full block-content-mini bg-gray-lighter text-danger font-w600">
                                                        ไม่อนุมัติ
                                                    </div>
                                                </a>
                                            </div>
                                        </div>';

        $request_profile = '<div class="block">
                                            <div class="block-content text-center overflow-hidden">
                                                <div class="push-30-t push animated fadeInDown js-gallery">
                                                    <a class="img-avatar img-avatar128 img-link" href="' . asset("assets/img/profiles/" . $user->profile) . '"
                                                     ><img class="img-responsive" src="' . asset("assets/img/avatars/" . $user->profile) . '" alt=""></a>
                                                </div>
                                                <div class="push-30 animated fadeInUp">
                                                    <h2 class="h2 font-w600 push-5" style="font-family: \'Sarabun\', sans-serif;padding-bottom: 5px;">
                                                        ' . $user->prefix->name . ' ' . $user->name . ' ' . $user->surname . '
                                                    </h2>
                                                    <h3 class="h5 text-muted" style="font-family: \'Sarabun\', sans-serif;">' . $users . '</h3>
                                                </div>
                                            </div>
                                        </div>';

        $request_account = '<div class="block">
                                            <div class="block-header bg-gray-lighter">
                                                <h3 class="block-title" style="font-family: \'Sarabun\', sans-serif;">รายละเอียดผู้ใช้งาน</h3>
                                            </div>
                                            <div class="block-content">
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="block block-bordered">
                                                            <div class="block-header">
                                                                <h3 class="block-title" style="font-family: \'Sarabun\', sans-serif;">บัญชีผู้ใช้งาน</h3>
                                                            </div>
                                                            <div class="block-content block-content-full">
                                                                    <table border="0">
                                                                         <tr>
                                                                            <td width="35%" style="padding-bottom: 5px">อีเมลผู้ใช้งาน</td>
                                                                            <td width="65%" style="padding-bottom: 5px">' . $user->email . '</td>
                                                                         </tr>
                                                                         <tr>
                                                                            <td width="35%" style="padding-bottom: 5px">เบอร์ติดต่อ</td>
                                                                            <td width="65%" style="padding-bottom: 5px">' . FormatThai::PhoneThai($user->phone) . '</td>
                                                                         </tr>
                                                                         <tr>
                                                                            <td width="35%" style="padding-bottom: 5px">วันที่สมัคร</td>
                                                                            <td width="65%" style="padding-bottom: 5px;min-width: 240px;">' . FormatThai::DateThai($user->register_at) . '</td>
                                                                         </tr>
                                                                         <tr>
                                                                            <td width="35%">สถานะ</td>
                                                                            <td width="65%">' . $status . '</td>
                                                                         </tr>
                                                                    </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="block block-bordered">
                                                            <div class="block-header">
                                                                <h3 class="block-title" style="font-family: \'Sarabun\', sans-serif;">สังกัดสถานพยาบาล</h3>
                                                            </div>
                                                            <div class="block-content block-content-full">
                                                                    <table border="0">
                                                                         <tr>
                                                                            <td width="35%" style="padding-bottom: 5px">ชื่อสถานพยาบาล</td>
                                                                            <td width="65%" style="padding-bottom: 5px">' . $office_name . '</td>
                                                                         </tr>
                                                                         <tr>
                                                                            <td width="35%" style="padding-bottom: 5px">เบอร์โทรติดต่อ</td>
                                                                            <td width="65%" style="padding-bottom: 5px">' . $office_phone . '</td>
                                                                         </tr>
                                                                         <tr>
                                                                            <td width="35%" style="padding-bottom: 5px;">ที่อยู่</td>
                                                                            <td width="65%" style="padding-bottom: 5px;min-width: 240px;">' . $office_address . ' ' . $office_district . ' ' . $office_country . ' ' . $office_province . ' ' . $office_code . '</td>
                                                                         </tr>
                                                                         <tr>
                                                                            <td width="35%">แผนที่</td>
                                                                            <td width="65%"><a href="https://maps.google.com/?q=' . $office_g_location_lat . ',' . $office_g_location_long . '" target="_blank">' . $office_g_location_lat . ' ' . $office_g_location_long . '</a></td>
                                                                         </tr>
                                                                    </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';

        if ($user->type != 'User') {
            $models .= $request_profile . $request_account;
        } else {
            if ($user->status == 'Disabled') {
                $models .= $request_approve . $request_profile . $request_account;
            } else {
                $models .= $request_profile . $request_account;
            }
        }

        return $models;
    }

    public function approved(Request $request) {
        $user = User::find(decrypt($request->input('id')));
        if (empty($user)) {
            return response()->json(['error' => 'ไม่พบข้อมูลผู้ใช้งาน'], 403);
        } else {
            $user->status = 'Active';
            if ($user->save()) {
                return response()->json(['success' => 'อนุมัติรายการที่เลือกเรียบร้อยแล้ว'], 200);
            } else {
                return response()->json(['error' => 'ไม่สามารถอนุมัติได้'], 401);
            }
        }
    }

    public function rejected(Request $request) {
        $user = User::find(decrypt($request->input('id')));
        if (empty($user)) {
            return response()->json(['error' => 'ไม่พบข้อมูลผู้ใช้งาน'], 403);
        } else {
            $user->deleted_at = Carbon::now();
            if ($user->save()) {
                return response()->json(['success' => 'ไม่อนุมัติรายการที่เลือกเรียบร้อยแล้ว'], 200);
            } else {
                return response()->json(['error' => 'ไม่สามารถดำเนินการได้'], 401);
            }
        }
    }

    public function logoutonweb(Request $request){
        Auth::logout();
        return response()->json(['success' => 'ออกจากระบบเรียบร้อยแล้ว','redirect'=> url('/')], 200);
    }

}
