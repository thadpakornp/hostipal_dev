<?php

namespace App\Http\Controllers;

use App\Helpers\FormatThai;
use App\Http\Controllers\Api\Notify;
use App\Http\Requests\DescriptionRequest;
use App\Models\User;
use Auth;
use App\Classes\Resize;
use App\Http\Requests\ChartRequest;
use App\Models\Charts_description;
use Carbon\Carbon;
use File;
use App\Models\Charts;
use App\Models\Charts_files;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;

class ChartsController extends Controller
{
    public function index(Request $request)
    {
        $files = Charts_files::where('add_by_user', Auth::user()->id)->whereNull('charts_desc_id')->whereNull('deleted_at')->get();
        if (!empty($files)) {
            foreach ($files as $file) {
                $charts_file = Charts_files::find($file->id);
                $charts_file->deleted_at = Carbon::now();
                $charts_file->save();
                File::delete(public_path('assets/img/photos/' . $file->files));
                File::delete(public_path('assets/img/temnails/' . $file->files));
            }
        }
        $request->session()->forget('upload_id');
        return view('chart_form');
    }

    public function users()
    {
        $users = Charts::Charts();
        return view('chart_users', compact('users'));
    }

    public function feeds($idcard)
    {
        $user = Charts::with(['charts_description'])->where('charts.idcard', decrypt($idcard))->latest()->first();
        $users = Charts::where('idcard', decrypt($idcard))->orderBy('created_at', 'desc')->get();
        return view('chart_feeds', compact('user', 'users'));
    }

    public function feed($id)
    {
        $user = Charts::with(['charts_description'])->where('charts.id', decrypt($id))->latest()->first();
        $users = Charts::where('idcard', $user->idcard)->orderBy('created_at', 'desc')->get();
        return view('chart_feeds', compact('user', 'users'));
    }

    public function fileDestroy(Request $request)
    {
        File::delete(public_path('assets/img/photos/' . $request->input('filename')));
        File::delete(public_path('assets/img/temnails/' . $request->input('filename')));
        Charts_files::where('files', $request->input('filename'))->update(['deleted_at' => Carbon::now()]);
        return response()->json(['success' => 'success'], 200);
    }

    public function edit($id)
    {
        $chart = Charts::find(decrypt($id));
        if (empty($chart)) {
            return redirect()->back()->with(['error' => 'ไม่พบข้อมูล']);
        }
        return view('charts_edit', compact('chart'));
    }

    public function edited(Request $request)
    {
        $chart = Charts::find(decrypt($request->input('id')));
        if (empty($chart)) {
            return redirect()->back()->with(['error' => 'ไม่พบข้อมูล']);
        }
        if ($chart->update($request->all())) {
            $chart->status = 'Activate';
            if ($request->hasFile('profile')) {
                $profile = time() . '.' . $request->file('profile')->extension();
                $request->file('profile')->move(public_path('assets/img/profiles'), $profile);
                Resize::Profile($profile);
                if ($chart->profile != NULL) {
                    File::delete(public_path('assets/img/profiles/' . $chart->profile));
                    File::delete(public_path('assets/img/avatars/' . $chart->profile));
                }
                $chart->profile = $profile;
            }
            $chart->save();
            return redirect()->route('backend.charts.users')->with(['success' => 'แก้ไขเรียบร้อยแล้ว']);
        } else {
            return redirect()->back()->with(['error' => 'ไม่สามารถแก้ไขได้']);
        }
    }

