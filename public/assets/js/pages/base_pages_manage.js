/*
 *  Document   : base_pages_register.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Register Page
 */

var BasePageManage = function() {
    // Init Register Form Validation, for more examples you can check out https://github.com/jzaefferer/jquery-validation
    var initValidationRegister = function(){
        jQuery('.js-validation-manage').validate({
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
                'mega-name': {
                    required: true
                },
                'mega-phone' : {
                    required: true,
                    digits: true
                },
                'mega-address' : {
                    required: true
                },
                'mega-province' : {
                    required: true
                },
                'mega-country' : {
                    required: true
                },
                'mega-district' : {
                    required: true
                },
                'mega-code' : {
                    required: true,
                    digits: true
                }
            },
            messages: {
                'mega-name': 'กรุณาระบุชื่อสถานพยาบาล',
                'mega-phone': {
                    required: 'กรุณาระบุเบอร์โทรติดต่อ',
                    digits: 'เฉพาะตัวเองเท่านั้น'
                },
                'mega-address': 'กรุณาระบุที่อยู่',
                'mega-province': 'กรุณาเลือกจังหวัด',
                'mega-code': {
                    required: 'กรุณาระบุรหัสไปรษณีย์',
                    digits: 'เฉพาะตัวเองเท่านั้น'
                },
                'mega-district': 'กรุณาเลือกตำบล/แขวง',
                'mega-country': 'กรุณาเลือกอำเภอ/เขต',
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
jQuery(function(){ BasePageManage.init(); });
