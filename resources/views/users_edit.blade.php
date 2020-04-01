@extends('layouts.backend')

@section('content')
    <div class="content content-boxed">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">แก้ไขข้อมูลผู้ใช้งาน</h3>
            </div>
            <div class="block-content">
                <form class="js-validation-users form-horizontal push-10-t push-10" action="{{ route('backend.users.edited') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{ encrypt($user->id) }}" name="id">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <img src="{{ asset('assets/img/profiles/'.$user->profile) }}" class="img-avatar128 img-avatar-thumb img-rounded img-responsive" width="500" height="550">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="prefix-id">คำนำหน้า</label>
                                    <select class="form-control select2" name="prefix_id" id="prefix-id" required>
                                        <option value="">โปรดเลือกคำนำหน้า</option>
                                        @foreach($prefixs as $prefix)
                                            <option value="{{ $prefix->code }}" @if($user->prefix_id == $prefix->code) selected @endif>{{ $prefix->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="name">ชื่อ</label>
                                    <input class="form-control" type="text" id="name" name="name" required value="{{ $user->name }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="surname">นามสกุล</label>
                                    <input class="form-control" type="text" id="surname" name="surname" required value="{{ $user->surname }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="email">อีเมลผู้ใช้งาน</label>
                                    <input class="form-control" type="email" id="email" name="email" required value="{{ $user->email }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="phone">เบอร์โทรติดต่อ</label>
                                    <input class="form-control" type="text" id="phone" name="phone" required value="{{ $user->phone }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="file-input">รูปโปรไฟล์</label>
                                    <input type="file" id="file-input" name="file-input">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label for="status">สถานะการใช้งาน</label>
                                    <select name="status" id="status" class="form-control" required>
                                        <option value="Active" @if($user->status == 'Active') selected @endif>ใช้งาน</option>
                                        <option value="Suspended" @if($user->status == 'Suspended') selected @endif>ระงับใช้งาน</option>
                                    </select>
                                </div>
                                <div class="col-xs-6">
                                    <label for="office-id">สังกัดสถานพยาบาล</label>
                                    <select name="office_id" id="office-id" class="form-control select2" @if(Auth::user()->type != 'Owner') required @endif>
                                        <option value="">โปรดเลือกสถานพยาบาล</option>
                                        @foreach($offices as $office)
                                            <option value="{{ $office->id }}" @if($user->office_id == $office->id) selected @endif>{{ $office->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="type">ประเภทการใช้งาน</label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="">โปรเลือกประเภทผู้ใช้งาน</option>
                                        @if(Auth::user()->type == 'Owner')
                                            <option value="Owner" @if($user->type == 'Owner') selected @endif>ผู้ดูแลระบบสูงสุด</option>
                                        @endif
                                        <option value="Admin" @if($user->type == 'Admin') selected @endif>ผู้ดูแลสถานพยาบาล</option>
                                        <option value="User" @if($user->type == 'User') selected @endif>ผู้ใช้งานทั่วไป</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <button class="btn btn-warning" type="submit"><i class="fa fa-check push-5-r"></i> แก้ไขข้อมูลผู้ใช้งาน</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script_ready')
    $('#prefix-id').select2();
    $('#office-id').select2();
@endsection
@section('script')
    <script src="{{ asset('assets/js/pages/base_pages_users_update.js') }}"></script>
    <script type="text/javascript">

    </script>
@endsection
