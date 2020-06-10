@extends('layouts.backend')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('content')
    <div class="content content-boxed">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">แก้ไขประวัติผู้ป่วย</h3>
            </div>
            <div class="block-content">
                <form class="form-horizontal push-10-t push-10" action="{{ route('backend.charts.edited') }}"
                      method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{ encrypt($chart->id) }}" name="id">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <img
                                        src="@if($chart->profile == null) {{ asset('assets/img/avatars/avatar1.jpg') }} @else {{ asset('assets/img/profiles/'.$chart->profile) }} @endif"
                                        class="img-avatar128 img-avatar-thumb img-rounded img-responsive" width="500"
                                        height="550">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="name">H/N</label>
                                    <input class="form-control" type="text" id="hn" name="hn" required
                                           value="{{ $chart->hn }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="name">หมายเลขบัตรประชาชน</label>
                                    <input class="form-control" type="text" id="idcard" name="idcard" required
                                           value="{{ $chart->idcard }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="prefix-id">คำนำหน้า</label>
                                    <select class="form-control select2" name="prefix_id" id="prefix-id" required>
                                        <option value="">โปรดเลือกคำนำหน้า</option>
                                        @foreach($prefixs as $prefix)
                                            <option value="{{ $prefix->code }}"
                                                    @if($chart->prefix_id == $prefix->code) selected @endif>{{ $prefix->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="name">ชื่อ</label>
                                    <input class="form-control" type="text" id="name" name="name" required
                                           value="{{ $chart->name }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="surname">นามสกุล</label>
                                    <input class="form-control" type="text" id="surname" name="surname"
                                           value="{{ $chart->surname }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="surname">เพศ</label>
                                    <input type="radio" name="sex" value="M"
                                           style="margin-top: 15px;margin-right: 10px;"
                                           @if($chart->sex == 'M') checked @endif> ผู้ชาย
                                    <input type="radio" name="sex" value="F"
                                           style="margin-top: 15px;margin-right: 10px;margin-left: 25px"
                                           @if($chart->sex == 'F') checked @endif>
                                    ผู้หญิง
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="surname">วัน/เดือน/ปี เกิด</label>
                                    <input class="form-control" type="text" id="validation-hbd" name="hbd"
                                           value="{{ $chart->hbd }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="phone">เบอร์โทรติดต่อ</label>
                                    <input class="form-control" type="text" id="phone" name="phone"
                                           value="{{ $chart->phone }}">
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="col-xs-4">
                                    <label for="file-input">รูปโปรไฟล์</label>
                                    <input type="file" id="file-input" name="profile" class="form-control">
                                </div>

                                <div class="col-xs-8">
                                    <label for="file-input">ที่อยู่</label>
                                    <input type="text" id="address" name="address" value="{{ $chart->address }}"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <button class="btn btn-warning" type="submit"><i class="fa fa-check push-5-r"></i>
                                แก้ไขประวัติ
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script_ready')
    $('#prefix-id').select2();
    $('#validation-hbd').datepicker({
    language: "th",
    format: "yyyy-mm-dd"
    });
@endsection
@section('script')
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/locales/bootstrap-datepicker.th.min.js') }}"></script>
@endsection
