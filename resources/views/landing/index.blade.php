<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Stroke Fast Track System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <link rel="manifest" href="site.webmanifest"> -->
    <!-- Place favicon.ico in the root directory -->

    <!-- CSS here -->
    <link rel="stylesheet" href="{{ asset('landing/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/gijgo.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/slicknav.css') }}">
    <link rel="stylesheet" href="{{ asset('landing/css/style.css') }}">

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
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">
</head>

<body>
    <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

    <!-- header-start -->
    <header>
        <div class="header-area ">
            <div id="sticky-header" class="main-header-area">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-xl-3 col-lg-2">
                            <div class="logo">
                                <a href="{{ url('/') }}">
                                    <img src="{{ asset('landing/img/logo.png') }}" width="36" height="36" alt="">
                                </a>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-7">
                            <div class="main-menu  d-none d-lg-block">
                                
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 d-none d-lg-block">
                            <div class="Appointment">
                                <div class="book_btn d-none d-lg-block">
                                    <a  href="{{ route('backend.index') }}">@if(auth()->check()) Backend @else Login @endif</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </header>
    <!-- header-end -->

    <!-- slider_area_start -->
    <div class="slider_area">
        <div class="single_slider  d-flex align-items-center slider_bg_1">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-7 col-md-6">
                        <div class="slider_text ">
                            <h3 class="wow fadeInDown" data-wow-duration="1s" data-wow-delay=".1s" >Stroke Fast Track</h3>
                            <p class="wow fadeInLeft" data-wow-duration="1s" data-wow-delay=".1s">แอปพลิเคชั่นสำหรับช่วยงานด้านผู้ป่วยสโตรก ประหยัดเวลาการจดบันทึกแบบเดิม ลดการศูนย์หายของข้อมูล</p>
                        </div>
                    </div>
                    <div class="col-xl-5 col-md-6">
                        <div class="phone_thumb wow fadeInDown" data-wow-duration="1.1s" data-wow-delay=".2s">
                            <img src="{{ asset('landing/img/ilstrator/phone.png') }}" width="360" height="680" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- slider_area_end -->
    <!-- service_area  -->
    <div class="service_area">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="section_title text-center  wow fadeInUp" data-wow-duration=".5s" data-wow-delay=".3s">
                        <h3>Features</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <div class="single_service  wow fadeInUp" data-wow-duration=".5s" data-wow-delay=".3s">
                        <h3>สมัครสมาชิก</h3>
                        <p>ผู้ใช้งานนสามารถสมัครสมาชิกเพื่อใช้งานแอปพลิเคชันได้ฟรี</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="single_service  wow fadeInUp" data-wow-duration=".6s" data-wow-delay=".4s">
                        <h3>ข้อมูลส่วนตัวและรหัสผ่าน</h3>
                        <p>ผู้ใช้งานสามารถเปลี่ยนแปลงข้อมูลส่วนตัวและแก้ไขรหัสผ่านได้</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4  wow fadeInUp" data-wow-duration=".7s" data-wow-delay=".5s">
                    <div class="single_service">
                        <h3>ห้องสนทนา</h3>
                        <p>ผู้ใช้งานสามารถพูดคุยและสอบถามปัญหาได้ผ่านทางหน้าแชทซึ่งมีผู้เชี่ยวชาญคอยให้คำตอบ</p>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4">
                    <div class="single_service  wow fadeInUp" data-wow-duration=".5s" data-wow-delay=".3s">
                        <h3>บันทึกข้อมูล</h3>
                        <p>ผู้ใช้งานสามารถบันทึกข้อมูลการรักษาได้</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4">
                    <div class="single_service  wow fadeInUp" data-wow-duration=".6s" data-wow-delay=".4s">
                        <h3>รูปภาพและวิดีโอที่เกี่ยวข้อง</h3>
                        <p>สามารถเพิ่มข้อมูลที่เกี่ยวข้องกับการรักษาของผู้ใช้งานได้</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4  wow fadeInUp" data-wow-duration=".7s" data-wow-delay=".5s">
                    <div class="single_service">
                        <h3>ค้นหาประวัติและติดตามประวัติ</h3>
                        <p>ตรวจสอบและค้นหาข้อมูลหรือประวัติการรักษาของท่านได้</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ service_area  -->
    
    <!-- productivity_area  -->
    <div class="productivity_area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-7 col-md-12 col-lg-6">
                    <h3 class="wow fadeInDown" data-wow-duration="1s" data-wow-delay=".1s">Get start from now <br></h3>
                </div>
                <div class="col-xl-5 col-md-12 col-lg-6">
                    <div class="app_download wow fadeInDown" data-wow-duration="1s" data-wow-delay=".1s">
                        <a href="#" >
                                <img src="{{ asset('landing/img/ilstrator/app.svg') }}" alt="">
                        </a>
                        <a href="#">
                                <img src="{{ asset('landing/img/ilstrator/play.svg') }}" alt="">
                        </a>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ productivity_area  -->

    <!-- footer start -->
    <footer class="footer">
        <div class="copy-right_text">
            <div class="container">
                <div class="footer_border"></div>
                <div class="row">
                    <div class="col-xl-12">
                        <p class="copy_right text-center">
                            <img src="{{ asset('landing/img/i_logo.png') }}" width="150" height="32">
 <br/> Imatthio Company Limited
592/110 Borromrachonnanee Road Thawi Watthana, Bangkok Thailand 10170 <br/>
Phone : +66 077 915 600
Email : info@imatthio.com <br/>Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!--/ footer end  -->

    <!-- JS here -->
    <script src="{{ asset('landing/js/vendor/modernizr-3.5.0.min.js') }}"></script>
    <script src="{{ asset('landing/js/vendor/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('landing/js/popper.min.js') }}"></script>
    <script src="{{ asset('landing/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('landing/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('landing/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('landing/js/ajax-form.js') }}"></script>
    <script src="{{ asset('landing/js/waypoints.min.js') }}"></script>
    <script src="{{ asset('landing/js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('landing/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('landing/js/scrollIt.js') }}"></script>
    <script src="{{ asset('landing/js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('landing/js/wow.min.js') }}"></script>
    <script src="{{ asset('landing/js/nice-select.min.js') }}"></script>
    <script src="{{ asset('landing/js/jquery.slicknav.min.js') }}"></script>
    <script src="{{ asset('landing/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('landing/js/plugins.js') }}"></script>
    <script src="{{ asset('landing/js/gijgo.min.js') }}"></script>

    <!--contact js-->
    <script src="{{ asset('landing/js/contact.js') }}"></script>
    <script src="{{ asset('landing/js/jquery.ajaxchimp.min.js') }}"></script>
    <script src="{{ asset('landing/js/jquery.form.js') }}"></script>
    <script src="{{ asset('landing/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('landing/js/mail-script.js') }}"></script>

    <script src="{{ asset('landing/js/main.js') }}"></script>
</body>

</html>