function bwg_shortcode_load() {
  jQuery(".spider_int_input").keypress(function (event) {
    var chCode1 = event.which || event.paramlist_keyCode;
    if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57) && (chCode1 != 46)) {
      return false;
    }
    return true;
  });
  jQuery("#display_panel").tooltip({
    track: true,
    content: function () {
      return jQuery(this).prop('title');
    }
  });
}

function bwg_enable_disable(display, id, current) {
  jQuery("#" + current).prop('checked', true);
  jQuery("#" + id).css('display', display);
}

function bwg_popup_fullscreen() {
  if (jQuery("#wd_fb_popup_fullscreen_1").is(':checked')) {
    jQuery("#wd_fb_tr_popup_width_height").css('display', 'none');
  }
  else {
    jQuery("#wd_fb_tr_popup_width_height").css('display', '');
  }
}

function bwg_thumb_click_action() {
  if (!jQuery("#thumb_click_action_2").is(':checked')) {
    jQuery("#tr_thumb_link_target").css('display', 'none');
    jQuery("#tbody_popup").css('display', '');
    jQuery("#tr_popup_width_height").css('display', '');
    jQuery("#tr_popup_effect").css('display', '');
    jQuery("#tr_popup_interval").css('display', '');
    jQuery("#tr_popup_enable_filmstrip").css('display', '');
    if (jQuery("input[name=popup_enable_filmstrip]:checked").val() == 1) {
      bwg_enable_disable('', 'tr_popup_filmstrip_height', 'popup_filmstrip_yes');
    }
    else {
      bwg_enable_disable('none', 'tr_popup_filmstrip_height', 'popup_filmstrip_no');
    }
    jQuery("#tr_popup_enable_ctrl_btn").css('display', '');
    if (jQuery("input[name=popup_enable_ctrl_btn]:checked").val() == 1) {
      bwg_enable_disable('', 'tbody_popup_ctrl_btn', 'popup_ctrl_btn_yes');
    }
    else {
      bwg_enable_disable('none', 'tbody_popup_ctrl_btn', 'popup_ctrl_btn_no');
    }
    jQuery("#tr_popup_enable_fullscreen").css('display', '');
    jQuery("#tr_popup_enable_info").css('display', '');
    jQuery("#tr_popup_enable_rate").css('display', '');
    jQuery("#tr_popup_enable_comment").css('display', '');
    jQuery("#tr_popup_enable_facebook").css('display', '');
    jQuery("#tr_popup_enable_twitter").css('display', '');
    jQuery("#tr_popup_enable_google").css('display', '');
    jQuery("#tr_popup_enable_pinterest").css('display', '');
    jQuery("#tr_popup_enable_tumblr").css('display', '');
    bwg_popup_fullscreen();
  }
  else {
    jQuery("#tr_thumb_link_target").css('display', '');
    jQuery("#tbody_popup").css('display', 'none');
    jQuery("#tbody_popup_ctrl_btn").css('display', 'none');
  }
}

function bwg_show_search_box() { 
  if (jQuery("#show_search_box_1").is(':checked')) {
    jQuery("#tr_search_box_width").css('display', '');
  }
  else {
    jQuery("#tr_search_box_width").css('display', 'none');
  }
}

