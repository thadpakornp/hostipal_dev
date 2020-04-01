@extends('layouts.backend')

@section('content')
    <div class="content content-boxed">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">เพิ่มข้อมูลผู้ใช้งาน</h3>
            </div>
            <div class="block-content">
                <form class="js-validation-users form-horizontal push-10-t push-10" action="{{ route('backend.users.created') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label for="prefix-id">คำนำหน้า</label>
                                    <select class="form-control select2" name="prefix_id" id="prefix-id" required>
                                        <option value="">โปรดเลือกคำนำหน้า</option>
                                        @foreach($prefixs as $prefix)
                                            <option value="{{ $prefix->code }}">{{ $prefix->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-6">
                                    <label for="name">ชื่อ</label>
                                    <input class="form-control" type="text" id="name" name="name" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="surname">นามสกุล</label>
                                    <input class="form-control" type="text" id="surname" name="surname" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label for="email">อีเมลผู้ใช้งาน</label>
                                    <input class="form-control" type="email" id="email" name="email" required>
                                </div>
                                <div class="col-xs-6">
                                    <label for="phone">เบอร์โทรติดต่อ</label>
                                    <input class="form-control" type="text" id="phone" name="phone" required maxlength="10">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="file-input">รูปโปรไฟล์</label>
                                    <input type="file" id="file-input" name="file-input" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="office-id">สังกัดสถานพยาบาล</label>
                                    <select name="office_id" id="office-id" class="form-control select2"  @if(Auth::user()->type != 'Owner') required @endif>
                                        <option value="">โปรดเลือกสถานพยาบาล</option>
                                        @foreach($offices as $office)
                                            <option value="{{ $office->id }}">{{ $office->name }}</option>
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
                                            <option value="Owner">ผู้ดูแลระบบสูงสุด</option>
                                        @endif
                                        <option value="Admin">ผู้ดูแลสถานพยาบาล</option>
                                        <option value="User">ผู้ใช้งานทั่วไป</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <button class="btn btn-success" type="submit"><i class="fa fa-check push-5-r"></i> เพิ่มข้อมูลผู้ใช้งาน</button>
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
    <script src="{{ asset('assets/js/pages/base_pages_users.js') }}"></script>
    <script type="text/javascript">

    </script>
@endsection
