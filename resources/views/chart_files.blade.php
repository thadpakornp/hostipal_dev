@extends('layouts.backend')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/magnific-popup/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="https://cdn.plyr.io/3.5.10/plyr.css"/>
@endsection

@section('content')
    <div class="content content-boxed">
        <div class="block">
            <div class="bg-gray">
                <div class="block-content bg-primary-dark-op clearfix">
                    <div class="pull-left push">
                        <img class="img-avatar img-avatar-thumb"
                             src="@if($user->profile == null) {{ asset('assets/img/avatars/avatar1.jpg') }} @else {{ asset('assets/img/avatars/'.$user->profile) }} @endif"
                             alt="">
                    </div>
                    <div class="pull-left push-15-t push-15-l">
                        <h2 class="h4 font-w300 text-white push-5"
                            style="font-family: 'Sarabun', sans-serif;">
                            {{ $user->prefix->name }} {{ $user->name }} {{ $user->surname }}
                            (อายุ
                            @if($user->hbd != '' || $user->hbd != NULL)
                                @php
                                    $hnd_ex = explode('-',$user->hbd);
                                    $today_ex = explode('-',date('Y-m-d'));
                                    $hnd = Carbon\Carbon::createMidnightDate($hnd_ex[0],$hnd_ex[1],$hnd_ex[2]);
                                    $today = Carbon\Carbon::createMidnightDate($today_ex[0],$today_ex[1],$today_ex[2]);
                                    echo $hnd->diffInYears($today);
                                @endphp
                            @endif
                            ปี)</h2>
                        <h3 class="h5 text-white-op small" style="font-family: 'Sarabun', sans-serif;">H/N
                            : {{ $user->hn }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3 col-lg-3">
                <div class="block">
                    <div class="block-content">
                        <ul class="nav nav-pills nav-stacked push">
                            <li>
                                <a href="{{ route('backend.charts.feed',encrypt($user->id)) }}"><i
                                        class="fa fa-fw fa-pencil push-5-r"></i> ลงบันทึก</a>
                            </li>
                            <li class="active">
                                <a href="{{ route('backend.charts.files',encrypt($user->id)) }}"><i
                                        class="fa fa-fw fa-file push-5-r"></i> ไฟล์ทั้งหมด</a>
                            </li>
                        </ul>
                        <div class="font-w600 font-s12 text-uppercase text-muted push-10">บันทึกโดย</div>
                        <ul class="nav nav-pills nav-stacked font-s13 push">
                            <li>
                                <a href="javascript:void(0)">{{ $user->username->prefix->name }} {{ $user->username->name }} {{ $user->username->surname }}</a>
                            </li>
                        </ul>
                        <div class="font-w600 font-s12 text-uppercase text-muted push-10">บันทึกเมื่อ</div>
                        <ul class="nav nav-pills nav-stacked font-s13 push">
                            <li>
                                <a href="javascript:void(0)">{{ App\Helpers\FormatThai::DateThai($user->created_at) }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-9 col-lg-9">
                @if(isset($user->charts_files))
                    <div class="block">
                        <div class="block-header">
                            <i
                                class="fa fa-fw fa-file push-5-r"></i> ไฟล์ทั้งหมด
                            (จำนวน {{ count($user->charts_files) }} ไฟล์)
                        </div>
                        <div class="block-content block-content-full">
                        <div class="row js-gallery">
                            @foreach($user->charts_files as $file)
                                            
                                                @if($file->type_files == 'png' || $file->type_files == 'bmp' || $file->type_files == 'jpg' || $file->type_files == 'jpeg' || $file->type_files == 'gif')
                                                    <div class="col-xs-6">
                                                        <a class="img-link"
                                                           href="{{ asset('assets/img/photos/'.$file->files) }}">
                                                            <img class="img-responsive"
                                                                 src="{{ asset('assets/img/temnails/'.$file->files) }}"
                                                                 alt="">
                                                        </a>
                                                    </div>
                                                @endif
                                          
                                                @if($file->type_files == 'mp4' || $file->type_files == 'mov' || $file->type_files == 'mpg' || $file->type_files == 'mpeg' || $file->type_files == 'avi')
                                                <video id="player_video" playsinline controls width="100%">
                                                    <source src="{{ asset('assets/img/photos/'.$file->files) }}"
                                                            type="video/{{ $file->type_files }}"/>
                                                </video><br/>
                                            @endif
                            @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="block">
                        <div class="block-content block-content-full">
                            <p class="push-10 pull-t">ไม่พบข้อมูล</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('script')
<script src="{{ asset('assets/js/plugins/magnific-popup/magnific-popup.min.js') }}"></script>
<script src="https://cdn.plyr.io/3.5.10/plyr.js"></script>
    <script>jQuery(function(){ 
        App.initHelpers(['magnific-popup']);
        const player_video = Plyr.setup('#player_video'); 
        });</script>
    <script type="text/javascript">
        function delete_file(id) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: "หากลบแล้ว ไม่สามารถกู้คืนได้ ยืนยัน?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ลบ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('backend.charts.files.destroy') }}',
                        data: {"_token": "{{ csrf_token() }}", id: id},
                        success: function (res) {
                            Swal.fire({
                                type: 'success',
                                title: 'สำเร็จ',
                                text: 'ลบเรียบร้อยแล้ว',
                                confirmButtonText: 'ตกลง',
                            }).then(function () {
                                window.location.reload();
                            });
                        },
                        error: function (e) {
                            console.log(e)
                            Swal.fire({
                                type: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: 'ไม่สามารถลบได้',
                                confirmButtonText: 'ตกลง',
                            });
                        }
                    });
                }
            })
        }
    </script>
@endsection
