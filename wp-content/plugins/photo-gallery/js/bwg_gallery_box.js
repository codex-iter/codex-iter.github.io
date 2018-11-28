var isPopUpOpened = false;
var bwg_overflow_initial_value = false;
var bwg_overflow_x_initial_value = false;
var bwg_overflow_y_initial_value = false;

function spider_createpopup(url, current_view, width, height, duration, description, lifetime, lightbox_ctrl_btn_pos) {
  url = url.replace(/&#038;/g, '&');
  if (isPopUpOpened) { return };
  isPopUpOpened = true;
  if (spider_hasalreadyreceivedpopup(description) || spider_isunsupporteduseragent()) {
    return;
  }
  bwg_overflow_initial_value = jQuery("html").css("overflow");
  bwg_overflow_x_initial_value = jQuery("html").css("overflow-x");
  bwg_overflow_y_initial_value = jQuery("html").css("overflow-y");
  jQuery("html").attr("style", "overflow:hidden !important;");
  jQuery("#bwg_spider_popup_loading_" + current_view).show();
  jQuery("#spider_popup_overlay_" + current_view).css({display: "block"});
  jQuery.ajax({
    type: "GET",
    url:   url,
    success: function (data) {
      var popup = jQuery(
        '<div id="spider_popup_wrap" class="spider_popup_wrap" style="' +
        ' width:' + width + 'px;' +
        ' height:' + height + 'px;' +
        ' margin-top:-' + height / 2 + 'px;' +
        ' margin-left: -' + width / 2 + 'px; ">' +
        data +
        '</div>')
        .hide()
        .appendTo("body");
      spider_showpopup(description, lifetime, popup, duration, lightbox_ctrl_btn_pos);
    },
    beforeSend: function() {},
    complete:function() {}
  });
}

function spider_showpopup(description, lifetime, popup, duration, lightbox_ctrl_btn_pos) {
  var cur_image_key = parseInt( jQuery( '#bwg_current_image_key' ).val() );
  if ( typeof data[cur_image_key] != 'undefined' ) {
    isPopUpOpened = true;
    var is_embed = data[cur_image_key]['filetype'].indexOf( "EMBED_" ) > -1 ? true : false;
    if ( !is_embed ) {
      if ( jQuery( '#spider_popup_wrap .bwg_popup_image_spun img' ).prop( 'complete' ) ) {
        /* Already loaded. */
        bwg_first_image_load( popup );
      }
      else {
        jQuery( '#spider_popup_wrap .bwg_popup_image_spun img' ).on( 'load error', function () {
          bwg_first_image_load( popup );
        } );
      }
    }
    else {
      bwg_first_image_load( popup );
    }
    spider_receivedpopup( description, lifetime, lightbox_ctrl_btn_pos );
  }
}

function bwg_first_image_load(popup) {
  popup.show();
  jQuery( ".bwg_spider_popup_loading" ).hide();
  if ( bwg_param['preload_images'] ) {
    bwg_preload_images( parseInt( jQuery( '#bwg_current_image_key' ).val() ) );
  }
  bwg_load_filmstrip();
}

function spider_hasalreadyreceivedpopup(description) {
  if (document.cookie.indexOf(description) > -1) {
    delete document.cookie[document.cookie.indexOf(description)];
  }
  return false;
}

function spider_receivedpopup(description, lifetime, lightbox_ctrl_btn_pos) {
  var date = new Date();
  date.setDate(date.getDate() + lifetime);
  document.cookie = description + "=true;expires=" + date.toUTCString() + ";path=/";
  if (lightbox_ctrl_btn_pos == 'bottom') {
    jQuery(".bwg_toggle_container").css("bottom", jQuery(".bwg_ctrl_btn_container").height() + "px");
  }
  else if (lightbox_ctrl_btn_pos == 'top') {
    jQuery(".bwg_toggle_container").css("top", jQuery(".bwg_ctrl_btn_container").height() + "px");
  }
}

function spider_isunsupporteduseragent() {
  return (!window.XMLHttpRequest);
}

function spider_destroypopup(duration) {
  if (document.getElementById("spider_popup_wrap") != null) {
    if (typeof jQuery().fullscreen !== 'undefined' && jQuery.isFunction(jQuery().fullscreen)) {
      if (jQuery.fullscreen.isFullScreen()) {
        jQuery.fullscreen.exit();
      }
    }
    if (typeof enable_addthis != "undefined" && enable_addthis) {
      jQuery(".at4-share-outer").hide();
    }
    setTimeout(function () {
      jQuery(".spider_popup_wrap").remove();
      jQuery(".bwg_spider_popup_loading").css({display: "none"});
      jQuery(".spider_popup_overlay").css({display: "none"});
      jQuery(document).off("keydown");
      if ( bwg_overflow_initial_value !== false ) {
        jQuery("html").css("overflow", bwg_overflow_initial_value);
      }
      if ( bwg_overflow_x_initial_value !== false ) {
        jQuery("html").css("overflow-x", bwg_overflow_x_initial_value);
      }
      if ( bwg_overflow_y_initial_value !== false ) {
        jQuery("html").css("overflow-y", bwg_overflow_y_initial_value);
      }
    }, 20);
  }
  isPopUpOpened = false;
  var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
  var viewportmeta = document.querySelector('meta[name="viewport"]');
  if (isMobile && viewportmeta) {
    viewportmeta.content = 'width=device-width, initial-scale=1';
  }
  var scrrr = jQuery(document).scrollTop();
  if ( bwg_objectL10n.is_pro ) {
    /*window.location.hash = "";*/
    location.replace("#");
  }
  jQuery(document).scrollTop(scrrr);
  if ( typeof bwg_param['bwg_playInterval'] != "undefined" ) {
    clearInterval(bwg_param['bwg_playInterval']);
  }
}
function get_ajax_pricelist(){
  var post_data = {};
  jQuery(".add_to_cart_msg").html("");
  post_data["ajax_task"] = "display";
  post_data["image_id"] = jQuery('#bwg_popup_image').attr('image_id');

  /* Loading. */
  jQuery("#ecommerce_ajax_loading").css('height', jQuery(".bwg_ecommerce_panel").css('height'));
  jQuery("#ecommerce_opacity_div").css('width', jQuery(".bwg_ecommerce_panel").css('width'));
  jQuery("#ecommerce_opacity_div").css('height', jQuery(".bwg_ecommerce_panel").css('height'));
  jQuery("#ecommerce_loading_div").css('width', jQuery(".bwg_ecommerce_panel").css('width'));
  jQuery("#ecommerce_loading_div").css('height', jQuery(".bwg_ecommerce_panel").css('height'));
  document.getElementById("ecommerce_opacity_div").style.display = '';
  document.getElementById("ecommerce_loading_div").style.display = 'table-cell';
  jQuery.ajax({
    type: "POST",
    url:  jQuery('#bwg_ecommerce_form').attr('action'),
    data: post_data,
    success: function (data) {
      jQuery(".pge_tabs li a").on("click", function(){
        jQuery(".pge_tabs_container > div").hide();
        jQuery(".pge_tabs li").removeClass("pge_active");
        jQuery(jQuery(this).attr("href")).show();
        jQuery(this).closest("li").addClass("pge_active");
        jQuery("[name=type]").val(jQuery(this).attr("href").substr(1));
        return false;
      });
      var manual = jQuery(data).find('.manual').html();
      jQuery('.manual').html(manual);

      var downloads = jQuery(data).find('.downloads').html();
      jQuery('.downloads').html(downloads);

      var pge_options = jQuery(data).find('.pge_options').html();
      jQuery('.pge_options').html(pge_options);

      var pge_add_to_cart = jQuery(data).find('.pge_add_to_cart').html();
      jQuery('.pge_add_to_cart').html(pge_add_to_cart);
    },
    beforeSend: function(){
    },
    complete:function(){
      document.getElementById("ecommerce_opacity_div").style.display = 'none';
      document.getElementById("ecommerce_loading_div").style.display = 'none';
      /*
      Update scrollbar.
      jQuery(".bwg_ecommece_panel").mCustomScrollbar({scrollInertia: 150 });
      jQuery(".bwg_ecommerce_close_btn").click(bwg_ecommerce);
      */
    }
  });
  return false;
}

/* Submit popup. */
function spider_ajax_save(form_id) {
  var post_data = {};
  post_data["bwg_name"] = jQuery("#bwg_name").val();
  post_data["bwg_comment"] = jQuery("#bwg_comment").val();
  post_data["bwg_email"] = jQuery("#bwg_email").val();
  post_data["bwg_captcha_input"] = jQuery("#bwg_captcha_input").val();
  post_data["ajax_task"] = jQuery("#ajax_task").val();
  post_data["image_id"] = jQuery("#image_id").val();
  post_data["comment_id"] = jQuery("#comment_id").val();

  /* Loading. */
  jQuery("#ajax_loading").css('height', jQuery(".bwg_comments").css('height'));
  jQuery("#opacity_div").css('width', jQuery(".bwg_comments").css('width'));
  jQuery("#opacity_div").css('height', jQuery(".bwg_comments").css('height'));
  jQuery("#loading_div").css('width', jQuery(".bwg_comments").css('width'));
  jQuery("#loading_div").css('height', jQuery(".bwg_comments").css('height'));
  document.getElementById("opacity_div").style.display = '';
  document.getElementById("loading_div").style.display = 'table-cell';
  jQuery.ajax({
    type: "POST",
    url:  jQuery('#' + form_id).attr('action'),
    data: post_data,
    success: function (data) {
      var str = jQuery(data).find('.bwg_comments').html();
      jQuery('.bwg_comments').html(str);
    },
    beforeSend: function(){
    },
    complete:function(){
      document.getElementById("opacity_div").style.display = 'none';
      document.getElementById("loading_div").style.display = 'none';
      /* Update scrollbar. */
      jQuery(".bwg_comments").mCustomScrollbar({scrollInertia: 150,
        advanced:{
          updateOnContentResize: true
        }
      });
      /* Bind comment container close function to close button. */
      jQuery(".bwg_comments_close_btn").click(bwg_comment);
      bwg_captcha_refresh('bwg_captcha');
    }
  });
  return false;
}

/* Submit rating. */
function spider_rate_ajax_save(form_id) {
  var post_data = {};
  post_data["image_id"] = jQuery("#" + form_id + " input[name='image_id']").val();
  post_data["rate"] = jQuery("#" + form_id + " input[name='score']").val();
  post_data["ajax_task"] = jQuery("#rate_ajax_task").val();
  jQuery.ajax({
    type: "POST",
    url:   jQuery('#' + form_id).attr('action'),
    data: post_data,
    success: function (data) {
      var str = jQuery(data).find('#' + form_id).html();
      jQuery('#' + form_id).html(str);
    },
    beforeSend: function(){
    },
    complete:function(){}
  });
  return false;
}

/* Set value by ID. */
function spider_set_input_value(input_id, input_value) {
  if (document.getElementById(input_id)) {
    document.getElementById(input_id).value = input_value;
  }
}

/* Submit form by ID. */
function spider_form_submit(event, form_id) {
  if (document.getElementById(form_id)) {
    document.getElementById(form_id).submit();
  }
  if (event.preventDefault) {
    event.preventDefault();
  }
  else {
    event.returnValue = false;
  }
}

/* Check if required field is empty. */
function spider_check_required(id, name) {
  if (jQuery('#' + id).val() == '') {
    alert(name + ' ' + bwg_objectL10n.bwg_field_required);
    jQuery('#' + id).attr('style', 'border-color: #FF0000;');
    jQuery('#' + id).focus();
    return true;
  }
  else {
    return false;
  }
}

/* Check if privacy polic field is checked. */
function comment_check_privacy_policy() {
	var bwg_submit = jQuery('#bwg_submit');
	bwg_submit.removeClass('bwg-submit-disabled');
	bwg_submit.removeAttr("disabled");
	if ( !jQuery('#bwg_comment_privacy_policy').is(':checked') ) {
		bwg_submit.addClass('bwg-submit-disabled');
		bwg_submit.attr('disabled', 'disabled');
	}
}

/* Check Email. */
function spider_check_email(id) {
  if (jQuery('#' + id).val() != '') {
    var email = jQuery('#' + id).val().replace(/^\s+|\s+$/g, '');
    if (email.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/) == -1) {
      alert(bwg_objectL10n.bwg_mail_validation);
      return true;
    }
    return false;
  }
}