function bwg_change_compuct_album_view_type() {
  if (jQuery("input[name=compuct_album_view_type]:checked").val() == 'thumbnail') {
    jQuery("#compuct_album_image_thumb_dimensions").html('Image thumbnail dimensions: ');
    jQuery("#compuct_album_image_thumb_dimensions_x").css('display', '');
    jQuery("#compuct_album_image_thumb_width").css('display', '');
    jQuery("#compuct_album_image_thumb_height").css('display', '');
    jQuery("#tr_compuct_album_image_title").css('display', '');
    jQuery("#tr_compuct_album_mosaic_hor_ver").css('display', 'none');
    jQuery("#tr_compuct_album_resizable_mosaic").css('display', 'none');
    jQuery("#tr_compuct_album_mosaic_total_width").css('display', 'none');
    jQuery("#tr_compuct_album_image_column_number").css('display', '');
  }
  
  else if(jQuery("input[name=compuct_album_view_type]:checked").val() == 'masonry'){
    jQuery("#compuct_album_image_thumb_dimensions").html('Image thumbnail width: '); 
    jQuery("#compuct_album_image_thumb_dimensions_x").css('display', 'none');
    jQuery("#compuct_album_image_thumb_width").css('display', '');
    jQuery("#compuct_album_image_thumb_height").css('display', 'none');
    jQuery("#tr_compuct_album_image_title").css('display', 'none');
    jQuery("#tr_compuct_album_mosaic_hor_ver").css('display', 'none');
    jQuery("#tr_compuct_album_resizable_mosaic").css('display', 'none');
    jQuery("#tr_compuct_album_mosaic_total_width").css('display', 'none');
    jQuery("#tr_compuct_album_image_column_number").css('display', '');
  }
  else {/*mosaic*/
     
    jQuery("#compuct_album_image_thumb_dimensions_x").css('display', 'none');
    jQuery("#tr_compuct_album_image_column_number").css('display', 'none');
    jQuery("#tr_compuct_album_image_title").css('display', '');
    jQuery("#tr_compuct_album_mosaic_hor_ver").css('display', '');
    jQuery("#tr_compuct_album_resizable_mosaic").css('display', '');
    jQuery("#tr_compuct_album_mosaic_total_width").css('display', '');
    if(jQuery("input[name=compuct_album_mosaic_hor_ver]:checked").val() == 'vertical'){
      jQuery("#compuct_album_image_thumb_dimensions").html('Image thumbnail width: ');
      jQuery("#compuct_album_image_thumb_height").css('display', 'none');
      jQuery("#compuct_album_image_thumb_width").css('display', '');
    }
    else{
      jQuery("#compuct_album_image_thumb_dimensions").html('Image thumbnail height: ');
      jQuery("#compuct_album_image_thumb_width").css('display', 'none');
      jQuery("#compuct_album_image_thumb_height").css('display', '');

    }
    
  }
  
}

function bwg_change_extended_album_view_type() {
  if (jQuery("input[name=extended_album_view_type]:checked").val() == 'thumbnail') {
    jQuery("#extended_album_image_thumb_dimensions").html('Image thumbnail dimensions: ');
    jQuery("#extended_album_image_thumb_dimensions_x").css('display', '');
    jQuery("#extended_album_image_thumb_width").css('display', '');
    jQuery("#extended_album_image_thumb_height").css('display', '');
    jQuery("#tr_extended_album_image_title").css('display', '');
    jQuery("#tr_extended_album_mosaic_hor_ver").css('display', 'none');
    jQuery("#tr_extended_album_resizable_mosaic").css('display', 'none');
    jQuery("#tr_extended_album_mosaic_total_width").css('display', 'none');
    jQuery("#tr_extended_album_image_column_number").css('display', '');
  }
  
  else if(jQuery("input[name=extended_album_view_type]:checked").val() == 'masonry'){
    jQuery("#extended_album_image_thumb_dimensions").html('Image thumbnail width: '); 
    jQuery("#extended_album_image_thumb_dimensions_x").css('display', 'none');
    jQuery("#extended_album_image_thumb_width").css('display', '');
    jQuery("#extended_album_image_thumb_height").css('display', 'none');
    jQuery("#tr_extended_album_image_title").css('display', 'none');
    jQuery("#tr_extended_album_mosaic_hor_ver").css('display', 'none');
    jQuery("#tr_extended_album_resizable_mosaic").css('display', 'none');
    jQuery("#tr_extended_album_mosaic_total_width").css('display', 'none');
    jQuery("#tr_extended_album_image_column_number").css('display', '');
  }
  else {/*mosaic*/
    jQuery("#extended_album_image_thumb_dimensions_x").css('display', 'none');
    jQuery("#tr_extended_album_image_column_number").css('display', 'none');
    jQuery("#tr_extended_album_image_title").css('display', '');
    jQuery("#tr_extended_album_mosaic_hor_ver").css('display', '');
    jQuery("#tr_extended_album_resizable_mosaic").css('display', '');
    jQuery("#tr_extended_album_mosaic_total_width").css('display', '');
    if(jQuery("input[name=extended_album_mosaic_hor_ver]:checked").val() == 'vertical'){
      jQuery("#extended_album_image_thumb_dimensions").html('Image thumbnail width: ');
      jQuery("#extended_album_image_thumb_height").css('display', 'none');
      jQuery("#extended_album_image_thumb_width").css('display', '');
    }
    else{
      jQuery("#extended_album_image_thumb_dimensions").html('Image thumbnail height: ');
      jQuery("#extended_album_image_thumb_width").css('display', 'none');
      jQuery("#extended_album_image_thumb_height").css('display', '');

    }
  }
  
}

