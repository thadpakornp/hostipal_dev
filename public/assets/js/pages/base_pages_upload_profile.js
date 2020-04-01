/*
 *  Document   : base_pages_register.js
 *  Author     : pixelcave
 *  Description: Custom JS code used in Register Page
 */

var BasePageUpdate = function() {
    // Init Register Form Validation, for more examples you can check out https://github.com/jzaefferer/jquery-validation
    var initValidationRegister = function(){
        jQuery('.js-validation-update').validate({
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
                'bd-qsettings-name': {
                    required: true
                },
                'prefix' : {
                    required: true
                },
                'bd-qsettings-surname' : {
                    required: true
                },
                'bd-qsettings-phone' : {
                    required: true,
                    digits: true
                },
                'bd-qsettings-email' : {
                    email: true
                }
            },
            messages: {
                'bd-qsettings-name': 'กรุณาระบุชื่อ',
                'prefix': 'กรุณาระบุคำนำหน้า',
                'bd-qsettings-surname': 'กรุณาระบุนามสกุล',
                'bd-qsettings-phone': {
                    required: 'กรุณาระบุเบอร์โทรติดต่อ',
                    digits: 'เฉพาะตัวเองเท่านั้น'
                }
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
jQuery(function(){ BasePageUpdate.init(); });
