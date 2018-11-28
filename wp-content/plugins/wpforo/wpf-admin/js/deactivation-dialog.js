jQuery(document).ready(function ($) {
    var dialog = $("#wpf_deactivation_dialog_wrap");

    var deactivateUrl = '';

    $(document).delegate('#the-list tr[data-plugin="wpforo/wpforo.php"] .deactivate a, #the-list tr[data-plugin="wpforo/wpforo.php"] a.wpforo-uninstall', 'click', function (e) {
        e.preventDefault();
        dialog.fadeIn( 400, "linear" );
        $('body').addClass('wpf-no-scroll');
        deactivateUrl = $(this).attr('href');
        return false;
    });

    $(document).on("click", "#wpf_deactivation_dialog_wrap #wpf_deactivation_dialog_close", function () {
        if( dialog.is(':visible') ){
            $('body').removeClass('wpf-no-scroll');
            dialog.fadeOut( 50, "linear" );
        }
    });

    $(document).on("keydown", dialog, function (e) {
        if( dialog.is(':visible') ) {
            var keycode = e.which;
            if (keycode === 27){
                $('body').removeClass('wpf-no-scroll');
                dialog.fadeOut(50, "linear");
            }
        }
    });

    var parentItem = $('.wpf-deactivation-reason:checked').parents('.wpf-deactivation-reason-item');
    $('.wpf-deactivation-reason-more-info').slideUp(500);
    $('.wpf-deactivation-reason-more-info', parentItem).slideDown(500);

    $(document).delegate('.wpf-deactivation-reason', 'change', function (e) {
        $('.wpf-deactivation-reason-more-info').slideUp(500);
        var parentItem = $(this).parents('.wpf-deactivation-reason-item');
        $('.wpf-deactivation-reason-more-info', parentItem).slideDown(500);
    });

    $(document).delegate('.wpf-deactivate', 'click', function (e) {
        if (isChecked($(this))) {
            var formData = '';
            if ($(this).hasClass('wpf-submit')) {
                var checkedItem = $('.wpf-deactivation-reason:checked');
                var parentItem = checkedItem.parents('.wpf-deactivation-reason-item');
                var reasonDesc = $('textarea[name="wpforo_deactivation_reason_desc"]', parentItem);
                var reasonFeedback = $('input[name="wpforo_deactivation_feedback"]', parentItem);
                var reasonFeedbackEmail = $('input[name="wpforo_deactivation_feedback_email"]', parentItem);
                var reasonFeedbackEmailVal = $.trim( reasonFeedbackEmail.val() );
                var isValid = true;

                if (reasonDesc.length && reasonDesc.is(':visible')) {
                    var attr = reasonDesc.attr('required');
                    if (typeof attr !== typeof undefined && attr !== false) {
                        if (reasonDesc.val().length === 0) {
                            isValid = false;
                        }
                    }
                }

                if (isValid) {
                    formData = 'deactivation_reason=' + checkedItem.val();
                    if (reasonDesc.length && reasonDesc.val().length > 0) {
                        formData += '&deactivation_reason_desc=' + reasonDesc.val();
                    }
                    $('.wpf-loading', this).toggleClass('wpforo-hidden');
                } else {
                    alert(wpforo_deactivation_obj.msgReasonDescRequired);
                    return false;
                }

                if( reasonFeedbackEmailVal.length !== 0 && !reasonFeedback.is(':checked') ){
                    alert( wpforo_deactivation_obj.msgFeedbackHasEmailNoCheckbox );
                    return false;
                }
                if( reasonFeedback.is(':checked') ){
                    if( reasonFeedbackEmailVal.length === 0 ){
                        alert( wpforo_deactivation_obj.msgFeedbackHasCheckboxNoEmail );
                        return false;
                    }else{
                        if( isValidEmail( reasonFeedbackEmailVal ) ){
                            formData += '&deactivation_feedback_email=' + reasonFeedbackEmailVal;
                        }else{
                            alert( wpforo_deactivation_obj.msgFeedbackNotValidEmail );
                            return false;
                        }
                    }
                }

            } else {
                formData = 'never_show=1';
            }

            if (formData) {
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: 'wpforo_deactivate',
                        deactivateData: formData
                    }
                }).done(function (response) {
                    try {
                        var r = $.parseJSON(response);
                        var patt = new RegExp("https?\:\/\/");
                        var locHref = deactivateUrl ? ( patt.test(deactivateUrl) ? deactivateUrl : wpforo_deactivation_obj.adminUrl + deactivateUrl ) : location.href;
                        if (r.code === 'dismiss_and_deactivate') {
                            setTimeout(function () {
                                location.href = locHref;
                            }, 100);
                        } else if (r.code === 'send_and_deactivate') {
                            $('.wpf-deactivation-reason-form, .wpforo-thankyou').toggleClass('wpforo-hidden');
                            setTimeout(function () {
                                location.href = locHref;
                            }, 1000);
                        }
                    } catch (e) {
                        console.log(e);
                    }
                });
            }
        } else {
            alert(wpforo_deactivation_obj.msgReasonRequired);
        }
    });

    function isChecked(btn) {
        if (btn.hasClass('wpf-submit')) {
            var elem = $('.wpf-deactivation-reason-form input[name="wpforo_deactivation_reason"]');
            for (var i = 0; i < elem.length; i++) {
                if (elem[i].type === 'radio' && elem[i].checked) {
                    return true;
                }
            }
            return false;
        }
        return true;
    }

    function isValid() {
        if ($('.wpf_dr_more_info').is(':visible')) {
            return $('.wpf_dr_more_info:visible').length;
        } else {
            return true;
        }
    }

    function isValidEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

});