/* Refresh captcha. */
function bwg_captcha_refresh(id) {
  if (document.getElementById(id + "_img") && document.getElementById(id + "_input")) {
    srcArr = document.getElementById(id + "_img").src.split("&r=");
    document.getElementById(id + "_img").src = srcArr[0] + '&r=' + Math.floor(Math.random() * 100);
    document.getElementById(id + "_img").style.display = "inline-block";
    document.getElementById(id + "_input").value = "";
  }
}

function bwg_play_instagram_video(obj,bwg) {
  jQuery(obj).parent().find("video").each(function () {
    if (jQuery(this).get(0).paused) {
      jQuery(this).get(0).play();
      jQuery(obj).children().hide();
    }
    else {
      jQuery(this).get(0).pause();
      jQuery(obj).children().show();
    }
  })
}

/**
 * Add comment.
 *
 * @returns {boolean}
 */
function bwg_add_comment() {
	var form = jQuery('#bwg_comment_form');
	var url = form.attr('action');
	var post_data = {};
	post_data['ajax_task'] = 'add_comment';
	post_data['comment_name'] = form.find('#bwg_name').val();
	post_data['comment_email'] = form.find('#bwg_email').val();
	post_data['comment_text'] = form.find('#bwg_comment').val();
	post_data['comment_captcha'] = form.find('#bwg_captcha_input').val();
	post_data['privacy_policy'] = ( form.find('#bwg_comment_privacy_policy').is(":checked") ) ? 1 : 0;
	post_data['comment_image_id'] = jQuery('#bwg_popup_image').attr('image_id');
  post_data['comment_moderation'] = form.find('#bwg_comment_moderation').val();
	jQuery('.bwg_spider_ajax_loading').hide();
	jQuery.ajax({
		url:   url,
		type: "POST",
		dataType: 'json',
		data: post_data,
		success: function ( res ) {
			jQuery('.bwg_comment_error').text('');
			if ( res.error == true ) {
				jQuery.each(res.error_messages, function( index, value ) {
				  if ( value ) {
					  jQuery('.bwg_comment_'+ index +'_error').text(value);
				  }
				});
			}
			else {
        form.find('#bwg_comment').val('');
				jQuery('.bwg_comment_waiting_message').hide();
				if( res.published == 0 ) {
					jQuery('.bwg_comment_waiting_message').show();
				}
				if ( res.html_comments_block != '' ) {
					jQuery('#bwg_added_comments').html(res.html_comments_block).show();
				}
			}
		},
		beforeSend: function() {
			jQuery('.bwg_spider_ajax_loading').show();
		},
		complete: function() {
			if ( form.find('#bwg_comment_privacy_policy').length > 0 ) {
				form.find('#bwg_comment_privacy_policy').prop('checked', false);
				comment_check_privacy_policy();
			}
			bwg_captcha_refresh('bwg_captcha');
			jQuery('.bwg_spider_ajax_loading').hide();
		},
		error:function() {}
	});
	return false;
}