    public function stored(ChartRequest $request)
    {
        if (strlen($request->input('id_card')) != 13) {
            return redirect()->back()->with(['error' => 'หมายเลขบัตรประชาชนไม่ถูกต้อง']);
        }
        $chart_insert['prefix_id'] = $request->input('prefix_id');
        $chart_insert['name'] = $request->input('name');
        $chart_insert['surname'] = $request->input('surname');
        $chart_insert['idcard'] = $request->input('id_card');
        $chart_insert['sex'] = $request->input('sex');
        $chart_insert['hbd'] = $request->input('hbd');
        $chart_insert['phone'] = $request->input('phone');
        $chart_insert['address'] = $request->input('address');
        $chart_insert['hn'] = $request->input('hn');
        $chart_insert['add_by_user'] = Auth::user()->id;
        $chart_insert['status'] = 'Activate';
        $chart_insert['g_location_lat'] = $request->input('g_location_lat_charts');
        $chart_insert['g_location_long'] = $request->input('g_location_long_charts');

        $chart = Charts::create($chart_insert);
        if ($chart) {
            if ($request->hasFile('profile')) {
                $profile = time() . '.' . $request->file('profile')->extension();
                $request->file('profile')->move(public_path('assets/img/profiles'), $profile);
                Resize::Profile($profile);
                $chart_update = Charts::find($chart->id);
                $chart_update->profile = $profile;
                $chart_update->save();
            } else {
                if ($request->input('old_id') != null || $request->input('old_id') != '') {
                    $old_profile = Charts::find($request->input('old_id'));
                    $chart_update = Charts::find($chart->id);
                    $chart_update->profile = $old_profile->profile;
                    $chart_update->save();
                }
            }

            $chart_insert_desc['charts_id'] = $chart->id;
            $chart_insert_desc['description'] = $request->input('description');
            $chart_insert_desc['add_by_user'] = Auth::user()->id;
            $chart_insert_desc['g_location_lat'] = $request->input('g_location_lat_charts_desc');
            $chart_insert_desc['g_location_long'] = $request->input('g_location_long_charts_desc');
            $chart_desc = Charts_description::create($chart_insert_desc);
            if ($chart_desc) {
                if ($request->input('description_id')) {
                    foreach ($request->input('description_id') as $description_id) {
                        $chart_d = Charts_description::find($description_id);
                        $chart_d->charts_id = $chart->id;
                        $chart_d->save();

                        $chart_file = Charts_files::where('charts_desc_id', $description_id);
                        if ($chart_file->count() > 0) {
                            foreach ($chart_file->get() as $file_update) {
                                $update_file = Charts_files::find($file_update->id);
                                $update_file->charts_id = $chart->id;
                                $update_file->save();
                            }
                        }
                    }
                }
                if ($request->session()->has('upload_id')) {
                    Charts_files::whereIn('id', $request->session()->get('upload_id'))->update(['charts_id' => $chart->id, 'charts_desc_id' => $chart_desc->id]);
                }
                //sent notify to web
                $notify_web = new Notify();
                $notify_web->sentNotifyWeb(Auth::user()->id, 'Activate', 'คุณ' . $request->input('name') . ' ' . $request->input('surname') . ' สร้างประวัติแล้ว', route('backend.charts.feeds', encrypt($chart->idcard)));

                // sent notify to app
                $device_token = User::whereNotNull('device_token');
                if ($device_token->count() > 0) {
                    foreach ($device_token->get(['device_token']) as $dt) {
                        $notify_app = new Notify();
                        $notify_app->sentNotifyDevice('Activate', 'คุณ' . $request->input('name') . ' ' . $request->input('surname') . ' สร้างประวัติแล้ว', $dt->device_token, $chart->id);
                    }
                }

                return redirect()->back()->with(['success' => 'สร้างประวัติแล้วเรียบร้อยแล้ว']);
            } else {
                return redirect()->back()->with(['error' => 'ไม่สามารถสร้างรายละเอียดได้']);
            }
        } else {
            return redirect()->back()->with(['error' => 'ไม่สามารถสร้างประวัติผู้ป่วยได้']);
        }
    }


