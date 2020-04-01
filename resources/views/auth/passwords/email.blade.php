@extends('layouts.login')

@section('content')
    <div class="block block-themed animated fadeIn">
        <div class="block-header bg-primary">
            <ul class="block-options">
                <li>
                    <a href="{{ route('login') }}" data-toggle="tooltip" data-placement="left" title="เข้าใช้งานระบบ"><i class="si si-login"></i></a>
                </li>
            </ul>
            <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">ลืมรหัสผ่าน</h3>
        </div>
        <div class="block-content block-content-full block-content-narrow">
            <!-- Reminder Title -->
            <p>โปรดระบุอีเมลของคุณและเราจะส่งรหัสผ่านให้คุณ</p>
            <!-- END Reminder Title -->

            <!-- Reminder Form -->
            <!-- jQuery Validation (.js-validation-reminder class is initialized in js/pages/base_pages_reminder.js) -->
            <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
            <form class="js-validation-reminder form-horizontal push-30-t push-50" action="{{ route('password.email') }}" method="post">
                @csrf
                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="form-material form-material-primary floating">
                            <input class="form-control" type="email" id="reminder-email" name="email" autocomplete="off" autofocus>
                            <label for="reminder-email">อีเมลผู้ใช้งาน</label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <button class="btn btn-block btn-primary" type="submit"><i class="si si-envelope-open pull-right"></i> รีเซ็ตรหัสผ่าน</button>
                    </div>
                </div>
            </form>
            <!-- END Reminder Form -->
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/pages/base_pages_reminder.js') }}"></script>
@endsection
