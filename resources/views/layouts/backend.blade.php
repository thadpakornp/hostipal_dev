<!DOCTYPE html>
<!--[if IE 9]>         <html class="ie9 no-focus" lang="{{ config('app.locale') }}"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-focus" lang="{{ config('app.locale') }}">
<!--<![endif]-->

<head>
    <meta charset="utf-8">

    <title>IMATTHIO Company Limited</title>

    <meta name="description" content="IMATTHIO Company Limited">
    <meta name="author" content="IMATTHIO Company Limited">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Stylesheets -->
    <!-- Web fonts -->
    <link href="https://fonts.googleapis.com/css?family=Sarabun&display=swap" rel="stylesheet">
    <!-- Bootstrap and OneUI CSS framework -->
    <link href="{{ asset('assets/css/select2.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.css') }}">

    @yield('css')
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png"') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
</head>

<body>
<div id="page-container" class="sidebar-l side-scroll header-navbar-fixed">
    <!-- Side Overlay-->
    <aside id="side-overlay">
        <!-- Side Overlay Scroll Container -->
        <div id="side-overlay-scroll">
            <!-- Side Header -->
            <div class="side-header side-content">
                <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                <button class="btn btn-default pull-right" type="button" data-toggle="layout"
                        data-action="side_overlay_close">
                    <i class="fa fa-times"></i>
                </button>
                <span class="font-w600">{{ Auth::user()->prefix->name }} {{ Auth::user()->name }}
                    {{ Auth::user()->surname }}</span>
            </div>
            <!-- END Side Header -->

            <!-- Side Content -->
            <div class="side-content remove-padding-t">
                <!-- Account -->
                <div class="block pull-r-l">
                    <div class="block-header bg-gray-lighter">
                        <ul class="block-options">
                            <li>
                                <button type="button" data-toggle="block-option"
                                        data-action="content_toggle"></button>
                            </li>
                        </ul>
                        <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">บัญชีผู้ใช้งาน</h3>
                    </div>
                    <div class="block-content">
                        <form class="js-validation-update form-horizontal"
                              action="{{ route('backend.users.updated') }}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label>สิทธิ์การใช้งาน : <b>{{ Auth::user()->type }}</b></label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="hospital">สถานพยายาล</label>
                                    <select name="hospital" size="1" class="form-control" id="hospital"
                                            @if(Auth::user()->type == 'User') disabled="true" @endif>
                                        <option value="">โปรดเลือกสถานพยายาล</option>
                                        @foreach($hospitals as $hospital)
                                            <option value="{{ $hospital->id }}" @if(Auth::user()->office_id ==
                                                $hospital->id) selected @endif>{{ $hospital->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="bd-qsettings-name">คำนำหน้า</label>
                                    <select name="prefix_id" size="1" class="form-control select2" id="prefix"
                                            required>
                                        @foreach($prefixs as $prefix)
                                            <option value="{{ $prefix->code }}" @if(Auth::user()->prefix_id ==
                                                $prefix->code) selected @endif>{{ $prefix->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="bd-qsettings-name">ชื่อ</label>
                                    <input class="form-control" type="text" id="bd-qsettings-name" name="name"
                                           value="{{ Auth::user()->name }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="bd-qsettings-name">นามสกุล</label>
                                    <input class="form-control" type="text" id="bd-qsettings-surname" name="surname"
                                           value="{{ Auth::user()->surname }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="bd-qsettings-name">เบอร์โทรติดต่อ</label>
                                    <input class="form-control" type="text" id="bd-qsettings-phone" name="phone"
                                           value="{{ Auth::user()->phone }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="bd-qsettings-email">อีเมลผู้ใช้งาน</label>
                                    <input class="form-control" type="email" id="bd-qsettings-email" name="email">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="bd-qsettings-password">รหัสผ่าน</label>
                                    <input class="form-control" type="password" id="bd-qsettings-password"
                                           name="password">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="bd-qsettings-password2">รหัสผ่านอีกครั้ง</label>
                                    <input class="form-control" type="password" id="bd-qsettings-password2"
                                           name="password_confirmation">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-xs-12" for="example-file-input">รูปโปรไฟล์</label>
                                <div class="col-xs-12">
                                    <input type="file" id="example-file-input" name="file-input">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <button class="btn btn-sm btn-minw btn-rounded btn-primary" type="submit">
                                        <i class="fa fa-check push-5-r"></i>แก้ไขข้อมูล
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- END Account -->
            </div>
            <!-- END Side Content -->
        </div>
        <!-- END Side Overlay Scroll Container -->
    </aside>
    <!-- END Side Overlay -->
    <!-- Header -->
    <header id="header-navbar">
        <div class="content-mini content-mini-full content-boxed">
            <!-- Header Navigation Right -->
            <ul class="nav-header pull-right">
                <li class="visible-xs">
                    <!-- Toggle class helper (for .js-header-search below), functionality initialized in App() -> uiToggleClass() -->
                    <button class="btn btn-default" data-toggle="class-toggle" data-target=".js-header-search"
                            data-class="header-search-xs-visible" type="button">
                        <i class="fa fa-search"></i>
                    </button>
                </li>
                <li class="js-header-search header-search remove-margin">
                    <form class="form-horizontal" action="{{ route('backend.search') }}" method="post">
                        @csrf
                        <div
                            class="form-material form-material-primary input-group remove-margin-t remove-margin-b">
                            <input class="form-control" type="text" id="base-material-text" name="input_search"
                                   placeholder="Search..">
                            <span class="input-group-addon"><i class="si si-magnifier"></i></span>
                        </div>
                    </form>
                </li>
                <li>
                    <!-- Layout API, functionality initialized in App() -> uiLayoutApi() -->
                    <button class="btn btn-default btn-image" data-toggle="layout" data-action="side_overlay_toggle"
                            type="button">
                        <img src="{{ asset('assets/img/avatars/'.Auth::user()->profile) }}" alt="Avatar">
                        <i class="fa fa-ellipsis-v"></i>
                    </button>
                </li>
            </ul>
            <!-- END Header Navigation Right -->

            <!-- Header Navigation Left -->
            <ul class="nav-header pull-left">
                <li class="header-content">
                    <a class="h5" href="{{ route('backend.index') }}">
                        <!-- logo -->
                        <i class="fa fa-info text-primary"></i> <span
                            class="h4 font-w600 text-primary-dark">MATTHIO</span>
                    </a>
                </li>
            </ul>
            <!-- END Header Navigation Left -->
        </div>
    </header>
    <!-- END Header -->

    <!-- Main Container -->
    <main id="main-container">
        <!-- Sub Header -->
        <div class="bg-gray-lighter visible-xs">
            <div class="content-mini content-boxed">
                <button class="btn btn-block btn-default visible-xs push" data-toggle="collapse"
                        data-target="#sub-header-nav">
                    <i class="fa fa-navicon push-5-r"></i>Menu
                </button>
            </div>
        </div>
        <div class="bg-primary-lighter collapse navbar-collapse remove-padding" id="sub-header-nav">
            <div class="content-mini content-boxed">
                <ul class="nav nav-pills nav-sub-header push">
                    <li class="dropdown @if(Request::segment(2) == 'charts') active @endif">
                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-briefcase push-5-r"></i>ข้อมูลผู้ป่วย <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li @if(Request::segment(3)==null) class="active" @endif>
                                <a href="{{ route('backend.charts.index') }}">ลงทะเบียนผู้ป่วย</a>
                            </li>
                            <li @if(Request::segment(3)=='users' ) class="active" @endif>
                                <a href="{{ route('backend.charts.users') }}">ระเบียนผู้ป่วย</a>
                            </li>
                        </ul>
                    </li>
                    @if(Auth::user()->type != 'User')
                        <li @if(Request::segment(2)=='users' ) class="active" @endif>
                            <a href="{{ route('backend.users.index') }}">
                                <i class="fa fa-users push-5-r"></i>จัดการผู้ใช้งาน
                            </a>
                        </li>
                        <li @if(Request::segment(2)=='manage' ) class="active" @endif>
                            <a href="{{ route('backend.manage.index') }}">
                                <i class="fa fa-cog push-5-r"></i>จัดการสถานพยาบาล
                            </a>
                        </li>
                    @endif
                    <li>
                        <a href="#" onclick="logout();">
                            <i class="si si-logout push-5-r"></i> ออกจากระบบ
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- END Sub Header -->

        <!-- Page Content -->
    @yield('content')
    <!-- END Page Content -->
    </main>
    <!-- END Main Container -->

    <!-- Footer -->
    <footer id="page-footer" class="bg-body font-s12">
        <div class="content-mini content-mini-full content-boxed clearfix push-15">
            <div class="pull-right">
                <span class="js-year-copy"></span> &copy; IMATTHIO Company Limited. All Rights Reserved.
            </div>
        </div>
    </footer>
    <!-- END Footer -->
</div>
<!-- END Page Container -->

<!-- OneUI Core JS: jQuery, Bootstrap, slimScroll, scrollLock, Appear, CountTo, Placeholder, Cookie and App.js -->
<script src="{{ asset('assets/js/core/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/core/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('assets/js/core/jquery.scrollLock.min.js') }}"></script>
<script src="{{ asset('assets/js/core/jquery.appear.min.js') }}"></script>
<script src="{{ asset('assets/js/core/jquery.countTo.min.js') }}"></script>
<script src="{{ asset('assets/js/core/jquery.placeholder.min.js') }}"></script>
<script src="{{ asset('assets/js/core/js.cookie.min.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- Page JS Code -->
<script src="{{ asset('assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/base_pages_upload_profile.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.th.js') }}"></script>
<script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/js/pusher.min.js') }}"></script>
@yield('script')
<script type="text/javascript">
    jQuery(function () {
        Notification.requestPermission();

        Pusher.logToConsole = false;

        var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
            cluster: 'ap1',
            forceTLS: true
        });

        var channel = pusher.subscribe('chart-channel');
        channel.bind('chart-event', function (data) {
            //alert(JSON.stringify(data));
            //console.log(data);
            Notification.requestPermission().then(function (result) {
                if (result == "granted") {
                    var img = "{{ asset('ms-icon-144x144.png') }}";
                    var dataID = data.id;
                    var dataTitle = data.title;
                    var dataContent = data.content;
                    var dataAction = data.action;
                    var UserID = '{{ Auth::user()->id }}';

                    if (dataID != UserID) {
                        var notify = new Notification(dataTitle, {body: dataContent, icon: img});
                        notify.onclick = function(event) {
                            event.preventDefault(); // prevent the browser from focusing the Notification's tab
                            window.open(dataAction, '_blank');
                        }
                        setTimeout(notify.close.bind(notify), 7000);
                    }
                }
            });
        });


        $('#prefix').select2();

        @yield('script_ready')
    });

    function logout() {
        Swal.fire({
            title: 'ออกจากระบบ?',
            text: "คุณ  {{ Auth::user()->name }} {{ Auth::user()->surname }} ต้องการออกจากระบบ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ดำเนินการต่อ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "{{ route('backend.users.logoutonweb') }}",
                    data: {"_token": "{{ csrf_token() }}"},
                    success: function (res) {
                        $(location).attr('href', res.redirect);
                    }, error: function (e) {
                        console.log(e)
                        Swal.fire({
                            type: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถออกจากระบบได้',
                            confirmButtonText: 'ตกลง',
                        });
                    }
                });
            }
        })
    }

    @if (session('error'))
    Swal.fire({
        type: 'error',
        title: 'เกิดข้อผิดพลาด',
        text: '{{ session('error') }}',
        confirmButtonText: 'ตกลง',
    });
    @endif

    @if (session('success'))
    Swal.fire({
        type: 'success',
        title: 'สำเร็จ',
        text: '{{ session('success') }}',
        confirmButtonText: 'ตกลง',
    });
    @endif

    @if($errors->any())
    @foreach ($errors->all() as $error)
    Swal.fire({
        type: 'error',
        title: 'เกิดข้อผิดพลาด',
        text: '{{ $error }}',
        confirmButtonText: 'ตกลง',
    });
    @endforeach
    @endif
</script>
</body>

</html>
