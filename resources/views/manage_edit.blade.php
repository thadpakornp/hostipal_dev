@extends('layouts.backend')

@section('content')
    <div class="content content-boxed">
        <div class="block block-rounded">
            <div class="block-header">
                <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">แก้ไขข้อมูลสถานพยาบาล</h3>
            </div>
            <div class="block-content">
                <form class="js-validation-manage form-horizontal push-10-t push-10" action="{{ route('backend.manage.stored') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ encrypt($office->id) }}">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="mega-name">ชื่อสถานพยาบาล</label>
                                    <input class="form-control" type="text" id="mega-name" name="name" value="{{ $office->name }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="mega-phone">เบอร์โทรติดต่อ</label>
                                    <input class="form-control" type="text" id="mega-phone" name="phone" value="{{ $office->phone }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="mega-address">ที่ตั้งสถานพยาบาล</label>
                                    <input class="form-control" type="text" id="mega-address" name="address" value="{{ $office->address }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="mega-province">จังหวัด</label>
                                    <select class="form-control select2" name="province" id="mega-province" onchange="getAddress(this.name, 'country', 'a')" required>
                                        <option value="">โปรดเลือดจังหวัด</option>
                                        @foreach($provinces as $province)
                                            <option value="{{ $province->PROVINCE_ID }},{{ $province->PROVINCE_NAME }}" @if($office->province == $province->PROVINCE_NAME) selected @endif>{{ $province->PROVINCE_NAME }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <label for="mega-country">อำเภอ/เขต</label>
                                    <select class="form-control" name="country" id="mega-country" onchange="getAddress(this.name, 'district', 't')" required>
                                        @foreach($country as $countrys)
                                            <option value="{{ $countrys->AMPHUR_ID }},{{ $countrys->AMPHUR_NAME }}" @if($office->country == $countrys->AMPHUR_NAME) selected @endif>{{ $countrys->AMPHUR_NAME }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-xs-6">
                                    <label for="mega-district">ตำบล/แขวง</label>
                                    <select class="form-control" name="district" id="mega-district" onchange="getAddress(this.name, 'code', 'z')" required>
                                        @foreach($district as $districts)
                                            <option value="{{ $districts->DISTRICT_ID }},{{ $districts->DISTRICT_NAME }}" @if($office->district == $districts->DISTRICT_NAME) selected @endif>{{ $districts->DISTRICT_NAME }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="mega-code">รหัสไปรษณีย์</label>
                                    <input class="form-control" type="text" id="mega-code" name="code" value="{{ $office->code }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <label for="mega-firstname">แผนที่ตั้งสถานพยาบาล</label>
                                    <div class="col-md-12">
                                        <input type="text" id="address-input" name="address_address"
                                               class="form-control map-input">
                                        <input type="hidden" name="g_location_lat" id="address-latitude" value="0"/>
                                        <input type="hidden" name="g_location_long" id="address-longitude"
                                               value="0"/>
                                        <div id="address-map-container" style="width:100%;height:400px;">
                                            <div style="width: 100%; height: 100%" id="address-map"></div>
                                        </div>
                                        <input id="latformHRML" value="{{ $office->g_location_lat }}" type="hidden">
                                        <input id="longformHRML" value="{{ $office->g_location_long }}" type="hidden">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <button class="btn btn-warning" type="submit"><i class="fa fa-check push-5-r"></i> แก้ไขข้อมูลสถานพยาบาล</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script_ready')
    $('#mega-province').select2({
        width: 'resolve'
    });
@endsection
@section('script')
    <script src="{{ asset('assets/js/pages/base_pages_manage.js') }}"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ config('app.GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initialize"
        async defer></script>
    <script src="{{ asset('assets/js/mapInput.js') }}"></script>
    <script type="text/javascript">
        function getAddress(iSelect, toSelect, iMode){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax(
                {type : "POST",
                url :"{{ route('backend.manage.getAddress') }}",
                data : { find: iMode, fvalue:$('select[name='+ iSelect+']').val()  },
                success : function(data) {
                    console.log(data)
                    if (iMode == "z") {
                        $('input[name=' + toSelect + ']').val(data);
                    } else {
                        $('select[name=' + toSelect + ']').empty().append(data);
                        $('input[name=code]').val('');
                    }

                    if (iMode == "a") {
                        var sname = "select[name=SubDistrict]";
                        $(sname).empty().append("<option value=\"\" selected=\"selected\">:::::&nbsp;เลือก&nbsp;:::::</option>");
                    }
                }, error : function (e) {
                    console.log(e)
                }
            });
        }
    </script>
@endsection
