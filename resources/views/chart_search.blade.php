@extends('layouts.backend')

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
                                    <label for="name">ชื่อ/นามสกุล/HN/เบอร์โทรติดต่อ</label>
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

    <div class="content content-boxed">
        <div class="row">
            @if(count($users) == 0)
                <div class="col-lg-pull-12">
                    <div class="bg-gray-light">
                        <div class="bg-black-op">
                            <div class="block block-themed block-transparent">
                                <div class="block-header">
                                    <h3 class="block-title text-center" style="font-family: 'Sarabun', sans-serif;">
                                        ไม่พบข้อมูล </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @foreach($users as $user)
                    <div class="col-sm-6 col-md-4 col-lg-4">
                        <div class="block block-rounded">
                            <div class="block-header">
                                <ul class="block-options">
                                    <li>
                                        <a type="button" href="{{ route('backend.charts.edit',encrypt($user->id)) }}">
                                            <i class="si si-pencil"></i>
                                        </a>
                                    </li>
                                </ul>
                                <div
                                    class="block-title">{{ $user->prefix_name }} {{ $user->name }} {{ $user->surname }}</div>
                            </div>
                            <div class="block-content block-content-full bg-primary text-center">
                                <img class="img-avatar img-avatar-thumb"
                                     src="@if($user->profile == null) {{ asset('assets/img/avatars/avatar1.jpg') }} @else {{ asset('assets/img/avatars/'.$user->profile) }} @endif"
                                     alt="">
                                <div class="font-s13 push-10-t">H/N {{ $user->hn }}</div>
                            </div>
                            <div class="block-content">
                                <table class="table table-borderless table-striped font-s13">
                                    <tbody>
                                    <tr>
                                        <td class="font-w600" style="width: 30%;">เพศ</td>
                                        <td>@if($user->sex == 'M') ชาย @else หญฺิง @endif</td>
                                    </tr>
                                    <tr>
                                        <td class="font-w600">อายุ(ปี)</td>
                                        <td>@if($user->hbd != '' || $user->hbd != NULL)
                                                @php
                                                    $hnd_ex = explode('-',$user->hbd);
                                                    $today_ex = explode('-',date('Y-m-d'));
                                                    $hnd = Carbon\Carbon::createMidnightDate($hnd_ex[0],$hnd_ex[1],$hnd_ex[2]);
                                                    $today = Carbon\Carbon::createMidnightDate($today_ex[0],$today_ex[1],$today_ex[2]);
                                                    echo $hnd->diffInYears($today);
                                                @endphp
                                            @endif</td>
                                    </tr>
                                    <tr>
                                        <td class="font-w600">เบอร์ติดต่อ</td>
                                        <td>{{ $user->phone }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="block-content block-content-full text-center">
                                <a class="btn btn-default"
                                   href="{{ route('backend.charts.feeds',encrypt($user->id)) }}">
                                    <i class="fa fa-fw fa-sticky-note-o"></i> ประวัติผู้ป่วย
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
