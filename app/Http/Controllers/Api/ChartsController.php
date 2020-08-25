<?php

namespace App\Http\Controllers\Api;


use App\Classes\Resize;
use App\Http\Controllers\Controller;
use App\Http\Resources\Chart as ChartResource;
use App\Http\Resources\Charts_info;
use App\Http\Resources\ChartsDate;
use App\Http\Resources\ChartsDescriptionResource;
use App\Http\Resources\ChatsResource;
use App\Http\Resources\ChartsFilesResource;
use App\Http\Resources\ChartsFilesResource_chart;
use App\Http\Resources\ChartsMonth;
use App\Http\Resources\Albums;
use App\Http\Resources\Album;
use App\Models\Charts;
use App\Models\Charts_description;
use App\Models\Charts_files;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use File;
use Illuminate\Support\Facades\DB;

class ChartsController extends Controller
{
    public function mouths(){
        $months = Charts::selectRaw("DATE_FORMAT(created_at, '%Y-%m') date_thai,DATE_FORMAT(created_at, '%Y-%m') date_value")->orderBy('date_value', 'desc')->groupBy('date_thai')->get();
        return response()->json([
            'code' => '200',
            'data' => ChartsMonth::collection($months)
        ]);
    }

    public function searching(Request $request){
        $months = Charts::selectRaw("MAX(id) id,DATE_FORMAT(created_at, '%Y-%m') date_thai,DATE_FORMAT(created_at, '%Y-%m') date_value")->where('hn',$request->input('hn'))->whereNull('deleted_at')->orderBy('date_value', 'desc')->groupBy('date_value')->get();
        $last = Charts::where('hn',$request->input('hn'))->orderBy('id','desc')->first();

        return response()->json([
            'code' => '200',
            'data' => count($months) == 0 ? null : ChartsMonth::collection($months),
            'charts_info' => $last == null ? null : Charts_info::make($last),
        ]);
    }

    public function users($date_value,$status)
    {
        if(empty($status)){
            $status = 'all';
        }
        if($status == 'all'){
            $users = Charts::where('created_at','LIKE',$date_value.'%')->orderBy('updated_at','desc')->orderBy('status', 'asc')->Charts();
        } else {
            $users = Charts::where('created_at','LIKE',$date_value.'%')->where('status','Activate')->orderBy('updated_at','desc')->orderBy('status', 'asc')->Charts();
        }
        return response()->json([
            'code' => '200',
            'data' => ChartResource::collection($users)
        ]);
    }

    public function getalbums(){
        $albums = Charts_description::where('status_chat','0')->where('type_desc','1')->orderBy('updated_at','DESC')->get(['id','description']);
        return response()->json([
            'code' => '200',
            'data' => Albums::collection($albums)
        ]);
    }

    public function album($id){
        $albums = Charts_files::where('charts_desc_id',$id)->whereNull('deleted_at')->whereNotIn('type_files',['mp4','mov','mp3'])->get(['id','files']);
        return response()->json([
            'code' => '200',
            'data' => Album::collection($albums)
        ]);
    }

    public function albumedit(Request $request)
    {
        $albumedit = Charts_description::where('id',$request->input('id'))->update(['description' => $request->input('description')]);
        if($albumedit){
            return response()->json([
                'code' => '200',
                'data' => 'แก้ไขชื่ออัลบั้มเรียบร้อยแล้ว'
            ]);
        } else {
            return response()->json([
                'code' => '201',
                'data' => 'การแก้ไขชื่ออัลบั้มล้มเหลว'
            ]);
        }
    }


    public function chats(Request $request)
    {
        return response()->json([
            'code' => '200',
            'data' => ChatsResource::collection(Charts_description::Getchats()->where('type_charts',1)->where('status_chat','0')->orderBy('created_at', 'asc')->get()),
        ]);
    }

    public function chatimagesdelete(Request $request)
    {
        $desc = Charts_description::find($request->input('id'));
        $desc->deleted_at = Carbon::now();
        $desc->status_chat = '1';
        if($desc->save()){
            return response()->json([
                'code' => '200',
                'data' => 'ลบรูปภาพเรียบร้อยแล้ว'
            ]);
        } else {
            return response()->json([
                'code' => '201',
                'data' => 'ลบรูปภาพล้มเหลว'
            ]);
        }
    }

