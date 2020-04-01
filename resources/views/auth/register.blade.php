@extends('layouts.login')

@section('content')
    <div class="block block-themed animated fadeIn">
        <div class="block-header bg-success">
            <ul class="block-options">
                <li>
                    <a href="#" data-toggle="modal" data-target="#modal-terms">เงื่อนไขและข้อกำหนด</a>
                </li>
                <li>
                    <a href="{{ route('login') }}" data-toggle="tooltip" data-placement="left" title="เข้าใช้งานระบบ"><i class="si si-login"></i></a>
                </li>
            </ul>
            <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">ลงทะเบียนใช้งานสำหรับสมาชิกใหม่</h3>
        </div>
        <div class="block-content block-content-full block-content-narrow">
            <!-- Register Title -->
            <p>โปรดระบุข้อมูลของคุณให้ครบถ้วน</p>
            <!-- END Register Title -->

            <!-- Register Form -->
            <!-- jQuery Validation (.js-validation-register class is initialized in js/pages/base_pages_register.js) -->
            <!-- For more examples you can check out https://github.com/jzaefferer/jquery-validation -->
            <form class="js-validation-register form-horizontal push-50-t push-50" action="{{ route('register') }}" method="post" enctype="multipart/form-data">
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
                    <label class="col-xs-12" for="example-file-input">รูปโปรไฟล์</label>
                    <div class="col-xs-12">
                        <input type="file" id="example-file-input" name="file-input" required>
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

    <div class="modal fade" id="modal-terms" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout">
            <div class="modal-content">
                <div class="block block-themed block-transparent remove-margin-b">
                    <div class="block-header bg-primary-dark">
                        <ul class="block-options">
                            <li>
                                <button data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                            </li>
                        </ul>
                        <h3 class="block-title">เงื่อนไข &amp; ข้อบังคับการให้บริการ</h3>
                    </div>
                    <div class="block-content">
                        <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                        <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                        <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                        <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                        <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">ปิด</button>
                    <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal"><i class="fa fa-check"></i> ยอมรับ</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#prefix').select2();
        });
        $('.btn-primary').on('click', function () {
            $('#register-terms').prop('checked',true);
        });
    </script>

    <script src="{{ asset('assets/js/pages/base_pages_register.js') }}"></script>
@endsection