/**
 * Remove comment.
 *
 * @param id_comment
 * @returns {boolean}
 */
function bwg_remove_comment( id_comment ) {
	var form = jQuery('#bwg_comment_form');
	var url = form.attr('action');
	var post_data = {};
	post_data['ajax_task'] = 'delete_comment';
	post_data['id_image'] = jQuery('#bwg_popup_image').attr('image_id');
	post_data['id_comment'] = id_comment;
	jQuery.ajax({
		url:   url,
		type: "POST",
		dataType: 'json',
		data: post_data,
		success: function ( res ) {
			if ( res.error == false) {
				jQuery('#bwg_comment_block_' + id_comment ).fadeOut( "slow").remove();
			}
		},
		beforeSend: function() {
		},
		complete:function() {
		},
		error:function() {}
	});
	return false;
}

function bwg_gallery_box( image_id, bwg_container, openEcommerce, gallery_id ) {
  jQuery(".bwg-validate").each(function() {
    jQuery(this).on("keypress change", function () {
      jQuery(this).parent().next().find(".bwg_comment_error").html("");
    });
  });
  if ( typeof openEcommerce == "undefined" ) {
    openEcommerce = false;
  }
  var bwg = bwg_container.data('bwg');
  var bwg_lightbox_url;
  if ( bwg_container.find(".bwg-container").data('lightbox-url') ) {
    /* To read from updated container after ajax (e.g. open lightbox from gallery in albums).*/
    bwg_lightbox_url = bwg_container.find(".bwg-container").data('lightbox-url');
  }
  else {
    bwg_lightbox_url = bwg_container.data('lightbox-url');
  }
  var filterTags = jQuery("#bwg_tag_id_" + bwg).val();
  filterTags = filterTags ? filterTags : 0;
  var ecommerce = openEcommerce == true ? "&open_ecommerce=1" : "";
  var filtersearchname = jQuery("#bwg_search_input_" + bwg ).val();
  var filtersortby = jQuery("#bwg_order_" + bwg).val() ? "&filtersortby=" + jQuery("#bwg_order_" + bwg).val() : '';
	filtersearchname = filtersearchname ? filtersearchname : '';

  if ( typeof gallery_id != "undefined" ) {
    /* Open lightbox with hash.*/
    bwg_lightbox_url += '&gallery_id=' + gallery_id;
  }
  var open_comment = '';
  var open_comment_attr = jQuery('#bwg_blog_style_share_buttons_' + image_id).attr('data-open-comment');
  if (typeof open_comment_attr !== typeof undefined && open_comment_attr !== false) {
	open_comment = '&open_comment=1';
  }
  spider_createpopup(bwg_lightbox_url + '&image_id=' + image_id + "&filter_tag=" + filterTags + ecommerce + open_comment + '&filter_search_name=' + filtersearchname + filtersortby, bwg, bwg_container.data('popup-width'), bwg_container.data('popup-height'), 1, 'testpopup', 5, bwg_container.data('buttons-position'));
}

