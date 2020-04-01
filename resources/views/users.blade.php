@extends('layouts.backend')
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/magnific-popup/magnific-popup.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.css') }}">
@endsection
@section('content')
    <div class="content content-boxed">
        <div class="block @if(!isset($requests)) block-opt-hidden @endif">
            <div class="block-header">
                <ul class="block-options">
                    <li>
                        <button type="button" data-toggle="block-option" data-action="content_toggle"></button>
                    </li>
                </ul>
                <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">ค้นหาผู้ใช้งานในระบบ</h3>
            </div>
            <div class="block-content">
                <form class="form-horizontal push-10-t push-10" action="{{ route('backend.users.search') }}"
                      method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label for="name">ชื่อ</label>
                                    <input class="form-control" type="text" id="name" name="name"
                                           @if(isset($requests)) value="{{ $requests['name'] }}" @endif>
                                </div>
                                <div class="col-xs-6">
                                    <label for="surname">นามสกุล</label>
                                    <input class="form-control" type="text" id="surname" name="surname"
                                           @if(isset($requests)) value="{{ $requests['surname'] }}" @endif>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="status">สถานะการใช้งาน</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">โปรดเลือกสถานะการใช้งาน</option>
                                        <option value="Active"
                                                @if(isset($requests)) @if($requests['status'] == 'Active') selected @endif @endif>
                                            ใช้งาน
                                        </option>
                                        <option value="Disabled"
                                                @if(isset($requests)) @if($requests['status'] == 'Disabled') selected @endif @endif>
                                            รออนุมัติ
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if(Auth::user()->type == 'Owner')
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <div class="col-xs-6">
                                        <label for="type">ระดับการใช้งาน</label>
                                        <select name="type" id="type" class="form-control">
                                            <option value="">โปรดเลือกระดับการใช้งาน</option>
                                            <option value="Owner"
                                                    @if(isset($requests)) @if($requests['type'] == 'Owner') selected @endif @endif>
                                                ผู้ดูแลระดับสูงสุด
                                            </option>
                                            <option value="Admin"
                                                    @if(isset($requests)) @if($requests['type'] == 'Admin') selected @endif @endif>
                                                ผู้ดูแลสถานพยาบาล
                                            </option>
                                            <option value="User"
                                                    @if(isset($requests)) @if($requests['type'] == 'User') selected @endif @endif>
                                                ผู้ใช้งาน
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-xs-6">
                                        <label for="office-id">สังกัดสถานพยาบาล</label>
                                        <select name="office_id" id="office-id" class="form-control select2">
                                            <option value="">โปรดเลือกสถานพยาบาล</option>
                                            @foreach($offices as $office)
                                                <option value="{{ $office->id }}"
                                                        @if(isset($requests)) @if($requests['office_id'] == $office->id) selected @endif @endif>{{ $office->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-sm-5">
                            <div class="form-group" style="padding-top: 25px;">
                                <div class="col-xs-12">
                                    <button class="btn btn-group-xs btn-primary" type="submit"><i
                                            class="fa fa-search-plus push-5-r"></i> ค้นหาผู้ใช้งาน
                                    </button>
                                    <a class="btn btn-group-xs btn-danger" href="{{ route('backend.users.index') }}"><i
                                            class="fa fa-refresh push-5-r"></i> ล้างการค้นหา</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="content content-boxed">
        <div class="block" id="loading">
            <div class="block-header">
                <ul class="block-options">
                    <li>
                        <a type="button" href="{{ route('backend.users.create') }}"><i class="fa fa-plus"></i>
                            เพิ่มข้อมูลผู้ใช้งาน</a>
                    </li>
                </ul>
                <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">จัดการผู้ใช้งาน</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped js-dataTable-full">
                        <thead>
                        <tr>
                            <th></th>
                            <th>รูปภาพ</th>
                            <th class="text-center" style="font-family: 'Sarabun', sans-serif">ชื่อ-นามสกุล</th>
                            <th class="text-center" style="font-family: 'Sarabun', sans-serif">สถานะ</th>
                            <th class="text-center" style="font-family: 'Sarabun', sans-serif">อีเมลผู้ใช้งาน</th>
                            <th class="text-center" style="font-family: 'Sarabun', sans-serif">สังกัดสถานพยาบาล</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!empty($users))
                            @foreach($users as $key => $user)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td class="text-center">
                                        <img class="img-avatar img-avatar48"
                                             src="{{ asset('assets/img/avatars/'.$user->profile) }}" alt="">
                                    </td>
                                    <td class="text-center">{{ $user->prefix->name }} {{ $user->name }} {{ $user->surname }}</td>
                                    <td class="text-center">
                                        @if($user->status == 'Active')
                                            <span class="label label-success">ใช้งาน</span>
                                        @else
                                            <span class="label label-danger">รออนุมัติ</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $user->email }}</td>
                                    <td class="text-center">
                                        {{ ($user->office->name) ?? '' }}
                                    </td>
                                    <td class="text-right">
                                        <button class="btn btn-default" type="button"
                                                onclick="details('{{ encrypt($user->id) }}');">
                                            <i class="fa fa-search push-5-r text-success"></i>รายละเอียด
                                        </button>
                                        @if($user->status == 'Active')
                                            <a class="btn btn-default" type="button"
                                               href="{{ route('backend.users.edit',encrypt($user->id)) }}">
                                                <i class="fa fa-pencil push-5-r text-warning"></i>แก้ไข
                                            </a>
                                            <button class="btn btn-default" type="button"
                                                    data-name="{{ $user->prefix->name }} {{ $user->name }} {{ $user->surname }}"
                                                    data-id="{{ encrypt($user->id) }}" onclick="confirmDelete(this);">
                                                <i class="fa fa-trash push-5-r text-danger"></i>ลบ
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="modal-details" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-popin">
                        <div class="modal-content">
                            <div class="block block-themed block-transparent remove-margin-b" id="load_class">
                                <div class="block-header bg-primary-dark">
                                    <ul class="block-options">
                                        <li>
                                            <button data-dismiss="modal" type="button"><i class="si si-close"></i>
                                            </button>
                                        </li>
                                    </ul>
                                    <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">
                                        รายละเอียดบัญชีผู้ใช้งาน</h3>
                                </div>
                                <div class="block-content">
                                    <div class="content content-boxed" id="details-show">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Modal -->
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/magnific-popup/magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/base_tables_datatables.js') }}"></script>
    <script type="text/javascript">
        function approve(d) {
            Swal.fire({
                title: 'ยันยันอนุมัติ?',
                text: "ต้องการอนุมัติ " + d.getAttribute("data-name") + "?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ดำเนินการต่อ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.value) {
                    const id = d.getAttribute("data-id");
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('backend.users.approved') }}',
                        data: {"_token": "{{ csrf_token() }}", id: id},
                        beforeSend: function () {
                            $('#load_class').addClass('block-opt-refresh');
                        },
                        success: function (res) {
                            $('#load_class').removeClass('block-opt-refresh');
                            Swal.fire({
                                type: 'success',
                                title: 'สำเร็จ',
                                text: 'อนุมัติเรียบร้อยแล้ว',
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
                                text: 'ไม่สามารถโหลดข้อมูลได้',
                                confirmButtonText: 'ตกลง',
                            });
                            $('#load_class').removeClass('block-opt-refresh');
                        }
                    });
                }
            })
        }

        function rejected(d) {
            Swal.fire({
                title: 'ยันยันไม่อนุมัติ?',
                text: "ต้องการไม่อนุมัติ " + d.getAttribute("data-name") + "?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ดำเนินการต่อ',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.value) {
                    const id = d.getAttribute("data-id");
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('backend.users.rejected') }}',
                        data: {"_token": "{{ csrf_token() }}", id: id},
                        beforeSend: function () {
                            $('#load_class').addClass('block-opt-refresh');
                        },
                        success: function (res) {
                            $('#load_class').removeClass('block-opt-refresh');
                            Swal.fire({
                                type: 'success',
                                title: 'สำเร็จ',
                                text: 'ไม่อนุมัติเรียบร้อยแล้ว',
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
                                text: 'ไม่สามารถโหลดข้อมูลได้',
                                confirmButtonText: 'ตกลง',
                            });
                            $('#load_class').removeClass('block-opt-refresh');
                        }
                    });
                }
            })
        }

        function details(id) {
            $.ajax({
                type: 'POST',
                url: '{{ route('backend.users.details') }}',
                data: {"_token": "{{ csrf_token() }}", id: id},
                beforeSend: function () {
                    $('#loading').addClass('block-opt-refresh');
                },
                success: function (res) {
                    $('#modal-details').modal('show');
                    $('#load_class').addClass('block-opt-refresh');
                    $('#details-show').html(res);
                    $('#loading').removeClass('block-opt-refresh');
                    $('#load_class').removeClass('block-opt-refresh');
                },
                error: function (e) {
                    console.log(e)
                    Swal.fire({
                        type: 'error',
                        title: 'เกิดข้อผิดพลาด',
                        text: 'ไม่สามารถโหลดข้อมูลได้',
                        confirmButtonText: 'ตกลง',
                    });
                    $('#loading').removeClass('block-opt-refresh');
                }
            });
        }

        function confirmDelete(d) {
            Swal.fire({
                title: 'ยืนยันการลบ?',
                text: "ต้องการลบ " + d.getAttribute("data-name") + "?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'ดำเนินการลบ',
                cancelButtonText: 'ยกเลิกการลบ'
            }).then((result) => {
                if (result.value) {
                    const id = d.getAttribute("data-id");
                    $.ajax({
                        type: "POST",
                        url: "{{ route('backend.users.destroy') }}",
                        data: {"_token": "{{ csrf_token() }}", id: id},
                        success: function (data) {
                            //console.log(data)
                            window.location.reload()
                        }, error: function (e) {
                            console.log(e)
                            Swal.fire({
                                type: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: 'ไม่สามารถดำเนินการลบได้',
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
    App.initHelpers('magnific-popup');
@endsection
