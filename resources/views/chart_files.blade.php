@extends('layouts.backend')

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
                            @foreach($user->charts_files as $file)
                                <ul class="list list-events">
                                    <li>@if(Auth::user()->type == 'Owner' || $file->add_by_user == Auth::user()->id)
                                            <a href="javascript:void(0)"><i
                                                    class="si si-close text-danger"
                                                    onclick="delete_file('{{ encrypt($file->id) }}')"></i></a>
                                        @endif
                                        <a
                                            href="{{ route('backend.charts.description.file.download',encrypt($file->id)) }}">{{ $file->files }}</a>
                                        <small
                                            class="pull-right">อัปโหลดเมื่อ {{ App\Helpers\FormatThai::DateThai($file->created_at) }}</small>
                                    </li>
                                </ul>
                            @endforeach
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