function bwg_change_image_lightbox(current_key, key, data, from_effect) {
  bwg_current_key = bwg_param['bwg_current_key'];
  /* var bwg_image_info_pos = jQuery(".bwg_ctrl_btn_container").height(); */
  jQuery(".bwg_image_info").css("height","auto");
  setTimeout(function(){
    if(jQuery(".bwg_image_info_container1").height() < (jQuery(".bwg_image_info").height() + jQuery(".bwg_toggle_container").height() + bwg_image_info_pos + 2*(parseInt(bwg_param['lightbox_info_margin'])))) {
      if(bwg_param['lightbox_ctrl_btn_pos'] == 'top') {
        jQuery(".bwg_image_info").css("top",bwg_image_info_pos + "px");
      }
      jQuery(".bwg_image_info").height(jQuery(".bwg_image_info_container1").height() - jQuery(".bwg_toggle_container").height() - bwg_image_info_pos - 2*(parseInt(bwg_param['lightbox_info_margin'])));
    }
  }, 200);
  jQuery("#spider_popup_left").show();
  jQuery("#spider_popup_right").show();
  jQuery(".bwg_image_info").hide();
  if (bwg_param['enable_loop'] == 0) {
    if (key == (parseInt(data.length) - 1)) {
      jQuery("#spider_popup_right").hide();
    }
    if (key == 0) {
      jQuery("#spider_popup_left").hide();
    }
  }
  var ecommerceACtive = bwg_param['ecommerceACtive'];
  if( ecommerceACtive == 1 && bwg_param['enable_image_ecommerce'] == 1 ) {
    if( data[key]["pricelist"] == 0) {
      jQuery(".bwg_ecommerce").hide();
    }
    else {
      jQuery(".bwg_ecommerce").show();
      jQuery(".pge_tabs li").hide();
      jQuery("#downloads").hide();
      jQuery("#manual").hide();
      var pricelistSections = data[key]["pricelist_sections"].split(",");

      if(pricelistSections){
        jQuery("#" + pricelistSections[0]).show();
        jQuery("[name=type]").val(pricelistSections[0]);
        if(pricelistSections.length > 1){
          jQuery(".pge_tabs").show();
          for( k=0 ; k<pricelistSections.length; k++ ){
            jQuery("#" + pricelistSections[k] + "_li").show();
          }
        }
        else{
          jQuery(".pge_tabs").hide();
        }
      }
      else{
        jQuery("[name=type]").val("");
      }
    }
  }
  /* Pause videos.*/
  jQuery("#bwg_image_container").find("iframe").each(function () {
    jQuery(this)[0].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
    jQuery(this)[0].contentWindow.postMessage('{ "method": "pause" }', "*");
    jQuery(this)[0].contentWindow.postMessage('pause', '*');
  });
  jQuery("#bwg_image_container").find("video").each(function () {
    jQuery(this).trigger('pause');
  });
  if ( typeof data[key] != 'undefined' ) {
    if (typeof data[current_key] != 'undefined') {
      if (jQuery(".bwg_play_pause").length && !jQuery(".bwg_play_pause").hasClass("fa-play")) {
        bwg_play( data );
      }

      if (!from_effect) {
        /* Change image key.*/
        jQuery("#bwg_current_image_key").val(key);
        /*if (current_key == '-1') {
          current_key = jQuery(".bwg_thumb_active").children("img").attr("image_key");
        }*/
      }
      if (bwg_param['bwg_trans_in_progress']) {
        bwg_param['event_stack'].push(current_key + '-' + key);
        return;
      }
      var direction = 'right';
      if (bwg_current_key > key) {
        var direction = 'left';
      }
      else if (bwg_current_key == key) {
        return;
      }
      /*
      jQuery("#spider_popup_left").hover().css({"display": "inline"});
            jQuery("#spider_popup_right").hover().css({"display": "inline"});
      */

      jQuery(".bwg_image_count").html(data[key]["number"]);
      /* Set filmstrip initial position.*/
      jQuery(".bwg_watermark").css({display: 'none'});
      /* Set active thumbnail position.*/
      if ( bwg_param['width_or_height'] == 'width' ) {
        bwg_current_filmstrip_pos = key * (jQuery(".bwg_filmstrip_thumbnail").width() + 2 + 2 * bwg_param['lightbox_filmstrip_thumb_border_width']);
      } else if ( bwg_param['width_or_height'] == 'height' ) {
        bwg_current_filmstrip_pos = key * (jQuery(".bwg_filmstrip_thumbnail").height() + 2 + 2 * bwg_param['lightbox_filmstrip_thumb_border_width']);
      }
      bwg_param['bwg_current_key'] = key;
      if ( bwg_objectL10n.is_pro ) {
        /* Change hash.*/
        /*window.location.hash = "bwg"+bwg_param['gallery_id'] +"/" + data[key]["id"];*/
        location.replace("#bwg" + bwg_param['gallery_id'] + "/" + data[key]["id"]);
        history.replaceState(undefined, undefined, "#bwg" + bwg_param['gallery_id'] + "/" + data[key]["id"]);
      }
      /* Change image id for rating.*/
      if (bwg_param['popup_enable_rate']) {
        jQuery("#bwg_rate_form input[name='image_id']").val(data[key]["id"]);
        jQuery("#bwg_star").attr("data-score", data[key]["avg_rating"]);
        jQuery("#bwg_star").removeAttr("title");
        bwg_rating(data[key]["rate"], data[key]["rate_count"], data[key]["avg_rating"], key);
      }
      /* Increase image hit counter.*/
      spider_set_input_value('rate_ajax_task', 'save_hit_count');

      spider_rate_ajax_save('bwg_rate_form');
      jQuery(".bwg_image_hits span").html(++data[key]["hit_count"]);
      /* Change image id.*/
      jQuery("#bwg_popup_image").attr('image_id', data[key]["id"]);
      /* Change image title, description.*/
      jQuery(".bwg_image_title").html(jQuery('<span style="display: block;" />').html(data[key]["alt"]).text());
      jQuery(".bwg_image_description").html(jQuery('<span style="display: block;" />').html(data[key]["description"]).text());
      /*jQuery(".bwg_image_info").removeAttr("style");*/
      /* Set active thumbnail.*/

      jQuery(".bwg_filmstrip_thumbnail").removeClass("bwg_thumb_active").addClass("bwg_thumb_deactive");
      jQuery("#bwg_filmstrip_thumbnail_" + key).removeClass("bwg_thumb_deactive").addClass("bwg_thumb_active");
      jQuery(".bwg_image_info").css("opacity", 1);
      if (data[key]["alt"].trim() == "") {
        if (data[key]["description"].trim() == "") {
          jQuery(".bwg_image_info").css("opacity", 0);
        }
      }
      if (jQuery(".bwg_image_info_container1").css("display") != 'none') {
        jQuery(".bwg_image_info_container1").css("display", "table-cell");
      }
      else {
        jQuery(".bwg_image_info_container1").css("display", "none");
      }
      /* Change image rating.*/
      if (jQuery(".bwg_image_rate_container1").css("display") != 'none') {
        jQuery(".bwg_image_rate_container1").css("display", "table-cell");
      }
      else {
        jQuery(".bwg_image_rate_container1").css("display", "none");
      }
      var current_image_class = jQuery(".bwg_popup_image_spun").css("zIndex") == 2 ? ".bwg_popup_image_spun" : ".bwg_popup_image_second_spun";
      var next_image_class = current_image_class == ".bwg_popup_image_second_spun" ? ".bwg_popup_image_spun" : ".bwg_popup_image_second_spun";

      var is_embed = data[key]['filetype'].indexOf("EMBED_") > -1 ? true : false;
      var is_embed_instagram_post = data[key]['filetype'].indexOf('INSTAGRAM_POST') > -1 ? true : false;
      var is_embed_instagram_video = data[key]['filetype'].indexOf('INSTAGRAM_VIDEO') > -1 ? true : false;
	  var is_ifrem = ( jQuery.inArray( data[key]['filetype'] , ['EMBED_OEMBED_YOUTUBE_VIDEO', 'EMBED_OEMBED_VIMEO_VIDEO', 'EMBED_OEMBED_FACEBOOK_VIDEO', 'EMBED_OEMBED_DAILYMOTION_VIDEO'] ) !== -1 ) ? true : false;
	  var cur_height = jQuery(current_image_class).height();
      var cur_width = jQuery(current_image_class).width();
      var innhtml = '<span class="bwg_popup_image_spun1" style="display: ' + ( !is_embed ? 'table' : 'block' ) + '; width: inherit; height: inherit;"><span class="bwg_popup_image_spun2" style="display:' + (!is_embed ? 'table-cell' : 'block') + '; vertical-align: middle;text-align: center;height: 100%;">';
      if ( !is_embed ) {
        jQuery(".bwg-loading").removeClass("hidden");
        jQuery("#bwg_download").removeClass("hidden");
        innhtml += '<img style="max-height: ' + cur_height + 'px; max-width: ' + cur_width + 'px;" class="bwg_popup_image bwg_popup_watermark" src="' + bwg_param['site_url'] + jQuery('<span style="display: block;" />').html(data[key]["image_url"]).text() + '" alt="' + data[key]["alt"] + '" />';
      }
      else { /*is_embed*/
        /* hide download button if image source is embed */
        jQuery("#bwg_download").addClass("hidden");
        innhtml += '<span class="bwg_popup_embed bwg_popup_watermark" style="display: ' + ( is_ifrem ? 'block' : 'table' ) + '; table-layout: fixed; height: 100%;">' + (is_embed_instagram_video ? '<div class="bwg_inst_play_btn_cont" onclick="bwg_play_instagram_video(this)" ><div class="bwg_inst_play"></div></div>' : ' ');
        if (is_embed_instagram_post) {
          var post_width = 0;
          var post_height = 0;
          if (cur_height < cur_width + 88) {
            post_height = cur_height;
            post_width = post_height - 88;
          }
          else {
            post_width = cur_width;
            post_height = post_width + 88;
          }
          innhtml += spider_display_embed(data[key]['filetype'], data[key]['image_url'], data[key]['filename'], { class:"bwg_embed_frame", 'data-width': data[key]['image_width'], 'data-height': data[key]['image_height'], frameborder: "0", allowfullscreen: "allowfullscreen", style: "width:" + post_width + "px; height:" + post_height + "px; vertical-align:middle; display:inline-block; position:relative;"});
        }
        else {
          innhtml += spider_display_embed(data[key]['filetype'],data[key]['image_url'], data[key]['filename'], { class:"bwg_embed_frame", frameborder:"0", allowfullscreen:"allowfullscreen", style: "display:" + ( is_ifrem ? 'block' : 'table-cell' ) + "; width:inherit; height:inherit; vertical-align:middle;" });
        }
        innhtml += "</span>";
      }
      innhtml += '</span></span>';
      jQuery(next_image_class).html(innhtml);
      jQuery(next_image_class).find("img").on("load error", function () {
        jQuery(".bwg-loading").addClass("hidden");
      });
      jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").css({
        maxWidth: cur_width,
        maxHeight: cur_height,
        height: 'auto',
      });

      function bwg_afterload() {
        if (bwg_param['preload_images']) {
          bwg_preload_images(key);
        }

        window['bwg_'+bwg_param['bwg_image_effect']](current_image_class, next_image_class, direction);
        jQuery(current_image_class).find('.bwg_fb_video').each(function () {
          jQuery(this).attr('src', '');
        });
        if ( !is_embed ) {
          jQuery("#bwg_fullsize_image").attr("href", bwg_param['site_url'] + data[key]['image_url']);
          jQuery("#bwg_download").attr("href", bwg_param['site_url'] + data[key]['thumb_url'].replace('/thumb/', '/.original/'));
        }
        else {
          jQuery("#bwg_fullsize_image").attr("href", data[key]['image_url']);
        }
        var image_arr = data[key]['image_url'].split("/");
        jQuery("#bwg_download").attr("download", image_arr[image_arr.length - 1].replace(/\?bwg=(\d+)$/, ""));
        /* Change image social networks urls.*/
        var bwg_share_url = encodeURIComponent(bwg_param['bwg_share_url']) + "=" + data[key]['id'] + encodeURIComponent('#bwg'+bwg_param['gallery_id']+'/') + data[key]['id'];

        if (is_embed) {
          var bwg_share_image_url = encodeURIComponent(data[key]['thumb_url']);
        }
        else {
          var bwg_share_image_url = bwg_param['bwg_share_image_url'] + encodeURIComponent(encodeURIComponent(data[key]['image_url']));
        }
        bwg_share_image_url = bwg_share_image_url.replace(/%252F/g, '%2F');
        if (typeof addthis_share != "undefined") {
          addthis_share.url = bwg_share_url;
        }
        jQuery("#bwg_facebook_a").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + bwg_share_url);
        jQuery("#bwg_twitter_a").attr("href", "https://twitter.com/share?url=" + bwg_share_url);
        jQuery("#bwg_google_a").attr("href", "https://plus.google.com/share?url=" + bwg_share_url);
        jQuery("#bwg_pinterest_a").attr("href", "http://pinterest.com/pin/create/button/?s=100&url=" + bwg_share_url + "&media=" + bwg_share_image_url + "&description=" + data[key]['alt'] + '%0A' + data[key]['description']);
        jQuery("#bwg_tumblr_a").attr("href", "https://www.tumblr.com/share/photo?source=" + bwg_share_image_url + "&caption=" + data[key]['alt'] + "&clickthru=" + bwg_share_url);
        /* Load comments.*/
        if (jQuery(".bwg_comment_container").hasClass("bwg_open")) {
          if (data[key]["comment_count"] == 0) {
            jQuery("#bwg_added_comments").hide();
          }
          else {
            jQuery("#bwg_added_comments").show();
            spider_set_input_value('ajax_task', 'display');
            spider_set_input_value('image_id', jQuery('#bwg_popup_image').attr('image_id'));
            spider_ajax_save('bwg_comment_form');
          }
        }
        if (jQuery(".bwg_ecommerce_container").hasClass("bwg_open")) {
          /* Pricelist */
          if(data[key]["pricelist"] == 0){
            /* Close ecommerce.*/
            bwg_popup_sidebar_close(jQuery(".bwg_ecommerce_container"));
            bwg_animate_image_box_for_hide_sidebar();

            jQuery(".bwg_ecommerce_container").attr("class", "bwg_ecommerce_container bwg_close");
            jQuery(".bwg_ecommerce").attr("title", bwg_objectsL10n.bwg_show_ecommerce);
            jQuery(".spider_popup_close_fullscreen").show();
          }
          else{
            get_ajax_pricelist();
          }
        }
        /* Update custom scroll.*/
        if (typeof jQuery().mCustomScrollbar !== 'undefined') {
          if (jQuery.isFunction(jQuery().mCustomScrollbar)) {
            jQuery(".bwg_comments").mCustomScrollbar({
              advanced:{
                updateOnContentResize: true
              }
            });
          }
        }
        jQuery(".bwg_comments .mCSB_scrollTools").hide();
        if (bwg_param['enable_image_filmstrip']) {
          bwg_move_filmstrip();
        }
        bwg_resize_instagram_post();
      }
      if ( !is_embed ) {
        var cur_img = jQuery(next_image_class).find('img');
        cur_img.one('load', function() {
          bwg_afterload();
        }).each(function() {
          if(this.complete) jQuery(this).load();
        });
      }
      else {
        bwg_afterload();
      }
    }
  }
}

