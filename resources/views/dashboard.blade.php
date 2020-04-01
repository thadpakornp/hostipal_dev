<div class="block">
    <div class="block-header">
        <h3 class="block-title" style="font-family: 'Sarabun', sans-serif;">แบบฟอร์มบันทึกประวัติคนไข้</h3>
    </div>
    <div class="block-content">
        <div class="js-wizard-validation block">
            <ul class="nav nav-tabs nav-tabs-alt nav-justified">
                <li class="active">
                    <a class="inactive" href="#validation-step1" data-toggle="tab">ข้อมูลส่วนบุคคล</a>
                </li>
                <li>
                    <a class="inactive" href="#validation-step2" data-toggle="tab">ข้อมูลทางการแพทย์</a>
                </li>
            </ul>
            <form class="js-form2 form-horizontal" action="{{ route('backend.charts.stored') }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="block-content tab-content">
                    <div class="tab-pane fade fade-right in push-30-t push-50 active" id="validation-step1">
                        <div class="form-group">
                            <div class="col-sm-4">
                                <div class="form-material">
                                    <select class="form-control" name="prefix_id" id="validation-prefix"
                                            required>
                                        <option value="">โปรดเลือกคำนำหน้า</option>
                                        @foreach($prefixs as $prefix)
                                            <option value="{{ $prefix->code }}">{{ $prefix->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="validation-prefix">คำนำหน้า</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-material">
                                    <input class="form-control" type="text" id="validation-name"
                                           name="name" required value="{{ old('name') }}">
                                    <label for="validation-name">ชื่อ</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-material">
                                    <input class="form-control" type="text"
                                           name="surname" id="validation-surname" required
                                           value="{{ old('surname') }}">
                                    <label for="validation-surname">นามสกุล</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4">
                                <div class="form-material">
                                    <input type="radio" name="sex" value="M"
                                           style="margin-top: 15px;margin-right: 10px;"> ผู้ชาย
                                    <input type="radio" name="sex" value="F"
                                           style="margin-top: 15px;margin-right: 10px;margin-left: 25px">
                                    ผู้หญิง
                                    <label for="validation-sex">เพศ</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-material">
                                    <input class="form-control" type="number" id="validation-id_card"
                                           name="id_card" minlength="13" maxlength="13" required value="{{ old('id_card') }}">
                                    <label for="validation-id_card">หมายเลขบัตรประชาชน</label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-material">
                                    <input class="form-control" type="text" id="validation-hbd"
                                           name="hbd" required value="{{ old('bhd') }}">
                                    <label for="validation-hbd">วัน/เดือน/ปี เกิด</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-4">
                                <div class="form-material">
                                    <input class="form-control" type="number" id="validation-phone"
                                           name="phone" maxlength="10" value="{{ old('phone') }}">
                                    <label for="validation-phone">เบอร์โทรติดต่อ</label>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-material">
                                    <input class="form-control" type="text" id="validation-address"
                                           name="address" required value="{{ old('address') }}">
                                    <label for="validation-address">ที่อยู่</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material">
                                    <input type="file" name="profile" class="form-control">
                                    <label>รูปภาพประจำตัว</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade fade-right push-30-t push-50" id="validation-step2">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material">
                                    <input type="text" name="hn" class="form-control" required
                                           value="{{ old('hn') }}">
                                    <label for="validation-details">H/N</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material">
                                            <textarea class="form-control" id="validation-details"
                                                      name="description" rows="3">{{ old('description') }}</textarea>
                                    <label for="validation-details">รายละเอียดเพิ่มเติม</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="form-material">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-content block-content-mini block-content-full border-t">
                    <div class="row">
                        <div class="col-xs-6">
                            <button class="wizard-prev btn btn-warning" type="button"><i
                                    class="fa fa-arrow-circle-o-left"></i> ย้อนกลับ
                            </button>
                        </div>
                        <div class="col-xs-6 text-right">
                            <button class="wizard-next btn btn-success" type="button">ถัดไป <i
                                    class="fa fa-arrow-circle-o-right"></i></button>
                            <button class="wizard-finish btn btn-primary" type="submit"><i
                                    class="fa fa-check-circle-o"></i> บันทึก
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