    public function chatalbumsdelete(Request $request)
    {
        $desc = Charts_description::find($request->input('id'));
        $desc->deleted_at = Carbon::now();
        $desc->status_chat = '1';
        if($desc->save()){
            return response()->json([
                'code' => '200',
                'data' => 'ลบอัลบั้มเรียบร้อยแล้ว'
            ]);
        } else {
            return response()->json([
                'code' => '201',
                'data' => 'ลบอัลบั้มล้มเหลว'
            ]);
        }
    }

    public function chatimagesalbumsdelete(Request $request)
    {
        $desc = Charts_files::find($request->input('id'));

        $descs = Charts_files::where('charts_desc_id',$desc->charts_desc_id)->whereNull('deleted_at')->count();
        if($descs == 1){
            $desc_delete = Charts_description::find($desc->charts_desc_id);
            $desc_delete->deleted_at = Carbon::now();
            $desc_delete->status_chat = '1';
            $desc_delete->save();
        }

        $desc->deleted_at = Carbon::now();
        if($desc->save()){
            
            return response()->json([
                'code' => '200',
                'data' => 'ลบรูปภาพเรียบร้อยแล้ว'
            ]);
        } else {
            return response()->json([
                'code' => '201',
                'data' => 'ลบรูปภาพล้มเหลว'
            ]);
        }
    }

    public function chatimages(Request $request){
        return response()->json([
            'code' => '200',
            'data' => ChartsFilesResource::collection(Charts_files::where('charts_desc_id',$request->input('id'))->whereNull('deleted_at')->get()),
        ]); 
    }