function bwg_preload_images_lightbox( key ) {
  var count_all = data.length;
  var preloadCount = ( bwg_param['preload_images_count'] == 0 || bwg_param['preload_images_count'] >= count_all ) ? count_all : bwg_param['preload_images_count'];
  var indexedImgCount = 0;
  for ( var i = 1; indexedImgCount < preloadCount; i++ ) {
    var sign = 1;
    do {
      var index = ( key + i * sign + count_all ) % count_all;
      if ( typeof data[index] != "undefined" ) {
        var is_embed = data[index]['filetype'].indexOf( "EMBED_" ) > -1 ? true : false;
        if ( !is_embed ) {
          jQuery( "<img/>" ).attr( "src", bwg_param['site_url'] + jQuery( '<span style="display: block;" />' ).html( data[index]["image_url"] ).text() );
        }
      }
      sign *= -1;
      indexedImgCount++;
    }
    while ( sign != 1 );
  }
}

/* open  popup sidebar  */
function bwg_popup_sidebar_open(obj){
  var comment_container_width = bwg_param['lightbox_comment_width'];
  var lightbox_comment_pos = bwg_param['lightbox_comment_pos'];
  if (comment_container_width > jQuery(window).width()) {
    comment_container_width = jQuery(window).width();
    obj.css({
      width: comment_container_width,
    });
    jQuery(".spider_popup_close_fullscreen").hide();
    jQuery(".spider_popup_close").hide();
    if (jQuery(".bwg_ctrl_btn").hasClass("fa-pause")) {
      var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
      jQuery(".bwg_play_pause").trigger(isMobile ? 'touchend' : 'click');
    }
  }
  else {
    jQuery(".spider_popup_close_fullscreen").show();
  }

  if(lightbox_comment_pos == 'left') {
    obj.animate({left: 0}, 100);
  } else {
    obj.animate({right: 0}, 100);
  }
}

/* Open/close comments.*/
function bwg_comment() {
  jQuery(".bwg_watermark").css({display: 'none'});
  jQuery(".bwg_ecommerce_wrap").css("z-index","-1");
  jQuery(".bwg_comment_wrap").css("z-index","25");
  if(jQuery(".bwg_ecommerce_container").hasClass("bwg_open") ){
    bwg_popup_sidebar_close(jQuery(".bwg_ecommerce_container"));
    jQuery(".bwg_ecommerce_container").attr("class", "bwg_ecommerce_container bwg_close");
    jQuery(".bwg_ecommerce").attr("title", bwg_objectsL10n.bwg_show_ecommerce);

  }
  if (jQuery(".bwg_comment_container").hasClass("bwg_open") ) {
    /* Close comment.*/
    /* Check if lightbox was play before oppening comment container */
    if( jQuery(".bwg_comment_container").attr("data-play-status") == "1" ) {
      jQuery(".bwg_ctrl_btn.bwg_play_pause").removeClass("fa-play").addClass("fa-pause").attr('title','Pause');
    }
    bwg_popup_sidebar_close(jQuery(".bwg_comment_container"));
    bwg_animate_image_box_for_hide_sidebar();
    jQuery(".bwg_comment_wrap").css("z-index","-1");
    jQuery(".bwg_comment_container").attr("class", "bwg_comment_container bwg_close");
    jQuery(".bwg_comment").attr("title", bwg_objectsL10n.bwg_show_comments);
    jQuery(".spider_popup_close_fullscreen").show();
  }
  else {
    /* Open comment.*/
    /* Check if lightbox is playing before oppening comment and set status */
    if ( jQuery(".bwg_play_pause").hasClass("fa-pause") ) {
      jQuery(".bwg_comment_container").attr("data-play-status", "1");
    } else {
      jQuery(".bwg_comment_container").attr("data-play-status", "0");
    }
    jQuery(".bwg_ctrl_btn.bwg_play_pause").removeClass("fa-pause").addClass("fa-play").attr('title','Play');
    bwg_popup_sidebar_open(jQuery(".bwg_comment_container"));
    bwg_animate_image_box_for_show_sidebar();
    jQuery(".bwg_comment_container").attr("class", "bwg_comment_container bwg_open");
    jQuery(".bwg_comment").attr("title", bwg_objectsL10n.bwg_hide_comments);
    /* Load comments.*/
    var cur_image_key = parseInt(jQuery("#bwg_current_image_key").val());
    if ( typeof data[cur_image_key] != "undefined" && data[cur_image_key]["comment_count"] != 0) {
      jQuery("#bwg_added_comments").show();
      spider_set_input_value('ajax_task', 'display');
      spider_set_input_value('image_id', jQuery('#bwg_popup_image').attr('image_id'));
      spider_ajax_save('bwg_comment_form');
    }
  }
  jQuery(".bwg_comments").mCustomScrollbar({scrollInertia: 150,
    advanced:{
      updateOnContentResize: true
    }
  });
}

