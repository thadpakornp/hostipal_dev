/*
 *  Document   : base_pages_register.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Register Page
 */

var BasePageUsers = function() {
    // Init Register Form Validation, for more examples you can check out https://github.com/jzaefferer/jquery-validation
    var initValidationRegister = function(){
        jQuery('.js-validation-users').validate({
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
                'phone' : {
                    required: true,
                    digits: true
                },
                'email' : {
                    required: true,
                    email: true
                },
                'name' : {
                    required: true
                },
                'surname' : {
                    required: true
                },
                'office-id' : {
                    required: true
                },
                'type' : {
                    required: true
                },
                'file-input' : {
                    required: true
                },
                'prefix-id' : {
                    required: true
                }
            },
            messages: {
                'name': 'กรุณาระบุชื่อ',
                'surname': 'กรุณาระบุนามสกุล',
                'email': 'กรุณาระบุอีเมล',
                'phone': {
                    required: 'กรุณาระบุเบอร์โทรติดต่อ',
                    digits: 'เฉพาะตัวเองเท่านั้น'
                },
                'office-id': 'กรุณาเลือกสังกัดสถานพยาบาล',
                'type': 'กรุณาเลือกประเภทการใช้งาน',
                'file-input': 'กรุณาเลือกรูปโปรไฟล์',
                'prefix-id' : 'กรุณาเลือกคำนำหน้าชื่อ'

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
jQuery(function(){ BasePageUsers.init(); });