    public function albumupload(Request $request)
    {
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $imageName = date('Ymd') . Str::random(8) . time() . '.' . $files->getClientOriginalExtension();
            $files->move(public_path('assets/img/photos'), $imageName);

            $file = Charts_files::create([
                'charts_desc_id' => $request->input('id'),
                'add_by_user' => Auth::guard('api')->user()->id,
                'files' => $imageName,
                'type_files' => $files->getClientOriginalExtension()
            ]);

            if($file){
                $mimeTypes=['image/jpeg','image/gif','image/png','image/bmp','image/svg+xml'];
                $mimeContentType = mime_content_type(public_path('assets/img/photos/'.$imageName));
                if(in_array($mimeContentType, $mimeTypes) ){
                    Resize::uploads($imageName);
                }
                return response()->json([
                    'code' => '200',
                    'data' => 'อัปโหลดเรียบร้อยแล้ว',
                ]);
            }

            return response()->json([
                'code' => '200',
                'data' => 'อัปโหลดล้มเหลว'
            ]);
            
        }
        return response()->json([
            'code' => '200',
            'data' => 'อัปโหลดล้มเหลว'
        ]);
    }

    public function chatUpload(Request $request){
        if ($request->hasFile('files')) {
            $files = $request->file('files');
            $imageName = date('Ymd') . Str::random(8) . time() . '.' . $files->getClientOriginalExtension();
            $files->move(public_path('assets/img/photos'), $imageName);

            $file_id = Charts_files::create([
                'add_by_user' => Auth::guard('api')->user()->id,
                'files' => $imageName,
                'type_files' => $files->getClientOriginalExtension()
            ])->id;

            $mimeTypes=['image/jpeg','image/gif','image/png','image/bmp','image/svg+xml'];
            $mimeContentType = mime_content_type(public_path('assets/img/photos/'.$imageName));
            if(in_array($mimeContentType, $mimeTypes) ){
                Resize::uploads($imageName);
            }
            return response()->json([
                'code' => '200',
                'data' => $file_id,
            ]);
        }
        return response()->json([
            'code' => '200',
            'data' => 'อัปโหลดล้มเหลว'
        ]);
    }

    public function lastProcessChat(Request $request){
        $descriptionid = Charts_description::create([
            'description' => $request->input('description'),
            'type_desc' => 1,
            'add_by_user' => Auth::guard('api')->user()->id,
            'type_charts' => 1
        ])->id;
        $id_update = json_decode($request->input('id'));
        Charts_files::whereIn('id',$id_update)->update(['charts_desc_id' => $descriptionid]);
        return response()->json([
            'code' => '200',
            'data' => 'อัปโหลดเรียบร้อยแล้ว'
        ]);
    }

    public function descriptions(Request $request)
    {
        if ($request->input('last_id')) {
            $last = Charts::find($request->input('last_id'));
        } else {
            $last = Charts::where('id', $request->input('charts_id'))->whereNull('deleted_at')->orderBy('id', 'desc')->first();
        }
        return response()->json([
            'code' => '200',
            'data' => [
                'charts_info' => Charts_info::make($last),
                'charts_status' => Charts::where('id', $last->id)->whereNull('deleted_at')->orderBy('created_at', 'desc')->get(['status']),
                'charts_date' => ChartsDate::collection(Charts::where('idcard', $last->idcard)->whereNull('deleted_at')->orderBy('created_at', 'desc')->get(['id', 'created_at'])),
                'lasted' => ChartsDescriptionResource::collection(Charts_description::where('charts_id', $last->id)->whereNull('deleted_at')->orderBy('created_at', 'desc')->get()),
            ],
        ]);
    }

    public function files(Request $request)
    {
        return response()->json([
            'code' => '200',
            'data' => Charts_files::whereNull('deleted_at')->where('charts_desc_id', $request->input('charts_desc_id'))->count() > 0 ? ChartsFilesResource::collection(Charts_files::whereNull('deleted_at')->where('charts_desc_id', $request->input('charts_desc_id'))->get()) : null
        ]);
    }

    public function uploaded(Request $request)
    {
        $description = Charts_description::create([
            'description' => $request->input('description'),
            'add_by_user' => Auth::guard('api')->user()->id,
            'g_location_lat' => $request->input('g_location_lat') == 'null' ? null : $request->input('g_location_lat'),
            'g_location_long' => $request->input('g_location_long') == 'null' ? null : $request->input('g_location_long'),
            'type_charts' => $request->input('type_charts') ? $request->input('type_charts') : 0
        ]);
        if ($description) {
            if ($request->hasFile('files')) {
                $files = $request->file('files');
                foreach  ($files as $file) {
                    $imageName = date('Ymd') . Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('assets/img/photos'), $imageName);

                    $mimeTypes=['image/jpeg','image/gif','image/png','image/bmp','image/svg+xml'];
                    $mimeContentType = mime_content_type(public_path('assets/img/photos/'.$imageName));
                    if(in_array($mimeContentType, $mimeTypes) ){
                        Resize::uploads($imageName);
                    }

                    Charts_files::create([
                        'charts_desc_id' => $description->id,
                        'add_by_user' => Auth::guard('api')->user()->id,
                        'files' => $imageName,
                        'type_files' => $file->getClientOriginalExtension()
                    ]);
                }
            }
            return response()->json([
                'code' => '200',
                'data' => 'บันทึกข้อมูลเรียบร้อยแล้ว'
            ]);
        }
        return response()->json([
            'code' => '101',
            'data' => 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง'
        ]);
    }

    public function stored(Request $request)
    {
        $description = Charts_description::create([
            'charts_id' => $request->input('id'),
            'description' => $request->input('description'),
            'add_by_user' => Auth::guard('api')->user()->id,
            'g_location_lat' => $request->input('g_location_lat') == 'null' ? null : $request->input('g_location_lat'),
            'g_location_long' => $request->input('g_location_long') == 'null' ? null : $request->input('g_location_long')
        ]);

        if ($description) {
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $imageName = date('Ymd') . Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('assets/img/photos'), $imageName);

                    $mimeTypes=['image/jpeg','image/gif','image/png','image/bmp','image/svg+xml'];
                    $mimeContentType = mime_content_type(public_path('assets/img/photos/'.$imageName));
                    if(in_array($mimeContentType, $mimeTypes) ){
                        Resize::uploads($imageName);
                    }

                    Charts_files::create([
                        'charts_id' => $request->input('id'),
                        'charts_desc_id' => $description->id,
                        'add_by_user' => Auth::guard('api')->user()->id,
                        'files' => $imageName,
                        'type_files' => $file->getClientOriginalExtension()
                    ]);
                }
            }

            return response()->json([
                'code' => '200',
                'data' => 'บันทึกข้อมูลเรียบร้อยแล้ว'
            ]);
        }
        return response()->json([
            'code' => '101',
            'data' => 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง'
        ]);
    }

    public function success(Request $request)
    {
        $charts = Charts::find($request->input('id'));
        if (empty($charts)) {
            return response()->json([
                'code' => '101',
                'data' => 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง'
            ]);
        }

        $charts->status = 'Deactivate';
        $charts->save();

        $id = Charts_description::create([
            'charts_id' => $charts->id,
            'description' => 'DISCHANGE SUMMARY',
            'add_by_user' => Auth::guard('api')->user()->id,
        ])->id;

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $imageName = date('Ymd') . Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('assets/img/photos'), $imageName);

                $mimeTypes=['image/jpeg','image/gif','image/png','image/bmp','image/svg+xml'];
                $mimeContentType = mime_content_type(public_path('assets/img/photos/'.$imageName));
                if(in_array($mimeContentType, $mimeTypes) ){
                    Resize::uploads($imageName);
                }

                Charts_files::create([
                    'charts_id' => $charts->id,
                    'charts_desc_id' => $id,
                    'add_by_user' => Auth::guard('api')->user()->id,
                    'files' => $imageName,
                    'type_files' => $file->getClientOriginalExtension()
                ]);
            }
        }

        return response()->json([
            'code' => '200',
            'data' => 'บันทึกข้อมูลเรียบร้อยแล้ว'
        ]);
    }

    public function deleted(Request $request)
    {
        $charts = Charts_description::find($request->input('id'));
        if (Auth::guard('api')->user()->type == 'Owner' || $charts->add_by_user == Auth::guard('api')->user()->id) {
            $files = Charts_files::where('charts_desc_id', $charts->id);
            if ($files->count() > 0) {
                $file = Charts_files::where('charts_desc_id', $charts->id)->update(['deleted_at' => Carbon::now()]);
                if ($file) {
                    $charts->deleted_at = Carbon::now();
                    if ($charts->save()) {
                        foreach ($files->get() as $f) {
                            File::delete(public_path('assets/img/photos/' . $f->files));
                        }
                        return response()->json([
                            'code' => '200',
                            'data' => 'บันทึกข้อมูลเรียบร้อยแล้ว'
                        ]);
                    }
                    return response()->json([
                        'code' => '404',
                        'data' => 'ไม่สามารถลบได้'
                    ]);
                }
                return response()->json([
                    'code' => '404',
                    'data' => 'ไม่สามารถลบได้'
                ]);
            }
            $charts->deleted_at = Carbon::now();
            if ($charts->save()) {
                return response()->json([
                    'code' => '200',
                    'data' => 'บันทึกข้อมูลเรียบร้อยแล้ว'
                ]);
            }
        }
        return response()->json([
            'code' => '404',
            'data' => 'ไม่อนุญาตให้ดำเนินการ'
        ]);
    }

    public function sentNotifyWeb(Request $request){
       $notify_web = new Notify();
       $user = User::find(Auth::guard('api')->user()->id);
       if(empty($user)){
            $sent_by = 'Not found User';
       } else {
            $sent_by = $user->prefix->name.$user->name.' '.$user->surname;
       }
       $message = $request->input('description');
       if($message == '' || $message == null){
           $msg = 'มีการเพิ่มไฟล์ใหม่';
       } else {
           $msg = $message;
       }
       $notify_web->sentNotifyWeb(Auth::guard('api')->user()->id, $sent_by, $msg, route('backend.charts.index'));
       $device_token = User::where('id', '<>', Auth::guard('api')->user()->id)->whereNotNull('device_token')->get();
       if(!empty($device_token)){
           foreach($device_token as $dt){
                $notify_app = new Notify();
                $notify_app->sentNotifyDevice($sent_by, $msg, $dt->device_token, 0);
           }
       }
    }

    public function sentNotifyWebAndMobile(Request $request){
        $charts_Desc = Charts_description::where('add_by_user', '<>', Auth::guard('api')->user()->id)->whereNull('deleted_at')->where('charts_id', $request->input('id'))->distinct('add_by_user')->get(['add_by_user']);
        if (!empty($charts_Desc)) {
            foreach ($charts_Desc as $cd) {
                $charts = Charts::find($request->input('id'),['idcard','name','surname']);
                $notify_web = New Notify();
                $notify_web->sentNotifyWeb(Auth::guard('api')->user()->id, 'Description', 'คุณ' . $charts->name . ' ' . $charts->surname . ' ถูกบันทึกรายละเอียดเพิ่มเติม', route('backend.charts.feed', encrypt($request->input('id'))));
                $device_token = User::find($cd->add_by_user, ['device_token']);
                if ($device_token->device_token != null) {
                    $notify_app = new Notify();
                    $notify_app->sentNotifyDevice('Description', 'คุณ' . $charts->name . ' ' . $charts->surname . ' ถูกบันทึกรายละเอียดเพิ่มเติม', $device_token->device_token, $request->input('id'));
                }
            }
        }
    }

    public function getImages($id){
        $last = Charts::find($id);
        $files = Charts_files::where('charts_id',$id)->whereNull('deleted_at')->get();
        return response()->json([
            'code' => '200',
            'data' => [
                'charts_info' => Charts_info::make($last),
                'charts_files' => ChartsFilesResource_chart::collection($files)
            ]
        ]);
    }
}