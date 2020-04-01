/*
 *  Document   : base_pages_register.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Register Page
 */

var BasePagesRegister = function() {
    // Init Register Form Validation, for more examples you can check out https://github.com/jzaefferer/jquery-validation
    var initValidationRegister = function(){
        jQuery('.js-validation-register').validate({
            errorClass: 'help-block text-right animated fadeInDown',
            errorElement: 'div',
            errorPlacement: function(error, e) {
                jQuery(e).parents('.form-group > div').append(error);
            },
            highlight: function(e) {
                jQuery(e).closest('.form-group').removeClass('has-error').addClass('has-error');
                jQuery(e).closest('.help-block').remove();
            },
            success: function(e) {
                jQuery(e).closest('.form-group').removeClass('has-error');
                jQuery(e).closest('.help-block').remove();
            },
            rules: {
                'register-email': {
                    required: true,
                    email: true
                },
                'register-password': {
                    required: true,
                    minlength: 8
                },
                'register-password2': {
                    required: true,
                    equalTo: '#register-password'
                },
                'register-name' :{
                    required: true
                },
                'register-surname' :{
                    required: true
                },
                'register-phone' :{
                    required: true
                },
                'register-terms': {
                    required: true
                },
                'example-file-input': {
                    required: true
                }
            },
            messages: {
                'register-email': 'กรุณาระบุอีเมลผู้ใช้งาน',
                'register-password': {
                    required: 'กรุณาระบุรหัสผ่าน',
                    minlength: 'รหัสผ่านสั้นเกินไป ขั้นต่ำ 8 ตัวอักษร'
                },
                'register-password2': {
                    required: 'กรุณาระบุรหัสผ่านอีกครั้ง',
                    minlength: 'รหัสผ่านสั้นเกินไป ขั้นต่ำ 8 ตัวอักษร',
                    equalTo: 'รหัสผ่านไม่ตรงกัน'
                },
                'register-name' : 'กรุณาระบุชื่อ',
                'register-surname' : 'กรุณาระบุนามสกุล',
                'register-phone' : 'กรุณาระบุเบอร์ติดต่อ',
                'register-terms': 'กรุณายอมรับเงื่อนไขและข้อตกลงการใช้งานบริการ',
                'example-file-input': 'กรุณาเลือกภาพโปรไฟล์'
            }
        });
    };

    return {
        init: function () {
            // Init Register Form Validation
            initValidationRegister();
        }
    };
}();

// Initialize when page loads
jQuery(function(){ BasePagesRegister.init(); });
