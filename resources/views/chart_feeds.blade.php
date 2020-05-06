@extends('layouts.backend')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/magnific-popup/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="https://cdn.plyr.io/3.5.10/plyr.css"/>
    <style type="text/css">
        .scroll-y {
            height: 400px;
            overflow-y: scroll;
        }
    </style>
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
                            <li class="active">
                                <a href="{{ route('backend.charts.feed',encrypt($user->id)) }}"><i
                                        class="fa fa-fw fa-pencil push-5-r"></i> ลงบันทึก</a>
                            </li>
                            <li>
                                <a href="{{ route('backend.charts.files',encrypt($user->id)) }}"><i
                                        class="fa fa-fw fa-file push-5-r"></i> ไฟล์ทั้งหมด</a>
                            </li>
                        </ul>
                        <div class="font-w600 font-s12 text-uppercase text-muted push-10">สร้างประวัติโดย</div>
                        <ul class="nav nav-pills nav-stacked font-s13 push">
                            <li>
                                <a href="javascript:void(0)">{{ $user->username->prefix->name }} {{ $user->username->name }} {{ $user->username->surname }}</a>
                            </li>
                        </ul>
                        <div class="font-w600 font-s12 text-uppercase text-muted push-10">สร้างเมื่อ</div>
                        <ul class="nav nav-pills nav-stacked font-s13 push">
                            <li>
                                <a href="javascript:void(0)">{{ App\Helpers\FormatThai::DateThai($user->created_at) }}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                @if($user->status == 'Activate')
                    <div class="block">
                        <div class="block-content">
                            <center>
                                <button class="btn btn-lg btn-success" style="margin-bottom: 18px"
                                        onclick="clickSuccess('{{ encrypt($user->id) }}');">กระบวนการรักษาเสร็จสิ้น
                                </button>
                            </center>
                        </div>
                    </div>
                @endif

                <div class="block">
                    <div class="block-content">
                        <div class="font-w600 font-s12 text-uppercase text-muted push-10">ประวัติการรักษา</div>
                        <ul class="nav nav-pills nav-stacked font-s13 push scroll-y">
                            @foreach($users as $u)
                                <li @if($u->id == $user->id) class="active" @endif>
                                    <a href="{{ route('backend.charts.feed', encrypt($u->id)) }}"><font
                                            color="@if($u->status == 'Activate') red @else green @endif"> {{ App\Helpers\FormatThai::DateThaiNoTime($u->created_at) }}</font></a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>


            <div class="col-sm-9 col-lg-9">
                @if($user->status == 'Activate')
                    <div class="block">
                        <div class="block-header" style="padding-bottom: 10px;">
                            <i class="fa fa-fw fa-pencil push-5-r font-w600"></i> ลงบันทึก</a>
                        </div>
                        <div class="block-content block-content-full">
                            <form class="form-horizontal push-10-t push-10"
                                  action="{{ route('backend.charts.description.stored') }}"
                                  method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="charts_id" value="{{ encrypt($user->id) }}">
                                <textarea type="text" name="description" rows="3" class="form-control"></textarea><br/>
                                เลือกหลายไฟล์โดยการกด Ctrl <font class="text-danger">(เฉพาะไฟล์
                                    .png,.jpeg,.gif,.jpg,.bmp,.mov,.mp4)</font>
                                <input type="file" name="files[]" multiple class="form-control"
                                       accept=".png,.jpeg,.gif,.jpg,.bmp,.mov,.mp4">
                                <br/>
                                <a type="button" class="btn btn-group btn-block btn-danger" onclick="check_in();">เช็คอิน (ระบบไม่บันทึกค่าเช็คอินพื้นฐานที่ถูกกำหนดตั้งแต่ต้น)</a><br/>
                                <div id="check-in" style="display: none">
                                    <br/>
                                    <input type="text" id="address-input" name="address_address"
                                           class="form-control map-input">
                                    <input type="hidden" name="g_location_lat" id="address-latitude" value="0"/>
                                    <input type="hidden" name="g_location_long" id="address-longitude"
                                           value="0"/>
                                    <div id="address-map-container" style="width:100%;height:400px;">
                                        <div style="width: 100%; height: 100%" id="address-map"></div>
                                    </div>
                                    <input id="latformHRML" value="13.744674" type="hidden">
                                    <input id="longformHRML" value="100.5633683" type="hidden">
                                </div><br/>
                                <button type="submit" class="btn btn-group btn-block btn-primary">บันทึก</button>
                            </form>
                        </div>
                    </div>
                @endif


                @if(isset($user->charts_description))
                    @foreach($user->charts_description as $desc)
                        <div class="block">
                            <div class="block-header">
                                @if(Auth::user()->type == 'Owner' || $desc->add_by_user == Auth::user()->id)
                                    <ul class="block-options">
                                        <li class="dropdown">
                                            <button type="button" data-toggle="dropdown">
                                                <i class="fa fa-angle-down"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right font-s13">
                                                <li>
                                                    <a tabindex="-1" href="javascript:void(0)"
                                                       onclick="deleteDesc('{{ encrypt($desc->id) }}');">
                                                        <i class="fa fa-times text-danger push-5-r"></i>
                                                        ลบ
                                                    </a>
                                                    <a tabindex="-1" href="javascript:void(0)"
                                                       onclick="description('{{ encrypt($desc->id) }}');">
                                                        <i class="fa fa-edit text-warning push-5-r"></i>
                                                        แก้ไข
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                @else
                                    @if(App\Models\User::checkPermission($desc->add_by_user) != 'Owner' && Auth::user()->type == 'Admin')
                                        <ul class="block-options">
                                            <li class="dropdown">
                                                <button type="button" data-toggle="dropdown">
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right font-s13">
                                                    <li>
                                                        <a tabindex="-1" href="javascript:void(0)"
                                                        onclick="deleteDesc('{{ encrypt($desc->id) }}');">
                                                            <i class="fa fa-times text-danger push-5-r"></i>
                                                            ลบ
                                                        </a>
                                                        <a tabindex="-1" href="javascript:void(0)"
                                                        onclick="description('{{ encrypt($desc->id) }}');">
                                                            <i class="fa fa-edit text-warning push-5-r"></i>
                                                            แก้ไข
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    @endif
                                @endif
                                <div class="clearfix">
                                    <div class="pull-left push-15-r">
                                        <img class="img-avatar img-avatar48"
                                             src="@if($desc->username->profile == null) assets/img/avatars/avatar1.jpg @else {{ asset('assets/img/avatars/'.$desc->username->profile) }} @endif"
                                             alt="">
                                    </div>
                                    <div class="push-5-t">
                                        <font
                                            class="font-w600">{{ $desc->username->prefix->name }} {{ $desc->username->name }} {{ $desc->username->surname }} @if($desc->g_location_lat != null && $desc->g_location_long != null) อยู่ที่ <a href="{{ "http://maps.google.com/?q=" . $desc->g_location_lat .",".$desc->g_location_long."" }}" target="_blank"> {{ $desc->g_location_lat }} {{ $desc->g_location_long }} </a> @endif</font><br>
                                        <span class="font-s12 text-muted">
                                          {{ App\Helpers\FormatThai::DateThai($desc->created_at) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="block-content block-content-full">
                                <p class="push-10 pull-t">{{ $desc->description }}</p>
                                @php
                                    $files = App\Models\Charts_files::where('charts_desc_id',$desc->id)->whereNull('deleted_at');
                                @endphp
                                @if($files->count() > 0)
                                    @php
                                    $files = $files->get();
                                    @endphp
                                    <div class="row js-gallery">
                                        @foreach($files as $file)
                                            @if($file->deleted_at == NULL)
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
                                            @endif
                                        @endforeach
                                    </div>

                                    @foreach($files as $file)
                                        @if($file->deleted_at == NULL)
                                            @if($file->type_files == 'mp4' || $file->type_files == 'mov' || $file->type_files == 'mpg' || $file->type_files == 'mpeg' || $file->type_files == 'avi')
                                                <video id="player_video" playsinline controls width="100%">
                                                    <source src="{{ asset('assets/img/photos/'.$file->files) }}"
                                                            type="video/{{ $file->type_files }}"/>
                                                </video><br/>
                                            @endif
                                        @endif
                                    @endforeach

                                    @foreach($files as $file)
                                        @if($file->deleted_at == NULL)
                                            @if($file->type_files == 'mp3')
                                                <audio id="player_audio" controls width="100%">
                                                    <source src="{{ asset('assets/img/photos/'.$file->files) }}"
                                                            type="audio/mp3"/>
                                                </audio><br/>
                                            @endif
                                        @endif
                                    @endforeach

                                    @foreach($files as $file)
                                        @if($file->deleted_at == NULL)
                                            @if($file->type_files == 'csv' || $file->type_files == 'xls' || $file->type_files == 'xlsx' || $file->type_files == 'pdf' || $file->type_files == 'doc' || $file->type_files == 'docx' ||  $file->type_files == 'pdf')
                                                <ul class="list-events">
                                                    <li>
                                                        <a href="{{ route('backend.charts.description.file.download',encrypt($file->id)) }}">{{ $file->files }}</a>
                                                    </li>
                                                </ul>
                                            @endif
                                        @endif
                                    @endforeach
                                @else
                                    @if($desc->g_location_lat != null && $desc->g_location_long != null)
                                        <iframe
                                            width="100%"
                                            height="450"
                                            frameborder="0" style="border:0"
                                            src="https://www.google.com/maps/embed/v1/place?key={{ config('app.GOOGLE_MAPS_API_KEY') }}&q={{ $desc->g_location_lat }},{{ $desc->g_location_long }}" allowfullscreen>
                                        </iframe>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="block">
                        <div class="block-content block-content-full">
                            <p class="push-10 pull-t">ไม่พบข้อมูล</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="modal-description" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-popin">
                <div class="modal-content">
                    <div class="block block-themed block-transparent remove-margin-b">
                        <div class="block-header bg-primary-dark">
                            <ul class="block-options">
                                <li>
                                    <button data-dismiss="modal" type="button"><i class="si si-close"></i>
                                    </button>
                                </li>
                            </ul>
                            <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">
                                แก้ไข</h3>
                        </div>
                        <div class="block-content" id="description-show">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Modal -->

    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/magnific-popup/magnific-popup.min.js') }}"></script>
    <script src="https://cdn.plyr.io/3.5.10/plyr.js"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('app.GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initialize"
        async defer></script>
    <script src="{{ asset('assets/js/mapInput.js') }}"></script>
    <script type="text/javascript">
        function check_in(){
            if($('#check-in').css('display') == 'none'){
                $('#check-in').show();
            } else {
                $('#check-in').hide();
            }
        }

        function clickSuccess(id) {
            Swal.fire({
                title: 'ยืนยัน?',
                text: "กระบวนการรักษาเสร็จสิ้นแล้ว?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'เสร็จสิ้น',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('backend.charts.success.chart') }}',
                        data: {"_token": "{{ csrf_token() }}", id: id},
                        success: function (res) {
                            Swal.fire({
                                type: 'success',
                                title: 'สำเร็จ',
                                text: 'บันทึกข้อมูลแล้ว',
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
                                text: 'ไม่สามารถดำเนินการได้',
                                confirmButtonText: 'ตกลง',
                            });
                        }
                    });
                }
            })
        }

        function description(id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('backend.charts.description.edit') }}',
                data: {"_token": "{{ csrf_token() }}", id: id},
                success: function (res) {
                    $('#modal-description').modal('show');
                    $('#description-show').html(res);
                },
                error: function (e) {
                    console.log(e)
                    Swal.fire({
                        type: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถโหลดข้อมูลได้',
                        confirmButtonText: 'ตกลง',
                    });
                }
            });
        }

        function delete_files(id, upload_id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('backend.charts.files.deleted') }}',
                data: {"_token": "{{ csrf_token() }}", upload_id: upload_id},
                success: function () {
                    $('#file_id_' + id).remove();
                },
                error: function (e) {
                    console.log(e);
                }
            });
        }

        function deleteDesc(id) {
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
                        url: '{{ route('backend.charts.description.destroy') }}',
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
@section('script_ready')
    App.initHelpers(['magnific-popup']);
    const player_video = Plyr.setup('#player_video');
    const player_audio = Plyr.setup('#player_audio');
@endsection
