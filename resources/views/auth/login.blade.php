@extends('layouts.login')

@section('content')
    <div class="block block-themed animated fadeIn">
        <div class="block-header bg-primary">
            <ul class="block-options">
                <li>
                    <a href="{{ route('password.request') }}">ลืมรหัสผ่าน?</a>
                </li>
                <li>
                    <a href="{{ route('register') }}" data-toggle="tooltip" data-placement="left" title="สร้างผู้ใช้งานใหม่"><i class="si si-plus"></i></a>
                </li>
            </ul>
            <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">ระบบตรวจสอบยืนยันตัวตน</h3>
        </div>
        <div class="block-content block-content-full block-content-narrow">
            <!-- Login Title -->
            <p>โปรดระบุอีเมลผู้ใช้งานและรหัสผ่าน</p>
            <!-- END Login Title -->

            <!-- Login Form -->
            <!-- jQuery Validation (.js-validation-login class is initialized in js/pages/base_pages_login.js) -->
            <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
            <form class="js-validation-login form-horizontal push-30-t push-50" action="{{ route('login') }}" method="post">
                @csrf
                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="form-material form-material-primary floating @error('email') has-error @enderror">
                            <input class="form-control" type="text" id="login-username" name="email" autocomplete="off" required>
                            <label for="login-username">อีเมลผู้ใช้งาน</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="form-material form-material-primary floating @error('password') has-error @enderror">
                            <input class="form-control" type="password" id="login-password" name="password" autocomplete="off" required>
                            <label for="login-password">รหัสผ่าน</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <label class="css-input switch switch-sm switch-primary">
                            <input type="checkbox" id="login-remember-me" name="remember" {{ old('remember') ? 'checked' : '' }}><span></span> จดจำการเข้าใช้งานของฉัน?
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <button class="btn btn-block btn-primary" type="submit"><i class="si si-login pull-right"></i> เข้าใช้งานระบบ</button>
                    </div>
                </div>
            </form>
            <!-- END Login Form -->
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/pages/base_pages_login.js') }}"></script>
@endsection