/* Open/close ecommerce.*/
function bwg_ecommerce() {
  jQuery(".bwg_watermark").css({display: 'none'});
  jQuery(".bwg_ecommerce_wrap").css("z-index","25");
  jQuery(".bwg_comment_wrap").css("z-index","-1");
  if (jQuery(".bwg_comment_container").hasClass("bwg_open")) {
    bwg_popup_sidebar_close(jQuery(".bwg_comment_container"));
    jQuery(".bwg_comment_container").attr("class", "bwg_comment_container bwg_close");
    // Must be translatable
    jQuery(".bwg_comment").attr("title", bwg_objectsL10n.bwg_how_comments);
  }
  if (jQuery(".bwg_ecommerce_container").hasClass("bwg_open")) {
    /* Close ecommerce.*/
    bwg_popup_sidebar_close(jQuery(".bwg_ecommerce_container"));
    bwg_animate_image_box_for_hide_sidebar();
    jQuery(".bwg_ecommerce_container").attr("class", "bwg_ecommerce_container bwg_close");
    // Must be translatable
    jQuery(".bwg_ecommerce").attr("title", bwg_objectsL10n.bwg_show_ecommerce);
    // jQuery(".spider_popup_close_fullscreen").show();
  }
  else {
    /* Open ecommerce.*/
    bwg_popup_sidebar_open(jQuery(".bwg_ecommerce_container"));
    bwg_animate_image_box_for_show_sidebar();
    jQuery(".bwg_ecommerce_container").attr("class", "bwg_ecommerce_container bwg_open");
    jQuery(".bwg_ecommerce").attr("title", bwg_objectsL10n.bwg_hide_ecommerce);
    get_ajax_pricelist();
  }
}

function bwg_popup_sidebar_close(obj){
  var border_width = parseInt(obj.css('borderRightWidth'));
  if (!border_width) {
    border_width = 0;
  }
  if( lightbox_comment_pos == 'left' ) {
    obj.animate({left: -obj.width() - border_width}, 100);
  } else if ( lightbox_comment_pos == 'right' ) {
    obj.animate({right: -obj.width() - border_width}, 100);
  }
}

function bwg_animate_image_box_for_hide_sidebar() {
  if ( lightbox_comment_pos == 'left' ) {
    jQuery( ".bwg_image_wrap" ).animate( {
      left: 0,
      width: jQuery( "#spider_popup_wrap" ).width()
    }, 100 );
  } else if ( lightbox_comment_pos == 'right' ) {
    jQuery( ".bwg_image_wrap" ).animate( {
      right: 0,
      width: jQuery( "#spider_popup_wrap" ).width()
    }, 100 );
  }
  jQuery( ".bwg_image_container" ).animate( {
    width: jQuery( "#spider_popup_wrap" ).width() - ( bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0 )
  }, 100 );
  jQuery( ".bwg_popup_image" ).animate( {
    maxWidth: jQuery( "#spider_popup_wrap" ).width() - ( bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0 )
  }, {
    duration: 100,
    complete: function () {
      bwg_change_watermark_container();
    }
  } );
  jQuery( ".bwg_popup_embed" ).animate( {
    width: jQuery( "#spider_popup_wrap" ).width() - ( bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0 )
  }, {
    duration: 100,
    complete: function () {
      bwg_resize_instagram_post();
      bwg_change_watermark_container();
    }
  } );
  if ( bwg_param['width_or_height'] == 'width' ) {
    jQuery( ".bwg_filmstrip_container" ).animate( { width: jQuery( ".spider_popup_wrap" ).width() }, 100 );
    jQuery( ".bwg_filmstrip" ).animate( { width: jQuery( ".spider_popup_wrap" ).width() - 40 }, 100 );
  } else if ( bwg_param['width_or_height'] == 'height' ) {
    jQuery( ".bwg_filmstrip_container" ).animate( { height: jQuery( ".spider_popup_wrap" ).width() }, 100 );
    jQuery( ".bwg_filmstrip" ).animate( { height: jQuery( ".spider_popup_wrap" ).width() - 40 }, 100 );
  }
  /* Set filmstrip initial position.*/
  bwg_set_filmstrip_pos( jQuery( ".spider_popup_wrap" ).width() - 40 );
  jQuery( ".spider_popup_close_fullscreen" ).show( 100 );
}

function bwg_animate_image_box_for_show_sidebar() {
  var bwg_comment_container = jQuery( ".bwg_comment_container" ).width() || jQuery( ".bwg_ecommerce_container" ).width();
  if ( lightbox_comment_pos == 'left' ) {
    jQuery( ".bwg_image_wrap" ).animate( {
      left: bwg_comment_container,
      width: jQuery( "#spider_popup_wrap" ).width() - bwg_comment_container
    }, 100 );
  } else if ( lightbox_comment_pos == 'right' ) {
    jQuery( ".bwg_image_wrap" ).animate( {
      right: bwg_comment_container,
      width: jQuery( "#spider_popup_wrap" ).width() - bwg_comment_container
    }, 100 );
  }
  jQuery( ".bwg_image_container" ).animate( {
    width: jQuery( "#spider_popup_wrap" ).width() - ( bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0 ) - bwg_comment_container
  }, 100 );
  jQuery( ".bwg_popup_image" ).animate( {
    maxWidth: jQuery( "#spider_popup_wrap" ).width() - bwg_comment_container - ( bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0 )
  }, {
    duration: 100,
    complete: function () {
      bwg_change_watermark_container();
    }
  } );
  jQuery( ".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video" ).animate( {
    maxWidth: jQuery( "#spider_popup_wrap" ).width() - bwg_comment_container - ( bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0 )
  }, {
    duration: 100,
    complete: function () {
      bwg_resize_instagram_post();
      bwg_change_watermark_container();
    }
  } );
  if ( bwg_param['width_or_height'] == 'width' ) {
    jQuery( ".bwg_filmstrip_container" ).css( { width: jQuery( "#spider_popup_wrap" ).width() - ( bwg_param['filmstrip_direction'] == 'vertical' ? 0 : bwg_comment_container ) } );
    jQuery( ".bwg_filmstrip" ).animate( { width: jQuery( ".bwg_filmstrip_container" ).width() - 40 }, 100 );
    /* Set filmstrip initial position.*/
    bwg_set_filmstrip_pos( jQuery( ".bwg_filmstrip_container" ).width() - 40 );
  }
}

function bwg_reset_zoom() {
  var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
  var viewportmeta = document.querySelector('meta[name="viewport"]');
  if (isMobile) {
    if (viewportmeta) {
      viewportmeta.content = 'width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=0';
    }
  }
}

