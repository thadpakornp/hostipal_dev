<?php

namespace App\Http\Controllers\Api;


use App\Classes\Resize;
use App\Http\Controllers\Controller;
use App\Http\Resources\Chart as ChartResource;
use App\Http\Resources\ChartsDate;
use App\Http\Resources\ChartsDescriptionResource;
use App\Http\Resources\ChartsFilesResource;
use App\Models\Charts;
use App\Models\Charts_description;
use App\Models\Charts_files;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use File;

class ChartsController extends Controller
{
    public function users()
    {
        $users = Charts::orderBy('status', 'asc')->Charts();
        return response()->json([
            'code' => '200',
            'data' => ChartResource::collection($users)
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
                'charts_info' => ChartResource::make($last),
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
            'add_by_user' => Auth::user()->id,
            'g_location_lat' => $request->input('g_location_lat'),
            'g_location_long' => $request->input('g_location_long')
        ]);

        if ($description) {
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $imageName = date('Ymd') . Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('assets/img/photos'), $imageName);

                    if ($file->getClientOriginalExtension() == 'png' || $file->getClientOriginalExtension() == 'bmp' || $file->getClientOriginalExtension() == 'jpg' || $file->getClientOriginalExtension() == 'jpeg' || $file->getClientOriginalExtension() == 'gif') {
                        Resize::uploads($imageName);
                    }

                    Charts_files::create([
                        'charts_desc_id' => $description->id,
                        'add_by_user' => Auth::user()->id,
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
            'add_by_user' => Auth::user()->id,
            'g_location_lat' => $request->input('g_location_lat'),
            'g_location_long' => $request->input('g_location_long')
        ]);

        if ($description) {
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $imageName = date('Ymd') . Str::random(8) . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('assets/img/photos'), $imageName);

                    if ($file->getClientOriginalExtension() == 'png' || $file->getClientOriginalExtension() == 'bmp' || $file->getClientOriginalExtension() == 'jpg' || $file->getClientOriginalExtension() == 'jpeg' || $file->getClientOriginalExtension() == 'gif') {
                        Resize::uploads($imageName);
                    }

                    Charts_files::create([
                        'charts_id' => $request->input('id'),
                        'charts_desc_id' => $description->id,
                        'add_by_user' => Auth::user()->id,
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

        Charts_description::create([
            'charts_id' => $charts->id,
            'description' => 'สิ้นสุดการรักษาหรือกระบวนการรักษาเสร็จสิ้นแล้ว',
            'add_by_user' => Auth::user()->id,
            'g_location_lat' => $request->input('g_location_lat_charts_desc'),
            'g_location_long' => $request->input('g_location_long_charts_desc')
        ]);

        return response()->json([
            'code' => '200',
            'data' => 'บันทึกข้อมูลเรียบร้อยแล้ว'
        ]);
    }

    public function deleted(Request $request)
    {
        $charts = Charts_description::find($request->input('id'));
        if (Auth::user()->type == 'Owner' || $charts->add_by_user == Auth::user()->id) {
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
}
