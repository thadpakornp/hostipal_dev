@extends('layouts.backend')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.css') }}">
@endsection

@section('content')
    <div class="content content-boxed">
        <div class="block">
            <div class="block-header">
                <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">ค้นหาผู้ป่วยในระบบ</h3>
            </div>
            <div class="block-content">
                <form class="form-horizontal push-10-t push-10" action="{{ route('backend.charts.search') }}"
                      method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-9">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="name">ชื่อ/นามสกุล/หมายเลขบัตรประชาชน/HN/เบอร์โทรติดต่อ</label>
                                    <input class="form-control" type="text" id="search" name="search"
                                           @if(isset($requests)) value="{{ $requests['search'] }}" @endif>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group" style="padding-top: 25px;">
                                <div class="col-xs-12">
                                    <button class="btn btn-group-xs btn-primary" type="submit"><i
                                            class="fa fa-search-plus push-5-r"></i> ค้นหาข้อมูล
                                    </button>
                                    <a class="btn btn-group-xs btn-danger" href="{{ route('backend.charts.users') }}"><i
                                            class="fa fa-refresh push-5-r"></i> ล้างการค้นหา</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(!isset($requests))
        <div class="content content-boxed">
            <div class="col-lg-pull-12">
                <div class="bg-danger">
                    <div class="bg-black-op">
                        <div class="block block-themed block-transparent">
                            <div class="block-header">
                                <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">
                                    ระเบียนผู้ป่วยอยู่ระหว่างการรักษา </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="block">
                <div class="block-content">
                    <table class="table table-bordered table-striped js-dataTable-full">
                        <thead>
                        <tr>
                            <th></th>
                            <th class="hidden-xs" style="font-family: 'Sarabun', sans-serif;" width="10%">รูปภาพ
                            </th>
                            <th class="hidden-xs" style="font-family: 'Sarabun', sans-serif;" width="10%">HN</th>
                            <th class="hidden-xs" style="font-family: 'Sarabun', sans-serif;" width="20%">ชื่อ -
                                นามสกุล
                            </th>
                            <th class="hidden-xs" style="font-family: 'Sarabun', sans-serif;" width="5%">เพศ</th>
                            <th class="hidden-xs" style="font-family: 'Sarabun', sans-serif;" width="30%">ที่อยู่</th>
                            <th class="text-center" style="font-family: 'Sarabun', sans-serif;" width="13%">
                                เบอร์ติดต่อ
                            </th>
                            <th width="12%"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($users) == 0)
                            <tr>
                                <td colspan="7"><h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">
                                        ไม่พบข้อมูลระเบียนผู้ป่วยอยู่ระหว่างการรักษา </h3></td>
                            </tr>
                        @else
                            @foreach($users as $key => $user_info)
                                @php
                                    if(isset($requests)){
                                        $user = \App\Models\Charts::where('idcard',$user_info->idcard)->latest()->first();
                                    } else {
                                        $user = \App\Models\Charts::where('idcard',$user_info->idcard)->where('status','Activate')->latest()->first();
                                    }
                                @endphp
                                @if(!empty($user))
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td><img class="img-avatar img-avatar-thumb"
                                                 src="@if($user->profile == null) {{ asset('assets/img/avatars/avatar1.jpg') }} @else {{ asset('assets/img/avatars/'.$user->profile) }} @endif"
                                                 alt=""></td>
                                        <td>{{ $user->hn }}</td>
                                        <td>{{ $user->prefix->name }} {{ $user->name }} {{ $user->surname }}
                                            <br/>{{ substr($user->idcard, 0,4) }}xxxxx{{ substr($user->idcard, -4) }}<br/>
                                            @if($user->hbd != '' || $user->hbd != NULL)
                                                (อายุ
                                                @php
                                                    $hnd_ex = explode('-',$user->hbd);
                                                    $today_ex = explode('-',date('Y-m-d'));
                                                    $hnd = Carbon\Carbon::createMidnightDate($hnd_ex[0],$hnd_ex[1],$hnd_ex[2]);
                                                    $today = Carbon\Carbon::createMidnightDate($today_ex[0],$today_ex[1],$today_ex[2]);
                                                    echo $hnd->diffInYears($today);
                                                @endphp
                                                ปี)
                                            @endif
                                        </td>
                                        <td>@if($user->sex == 'M') ชาย @else หญฺิง @endif</td>
                                        <td></td>
                                        <td>{{ $user->phone }}</td>
                                        <td>
                                            <div class="btn-group">
                                                    <a type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" title="แก้ไขประวัติผู้ป่วย" href="{{ route('backend.charts.edit',encrypt($user->id)) }}">
                                                        <i class="fa fa-edit"></i>
                                </a>
                                                    <a type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="แฟ้มประวัติผู้ป่วย" href="{{ route('backend.charts.feeds',encrypt($user->idcard)) }}">
                                                        <i class="fa fa-id-card-o"></i>
                                </a>
                                                </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif


    <div class="content content-boxed">
        <div class="col-lg-pull-12">
            <div class="bg-primary">
                <div class="bg-black-op">
                    <div class="block block-themed block-transparent">
                        <div class="block-header">
                            <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">
                                ระเบียนผู้ป่วยในระบบ </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="block">
            <div class="block-content">
                <table class="table table-bordered table-striped js-dataTable-full">
                    <thead>
                    <tr>
                        <th></th>
                        <th class="hidden-xs" style="font-family: 'Sarabun', sans-serif;" width="10%">รูปภาพ
                        </th>
                        <th class="hidden-xs" style="font-family: 'Sarabun', sans-serif;" width="10%">HN</th>
                        <th class="hidden-xs" style="font-family: 'Sarabun', sans-serif;" width="20%">ชื่อ -
                            นามสกุล
                        </th>
                        <th class="hidden-xs" style="font-family: 'Sarabun', sans-serif;" width="5%">เพศ</th>
                        <th class="hidden-xs" style="font-family: 'Sarabun', sans-serif;" width="30%">ที่อยู่</th>
                        <th class="text-center" style="font-family: 'Sarabun', sans-serif;" width="13%">
                                เบอร์ติดต่อ
                            </th>
                            <th width="12%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($users) == 0)
                        <tr>
                            <td colspan="7"><h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">
                                    ไม่พบข้อมูลระเบียนผู้ป่วยในระบบ </h3></td>
                        </tr>
                    @else
                        @foreach($users as $key => $user_info)
                            @php
                                $user = \App\Models\Charts::where('idcard',$user_info->idcard)->latest()->first();
                            @endphp
                            @if(!empty($user))
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td><img class="img-avatar img-avatar-thumb"
                                             src="@if($user->profile == null) {{ asset('assets/img/avatars/avatar1.jpg') }} @else {{ asset('assets/img/avatars/'.$user->profile) }} @endif"
                                             alt=""></td>
                                    <td>{{ $user->hn }}</td>
                                    <td>{{ $user->prefix->name }} {{ $user->name }} {{ $user->surname }}
                                        <br/>{{ substr($user->idcard, 0,4) }}xxxxx{{ substr($user->idcard, -4) }}<br/>
                                        @if($user->hbd != '' || $user->hbd != NULL)
                                            (อายุ
                                            @php
                                                $hnd_ex = explode('-',$user->hbd);
                                                $today_ex = explode('-',date('Y-m-d'));
                                                $hnd = Carbon\Carbon::createMidnightDate($hnd_ex[0],$hnd_ex[1],$hnd_ex[2]);
                                                $today = Carbon\Carbon::createMidnightDate($today_ex[0],$today_ex[1],$today_ex[2]);
                                                echo $hnd->diffInYears($today);
                                            @endphp
                                            ปี)
                                        @endif
                                    </td>
                                    <td>@if($user->sex == 'M') ชาย @else หญฺิง @endif</td>
                                    <td></td>
                                    <td>{{ $user->phone }}</td>
                                    <td>
                                    <div class="btn-group">
                                                    <a type="button" class="btn btn-sm btn-warning" data-toggle="tooltip" title="แก้ไขประวัติผู้ป่วย" href="{{ route('backend.charts.edit',encrypt($user->id)) }}">
                                                        <i class="fa fa-edit"></i>
                                </a>
                                                    <a type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="แฟ้มประวัติผู้ป่วย" href="{{ route('backend.charts.feeds',encrypt($user->idcard)) }}">
                                                        <i class="fa fa-id-card-o"></i>
                                </a>
                                                </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/base_tables_datatables.js') }}"></script>
@endsection


