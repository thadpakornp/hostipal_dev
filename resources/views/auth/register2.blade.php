@extends('layouts.login')

@section('content')
    <div class="block block-themed animated fadeIn">
        <div class="block-header bg-success">
            <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">ลงทะเบียนใช้งานสำหรับสมาชิกใหม่</h3>
        </div>
        <div class="block-content block-content-full block-content-narrow">
            <!-- Register Title -->
            <p>โปรดระบุข้อมูลของคุณให้ครบถ้วน <font color="red">และรอการยืนยันจากผู้แลระบบก่อนการใช้งาน</font></p>
            <!-- END Register Title -->

            <!-- Register Form -->
            <!-- jQuery Validation (.js-validation-register class is initialized in js/pages/base_pages_register.js) -->
            <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
            <form class="js-validation-register form-horizontal push-50-t push-50" action="{{ url('register2/posted') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="form-material form-material-success floating">
                            <input class="form-control" type="email" id="register-email" name="email" autocomplete="off" autofocus required>
                            <label for="register-email">อีเมลผู้ใช้งาน</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="form-material form-material-success floating">
                            <input class="form-control" type="password" id="register-password" name="password" autocomplete="off" required>
                            <label for="register-password">รหัสผ่าน</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="form-material form-material-success floating">
                            <input class="form-control" type="password" id="register-password2" name="password_confirmation" autocomplete="off" required>
                            <label for="register-password2">รหัสผ่านอีกครั้ง</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12" for="contact1-subject">คำนำหน้า</label>
                    <div class="col-xs-12">
                        <select name="prefix_id" size="1" class="form-control select2" id="prefix" required>
                            <option value="">โปรดระบุคำนำหน้าชื่อ</option>
                                @foreach($prefixs as $prefix)
                                    <option value="{{ $prefix->code }}">{{ $prefix->name }}</option>
                                @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="form-material form-material-success floating">
                            <input class="form-control" type="text" id="register-name" name="name" autocomplete="off" required>
                            <label for="register-name">ชื่อ</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="form-material form-material-success floating">
                            <input class="form-control" type="text" id="register-surname" name="surname" autocomplete="off" required>
                            <label for="register-surname">นามสกุล</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="form-material form-material-success floating">
                            <input class="form-control" type="text" id="register-phone" name="phone" autocomplete="off" required minlength="9" maxlength="10">
                            <label for="register-phone">เบอร์ติดต่อ</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-12" for="contact1-subject">สังกัดสถานพยาบาล</label>
                    <div class="col-xs-12">
                        <select name="office_id" size="1" class="form-control select2" id="office" required>
                            <option value="">โปรดระบุสถานพยาบาล</option>
                                @php
                                $offices = App\Models\OfficeModel::whereNull('deleted_at')->get();
                                @endphp
                                @if(isset($offices))
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}">{{ $office->name }}</option>
                                @endforeach
                                @endif
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <label class="css-input switch switch-sm switch-success">
                            <input type="checkbox" id="register-terms" name="agree-terms" required><span></span> ยอมรับเงี่ยนไข &amp; ข้อกำหนดการใช้บริการ
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <button class="btn btn-block btn-success" type="submit"><i class="fa fa-plus pull-right"></i> ลงทะเบียน</button>
                    </div>
                </div>
            </form>
            <!-- END Register Form -->
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#prefix').select2();
            $('#office').select2();
        });
        $('.btn-primary').on('click', function () {
            $('#register-terms').prop('checked',true);
        });
    </script>

    <script src="{{ asset('assets/js/pages/base_pages_register.js') }}"></script>
@endsection