/* Open with fullscreen.*/
function bwg_open_with_fullscreen() {
  jQuery(".bwg_watermark").css({display: 'none'});
  var comment_container_width = 0;
  if (jQuery(".bwg_comment_container").hasClass("bwg_open") || jQuery(".bwg_ecommerce_container").hasClass("bwg_open")) {
    comment_container_width = jQuery(".bwg_comment_container").width() || jQuery(".bwg_ecommerce_container").width();
  }
  bwg_popup_current_width = jQuery(window).width();
  bwg_popup_current_height = window.innerHeight;
  jQuery("#spider_popup_wrap").css({
    width: jQuery(window).width(),
    height: window.innerHeight,
    left: 0,
    top: 0,
    margin: 0,
    zIndex: 100002
  });
  jQuery(".bwg_image_wrap").css({width: (jQuery(window).width() - comment_container_width)});
  jQuery(".bwg_image_container").css({height: (bwg_popup_current_height - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)), width: bwg_popup_current_width - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0)});
  jQuery(".bwg_popup_image").css({
    maxWidth: jQuery(window).width() - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0),
    maxHeight: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
  },  {
    complete: function () { bwg_change_watermark_container(); }
  });
  jQuery(".bwg_popup_video").css({
    width: jQuery(window).width() - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0),
    height: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
  },  {
    complete: function () { bwg_change_watermark_container(); }
  });
  jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").css({
    maxWidth: jQuery(window).width() - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0),
    maxHeight: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
  },  {
    complete: function () {
      bwg_resize_instagram_post();
      bwg_change_watermark_container(); }
  });
  if ( bwg_param['width_or_height'] == 'width' ) {
    jQuery(".bwg_filmstrip_container").css({width: jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'horizontal' ? 'comment_container_width' : 0)});
    jQuery(".bwg_filmstrip").css({width: jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'horizontal' ? 'comment_container_width' : 0) - 40});
    /* Set filmstrip initial position.*/
    bwg_set_filmstrip_pos(jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'horizontal' ? 'comment_container_width' : 0) - 40);
  } else {
    jQuery(".bwg_filmstrip_container").css({height: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? 'comment_container_width' : 0)});
    jQuery(".bwg_filmstrip").css({height: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? 'comment_container_width' : 0) - 40});
    /* Set filmstrip initial position.*/
    bwg_set_filmstrip_pos(window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? 'comment_container_width' : 0) - 40);
  }
  jQuery(".bwg_resize-full").attr("class", "bwg_ctrl_btn bwg_resize-full fa fa-resize-small");
  jQuery(".bwg_resize-full").attr("title", bwg_objectsL10n.bwg_restore);
  jQuery(".spider_popup_close").attr("class", "bwg_ctrl_btn spider_popup_close_fullscreen");
}

function bwg_resize_full() {
  jQuery(".bwg_watermark").css({display: 'none'});
  var comment_container_width = 0;
  if (jQuery(".bwg_comment_container").hasClass("bwg_open") || jQuery(".bwg_ecommerce_container").hasClass("bwg_open") ) {
    comment_container_width = jQuery(".bwg_comment_container").width() || jQuery(".bwg_ecommerce_container").width();
  }
  // resize to small from full
  if (jQuery(".bwg_resize-full").hasClass("fa-resize-small")) {
    if (jQuery(window).width() > bwg_param['image_width']) {
      bwg_popup_current_width = bwg_param['image_width'];
    }
    if (window.innerHeight > bwg_param['image_height']) {
      bwg_popup_current_height = bwg_param['image_height'];
    }
    /* Minimize.*/
    jQuery("#spider_popup_wrap").animate({
      width: bwg_popup_current_width,
      height: bwg_popup_current_height,
      left: '50%',
      top: '50%',
      marginLeft: -bwg_popup_current_width / 2,
      marginTop: -bwg_popup_current_height / 2,
      zIndex: 100002
    }, 500);
    jQuery(".bwg_image_wrap").animate({width: bwg_popup_current_width - comment_container_width}, 500);
    jQuery(".bwg_image_container").animate({height: bwg_popup_current_height - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0), width: bwg_popup_current_width - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0)}, 500);
    jQuery(".bwg_popup_image").animate({
      maxWidth: bwg_popup_current_width - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0),
      maxHeight: bwg_popup_current_height - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
    }, {
      duration: 500,
      complete: function () {
        bwg_change_watermark_container();
        if ((jQuery("#spider_popup_wrap").width() < jQuery(window).width())) {
          if (jQuery("#spider_popup_wrap").height() < window.innerHeight) {
            jQuery(".spider_popup_close_fullscreen").attr("class", "spider_popup_close");
          }
        }
      }
    });
    jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").animate({
      maxWidth: bwg_popup_current_width - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0),
      maxHeight: bwg_popup_current_height - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
    }, {
      duration: 500,
      complete: function () {
        bwg_resize_instagram_post();
        bwg_change_watermark_container();
        if (jQuery("#spider_popup_wrap").width() < jQuery(window).width()) {
          if (jQuery("#spider_popup_wrap").height() < window.innerHeight) {
            jQuery(".spider_popup_close_fullscreen").attr("class", "spider_popup_close");
          }
        }
      }
    });
    if ( bwg_param['width_or_height'] == 'width' ) {
      jQuery(".bwg_filmstrip_container").animate({width: bwg_popup_current_width - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0)}, 500);
      jQuery(".bwg_filmstrip").animate({width: bwg_popup_current_width - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0) - 40}, 500);
      /* Set filmstrip initial position.*/
      bwg_set_filmstrip_pos(bwg_popup_current_width - 40);
    } else {
      jQuery(".bwg_filmstrip_container").animate({height: bwg_popup_current_height - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0)}, 500);
      jQuery(".bwg_filmstrip").animate({height: bwg_popup_current_height - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0) - 40}, 500);
      /* Set filmstrip initial position.*/
      bwg_set_filmstrip_pos(bwg_popup_current_height - 40);
    }
    jQuery(".bwg_resize-full").attr("class", "bwg_ctrl_btn bwg_resize-full fa fa-resize-full");
    jQuery(".bwg_resize-full").attr("title", "<?php echo __('Maximize', BWG()->prefix); ?>");

  }
  else { // resize to full from small
    bwg_popup_current_width = jQuery(window).width();
    bwg_popup_current_height = window.innerHeight;
    /* Maximize.*/
    jQuery("#spider_popup_wrap").animate({
      width: jQuery(window).width(),
      height: window.innerHeight,
      left: 0,
      top: 0,
      margin: 0,
      zIndex: 100002
    }, 500);
    jQuery(".bwg_image_wrap").animate({width: (jQuery(window).width() - comment_container_width)}, 500);
    jQuery(".bwg_image_container").animate({height: (bwg_popup_current_height - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)), width: bwg_popup_current_width - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0)}, 500);
    jQuery(".bwg_popup_image").animate({
      maxWidth: jQuery(window).width() - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0),
      maxHeight: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
    }, {
      duration: 500,
      complete: function () { bwg_change_watermark_container(); }
    });
    jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").animate({
      maxWidth: jQuery(window).width() - comment_container_width - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0),
      maxHeight: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
    }, {
      duration: 500,
      complete: function () {
        bwg_resize_instagram_post();
        bwg_change_watermark_container(); }
    });
    if ( bwg_param['width_or_height'] == 'width' ) {
      jQuery(".bwg_filmstrip_container").animate({width: jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0)}, 500);
      jQuery(".bwg_filmstrip").animate({width: jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0) - 40}, 500);
      /* Set filmstrip initial position.*/
      bwg_set_filmstrip_pos(jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0) - 40);
    } else {
      jQuery(".bwg_filmstrip_container").animate({height: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0)}, 500);
      jQuery(".bwg_filmstrip").animate({height: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0) - 40}, 500);
      /* Set filmstrip initial position.*/
      bwg_set_filmstrip_pos(window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? comment_container_width : 0) - 40);
    }
    jQuery(".bwg_resize-full").attr("class", "bwg_ctrl_btn bwg_resize-full fa fa-resize-small");
    jQuery(".bwg_resize-full").attr("title", "<?php echo __('Restore', BWG()->prefix); ?>");
    jQuery(".spider_popup_close").attr("class", "bwg_ctrl_btn spider_popup_close_fullscreen");
  }
}