function bwg_change_label(id, text) {
  jQuery('#' + id).html(text);
}

function wd_fb_view_type(wd_fb_prefix, view_type) {
  var fb_type = jQuery("select[id=wd_fb_feed]").find(":selected").attr('fb_type'),
			fb_content_type = jQuery("select[id=wd_fb_feed]").find(":selected").attr('fb_content_type'),
			fb_content = jQuery("select[id=wd_fb_feed]").find(":selected").attr('fb_content');
			jQuery("#" + wd_fb_prefix + view_type).prop('checked', true);

  // Thumbnails, Masonry.
  jQuery("#"+wd_fb_prefix+"_tr_masonry_hor_ver").css('display', 'none');
  bwg_change_label("col_num_label", 'Max. number of image columns');
  jQuery("#"+wd_fb_prefix+"_tr_image_max_columns").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_thumb_width_height").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_thumb_comments").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_thumb_likes").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_thumb_name").css('display', 'none');
  
  // Blog Style.
  jQuery("#"+wd_fb_prefix+"_tr_blog_style_view_type").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_blog_style_comments").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_blog_style_likes").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_blog_style_message_desc").css('display', 'none');
	jQuery("#"+wd_fb_prefix+"_tr_blog_style_shares").css('display', 'none');
	jQuery("#"+wd_fb_prefix+"_tr_blog_style_shares_butt").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_blog_style_width").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_blog_style_height").css('display', 'none');
	jQuery("#"+wd_fb_prefix+"_tr_blog_style_author").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_blog_style_name").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_blog_style_place_name").css('display', 'none');
	jQuery("#"+wd_fb_prefix+"_tr_blog_style_facebook").css('display', 'none');
	jQuery("#"+wd_fb_prefix+"_tr_blog_style_twitter").css('display', 'none');
	jQuery("#"+wd_fb_prefix+"_tr_blog_style_google").css('display', 'none');
  
 
  //Album compact
  jQuery("#"+wd_fb_prefix+"_tr_compuct_album_column_number").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_compuct_albums_per_page").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_compuct_album_title_hover").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_compuct_album_thumb_width_height").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_compuct_album_image_column_number").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_compuct_album_images_per_page").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_compuct_album_image_title").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_compuct_album_image_thumb_width_height").css('display', 'none');
  
	// For all
	jQuery("#"+wd_fb_prefix+"_tr_fb_plugin").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_fb_name").css('display', 'none');
	
  // Popup.
  jQuery("#"+wd_fb_prefix+"_trtbody_popup").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_popup_width_height").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_popup_effect").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_popup_interval").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_filmstrip").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_popup_filmstrip_height").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_ctrl_btn").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_fullscreen").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_info_btn").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_facebook").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_twitter").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_google").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_pinterest").css('display', 'none');
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_tumblr").css('display', 'none');
	jQuery("#"+wd_fb_prefix+"_tr_popup_comments").css('display', 'none');
	jQuery("#"+wd_fb_prefix+"_tr_popup_likes").css('display', 'none');
	jQuery("#"+wd_fb_prefix+"_tr_popup_shares").css('display', 'none');
	jQuery("#"+wd_fb_prefix+"_tr_popup_author").css('display', 'none');
	jQuery("#"+wd_fb_prefix+"_tr_popup_name").css('display', 'none');
	jQuery("#"+wd_fb_prefix+"_tr_popup_place_name").css('display', 'none');
	jQuery("#"+wd_fb_prefix+"_tr_popup_message_desc").css('display', 'none');

  switch (view_type) {
    case 'thumbnails': {
      bwg_change_label(wd_fb_prefix+'_image_max_columns_label', 'Max. number of image columns: ');
      bwg_change_label(wd_fb_prefix+'_thumb_width_height_label', 'Image thumbnail dimensions: ');
      jQuery('#'+wd_fb_prefix+'_thumb_width').show();
      jQuery('#'+wd_fb_prefix+'_thumb_height').show();
      jQuery("#"+wd_fb_prefix+"_tr_image_max_columns").css('display', '');
      jQuery("#"+wd_fb_prefix+"_tr_thumb_width_height").css('display', '');
			jQuery("#"+wd_fb_prefix+"_tr_thumb_comments").css('display', '');
			jQuery("#"+wd_fb_prefix+"_tr_thumb_likes").css('display', '');
			jQuery("#"+wd_fb_prefix+"_tr_thumb_name").css('display', '');
      break;

    }
    case 'thumbnails_masonry': {
      if (jQuery("input[name="+wd_fb_prefix+"_masonry_hor_ver]:checked").val() == 'horizontal') {
        bwg_change_label(wd_fb_prefix+'_image_max_columns_label', 'Number of image rows: ');
        bwg_change_label(wd_fb_prefix+'_thumb_width_height_label', 'Image thumbnail height: ');
        jQuery('#'+wd_fb_prefix+'_thumb_width').hide();
        jQuery('#'+wd_fb_prefix+'_thumb_height').show();
      }
      else {
        bwg_change_label(wd_fb_prefix+'_image_max_columns_label', 'Max. number of image columns: ');
        bwg_change_label(wd_fb_prefix+'_thumb_width_height_label', 'Image thumbnail width: ');
        jQuery('#'+wd_fb_prefix+'_thumb_width').show();
        jQuery('#'+wd_fb_prefix+'_thumb_height').hide();
				jQuery("#"+wd_fb_prefix+"_tr_thumb_name").css('display', '');
      }
      jQuery("#"+wd_fb_prefix+"_tr_masonry_hor_ver").css('display', '');
      jQuery('#'+wd_fb_prefix+'_thumb_width_height_separator').hide();
      jQuery("#"+wd_fb_prefix+"_tr_image_max_columns").css('display', '');
      jQuery("#"+wd_fb_prefix+"_tr_thumb_width_height").css('display', '');
			jQuery("#"+wd_fb_prefix+"_tr_thumb_comments").css('display', '');
			jQuery("#"+wd_fb_prefix+"_tr_thumb_likes").css('display', '');
			/*jQuery("#"+wd_fb_prefix+"_tr_thumb_name").css('display', '');*/
      break;

    }

    case 'blog_style': {
      jQuery("#"+wd_fb_prefix+"_tr_blog_style_view_type").css('display', '');
      jQuery("#"+wd_fb_prefix+"_tr_blog_style_view_type").css('display', '');
      jQuery("#"+wd_fb_prefix+"_tr_blog_style_comments").css('display', '');
      jQuery("#"+wd_fb_prefix+"_tr_blog_style_likes").css('display', '');
		  jQuery("#"+wd_fb_prefix+"_tr_blog_style_message_desc").css('display', '');
      jQuery("#"+wd_fb_prefix+"_tr_blog_style_shares").css('display', '');
      jQuery("#"+wd_fb_prefix+"_tr_blog_style_shares_butt").css('display', '');
			jQuery("#"+wd_fb_prefix+"_tr_blog_style_width").css('display', '');      
			jQuery("#"+wd_fb_prefix+"_tr_blog_style_height").css('display', ''); 
			jQuery("#"+wd_fb_prefix+"_tr_blog_style_author").css('display', '');
			jQuery("#"+wd_fb_prefix+"_tr_blog_style_name").css('display', '');
			jQuery("#"+wd_fb_prefix+"_tr_blog_style_place_name").css('display', '');		
			jQuery("#"+wd_fb_prefix+"_tr_blog_style_facebook").css('display', '');
			jQuery("#"+wd_fb_prefix+"_tr_blog_style_twitter").css('display', '');
			jQuery("#"+wd_fb_prefix+"_tr_blog_style_google").css('display', '');			
      break;
    }
    
    case 'album_compact': {
      jQuery("#"+wd_fb_prefix+"_tr_compuct_album_column_number").css('display', '');
      jQuery("#"+wd_fb_prefix+"_tr_compuct_albums_per_page").css('display', '');
      jQuery("#"+wd_fb_prefix+"_tr_compuct_album_title_hover").css('display', '');
      jQuery("#"+wd_fb_prefix+"_tr_compuct_album_thumb_width_height").css('display', '');
      jQuery("#"+wd_fb_prefix+"_tr_compuct_album_image_column_number").css('display', '');
      jQuery("#"+wd_fb_prefix+"_tr_compuct_album_images_per_page").css('display', '');
      jQuery("#"+wd_fb_prefix+"_tr_compuct_album_image_title").css('display', '');
      jQuery("#"+wd_fb_prefix+"_tr_compuct_album_image_thumb_width_height").css('display', '');
    
      break;
    }
  }
	
	jQuery("#"+wd_fb_prefix+"_tr_fb_name").css('display', '');
	if(fb_type == "page")
		jQuery("#"+wd_fb_prefix+"_tr_fb_plugin").css('display', '');	
  jQuery("#"+wd_fb_prefix+"_tbody_popup").css('display', '');
  jQuery("#"+wd_fb_prefix+"_tr_popup_width_height").css('display', '');
  jQuery("#"+wd_fb_prefix+"_tr_popup_effect").css('display', '');
  jQuery("#"+wd_fb_prefix+"_tr_popup_interval").css('display', '');
	if(view_type != 'blog_style' && view_type != 'album_compact') { 
		jQuery("#"+wd_fb_prefix+"_tr_popup_enable_filmstrip").css('display', '');
		if (jQuery("input[name="+wd_fb_prefix+"_popup_enable_filmstrip]:checked").val() == 1) {
			bwg_enable_disable('', wd_fb_prefix+'_tr_popup_filmstrip_height', wd_fb_prefix+'_popup_filmstrip_yes');
		}
		else {
			bwg_enable_disable('none', wd_fb_prefix+'_tr_popup_filmstrip_height', wd_fb_prefix+'_popup_filmstrip_no');
		}
	}else {
		bwg_enable_disable('none', wd_fb_prefix+'_tr_popup_filmstrip_height', wd_fb_prefix+'_popup_filmstrip_no');
	}
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_ctrl_btn").css('display', '');
  if (jQuery("input[name="+wd_fb_prefix+"_popup_enable_ctrl_btn]:checked").val() == 1) {
    bwg_enable_disable('', wd_fb_prefix+'_tbody_popup_ctrl_btn', wd_fb_prefix+'_popup_ctrl_btn_yes');
  }
  else {
    bwg_enable_disable('none', wd_fb_prefix+'_tbody_popup_ctrl_btn', wd_fb_prefix+'_popup_ctrl_btn_no');
  }
	jQuery("#"+wd_fb_prefix+"_tr_popup_author").css('display', '');
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_fullscreen").css('display', '');
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_info_btn").css('display', '');
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_facebook").css('display', '');
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_twitter").css('display', '');
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_google").css('display', '');
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_pinterest").css('display', '');
  jQuery("#"+wd_fb_prefix+"_tr_popup_enable_tumblr").css('display', '');
	if(fb_content_type == "timeline") {
		jQuery("#"+wd_fb_prefix+"_tr_popup_name").css('display', '');
		jQuery("#"+wd_fb_prefix+"_tr_popup_place_name").css('display', '');
		jQuery("#"+wd_fb_prefix+"_tr_popup_comments").css('display', '');
		jQuery("#"+wd_fb_prefix+"_tr_popup_likes").css('display', '');
		jQuery("#"+wd_fb_prefix+"_tr_popup_shares").css('display', '');
		jQuery("#"+wd_fb_prefix+"_tr_popup_message_desc").css('display', '');
	}
	else {
		switch(fb_content) {
			case "photos":
			case "videos":
			case "albums":
			  jQuery("#"+wd_fb_prefix+"_tr_popup_name").css('display', '');
				jQuery("#"+wd_fb_prefix+"_tr_popup_comments").css('display', '');
				jQuery("#"+wd_fb_prefix+"_tr_popup_likes").css('display', '');
        jQuery("#"+wd_fb_prefix+"_tr_popup_message_desc").css('display', '');				
			break;
			case "events":
			  jQuery("#"+wd_fb_prefix+"_tr_popup_name").css('display', '');
			  jQuery("#"+wd_fb_prefix+"_tr_popup_comments").css('display', '');
				jQuery("#"+wd_fb_prefix+"_tr_popup_message_desc").css('display', '');
				jQuery("#"+wd_fb_prefix+"_tr_popup_place_name").css('display', '');
			break;
		}
	} 
  bwg_popup_fullscreen();
}

function bwg_onKeyDown(e) {
  var e = e || window.event;
  var chCode1 = e.which || e.paramlist_keyCode;
  if (chCode1 != 37 && chCode1 != 38 && chCode1 != 39 && chCode1 != 40) {
    if ((!e.ctrlKey && !e.metaKey) || (chCode1 != 86 && chCode1 != 67 && chCode1 != 65 && chCode1 != 88)) {
      e.preventDefault();
    }
  }
}
