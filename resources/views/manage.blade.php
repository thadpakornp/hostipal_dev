@extends('layouts.backend')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.css') }}">
@endsection

@section('content')
    <div class="content content-boxed">
        <div class="block block-rounded">
            <div class="block-header">
                <ul class="block-options">
                    <li>
                        <a class="button" href="{{ route('backend.manage.create') }}"><i class="fa fa-plus"></i>
                            เพิ่มข้อมูลสถานพยาบาล</a>
                    </li>
                </ul>
                <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">จัดการสถานพยาบาล</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped js-dataTable-full">
                        <thead>
                        <tr>
                            <th></th>
                            <th style="font-family: 'Sarabun', sans-serif" width="30%">ชื่อสถานพยาบาล</th>
                            <th style="font-family: 'Sarabun', sans-serif" width="40%">ที่อยู่สถานพยาบาล</th>
                            <th style="font-family: 'Sarabun', sans-serif" width="15%">เบอร์ติดต่อ</th>
                            <th width="15%" class="text-center"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($office) > 0)
                            @foreach($office as $key => $off)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $off->name }}</td>
                                    <td>{{ $off->address }} {{ $off->district }} {{ $off->country }} {{ $off->province }} {{ $off->code }}</td>
                                    <td>{{ $off->phone }}</td>
                                    <td>
                                        <a class="btn btn-default" type="button"
                                           href="{{ route('backend.manage.edit',encrypt($off->id)) }}">
                                            <i class="fa fa-pencil push-5-r text-success"></i>แก้ไข
                                        </a>
                                        <button class="btn btn-default" type="button" data-name="{{ $off->name }}"
                                                data-id="{{ encrypt($off->id) }}" onclick="confirmDelete(this);">
                                            <i class="fa fa-trash push-5-r text-primary"></i>ลบ
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/base_tables_datatables.js') }}"></script>
    <script type="text/javascript">
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
                        url: "{{ route('backend.manage.destroy') }}",
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