function bwg_popup_resize_lightbox() {
  if (typeof jQuery().fullscreen !== 'undefined') {
    if (jQuery.isFunction(jQuery().fullscreen)) {
      if (!jQuery.fullscreen.isFullScreen()) {
        jQuery(".bwg_resize-full").show();
        if(!jQuery('.bwg_resize-full').hasClass('fa-resize-small')) {
          jQuery(".bwg_resize-full").attr("class", "bwg_ctrl_btn bwg_resize-full fa fa-resize-full");
        }
        jQuery(".bwg_resize-full").attr("title", bwg_objectsL10n.bwg_maximize);
        jQuery(".bwg_fullscreen").attr("class", "bwg_ctrl_btn bwg_fullscreen fa fa-fullscreen");
        jQuery(".bwg_fullscreen").attr("title", bwg_objectsL10n.fulscreen);
      }
    }
  }
  var comment_container_width = 0;
  if (jQuery(".bwg_comment_container").hasClass("bwg_open") || jQuery(".bwg_ecommerce_container").hasClass("bwg_open")) {
    comment_container_width = bwg_param['lightbox_comment_width'];
  }
  if (comment_container_width > jQuery(window).width()) {
    comment_container_width = jQuery(window).width();
    jQuery(".bwg_comment_container").css({
      width: comment_container_width
    });
    jQuery(".bwg_ecommerce_container").css({
      width: comment_container_width
    });
    jQuery(".spider_popup_close_fullscreen").hide();
  }
  else {
    jQuery(".spider_popup_close_fullscreen").show();
  }
  if (!(!(window.innerHeight > bwg_param['image_height']) || !(bwg_param['open_with_fullscreen'] != 1)) && !jQuery('.bwg_resize-full').hasClass('fa-resize-small')) {
    jQuery("#spider_popup_wrap").css({
      height: bwg_param['image_height'],
      top: '50%',
      marginTop: -bwg_param['image_height'] / 2,
      zIndex: 100002
    });
    jQuery(".bwg_image_container").css({height: (bwg_param['image_height'] - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0))});
    jQuery(".bwg_popup_image").css({
      maxHeight: bwg_param['image_height'] - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
    });
    jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").css({
      maxHeight: bwg_param['image_height'] - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
    });
    if (bwg_param['filmstrip_direction'] == 'vertical') {
      jQuery(".bwg_filmstrip_container").css({height: bwg_param['image_height']});
      jQuery(".bwg_filmstrip").css({height: (bwg_param['image_height'] - 40)})
    }
    bwg_popup_current_height = bwg_param['image_height'];
  }
  else {
    jQuery("#spider_popup_wrap").css({
      height: window.innerHeight,
      top: 0,
      marginTop: 0,
      zIndex: 100002
    });

    jQuery(".bwg_image_container").css({height: (window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0))});
    jQuery(".bwg_popup_image").css({
      maxHeight: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
    });
    jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").css({
      maxHeight: window.innerHeight - (bwg_param['filmstrip_direction'] == 'horizontal' ? bwg_param['image_filmstrip_height'] : 0)
    });
    if (bwg_param['filmstrip_direction'] == 'vertical') {
      jQuery(".bwg_filmstrip_container").css({height: (window.innerHeight)});
      jQuery(".bwg_filmstrip").css({height: (window.innerHeight - 40)});
    }
    bwg_popup_current_height = window.innerHeight;
  }
  if (!(!(jQuery(window).width() >= bwg_param['image_width']) || !(bwg_param['open_with_fullscreen'] != 1))) {
    jQuery("#spider_popup_wrap").css({
      width: bwg_param['image_width'],
      left: '50%',
      marginLeft: -bwg_param['image_width'] / 2,
      zIndex: 100002
    });
    jQuery(".bwg_image_wrap").css({width: bwg_param['image_width'] - comment_container_width});
    jQuery(".bwg_image_container").css({width: (bwg_param['image_width'] - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0) - comment_container_width)});
    jQuery(".bwg_popup_image").css({
      maxWidth: bwg_param['image_width'] - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0) - comment_container_width
    });
    jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").css({
      maxWidth: bwg_param['image_width'] - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0) - comment_container_width
    });
    if (bwg_param['filmstrip_direction'] == 'horizontal') {
      jQuery(".bwg_filmstrip_container").css({width: bwg_param['image_width'] - comment_container_width});
      jQuery(".bwg_filmstrip").css({width: (bwg_param['image_width']  - comment_container_width- 40)});
    }
    bwg_popup_current_width = bwg_param['image_width'];
  }
  else {
    jQuery("#spider_popup_wrap").css({
      width: jQuery(window).width(),
      left: 0,
      marginLeft: 0,
      zIndex: 100002
    });
    jQuery(".bwg_image_wrap").css({width: (jQuery(window).width() - comment_container_width)});
    jQuery(".bwg_image_container").css({width: (jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0) - comment_container_width)});
    jQuery(".bwg_popup_image").css({
      maxWidth: jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0) - comment_container_width
    });
    jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").css({
      maxWidth: jQuery(window).width() - (bwg_param['filmstrip_direction'] == 'vertical' ? bwg_param['image_filmstrip_width'] : 0) - comment_container_width
    });
    if (bwg_param['filmstrip_direction'] == 'horizontal') {
      jQuery(".bwg_filmstrip_container").css({width: (jQuery(window).width() - comment_container_width)});
      jQuery(".bwg_filmstrip").css({width: (jQuery(window).width() - comment_container_width - 40)});
    }
    bwg_popup_current_width = jQuery(window).width();
  }
  /* Set watermark container size.*/
  bwg_resize_instagram_post();
  bwg_change_watermark_container();
  if (!(!(window.innerHeight > bwg_param['image_height'] - 2 * bwg_param['lightbox_close_btn_top']) || !(jQuery(window).width() >= bwg_param['image_width'] - 2 * bwg_param['lightbox_close_btn_right']) || !(bwg_param['open_with_fullscreen'] != 1))) {
    jQuery(".spider_popup_close_fullscreen").attr("class", "spider_popup_close");
  }
  else {
    if (!(!(jQuery("#spider_popup_wrap").width() < jQuery(window).width()) || !(jQuery("#spider_popup_wrap").height() < jQuery(window).height()))) {
      jQuery(".spider_popup_close").attr("class", "bwg_ctrl_btn spider_popup_close_fullscreen");
    }
  }
  if ( bwg_param['lightbox_ctrl_btn_pos'] == 'bottom' ) {
    if (jQuery(".bwg_toggle_container i").hasClass('fa-angle-down')) {
      jQuery(".bwg_toggle_container").css("bottom", jQuery(".bwg_ctrl_btn_container").height() + "px");
    }
  }
  if ( bwg_param['lightbox_ctrl_btn_pos'] == 'top') {
    if (jQuery(".bwg_toggle_container i").hasClass('fa-angle-up')) {
      jQuery(".bwg_toggle_container").css("top", jQuery(".bwg_ctrl_btn_container").height() + "px");
    }
  }
}
