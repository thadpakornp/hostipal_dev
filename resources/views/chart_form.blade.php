@extends('layouts.backend')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/dropzonejs/dropzone.min.css') }}">
    <style type="text/css">
        .select2-selection__rendered {
            line-height: 43px !important;
        }

        .select2-container .select2-selection--single {
            height: 46px !important;
        }

        .select2-selection__arrow {
            height: 46px !important;
        }
    </style>
@endsection
@section('content')
    <div class="content content-boxed">
        <div class="block block-bordered">
            <div class="block-header bg-gray-lighter">
                <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">แบบฟอร์มลงทะเบียนผู้ป่วย</h3>
            </div>
            <div class="block-content">
                <form class="form-horizontal push-10-t push-10" action="{{ route('backend.charts.stored') }}"
                      method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label for="mega-icard">หมายเลขบัตรประชาชน</label>
                                    <input class="form-control input-lg" type="number" id="mega-icard"
                                           name="id_card" minlength="13" maxlength="13" required
                                           value="{{ old('id_card') }}" autocomplete="off" autofocus
                                           onkeyup="iCheck(this.value);">

                                </div>
                                <div class="col-xs-6">
                                    <label for="mega-hn">เลขประจำตัวผู้ป่วย (HN)</label>
                                    <input type="text" name="hn" class="form-control input-lg"
                                           value="{{ old('hn') }}" id="mega-hn" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="mega-phone">เบอร์โทรติดต่อ</label>
                                    <input class="form-control input-lg" type="number" id="mega-phone"
                                           name="phone" maxlength="10" value="{{ old('phone') }}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label for="mega-prefix">คำนำหน้า</label>
                                    <select class="form-control input-lg" name="prefix_id" id="mega-prefix"
                                            required>
                                        <option value="">โปรดเลือกคำนำหน้า</option>
                                        @foreach($prefixs as $prefix)
                                            <option value="{{ $prefix->code }}">{{ $prefix->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-6">
                                    <label for="mega-name">ชื่อ</label>
                                    <input class="form-control input-lg" type="text" id="mega-name"
                                           name="name" required value="{{ old('name') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="mega-surname">นามสกุล</label>
                                    <input class="form-control input-lg" type="text"
                                           name="surname" id="mega-surname" required
                                           value="{{ old('surname') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label for="mega-hbd">วันเดือนปี เกิด</label>
                                    <input class="form-control input-lg" type="text" id="mega-hbd"
                                           name="hbd" required value="{{ old('bhd') }}" autocomplete="off">
                                </div>
                                <div class="col-xs-6">
                                    <label for="mega-sex">เพศ</label>
                                    <div class="col-xs-12">
                                        <label class="css-input css-radio css-radio-warning push-10-r">
                                            <input type="radio" name="sex" value="M"><span></span> ผู้ชาย
                                        </label>
                                        <label class="css-input css-radio css-radio-warning">
                                            <input type="radio" name="sex" value="F"><span></span> ผู้หญิง
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="mega-profile">รูปภาพประจำตัว</label>
                                    <input type="file" name="profile" class="form-control input-lg" id="mega-profile"
                                           accept="image/jpeg,image/png">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="mega-address">ที่อยู่</label>
                                    <textarea class="form-control input-lg" id="mega-address"
                                              name="address" rows="2">{{ old('address') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="mega-description">รายละเอียดเพิ่มเติม</label>
                                    <textarea class="form-control input-lg" id="mega-description"
                                              name="description" rows="4">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="block block-opt-refresh" id="block" style="display: none;">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="col-xs-12">
                                        <label>โหลดข้อมูลจากแอพ (สามารถคลิกที่รายละเอียดเพื่อผู้เอกสารประกอบได้)</label>
                                        <table class="table table-hover table-bordered table-responsive" width="100%">
                                            <thead>
                                            <th width="5%">เลือก</th>
                                            <th width="50%">รายละเอียด</th>
                                            <th width="25%" class="text-center">ผู้บันทึก</th>
                                            <th width="20%" class="text-center">วันที่และเวลา</th>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="old_id" value="" id="old_id">
                    <div class="form-group">
                        <div class="col-xs-12">
                            <a class="btn btn-primary" onclick="LoadingDataFromApp();" id="1"><i
                                    class="fa fa-refresh push-5-r"></i> โหลดข้อมูลจากแอพ
                            </a>
                            <a class="btn btn-info" onclick="uploadFromComputer();" id="2"><i
                                    class="fa fa-upload push-5-r"></i> อัปโหลดข้อมูลจากคอมพิวเตอร์
                            </a>
                            <button class="btn btn-lg btn-success pull-right" type="submit" id="3"><i
                                    class="fa fa-save push-5-r"></i> บันทึก
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="modal-upload" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg">
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
                                อัปโหลดข้อมูลจากคอมพิวเตอร์ Max size : 20MB</h3>
                        </div>
                        <div class="block-content">
                            <form method="post" action="{{ route('backend.charts.uploaded') }}"
                                  enctype="multipart/form-data" class="dropzone" id="dropzone">
                                @csrf
                                <div class="dz-message" data-dz-message><span>วางหรือลากไฟล์ที่นี่เพื่ออัปโหลด</span>
                                </div>
                            </form>
                            <br/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Modal -->

    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/locales/bootstrap-datepicker.th.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dropzonejs/dropzone.min.js') }}"></script>
    <script type="text/javascript">
        Dropzone.options.dropzone =
            {
                maxFilesize: 20,
                renameFile: function (file) {
                    var dt = new Date();
                    var d = dt.getDate();
                    var time = dt.getTime();
                    return d + time + file.name;
                },
                acceptedFiles: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel,audio/wav,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-powerpoint,application/pdf,image/png,image/jpeg,image/gif,text/csv,image/bmp,audio/mpeg,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,video/x-flv,video/mp4',
                addRemoveLinks: true,
                timeout: 5000,
                removedfile: function (file) {
                    var name = file.upload.filename;
                    $.ajax({
                        type: "POST",
                        url: "{{ route('backend.charts.destroy.file') }}",
                        data: {"_token": "{{ csrf_token() }}", filename: name},
                        success: function (data) {
                            //console.log("File has been successfully removed!!");
                        },
                        error: function (e) {
                            console.log(e);
                        }
                    });
                    var fileRef;
                    return (fileRef = file.previewElement) != null ?
                        fileRef.parentNode.removeChild(file.previewElement) : void 0;
                },
                success: function (file, response) {
                    //console.log(response.id);
                },
                error: function (file, response) {
                    Swal.fire({
                        type: 'error',
                        title: 'ไฟล์นี้ไม่ได้รับการบันทึก',
                        text: response,
                        confirmButtonText: 'ตกลง',
                    });
                    this.removeFile(file)
                }
            };

        function LoadingDataFromApp() {
            $("table tbody>tr").remove();
            $('#block').hide();
            $('#block').addClass('block-opt-refresh');
            $('#1').addClass('disabled');
            $('#2').addClass('disabled');
            $('#3').addClass('disabled');
            $('#block').show();
            $.ajax({
                type: "GET",
                url: "{{ route('backend.charts.data.app') }}",
                success: function (res) {
                    $("table tbody").html(res.info);
                    $('#1').removeClass('disabled');
                    $('#2').removeClass('disabled');
                    $('#3').removeClass('disabled');
                    $('#block').removeClass('block-opt-refresh');
                },
                error: function (e) {
                    console.log(e);
                    $('#1').removeClass('disabled');
                    $('#2').removeClass('disabled');
                    $('#3').removeClass('disabled');
                    $('#block').removeClass('block-opt-refresh');
                }
            });

        }

        function uploadFromComputer() {
            $('#modal-upload').modal('show');
        }

        function iCheck(id) {
            if (id.length == 13) {
                $.ajax({
                    url: "{{ route('backend.charts.i.check') }}",
                    type: "POST",
                    data: {"_token": "{{ csrf_token() }}", id: id},
                    success: function (res) {
                        if (res.info != null) {
                            $('#mega-prefix').val(res.info.prefix_id);
                            $('#mega-prefix').trigger('change');
                            $('#mega-hn').val(res.info.hn);
                            $('#mega-phone').val(res.info.phone);
                            $('#mega-name').val(res.info.name);
                            $('#mega-surname').val(res.info.surname);
                            $('#mega-address').val(res.info.address);
                            $('#old_id').val(res.info.id);
                            if (res.info.hbd != null) {
                                var hdb = res.info.hbd.split('-');
                                $('#mega-hbd').datepicker("setDate", new Date(hdb[0], hdb[1], hdb[2]));
                            }
                            if (res.info.sex != null) {
                                var $radios = $('input:radio[name=sex]');
                                if ($radios.is(':checked') === false) {
                                    $radios.filter('[value=' + res.info.sex + ']').prop('checked', true);
                                }
                            }
                        }
                    },
                    error: function (e) {
                        console.log(e);
                    }
                });
            }
        }
    </script>
@endsection
@section('script_ready')
    $('#mega-prefix').select2();
    $('#mega-hbd').datepicker({
    language: "th",
    format: "yyyy-mm-dd"
    });
@endsection
