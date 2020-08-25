<!DOCTYPE html>
<!--[if IE 9]>         <html class="ie9 no-focus" lang="{{ config('app.locale') }}"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-focus" lang="{{ config('app.locale') }}"> <!--<![endif]-->
<head>
    <meta charset="utf-8">

    <title>Stroke Fast Track System : IMATTHIO Company Limited</title>

    <meta name="description" content="IMATTHIO Company Limited">
    <meta name="author" content="IMATTHIO Company Limited">
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Stylesheets -->
    <!-- Web fonts -->
    <link href="https://fonts.googleapis.com/css?family=Sarabun&display=swap" rel="stylesheet">

    <!-- Bootstrap and OneUI CSS framework -->
    <link href="{{ asset('assets/css/select2.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/oneui.css') }}">

    <!-- Page JS Plugins CSS -->
    @yield('css')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.css') }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
</head>
<body>
<!-- Login Content -->
<div class="content overflow-hidden">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
            <!-- Login Block -->
            @yield('content')
            <!-- END Login Block -->
        </div>
    </div>
</div>
<!-- END Login Content -->

<!-- Login Footer -->
<div class="push-10-t text-center animated fadeInUp">
    <small class="text-muted font-w600"><span class="js-year-copy"></span> &copy; IMATTHIO Company Limited. All Rights Reserved.</small><br/>
    <small class="text-muted font-w600"><a href="{{ url('policy') }}">Privacy Policy</a> || <a href="{{ url('terms') }}">Terms &amp; Conditions</a></small>
</div>
<!-- END Login Footer -->
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

<!-- Page JS Plugins -->
<script src="{{ asset('assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.th.js') }}"></script>
<!-- Page JS Code -->
@yield('script')
<script type="text/javascript">
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
