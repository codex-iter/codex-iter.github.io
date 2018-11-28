jQuery(document).ready(function($) {
	
	//Tooltips
	jQuery('#cff-admin .cff-tooltip-link').click(function(){
		jQuery(this).closest('tr, h3, div').find('.cff-tooltip').slideToggle();
	});

	//Toggle Access Token field
	if( jQuery('#cff_show_access_token').is(':checked') ) jQuery('.cff-access-token-hidden').show();
	jQuery('#cff_show_access_token').change(function(){
		jQuery('.cff-access-token-hidden').fadeToggle();
	});


	//Is this a page, group or profile?
	var cff_page_type = jQuery('.cff-page-type select').val(),
		$cff_page_type_options = jQuery('.cff-page-options'),
		$cff_profile_error = jQuery('.cff-profile-error.cff-page-type'),
		$cff_group_error = jQuery('.cff-group-error.cff-page-type');

	//Should we show anything initially?
	if(cff_page_type !== 'page') $cff_page_type_options.hide();
	if(cff_page_type == 'profile') $cff_profile_error.show();
	if(cff_page_type == 'group') $cff_group_error.show();

	//When page type is changed show the relevant item
	jQuery('.cff-page-type').change(function(){
		cff_page_type = jQuery('.cff-page-type select').val();

		if( cff_page_type !== 'page' ) {
			$cff_page_type_options.hide();
			if( cff_page_type == 'profile' ) {
					$cff_profile_error.show();
					$cff_group_error.hide();
				} else if( cff_page_type == 'group' ) {
					$cff_group_error.show();
					$cff_profile_error.hide();
				} else {
					$cff_group_error.hide();
					$cff_profile_error.hide();
				}
			
		} else {
			$cff_page_type_options.show();
			$cff_profile_error.hide();
			$cff_group_error.hide();
		}
	});


	//Post limit manual setting
	var cff_limit_setting = jQuery('#cff_limit_setting').val(),
			cff_post_limit = jQuery('#cff_post_limit').val(),
			$cff_limit_manual_settings = jQuery('#cff_limit_manual_settings');
	if( typeof cff_post_limit === 'undefined' ) cff_post_limit = '';

	//Should we show anything initially?
	if(cff_limit_setting == 'auto') $cff_limit_manual_settings.hide();
	if(cff_post_limit.length > 0){
		$cff_limit_manual_settings.show();
		jQuery('#cff_limit_setting').val('manual');
	}

	jQuery('#cff_limit_setting').change(function(){
		cff_limit_setting = jQuery('#cff_limit_setting').val();

		if(cff_limit_setting == 'auto'){
			$cff_limit_manual_settings.hide();
			jQuery('#cff_post_limit').val('');
		} else {
			$cff_limit_manual_settings.show();
		}
	});


	//Header icon
	//Icon type
	//Check the saved icon type on page load and display it
	jQuery('#cff-header-icon-example').removeClass().addClass('fa fa-' + jQuery('#cff-header-icon').val() );
	//Change the header icon when selected from the list
	jQuery('#cff-header-icon').change(function() {
	    var $self = jQuery(this);

	    jQuery('#cff-header-icon-example').removeClass().addClass('fa fa-' + $self.val() );
	});


	//Test Facebook API connection button
	jQuery('#cff-api-test').click(function(e){
		e.preventDefault();
		//Show the JSON
		jQuery('#cff-api-test-result textarea').css('display', 'block');
	});


	//If 'Others only' is selected then show a note
	var $cffOthersOnly = jQuery('#cff-others-only');

	if ( jQuery("#cff_show_others option:selected").val() == 'onlyothers' ) $cffOthersOnly.show();
	
	jQuery("#cff_show_others").change(function() {
		if ( jQuery("#cff_show_others option:selected").val() == 'onlyothers' ) {
			$cffOthersOnly.show();
		} else {
			$cffOthersOnly.hide();
		}
	});


	//If '__ days ago' date is selected then show 'Translate this'
	var $cffTranslateDate = jQuery('#cff-translate-date');

	if ( jQuery("#cff-date-formatting option:selected").val() == '1' ) $cffTranslateDate.show();
	
	jQuery("#cff-date-formatting").change(function() {
		if ( jQuery("#cff-date-formatting option:selected").val() == '1' ) {
			$cffTranslateDate.fadeIn();
		} else {
			$cffTranslateDate.fadeOut();
		}
	});

  //Add the color picker
	if( jQuery('.cff-colorpicker').length > 0 ) jQuery('.cff-colorpicker').wpColorPicker();


	//Mobile width
	var cff_feed_width = jQuery('#cff-admin #cff_feed_width').val(),
			$cff_width_options = jQuery('#cff-admin #cff_width_options');

	if (typeof cff_feed_width !== 'undefined') {
		//Show initially if a width is set
		if(cff_feed_width.length > 1 && cff_feed_width !== '100%') $cff_width_options.show();

		jQuery('#cff_feed_width').change(function(){
			cff_feed_width = jQuery(this).val();

			if( cff_feed_width.length < 2 || cff_feed_width == '100%' ) {
				$cff_width_options.slideUp();			
			} else {
				$cff_width_options.slideDown();
			}
		});
	}

	//Scroll to hash for quick links
	jQuery('#cff-admin a').click(function() {
	if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
	  var target = jQuery(this.hash);
	  target = target.length ? target : this.hash.slice(1);
	  if (target.length) {
	    jQuery('html,body').animate({
	      scrollTop: target.offset().top
	    }, 500);
	    return false;
	  }
	}
	});

	//Shortcode tooltips
	jQuery('#cff-admin label').click(function(){
	  	var $el = jQuery(this);
	    var $cff_shortcode = $el.siblings('.cff_shortcode');
	    if($cff_shortcode.is(':visible')){
	      $el.siblings('.cff_shortcode').css('display','none');
	    } else {
	      $el.siblings('.cff_shortcode').css('display','block');
	    }  
	});
	jQuery('#cff-admin th').hover(function(){
		if( jQuery(this).find('.cff_shortcode').length > 0 ){
		  jQuery(this).find('label').append('<code class="cff_shortcode_symbol">[]</code>');
		}
	}, function(){
		jQuery(this).find('.cff_shortcode_symbol').remove();
	});
	jQuery('#cff-admin label').hover(function(){
		if( jQuery(this).siblings('.cff_shortcode').length > 0 ){
		  jQuery(this).attr('title', 'Click for shortcode option');
		}
	}, function(){});

	//Open/close the expandable option sections
	jQuery('.cff-expandable-options').hide();
	jQuery('.cff-expand-button a').on('click', function(e){
		e.preventDefault();
		var $self = jQuery(this);
		$self.parent().next('.cff-expandable-options').toggle();
		if( $self.text().indexOf('Show') !== -1 ){
			$self.text( $self.text().replace('Show', 'Hide') );
		} else {
			$self.text( $self.text().replace('Hide', 'Show') );
		}
	});

	//Facebook login
	$('#cff_fb_login').on('click', function(){
		$('#cff_fb_login_modal').show();
	});
	$('#cff_admin_cancel_btn').on('click', function(){
		$('#cff_fb_login_modal').hide();
	});

	//Select a page for token
	$('.cff-managed-page').on('click', function(){
		$('#cff_access_token, #cff_page_access_token').val( $(this).attr('data-token') ).addClass('cff-success');
		if( $('#cff_page_id').val().trim() == '' ) $('#cff_page_id').val( $(this).attr('data-page-id') );
		$(this).siblings().removeClass('cff-page-selected');
		$(this).addClass('cff-page-selected');
		// $('.cff-save-page-token').show();
		//Check the own access token setting so it reveals token field
		if( $('#cff_show_access_token:checked').length < 1 ){
			$("#cff_show_access_token").trigger("change").prop( "checked", true );
		}
	});

	// Clear avatar cache
    var $cffClearAvatarsBtn = $('#cff-admin #cff_clear_avatars');
    $cffClearAvatarsBtn.click(function(event) {
        event.preventDefault();
        $('#cff-clear-avatars-success').remove();
        $(this).prop("disabled",true);
        $.ajax({
            url : ajaxurl,
            type : 'post',
            data : {
                action : 'cff_clear_avatar_cache'
            },
            success : function(data) {
                $cffClearAvatarsBtn.prop('disabled',false);
                if(!data===false) {
                    $cffClearAvatarsBtn.after('<i id="cff-clear-avatars-success" class="fa fa-check-circle cff-success-check"></i>');
                } else {
                    $cffClearAvatarsBtn.after('<span>error</span>');
                }
            }
        });
    });

});