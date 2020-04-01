<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManageRequest;
use App\Models\Amphur;
use App\Models\District;
use App\Models\OfficeModel;
use App\Models\Province;
use App\Models\Zipcode;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManageController extends Controller
{
    public function index(){
        $office = OfficeModel::Manage()->get(['id','name','address','district','country','province','code']);
        return view('manage',compact('office'));
    }

    public function create(){
        $provinces = Province::all(['PROVINCE_ID','PROVINCE_NAME']);
        return view('manage_form', compact('provinces'));
    }

    public function stored(ManageRequest $request){
        $data['name'] = $request->input('name');
        $data['phone'] = $request->input('phone');
        $data['address'] = $request->input('address');
        $province = explode(',',$request->input('province'));
        $data['province'] = $province[1];
        $country = explode(',',$request->input('country'));
        $data['country'] = $country[1];
        $district = explode(',',$request->input('district'));
        $data['district'] = $district[1];
        $data['code'] = $request->input('code');
        $data['g_location_lat'] = $request->input('g_location_lat');
        $data['g_location_long'] = $request->input('g_location_long');

        if($request->input('id')){
            $office = OfficeModel::find(decrypt($request->input('id')))->update($data);
        } else {
            $office = OfficeModel::create($data);
        }
        if($office){
            return redirect()->route('backend.manage.index')->with(['success' => 'บันทึกข้อมูลเข้าระบบแล้ว']);
        } else {
            return redirect()->back()->with(['error' => 'ไม่สามารถบันทึกข้อมูลได้ ลองใหม่อีกครั้ง']);
        }
    }

    public function destroy(Request $request){
        $office = OfficeModel::find(decrypt($request->input('id')));
        if(empty($office)){
            return response()->json(['error' => 'ไม่พบข้อมูลที่ต้องการลบ'], 401);
        }
        $office->deleted_at = Carbon::now();
        if(!$office->save()){
            return response()->json(['error' => 'ไม่สามารถลบได้'], 401);
        }
    }

    public function edit($id){
        $office = OfficeModel::find(decrypt($id),['id','name','phone','address','district','country','province','code','g_location_long', 'g_location_lat']);
        $provinces = Province::all(['PROVINCE_ID','PROVINCE_NAME']);
        if(empty($office)){
            return redirect()->back()->with(['error' => 'ไม่พบข้อมูล']);
        }
        $province = Province::where('PROVINCE_NAME',$office->province)->first(['PROVINCE_ID','PROVINCE_NAME']);
        $amphur = Amphur::where('AMPHUR_NAME',$office->country)->first(['AMPHUR_ID','AMPHUR_NAME']);
        $country = Amphur::where('PROVINCE_ID',$province->PROVINCE_ID)->get(['AMPHUR_ID','AMPHUR_NAME']);
        $district = District::where('PROVINCE_ID',$province->PROVINCE_ID)->where('AMPHUR_ID',$amphur->AMPHUR_ID)->get(['DISTRICT_ID','DISTRICT_NAME']);
        return view('manage_edit', compact('office','provinces','country','district'));
    }

    public function getAddress(Request $request){
        $fvalue = explode(',',$request->input('fvalue'));
        $fvalue = $fvalue[0];
        if ($request->input('find') == 'a'){
            $result = Amphur::where('PROVINCE_ID',$fvalue)->where('AMPHUR_NAME','NOT LIKE','*%')->orderBy('AMPHUR_NAME', 'ASC')->get(['AMPHUR_ID','AMPHUR_NAME']);
            if ( count($result)>0 ) {
                $temp = '<option value="">:::::&nbsp;เลือกอำเภอ&nbsp;:::::</option>';
                foreach($result as $read){
                    $temp .= '<option value="'.$read['AMPHUR_ID'].','.$read['AMPHUR_NAME'].'">'.$read['AMPHUR_NAME'].'</option>';
                }
            } else {
                $temp = '<option value="">:::&nbsp;ไม่มีข้อมูล&nbsp;:::</option>';
            }
        } else if($request->input('find') == 't') {
            $result = District::where('AMPHUR_ID',$fvalue)->where('DISTRICT_NAME','NOT LIKE','*%')->orderBy('DISTRICT_NAME', 'ASC')->get(['DISTRICT_CODE','DISTRICT_NAME']);
            if ( count($result)>0 ) {
                $temp = '<option value="" selected="selected">:::::&nbsp;เลือกตำบล&nbsp;:::::</option>';
                foreach($result as $read){
                    $temp .= '<option value="'.$read['DISTRICT_CODE'].','.$read['DISTRICT_NAME'].'">'.$read['DISTRICT_NAME'].'</option>';
                }
            } else {
                $temp = '<option value="" selected="selected">:::&nbsp;ไม่มีข้อมูล&nbsp;:::</option>';
            }
        } else if($request->input('find') == 'z') {
            $result = Zipcode::where('DISTRICT_CODE',$fvalue)->first(['ZIPCODE']);
            $temp = $result['ZIPCODE'];
        } else {
            if ( $request->input('find') != 'z' )
            {
                $temp = '<option value="">:::::&nbsp;เลือก&nbsp;:::::</option>';
            }
        }
        return $temp;
    }
}
