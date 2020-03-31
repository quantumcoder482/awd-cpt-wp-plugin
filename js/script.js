(function ($) {
    $(document).ready(function() {
        /** Global Variable */
        const ajax_url = settings.ajaxurl;
        const current_page_url = window.location.href;


        /**
         * Resource Archive Page Actions
         */

        var $search_string = $('#search_string');
        var $resource_category = $('#resource_category');
        var $trimester = $('#trimester');

        $search_string.keypress( function(event){
            var keycode = (event.keyCode ? event.keyCode : event.which);
            if (keycode == '13') {
                if( $(this).val() != ''){
                    reset_resource_category();
                    reset_trimester();
                    get_post_data();
                }
            }
        });

        $('.search-button').on('click', function(event){
            event.preventDefault();
            reset_resource_category();
            reset_trimester();
            get_post_data();
        })

        $resource_category.on('change', function(event){
            reset_search_string();
            reset_trimester();
            get_post_data();
        });

        $('.select-down').on('click', function(event){
            event.preventDefault();
        });

        $('.post-card').on('click', function(event){
            event.preventDefault();

            $('.post-card').removeClass('active');
            $(this).addClass('active');

            var post_id = $(this).attr('id');
            
            var post_data = {
                'action': 'get_custom_post_link',
                'post_id': post_id
            }

            $.post(ajax_url, post_data, function(response) {
                var response_data = JSON.parse(response);
                if( response_data.url !== ''){
                    window.open(response_data.url, response_data.target);
                }
            })

        });

        function reset_search_string(){
            $search_string.val('');
        }

        function reset_resource_category(){
            $resource_category.val('');
        }

        function reset_trimester(){
            $trimester.val('');
            $('.tabs-menuitem .tab-menu').removeClass('active-tab');
            $(".tabs-container .tabs-content").each(function () {
                $(this).hide();
                if ($(this).attr('id') == 'tab-0') {
                    $(this).fadeIn('slow');
                }
            });
        }

        function get_post_data() {

            var post_data = {
                'action': 'get_resource_posts',
                'form_data': $('#search_form').serialize()
            }

            $.post(ajax_url, post_data, function (response) {
                var response_data = JSON.parse(response);
                
                if (!response_data.success) {
                    $('#section_posts').html(response_data.msg);
                } else {
                    $('#section_posts').html(response_data.result);
                }

            })

        }

        /** End Archive Page Actions */


        /**
         *  Custom Style fields Action
         */
        
         $(".accordion").on("click", ".accordion-title", function() {
            if( $(this).hasClass('active')) {
                $(".accordion-title").removeClass("active");
                $(".accordion-answer").slideUp();
            } else {
                $(".accordion-title").removeClass("active");
                $(".accordion-answer").slideUp();
                $(this).toggleClass("active").next().slideToggle();
            }
        });

        $(".tabs-container .tabs-content").each(function(){
            $(this).hide();
            if($(this).attr('id') == 'tab-0') {
                $(this).fadeIn('slow');
            }
        });

        $('.tabs-menuitem .tab-menu').on( "click", function(e) {
            e.preventDefault();
            var tab_attr = $(this).attr('tab-attr');

            reset_resource_category();
            reset_search_string();

            if ($(this).hasClass('active-tab')) {
                reset_trimester();
                get_post_data()
            } else {
                $('.tabs-menuitem .tab-menu').removeClass('active-tab');
                $(this).addClass('active-tab');
                $(".tabs-container .tabs-content").each(function(){
                    $(this).hide();
                    if($(this).attr('id') == tab_attr ) {
                        $(this).fadeIn('slow');
                    }
                });
                
                switch (tab_attr) {
                    case 'tab-1':
                        $trimester.val('First Trimester');
                        get_post_data();
                        break;
                    case 'tab-2':
                        $trimester.val('Second Trimester');
                        get_post_data();
                        break;
                    case 'tab-3':
                        $trimester.val('Third Trimester');
                        get_post_data();
                        break;
                    case 'tab-4':
                        $trimester.val('After Baby');
                        get_post_data();
                        break;
                    default:
                        break;
                }
            }
        });
        
        /**
         *  Email Send Modal 
         */

        var $email_modal = $('#mail_send_modal');
        var $confirm_modal_1 = $('#email_sent_confirm');
        var $confirm_modal_2 = $('#page_link_copy');

        var $mail_send_btn = $('#mail_send_modal #mail_send_btn');
        var $copy_path_btn = $('#mail_send_modal #copy_path_btn');
        
        $mail_send_btn.on( 'click', function(e) {
            e.preventDefault();

            $('#awd_current_url').val(current_page_url);

            var post_data = {
                'action': 'awd_send_email',
                'form_data': $('#awd_email_send_form').serialize()
            }
            
            const form_validate = validate_email_form();
                        
            if( form_validate.validate_result != true ){

                $('.frm_error').html( settings.translations.please_input_name_email );

            } else {

                $('.frm_error').html('');

                $.post(ajax_url, post_data, function(response) {

                    var response_data = JSON.parse(response);

                    if ( !response_data.success ) {
                        $('#email_sent_confirm .modal-body p').html( settings.translations.cant_send_email );
                        $email_modal.modal('hide');
                        $confirm_modal_1.modal('show');
                    } else {
                        $('#awd_email_sent').html(response_data.result.email);
                        
                        $email_modal.modal('hide');
                        $confirm_modal_1.modal('show');
                    }
                })
            }
        })

        $copy_path_btn.on('click', function(e) {
            e.preventDefault();
            
            // Copy Url to clipboard
            var dummy = document.createElement('input');
            document.body.appendChild(dummy);
            dummy.value = window.location.href;
            dummy.select();
            document.execCommand('copy');
            document.body.removeChild(dummy);

            $email_modal.modal('hide');
            $confirm_modal_2.modal('show');
        })

        function validate_email_form(){
            
            var email = $('#awd_modal_to_email').val();
            var name = $('#awd_modal_name').val();

            var msg = {};

            if( email == '' ) {
                msg.blank_email = settings.translations.email_blank;
            }

            if( name == '' ) {
                msg.blank_name = settings.translations.name_blank;
            }

            if( ! IsEmail(email) ){
                msg.invalid_email = settings.translations.email_invalid_format;
            }

            if( Object.keys(msg).length != 0 ){
                return {
                    'validate_result' : false,
                    'msg'             : msg
                };
            } else {
                return {
                    'validate_result' : true,
                    'msg'             : msg
                }
            }

        }

        function IsEmail(email) {
            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if( !regex.test(email) ) {
                return false;
            } else {
                return true;
            }
        }
    });
})(jQuery);