    public
    function descrtipionStored(DescriptionRequest $request)
    {
        $charts_id = decrypt($request->input('charts_id'));
        $desc = Charts_description::create([
            'charts_id' => $charts_id,
            'description' => $request->input('description'),
            'add_by_user' => Auth::user()->id,
            'g_location_lat' => $request->input('g_location_lat') == '13.744674' ? null : $request->input('g_location_lat'),
            'g_location_long' => $request->input('g_location_long') == '100.5633683' ? null : $request->input('g_location_long')
        ]);
        if ($desc) {
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $imageName = date('Ymd') . Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('assets/img/photos'), $imageName);

                    if ($file->getClientOriginalExtension() == 'png' || $file->getClientOriginalExtension() == 'bmp' || $file->getClientOriginalExtension() == 'jpg' || $file->getClientOriginalExtension() == 'jpeg' || $file->getClientOriginalExtension() == 'gif') {
                        Resize::uploads($imageName);
                    }

                    Charts_files::create([
                        'charts_id' => $charts_id,
                        'charts_desc_id' => $desc->id,
                        'add_by_user' => Auth::user()->id,
                        'files' => $imageName,
                        'type_files' => $file->getClientOriginalExtension()
                    ]);
                }
            }


            $charts_Desc = Charts_description::where('add_by_user', '<>', Auth::user()->id)->whereNull('deleted_at')->where('charts_id', $charts_id)->distinct('add_by_user')->get(['add_by_user']);
            if (!empty($charts_Desc)) {
                foreach ($charts_Desc as $cd) {
                    $charts = Charts::find($charts_id, ['idcard', 'name', 'surname']);
                    $notify_web = new Notify();
                    $notify_web->sentNotifyWeb(Auth::user()->id, 'Description', 'คุณ' . $charts->name . ' ' . $charts->surname . ' ถูกบันทึกรายละเอียดเพิ่มเติม', route('backend.charts.feed', encrypt($charts_id)));
                    $device_token = User::find($cd->add_by_user, ['device_token']);
                    if ($device_token->device_token != null) {
                        $notify_app = new Notify();
                        $notify_app->sentNotifyDevice('Description', 'คุณ' . $charts->name . ' ' . $charts->surname . ' ถูกบันทึกรายละเอียดเพิ่มเติม', $device_token->device_token, $charts_id);
                    }
                }
            }

            return redirect()->back()->with(['success' => 'บันทึกข้อมูลเรียบร้อยแล้ว']);
        } else {
            return redirect()->back()->with(['error' => 'ไม่สามารถบันทึกข้อมูลได้']);
        }
    }

    public
    function files($id)
    {
        $user = Charts::with(['charts_files'])->find(decrypt($id));
        return view('chart_files', compact('user'));
    }

    public
    function download($id)
    {
        $file = Charts_files::find(decrypt($id));
        if (empty($file)) {
            return redirect()->back()->with(['error' => 'ไฟล์นี้ไม่พร้อมให้ดาวน์โหลด']);
        }
        if (!File::exists(public_path('assets/img/photos/' . $file->files))) {
            return redirect()->back()->with(['error' => 'ไฟล์นี้ไม่พร้อมให้ดาวน์โหลด']);
        }
        return response()->download(public_path('assets/img/photos/' . $file->files));
    }

    public
    function destroy(Request $request)
    {
        $desc = Charts_description::find(decrypt($request->input('id')));
        if (Auth::user()->type == 'Owner' || $desc->add_by_user == Auth::user()->id) {
            if (empty($desc)) {
                return response()->json(['error' => 'ไม่พบข้อมูล'], 404);
            } elseif ($desc->deleted_at != NULL) {
                return response()->json(['error' => 'ไม่พร้อมใช้งาน'], 404);
            } else {
                $files = Charts_files::where('charts_desc_id', $desc->id);
                if ($files->count() > 0) {
                    $file = Charts_files::where('charts_desc_id', $desc->id)->update(['deleted_at' => Carbon::now()]);
                    if ($file) {
                        $desc->deleted_at = Carbon::now();
                        if ($desc->save()) {
                            foreach ($files->get() as $f) {
                                File::delete(public_path('assets/img/photos/' . $f->files));
                            }
                            return response()->json(['success' => 'ลบเรียบร้อยแล้ว'], 200);
                        }
                        return response()->json(['error' => 'ไม่สามารถลบรายละเอียดได้'], 404);
                    }
                    return response()->json(['error' => 'ไม่สามารถลบไฟล์ได้'], 404);
                }
                $desc->deleted_at = Carbon::now();
                if ($desc->save()) {
                    return response()->json(['success' => 'ลบเรียบร้อยแล้ว'], 200);
                }
            }
        } else {
            if(User::checkPermission($desc->add_by_user) != 'Owner' && Auth::user()->type == 'Admin'){
                if (empty($desc)) {
                    return response()->json(['error' => 'ไม่พบข้อมูล'], 404);
                } elseif ($desc->deleted_at != NULL) {
                    return response()->json(['error' => 'ไม่พร้อมใช้งาน'], 404);
                } else {
                    $files = Charts_files::where('charts_desc_id', $desc->id);
                    if ($files->count() > 0) {
                        $file = Charts_files::where('charts_desc_id', $desc->id)->update(['deleted_at' => Carbon::now()]);
                        if ($file) {
                            $desc->deleted_at = Carbon::now();
                            if ($desc->save()) {
                                foreach ($files->get() as $f) {
                                    File::delete(public_path('assets/img/photos/' . $f->files));
                                }
                                return response()->json(['success' => 'ลบเรียบร้อยแล้ว'], 200);
                            }
                            return response()->json(['error' => 'ไม่สามารถลบรายละเอียดได้'], 404);
                        }
                        return response()->json(['error' => 'ไม่สามารถลบไฟล์ได้'], 404);
                    }
                    $desc->deleted_at = Carbon::now();
                    if ($desc->save()) {
                        return response()->json(['success' => 'ลบเรียบร้อยแล้ว'], 200);
                    }
                }
            }
        }
        return response()->json(['error' => 'ไม่อนุญาตให้ดำเนินการ'], 404);
    }

    public
    function filesDestroy(Request $request)
    {
        $file = Charts_files::find(decrypt($request->input('id')));
        if (Auth::user()->type == 'Owner' || $file->add_by_user == Auth::user()->id) {
            if (empty($file)) {
                return response()->json(['error' => 'ไม่พบข้อมูล'], 404);
            }
            $file->deleted_at = Carbon::now();
            if ($file->save()) {
                File::delete(public_path('assets/img/photos/' . $file->files));
                return response()->json(['success' => 'ลบเรียบร้อยแล้ว'], 200);
            }
            return response()->json(['error' => 'ไม่สามารถลบไฟล์ได้'], 404);
        }
        return response()->json(['error' => 'ไม่อนุญาตให้ดำเนินการ'], 404);
    }

    public
    function descrtipionEdit(Request $request)
    {
        $request->session()->forget('upload_id');

        $desc = Charts_description::find(decrypt($request->input('id')));
        if (empty($desc)) {
            return response()->json(['error' => 'ไม่พบข้อมูล'], 404);
        }

        $html = '';
        $html .= '<form class="form-horizontal push-10-t push-10" action="' . route("backend.charts.description.edited") . '"
                              method="post" enctype="multipart/form-data">
                             <input type="hidden" name="_token" value="' . csrf_token() . '">
                             <input type="hidden" name="id" value="' . encrypt($desc->id) . '">
                   <div class="row">
                     <div class="col-sm-12">
                        <div class="form-group">
                            <textarea type="text" class="form-control" rows="5" name="description">' . $desc->description . '</textarea>
                        </div>';

        $files = Charts_files::where('charts_desc_id', $desc->id)->whereNull('deleted_at');
        if ($files->count() > 0) {
            $html .= '<div class="form-group"><ul class="list list-li-clearfix">';
            foreach ($files->get() as $key => $file) {
                $html .= '<li id="file_id_' . $key . '"><a href="javascript:void(0)"><i
                                                    class="si si-close text-danger"
                                                    onclick="delete_files(' . $key . ',' . $file->id . ')"></i></a><input type="text" readonly class="form-control" name="uploads[]" value="' . $file->files . '"></li>';
            }
            $html .= '</ul></div>';
        }

        $html .= '<div class="form-group">
                              เลือกหลายไฟล์โดยการกด Ctrl <font class="text-danger">(เฉพาะไฟล์
                                .png,.jpeg,.gif,.jpg,.bmp,.mov,.mp4)</font>
                            <input type="file" name="files[]" multiple class="form-control"
                                   accept=".png,.jpeg,.gif,.jpg,.bmp,.mov,.mp4">
                        </div>
                     </div>
                     <div class="col-sm-12">
                        <button type="submit" class="btn btn-group btn-block btn-warning">แก้ไข</button>
                     </div>
                  </div></form>';
        return $html;
    }

    public
    function filesDeleted(Request $request)
    {
        $request->session()->push('upload_id', $request->input('upload_id'));
    }

    public
    function descrtipionEdited(DescriptionRequest $request)
    {
        $desc = Charts_description::find(decrypt($request->input('id')));
        if (empty($desc)) {
            return redirect()->back()->with(['error' => 'ไม่พบข้อมูล']);
        }

        if ($request->session()->has('upload_id')) {
            foreach ($request->session()->get('upload_id') as $id_file) {
                $file_name = Charts_files::find($id_file);
                File::delete(public_path('assets/img/photos/' . $file_name->files));
                File::delete(public_path('assets/img/temnails/' . $file_name->files));
                $file_name->deleted_at = Carbon::now();
                $file_name->save();
            }
        }
        if ($desc->update($request->all())) {
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $imageName = date('Ymd') . Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('assets/img/photos'), $imageName);
                    if ($file->getClientMimeType() == 'image/*') {
                        Resize::uploads($imageName);
                    }

                    Charts_files::create([
                        'charts_id' => $desc->charts_id,
                        'charts_desc_id' => $desc->id,
                        'add_by_user' => Auth::user()->id,
                        'files' => $imageName,
                        'type_files' => $file->getClientOriginalExtension()
                    ]);
                }
            }
            return redirect()->back()->with(['success' => 'แก้ไขเรียบร้อยแล้ว']);
        }
    }

    public
    function uploaded(Request $request)
    {
        if ($request->file('file')) {
            $image = $request->file('file');
            $imageName = $image->getClientOriginalName();
            $request->file('file')->move(public_path('assets/img/photos'), $imageName);
            if ($image->getClientOriginalExtension() == 'png' || $image->getClientOriginalExtension() == 'bmp' || $image->getClientOriginalExtension() == 'jpg' || $image->getClientOriginalExtension() == 'jpeg' || $image->getClientOriginalExtension() == 'gif') {
                Resize::uploads($imageName);
            }

            $id = Charts_files::create([
                'files' => $imageName,
                'add_by_user' => Auth::user()->id,
                'type_files' => $request->file('file')->getClientOriginalExtension()
            ]);
            $request->session()->push('upload_id', $id->id);
            return response()->json(['id' => $request->session()->get('upload_id')], 200);
        }
    }

    public
    function datafromApp()
    {
        $charts = Charts_description::whereNull('charts_id')->whereNull('deleted_at')->orderBy('created_at', 'desc');
        if ($charts->count() > 0) {
            $table = '';
            foreach ($charts->get() as $chart) {
                $add_by = $chart->username->prefix->name . ' ' . $chart->username->name . ' ' . $chart->username->surname;
                $table .= '<tr><td class="text-center"><input type="checkbox" name="description_id[]" value="' . $chart->id . '"></td><td>' . $chart->description . '<br/>';

                $files = Charts_files::whereNull('deleted_at')->where('charts_desc_id', $chart->id)->get();
                if (!empty($files)) {
                    foreach ($files as $file) {
                        if ($file->type_files == 'csv' || $file->type_files == 'xls' || $file->type_files == 'xlsx' || $file->type_files == 'pdf' || $file->type_files == 'doc' || $file->type_files == 'docx' || $file->type_files == 'pdf' || $file->type_files == 'gz' || $file->type_files == 'tgz' || $file->type_files == 'htm' || $file->type_files == 'html' || $file->type_files == 'pptx' || $file->type_files == 'ppt') {
                            $table .= '<ul>
                                                    <li>
                                                        <a href="' . url('backend/charts/get/' . encrypt($file->id) . '/files/show') . '">' . $file->files . '</a>
                                                    </li>
                                                </ul>';
                        }
                    }

                    foreach ($files as $file) {
                        if ($file->type_files == 'png' || $file->type_files == 'bmp' || $file->type_files == 'jpg' || $file->type_files == 'jpeg' || $file->type_files == 'gif') {
                            $table .= '<div class="col-xs-6">
                                                        <a
                                                           href="' . url('backend/charts/get/' . encrypt($file->id) . '/files/show') . '" target="_blank">
                                                            <img class="img-responsive"
                                                                 src="' . config('app.asset_url') . '/assets/img/temnails/' . $file->files . '"
                                                                 alt="">
                                                        </a>
                                                    </div>';
                        }
                    }

                    foreach ($files as $file) {
                        if ($file->type_files == 'mov' || $file->type_files == 'mp4' || $file->type_files == 'm4v' || $file->type_files == 'wmv' || $file->type_files == 'mov' || $file->type_files == 'mpg' || $file->type_files == 'mpeg' || $file->type_files == 'avi') {
                            $table .= '<video controls width="100%"><source src="' . config('app.asset_url') . '/assets/img/photos/' . $file->files . '" type="video/' . $file->type_files . '" /></video>';
                        }
                    }

                    foreach ($files as $file) {
                        if ($file->type_files == 'mp3' || $file->type_files == 'wav') {
                            $table .= '<audio controls width="100%"><source src="' . config('app.asset_url') . '/assets/img/photos/' . $file->files . '" type="audio/mp3" /></audio>';
                        }
                    }
                }

                $table .= '</td><td class="text-center"><a target="_blank" href="http://maps.google.com/?q=' . $chart->g_location_lat . ',' . $chart->g_location_long . '">' . $chart->g_location_lat . ' ' . $chart->g_location_long . '</a></td><td class="text-center">' . $add_by . '</td><td class="text-center">' . FormatThai::DateThai($chart->created_at) . '</td></tr>';
            }
        } else {
            $table = '<tr><td class="text-center text-danger" colspan="4">ไม่พบข้อมูล</td></tr>';
        }
        return response()->json([
            'info' => $table
        ]);
    }

    public
    function showFiles($id)
    {
        $file = Charts_files::find(decrypt($id));
        if (!empty($file)) {
            if ($file->type_files == 'png' || $file->type_files == 'bmp' || $file->type_files == 'jpg' || $file->type_files == 'jpeg' || $file->type_files == 'gif') {
                return '<center><img src="' . config('app.asset_url') . '/assets/img/photos/' . $file->files . '"></center>';
            } else if ($file->type_files == 'mp4' || $file->type_files == 'mov' || $file->type_files == 'mpg' || $file->type_files == 'mpeg' || $file->type_files == 'avi') {
                return '<center><video controls width="100%"><source src="' . public_path() . '/assets/img/photos/' . $file->files . '" type="video/' . $file->type_files . '">Your browser does not support the video tag.</video></center>';
            } else if ($file->type_files == 'mp3') {
                return '<center><audio controls width="100%"><source src="' . public_path() . '/assets/img/photos/' . $file->files . '" type="video/' . $file->type_files . '"></audio></center>';
            } else {
                return response()->download(public_path() . '/assets/img/photos/' . $file->files);
            }
        } else {
            return '<font color="red"><center><h2>ไม่พบข้อมูล</h2></center></font>';
        }
    }

    public
    function iCheck(Request $request)
    {
        return response()->json([
            'info' => Charts::where('idcard', $request->input('id'))->latest()->first()
        ], 200);
    }

    public
    function successChart(Request $request)
    {
        $charts = Charts::find(decrypt($request->input('id')));
        if (!empty($charts)) {
            $charts->status = 'Deactivate';
            if ($charts->save()) {
                Charts_description::create([
                    'charts_id' => $charts->id,
                    'description' => 'สิ้นสุดการรักษาหรือกระบวนการรักษาเสร็จสิ้นแล้ว',
                    'add_by_user' => Auth::user()->id,
                    'g_location_lat' => $request->input('g_location_lat_charts_desc'),
                    'g_location_long' => $request->input('g_location_long_charts_desc')
                ]);
                return response()->json(['success' => 'success'], 200);
            }
            return response()->json(['error' => 'error'], 401);
        }
        return response()->json(['error' => 'error'], 404);
    }
}
