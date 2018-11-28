function spider_ajax_save(wd_fb_prefix) {
  var current_id = jQuery("#current_id").val(),
			published = jQuery("input[name=published]:checked").val();
    fb_type = jQuery("#" + wd_fb_prefix + "_type").val();
    switch (fb_type) {
        case 'profile' :
        case 'group' :
            alert('Facebook API has been changed recently, and now it does not allow bringing posts from public groups')
            return false;
            break;

    }

  /* Get params for facebook call */
  var page_access_token = jQuery("#page_access_token").val();
  var ffwd_nonce = jQuery("#ffwd_nonce").val(),
			name = jQuery("#name").val(),

			update_mode = jQuery("input[name=update_mode]:checked").val(),

			content_url = '',
			exist_access = 1,
			content_type = jQuery("input[name="+wd_fb_prefix+"_content_type]:checked").val(),
			timeline_type = jQuery("select[name="+wd_fb_prefix+"_timeline_type]").val(),
			content = [],
			limit = jQuery("#"+wd_fb_prefix+"_limit").val(),
			access_token = jQuery("#"+wd_fb_prefix+"_access_token").val();
      //////////////////////////////
      theme = jQuery("#"+wd_fb_prefix+"_theme").val()
      image_max_columns = jQuery("#"+wd_fb_prefix+"_image_max_columns").val()
      thumb_width = jQuery("#"+wd_fb_prefix+"_thumb_width").val()
      thumb_height = jQuery("#"+wd_fb_prefix+"_thumb_height").val()
      blog_style_width = jQuery("#"+wd_fb_prefix+"_blog_style_width").val()
      blog_style_height = jQuery("#"+wd_fb_prefix+"_blog_style_height").val()
      pagination_type = jQuery("#"+wd_fb_prefix+"_pagination_type").val()
      objects_per_page = jQuery("#"+wd_fb_prefix+"_objects_per_page").val()
      album_max_columns = jQuery("#"+wd_fb_prefix+"_compuct_album_column_number").val()
      album_thumb_width = jQuery("#"+wd_fb_prefix+"_compuct_album_thumb_width").val()
      album_thumb_height = jQuery("#"+wd_fb_prefix+"_compuct_album_thumb_height").val()
      album_image_max_columns = jQuery("#"+wd_fb_prefix+"_compuct_album_image_column_number").val()
      album_image_thumb_width = jQuery("#"+wd_fb_prefix+"_compuct_album_image_thumb_width").val()
      album_image_thumb_height = jQuery("#"+wd_fb_prefix+"_compuct_album_image_thumb_height").val()
      popup_width = jQuery("#"+wd_fb_prefix+"_popup_width").val()
      popup_height = jQuery("#"+wd_fb_prefix+"_popup_height").val()
      popup_effect = jQuery("#"+wd_fb_prefix+"_popup_effect").val()
      popup_interval = jQuery("#"+wd_fb_prefix+"_popup_interval").val()
      fb_view_type = jQuery("#"+wd_fb_prefix+"_fb_view_type").val()


    masonry_hor_ver = jQuery("input[name="+wd_fb_prefix+"_masonry_hor_ver]:checked").val()
    thumb_comments = jQuery("input[name="+wd_fb_prefix+"_thumb_comments]:checked").val()
    thumb_likes = jQuery("input[name="+wd_fb_prefix+"_thumb_likes]:checked").val()
    thumb_name = jQuery("input[name="+wd_fb_prefix+"_thumb_name]:checked").val()
    blog_style_view_type = jQuery("input[name="+wd_fb_prefix+"_blog_style_view_type]:checked").val()
    blog_style_comments = jQuery("input[name="+wd_fb_prefix+"_blog_style_comments]:checked").val()
    blog_style_likes = jQuery("input[name="+wd_fb_prefix+"_blog_style_likes]:checked").val()
    blog_style_message_desc = jQuery("input[name="+wd_fb_prefix+"_blog_style_message_desc]:checked").val()
    blog_style_shares = jQuery("input[name="+wd_fb_prefix+"_blog_style_shares]:checked").val()
    blog_style_shares_butt = jQuery("input[name="+wd_fb_prefix+"_blog_style_shares_butt]:checked").val()
    blog_style_facebook = jQuery("input[name="+wd_fb_prefix+"_blog_style_facebook]:checked").val()
    blog_style_twitter = jQuery("input[name="+wd_fb_prefix+"_blog_style_twitter]:checked").val()
    blog_style_google = jQuery("input[name="+wd_fb_prefix+"_blog_style_google]:checked").val()
    blog_style_author = jQuery("input[name="+wd_fb_prefix+"_blog_style_author]:checked").val()
    blog_style_name = jQuery("input[name="+wd_fb_prefix+"_blog_style_name]:checked").val()
    blog_style_place_name = jQuery("input[name="+wd_fb_prefix+"_blog_style_place_name]:checked").val()
    fb_name = jQuery("input[name="+wd_fb_prefix+"_fb_name]:checked").val()
    fb_plugin = jQuery("input[name="+wd_fb_prefix+"_fb_plugin]:checked").val()
    album_title = jQuery("input[name="+wd_fb_prefix+"_compuct_album_title]:checked").val()
    popup_fullscreen = jQuery("input[name="+wd_fb_prefix+"_popup_fullscreen]:checked").val()
    popup_autoplay = jQuery("input[name="+wd_fb_prefix+"_popup_autoplay]:checked").val()
    open_commentbox = jQuery("input[name="+wd_fb_prefix+"_open_commentbox]:checked").val()
    popup_enable_filmstrip = jQuery("input[name="+wd_fb_prefix+"_popup_enable_filmstrip]:checked").val()
    popup_filmstrip_height = jQuery("#"+wd_fb_prefix+"_popup_filmstrip_height").val()
    popup_comments = jQuery("input[name="+wd_fb_prefix+"_popup_comments]:checked").val()
    popup_likes = jQuery("input[name="+wd_fb_prefix+"_popup_likes]:checked").val()
    popup_shares = jQuery("input[name="+wd_fb_prefix+"_popup_shares]:checked").val()
    popup_author = jQuery("input[name="+wd_fb_prefix+"_popup_author]:checked").val()
    popup_name = jQuery("input[name="+wd_fb_prefix+"_popup_name]:checked").val()
    popup_place_name = jQuery("input[name="+wd_fb_prefix+"_popup_place_name]:checked").val()
    popup_enable_ctrl_btn = jQuery("input[name="+wd_fb_prefix+"_popup_enable_ctrl_btn]:checked").val()
    popup_enable_fullscreen = jQuery("input[name="+wd_fb_prefix+"_popup_enable_fullscreen]:checked").val()
    popup_enable_info_btn = jQuery("input[name="+wd_fb_prefix+"_popup_enable_info]:checked").val()
    popup_message_desc = jQuery("input[name="+wd_fb_prefix+"_popup_message_desc]:checked").val()
    popup_enable_facebook = jQuery("input[name="+wd_fb_prefix+"_popup_enable_facebook]:checked").val()
    popup_enable_twitter = jQuery("input[name="+wd_fb_prefix+"_popup_enable_twitter]:checked").val()
    popup_enable_google = jQuery("input[name="+wd_fb_prefix+"_popup_enable_google]:checked").val()

    view_on_fb=jQuery("input[name="+wd_fb_prefix+"_view_on_fb]:checked").val()
    post_text_length=jQuery("#"+wd_fb_prefix+"_post_text_length").val()
    post_date_format=jQuery("#"+wd_fb_prefix+"_post_date_format").val()
    date_timezone=jQuery("#"+wd_fb_prefix+"_date_timezone").val()
    event_street=jQuery("input[name="+wd_fb_prefix+"_event_street]:checked").val()
    event_city="0"
    event_country="0"
    event_zip="0"
    event_map="0"
    event_date=jQuery("input[name="+wd_fb_prefix+"_event_date]:checked").val()
    event_date_format=jQuery("#"+wd_fb_prefix+"_event_date_format").val()
    event_desp_length=jQuery("#"+wd_fb_prefix+"_event_desp_length").val()
    comments_replies=jQuery("input[name="+wd_fb_prefix+"_comments_replies]:checked").val()
    comments_order=jQuery("input[name="+wd_fb_prefix+"_comments_order]:checked").val()
    comments_filter=jQuery("input[name="+wd_fb_prefix+"_comments_filter]:checked").val()
    page_plugin_pos=jQuery("input[name="+wd_fb_prefix+"_page_plugin_pos]:checked").val()
    page_plugin_fans=jQuery("input[name="+wd_fb_prefix+"_page_plugin_fans]:checked").val()
    page_plugin_cover=jQuery("input[name="+wd_fb_prefix+"_page_plugin_cover]:checked").val()
    page_plugin_header=jQuery("input[name="+wd_fb_prefix+"_page_plugin_header]:checked").val()
    page_plugin_width=jQuery("#"+wd_fb_prefix+"_page_plugin_width").val()

    image_onclick_action=jQuery("input[name="+wd_fb_prefix+"_image_onclick_action]:checked").val()



  switch(fb_type) {
    case 'page' :
			content_url = jQuery("#"+wd_fb_prefix+"_page_url").val();
			exist_access = (jQuery("#"+wd_fb_prefix+"_page_exist_access_tok").is(':checked')) ? 1 : 0;
		break;
		case 'group' :
			content_url = jQuery("#"+wd_fb_prefix+"_group_url").val();
			exist_access = (jQuery("#"+wd_fb_prefix+"_group_exist_access_tok").is(':checked')) ? 1 : 0;
		break;
		/*case 'profile' :
		break;*/
		default:
		break;
  }

  /* If content type is specific choose type of post to show */
  if(content_type == 'specific') {
    content.push(jQuery("input[name="+wd_fb_prefix+"_specific]:checked").val());
  }
  /* If content type is timeline choose what post type to include in content */
  else {
    if (jQuery("#"+wd_fb_prefix+"_timeline_statuses").is(':checked')) content.push(jQuery("#"+wd_fb_prefix+"_timeline_statuses").val());
		if (jQuery("#"+wd_fb_prefix+"_timeline_photos").is(':checked')) content.push(jQuery("#"+wd_fb_prefix+"_timeline_photos").val());
		if (jQuery("#"+wd_fb_prefix+"_timeline_videos").is(':checked')) content.push(jQuery("#"+wd_fb_prefix+"_timeline_videos").val());
		if (jQuery("#"+wd_fb_prefix+"_timeline_links").is(':checked'))  content.push(jQuery("#"+wd_fb_prefix+"_timeline_links").val());
		if (jQuery("#"+wd_fb_prefix+"_timeline_events").is(':checked')) content.push(jQuery("#"+wd_fb_prefix+"_timeline_events").val());
  }
  var data = {};
			data["action"] = 'save_facebook_feed';
			data["current_id"] = current_id;
			data["published"] = published;
			data["ffwd_nonce"] = ffwd_nonce;
			data["name"] = name;
            data["page_access_token"] = page_access_token;
            data["fb_page_id"] = jQuery('#fb_page_id').val();
			data["fb_type"] = fb_type;
			data["content_url"] = content_url;
			data["content_type"] = content_type;
			data["timeline_type"] = timeline_type;
			data["content"] = content;
			data["limit"] = limit;
			data["exist_access"] = exist_access;
			data["access_token"] = access_token;
			data["update_mode"] = update_mode;

      data["theme"] = theme;
      data["image_max_columns"] = image_max_columns;
      data["thumb_width"] = thumb_width;
      data["thumb_height"] = thumb_height;
      data["blog_style_width"] = blog_style_width;
      data["blog_style_height"] = blog_style_height;
      data["pagination_type"] = pagination_type;
      data["objects_per_page"] = objects_per_page;
      data["album_max_columns"] = album_max_columns;
      data["album_thumb_width"] = album_thumb_width;
      data["album_thumb_height"] = album_thumb_height;
      data["album_image_max_columns"] = album_image_max_columns;
      data["album_image_thumb_width"] = album_image_thumb_width;
      data["album_image_thumb_height"] = album_image_thumb_height;
      data["popup_width"] = popup_width;
      data["popup_height"] = popup_height;
      data["popup_effect"] = popup_effect;
      data["popup_interval"] = popup_interval;
      data["fb_view_type"] = fb_view_type;

data["masonry_hor_ver"] = masonry_hor_ver;
data["thumb_comments"] = thumb_comments;
data["thumb_likes"] = thumb_likes;
data["thumb_name"] = thumb_name;
data["blog_style_view_type"] = blog_style_view_type;
data["blog_style_comments"] = blog_style_comments;
data["blog_style_likes"] = blog_style_likes;
data["blog_style_message_desc"] = blog_style_message_desc;
data["blog_style_shares"] = blog_style_shares;
data["blog_style_shares_butt"] = blog_style_shares_butt;
data["blog_style_facebook"] = blog_style_facebook;
data["blog_style_twitter"] = blog_style_twitter;
data["blog_style_google"] = blog_style_google;
data["blog_style_author"] = blog_style_author;
data["blog_style_name"] = blog_style_name;
data["blog_style_place_name"] = blog_style_place_name;
data["fb_name"] = fb_name;
data["fb_plugin"] = fb_plugin;
data["album_title"] = album_title;
data["popup_fullscreen"] = popup_fullscreen;
data["popup_autoplay"] = popup_autoplay;
data["open_commentbox"] = open_commentbox;
data["popup_enable_filmstrip"] = popup_enable_filmstrip;
data["popup_filmstrip_height"] = popup_filmstrip_height;
data["popup_comments"] = popup_comments;
data["popup_likes"] = popup_likes;
data["popup_shares"] = popup_shares;
data["popup_author"] = popup_author;
data["popup_name"] = popup_name;
data["popup_place_name"] = popup_place_name;
data["popup_enable_ctrl_btn"] = popup_enable_ctrl_btn;
data["popup_enable_fullscreen"] = popup_enable_fullscreen;
data["popup_enable_info_btn"] = popup_enable_info_btn;
data["popup_message_desc"] = popup_message_desc;
data["popup_enable_facebook"] = popup_enable_facebook;
data["popup_enable_twitter"] = popup_enable_twitter;
data["popup_enable_google"] = popup_enable_google;



data['view_on_fb']=view_on_fb
data['post_text_length']=post_text_length
data['post_date_format']=post_date_format
data['date_timezone']=date_timezone
data['event_street']=event_street
data['event_city']=event_city
data['event_country']=event_country
data['event_zip']=event_zip
data['event_map']=event_map
data['event_date']=event_date
data['event_date_format']=event_date_format
data['event_desp_length']=event_desp_length
data['comments_replies']=comments_replies
data['comments_filter']=comments_filter
data['comments_order']=comments_order
data['page_plugin_pos']=page_plugin_pos
data['page_plugin_fans']=page_plugin_fans
data['page_plugin_cover']=page_plugin_cover
data['page_plugin_header']=page_plugin_header
data['page_plugin_width']=page_plugin_width
data['image_onclick_action']=image_onclick_action

  // Loading.
  jQuery('#opacity_div').show();
  jQuery('#loading_div').show();

  jQuery.ajax({
    method: "POST",
    url: ajax_url,
		data: data,
		success: function(result){
			var task = jQuery("#task").val();
			console.log(result);
			result = JSON.parse(result);
			if(result[0] == "success") {
				if(current_id == 0) {
					jQuery("#current_id").val(result[1]);
				}
				switch(task) {
					case "apply":
						jQuery("#task").val("");
						jQuery('#message_div').html("<strong><p>Items Succesfully Saved.</p></strong>");
						jQuery('#message_div').show();
						jQuery('#ffwd_page_url,#page_access_token,#name').removeAttr("style");
					break;
					case "save":
						jQuery("#ffwd_info_form").submit();
					break;
					default:
					  jQuery("#task").val("");
					break;
				}
			}
			else {
				confirm(result[0] + " : " + result[1] + ".");
			}
		  jQuery('#opacity_div').hide();
		  jQuery('#loading_div').hide();
	  }
  });


  //jQuery("#ffwd_info_form").ajaxSubmit({url: ajax_url, type: 'post'});
  return false;
}


function choose_fb_type(wd_fb_prefix, value) {
  jQuery('#'+wd_fb_prefix+'_type_page').hide();
  jQuery('#'+wd_fb_prefix+'_type_group').hide();
  jQuery('#'+wd_fb_prefix+'_type_profile').hide();
  jQuery('#'+wd_fb_prefix+'_type_' + value).show();
  jQuery('#'+wd_fb_prefix+'_content_type').show();
  jQuery('.'+wd_fb_prefix+'_views_c').show();
  if(value == 'group') {
		jQuery('#'+wd_fb_prefix+'_specific_events').css('display', 'inline-block').next().css('display', 'inline-block');
    jQuery('#'+wd_fb_prefix+'_timeline_events').css('display', 'inline-block').next().css('display', 'inline-block');

    jQuery('#'+wd_fb_prefix+'_content_type_specific').css('display', 'none');
	  jQuery('#'+wd_fb_prefix+'_content_specific').css('display', 'none').next().css('display', 'none');
      jQuery('#'+wd_fb_prefix+'_content_type_timeline_type').css('display', 'none');
	  choose_fb_content_type(wd_fb_prefix, 'timeline');
  }
	else if(value == 'profile') {
    jQuery('#'+wd_fb_prefix+'_specific_events').css('display', 'none').next().css('display', 'none');

		jQuery('#'+wd_fb_prefix+'_content_type_specific').css('display', 'table-row');
	  jQuery('#'+wd_fb_prefix+'_content_specific').css('display', 'inline-block').next().css('display', 'inline-block');
      jQuery('#'+wd_fb_prefix+'_content_type_timeline_type').css('display', '');
	  choose_fb_content_type(wd_fb_prefix, 'specific');
	}
  else {
		jQuery('#'+wd_fb_prefix+'_specific_events').css('display', 'inline-block').next().css('display', 'inline-block');
    jQuery('#'+wd_fb_prefix+'_timeline_events').css('display', 'inline-block').next().css('display', 'inline-block');

    jQuery('#'+wd_fb_prefix+'_content_type_specific').css('display', 'table-row');
	  jQuery('#'+wd_fb_prefix+'_content_specific').css('display', 'inline-block').next().css('display', 'inline-block');
      jQuery('#'+wd_fb_prefix+'_content_type_timeline_type').css('display', '');
	  choose_fb_content_type(wd_fb_prefix, 'timeline');
  }

}

function choose_fb_content_type(wd_fb_prefix, value) {
  value = 'timeline';//only free
  jQuery('#'+wd_fb_prefix+'_content_type_specific').hide();
  jQuery('#'+wd_fb_prefix+'_content_type_timeline').hide();
  jQuery('#'+wd_fb_prefix+'_content_type_timeline_type').hide();

  jQuery('#'+wd_fb_prefix+'_content_'+value).attr('checked', 'checked');
  jQuery('#'+wd_fb_prefix+'_content_type_'+value).show();

    if (jQuery('#ffwd_type').val() == 'group') {
        jQuery('#ffwd_profile_warning').hide();
        jQuery('#ffwd_group_warning').show();
    }
    else if (jQuery('#ffwd_type').val() == 'profile') {
        jQuery('#ffwd_profile_warning').show();
        jQuery('#ffwd_group_warning').hide();
    } else {
        jQuery('#ffwd_profile_warning').hide();
        jQuery('#ffwd_group_warning').hide();

    }


    if (jQuery('#ffwd_type').val() != 'group') {
        jQuery('#' + wd_fb_prefix + '_content_type_' + value + '_type').show();
    }
if(  jQuery('#'+wd_fb_prefix+'_content_timeline').prop('checked'))
{

jQuery('.ffwd_blog_style').css('display','');
jQuery('.ffwd_thumbnails').css('display','none');
jQuery('.ffwd_thumbnails_masonry').css('display','none');
jQuery('.ffwd_album_compact').css('display','none');
jQuery('#ffwd_tab_events').css('display','');
ffwd_view_type(wd_fb_prefix, 'blog_style', jQuery('.'+wd_fb_prefix+'_blog_style'))

}

if(jQuery('#'+wd_fb_prefix+'_content_specific').prop('checked'))
{
//ffwd_blog_style
switch (jQuery('input[name="ffwd_specific"]:checked').val()) {
  case 'albums':
  jQuery('.ffwd_blog_style').css('display','none');
  jQuery('.ffwd_thumbnails').css('display','none');
  jQuery('.ffwd_thumbnails_masonry').css('display','none');
  jQuery('.ffwd_album_compact').css('display','');
  jQuery('#ffwd_tab_events').css('display','none');

  if(!jQuery('.ffwd_album_compact ').hasClass('ffwd_view_s'))
  {
    ffwd_view_type(wd_fb_prefix, 'album_compact', jQuery('.'+wd_fb_prefix+'album_compact'))
    jQuery('.ffwd_album_compact').find('input[type="radio"]').attr('checked','checked');

    jQuery('.ffwd_album_compact ').addClass('ffwd_view_s')

  }

  break;

  case 'events':
  jQuery('.ffwd_blog_style').css('display','');
  jQuery('.ffwd_thumbnails').css('display','');
  jQuery('.ffwd_thumbnails_masonry').css('display','none');
  jQuery('.ffwd_album_compact').css('display','none');
  jQuery('#ffwd_tab_events').css('display','');

  if(!jQuery('.ffwd_blog_style ').hasClass('ffwd_view_s') && !jQuery('.ffwd_thumbnails ').hasClass('ffwd_view_s'))
  {
    ffwd_view_type(wd_fb_prefix, 'thumbnails', jQuery('.'+wd_fb_prefix+'thumbnails'))
    jQuery('.ffwd_thumbnails').find('input[type="radio"]').attr('checked','checked');

    jQuery('.ffwd_thumbnails ').addClass('ffwd_view_s')

  }



  break;

  case 'videos':
  case 'photos':
  jQuery('.ffwd_blog_style').css('display','none');
  jQuery('.ffwd_thumbnails').css('display','');
  jQuery('.ffwd_thumbnails_masonry').css('display','');
  jQuery('.ffwd_album_compact').css('display','none');
  jQuery('#ffwd_tab_events').css('display','none');

  if(!jQuery('.ffwd_thumbnails_masonry ').hasClass('ffwd_view_s') && !jQuery('.ffwd_thumbnails ').hasClass('ffwd_view_s'))
  {
    ffwd_view_type(wd_fb_prefix, 'thumbnails', jQuery('.'+wd_fb_prefix+'thumbnails'))
    jQuery('.ffwd_thumbnails ').addClass('ffwd_view_s')
    jQuery('.ffwd_thumbnails').find('input[type="radio"]').attr('checked','checked');


  }


  break;


  default:

}



}


}




function check_app(wd_fb_prefix) {
    var app_id = jQuery('#' + wd_fb_prefix + '_app_id').val(),
        app_secret = jQuery('#' + wd_fb_prefix + '_app_secret').val();

	if((app_id_first == "" || app_secret_first == "")  && app_id != "" && app_secret != ""){
		localStorage.setItem('show_menus', "1");
	}
}


function bwg_change_option_type(type) {
  type = (type == '' ? 1 : type);
  document.getElementById('type').value = type;
	var count = jQuery('.gallery_type').length;
  for (var i = 1; i <= count; i++) {
    if (i == type) {
      document.getElementById('div_content_' + i).style.display = 'block';
      document.getElementById('div_' + i).style.background = '#C5C5C5';
    }
    else {
      document.getElementById('div_content_' + i).style.display = 'none';
      document.getElementById('div_' + i).style.background = '#F4F4F4';
    }
  }
  document.getElementById('display_panel').style.display = 'inline-block';
}

function spider_run_checkbox() {
  jQuery("tbody").children().children(".check-column").find(":checkbox").click(function (l) {
    if ("undefined" == l.shiftKey) {
      return true
    }
    if (l.shiftKey) {
      if (!i) {
        return true
      }
      d = jQuery(i).closest("form").find(":checkbox");
      f = d.index(i);
      j = d.index(this);
      h = jQuery(this).prop("checked");
      if (0 < f && 0 < j && f != j) {
        d.slice(f, j).prop("checked", function () {
          if (jQuery(this).closest("tr").is(":visible")) {
            return h
          }
          return false
        })
      }
    }
    i = this;
    var k = jQuery(this).closest("tbody").find(":checkbox").filter(":visible").not(":checked");
    jQuery(this).closest("table").children("thead, tfoot").find(":checkbox").prop("checked", function () {
      return(0 == k.length)
    });
    return true
  });
  jQuery("thead, tfoot").find(".check-column :checkbox").click(function (m) {
    var n = jQuery(this).prop("checked"), l = "undefined" == typeof toggleWithKeyboard ? false : toggleWithKeyboard, k = m.shiftKey || l;
    jQuery(this).closest("table").children("tbody").filter(":visible").children().children(".check-column").find(":checkbox").prop("checked", function () {
      if (jQuery(this).is(":hidden")) {
        return false
      }
      if (k) {
        return jQuery(this).prop("checked")
      } else {
        if (n) {
          return true
        }
      }
      return false
    });
    jQuery(this).closest("table").children("thead,  tfoot").filter(":visible").children().children(".check-column").find(":checkbox").prop("checked", function () {
      if (k) {
        return false
      } else {
        if (n) {
          return true
        }
      }
      return false
    })
  });
}

// Set value by id.
function spider_set_input_value(input_id, input_value) {
  if (document.getElementById(input_id)) {
    document.getElementById(input_id).value = input_value;
  }
}

// Submit form by id.
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

// Check if required field is empty.
function spider_check_required(id, name) {
  if (jQuery('#' + id).val() == '') {
    alert(name + '* field is required.');
    jQuery('#' + id).attr('style', 'border-color: #FF0000;');
    jQuery('#' + id).focus();
    jQuery('html, body').animate({
      scrollTop:jQuery('#' + id).offset().top - 200
    }, 500);
    return true;
  }
  else {
    return false;
  }
}

// Show/hide order column and drag and drop column.
function spider_show_hide_weights() {
  if (jQuery("#show_hide_weights").val() == 'Show order column') {
    jQuery(".connectedSortable").css("cursor", "default");
    jQuery("#tbody_arr").find(".handle").hide(0);
    jQuery("#th_order").show(0);
    jQuery("#tbody_arr").find(".spider_order").show(0);
    jQuery("#show_hide_weights").val("Hide order column");
    if (jQuery("#tbody_arr").sortable()) {
      jQuery("#tbody_arr").sortable("disable");
    }
  }
  else {
    jQuery(".connectedSortable").css("cursor", "move");
    var page_number;
    if (jQuery("#page_number") && jQuery("#page_number").val() != '' && jQuery("#page_number").val() != 1) {
      page_number = (jQuery("#page_number").val() - 1) * 20 + 1;
    }
    else {
      page_number = 1;
    }
    jQuery("#tbody_arr").sortable({
      handle:".connectedSortable",
      connectWith:".connectedSortable",
      update:function (event, tr) {
        jQuery("#draganddrop").attr("style", "");
        jQuery("#draganddrop").html("<strong><p>Changes made in this table should be saved.</p></strong>");
        var i = page_number;
        jQuery('.spider_order').each(function (e) {
          if (jQuery(this).find('input').val()) {
            jQuery(this).find('input').val(i++);
          }
        });
      }
    });//.disableSelection();
    jQuery("#tbody_arr").sortable("enable");
    jQuery("#tbody_arr").find(".handle").show(0);
    jQuery("#tbody_arr").find(".handle").attr('class', 'handle connectedSortable');
    jQuery("#th_order").hide(0);
    jQuery("#tbody_arr").find(".spider_order").hide(0);
    jQuery("#show_hide_weights").val("Show order column");
  }
}

// Check all items.
function spider_check_all_items() {
  spider_check_all_items_checkbox();
  // if (!jQuery('#check_all').attr('checked')) {
    jQuery('#check_all').trigger('click');
  // }
}
function spider_check_all_items_checkbox() {
  if (jQuery('#check_all_items').attr('checked')) {
    jQuery('#check_all_items').attr('checked', false);
    jQuery('#draganddrop').hide();
  }
  else {
    var saved_items = (parseInt(jQuery(".displaying-num").html()) ? parseInt(jQuery(".displaying-num").html()) : 0);
    var added_items = (jQuery('input[id^="check_pr_"]').length ? parseInt(jQuery('input[id^="check_pr_"]').length) : 0);
    var items_count = added_items + saved_items;
    jQuery('#check_all_items').attr('checked', true);
    if (items_count) {
      jQuery('#draganddrop').html("<strong><p>Selected " + items_count + " item" + (items_count > 1 ? "s" : "") + ".</p></strong>");
      jQuery('#draganddrop').show();
    }
  }
}

function spider_check_all(current) {
  if (!jQuery(current).attr('checked')) {
    jQuery('#check_all_items').attr('checked', false);
    jQuery('#draganddrop').hide();
  }
}

// Set uploader to button class.
function spider_uploader(button_id, input_id, delete_id, img_id) {
  if (typeof img_id == 'undefined') {
    img_id = '';
  }
  jQuery(function () {
    var formfield = null;
    window.original_send_to_editor = window.send_to_editor;
    window.send_to_editor = function (html) {
      if (formfield) {
        var fileurl = jQuery('img', html).attr('src');
        if (!fileurl) {
          var exploded_html;
          var exploded_html_askofen;
          exploded_html = html.split('"');
          for (i = 0; i < exploded_html.length; i++) {
            exploded_html_askofen = exploded_html[i].split("'");
          }
          for (i = 0; i < exploded_html.length; i++) {
            for (j = 0; j < exploded_html_askofen.length; j++) {
              if (exploded_html_askofen[j].search("href")) {
                fileurl = exploded_html_askofen[i + 1];
                break;
              }
            }
          }
          if (img_id != '') {
            alert('You must select an image file.');
            tb_remove();
            return;
          }
          window.parent.document.getElementById(input_id).value = fileurl;
          window.parent.document.getElementById(button_id).style.display = "none";
          window.parent.document.getElementById(input_id).style.display = "inline-block";
          window.parent.document.getElementById(delete_id).style.display = "inline-block";
        }
        else {
          if (img_id == '') {
            alert('You must select an audio file.');
            tb_remove();
            return;
          }
          window.parent.document.getElementById(input_id).value = fileurl;
          window.parent.document.getElementById(button_id).style.display = "none";
          window.parent.document.getElementById(delete_id).style.display = "inline-block";
          if ((img_id != '') && window.parent.document.getElementById(img_id)) {
            window.parent.document.getElementById(img_id).src = fileurl;
            window.parent.document.getElementById(img_id).style.display = "inline-block";
          }
        }
        formfield.val(fileurl);
        tb_remove();
      }
      else {
        window.original_send_to_editor(html);
      }
      formfield = null;
    };
    formfield = jQuery(this).parent().parent().find(".url_input");
    tb_show('', 'media-upload.php?type=image&TB_iframe=true');
    jQuery('#TB_overlay,#TB_closeWindowButton').bind("click", function () {
      formfield = null;
    });
    return false;
  });
}

// Remove uploaded file.
function spider_remove_url(button_id, input_id, delete_id, img_id) {
  if (typeof img_id == 'undefined') {
    img_id = '';
  }
  if (document.getElementById(button_id)) {
    document.getElementById(button_id).style.display = '';
  }
  if (document.getElementById(input_id)) {
    document.getElementById(input_id).value = '';
    document.getElementById(input_id).style.display = 'none';
  }
  if (document.getElementById(delete_id)) {
    document.getElementById(delete_id).style.display = 'none';
  }
  if ((img_id != '') && window.parent.document.getElementById(img_id)) {
    document.getElementById(img_id).src = '';
    document.getElementById(img_id).style.display = 'none';
  }
}

function spider_reorder_items(tbody_id) {
  jQuery("#" + tbody_id).sortable({
    handle:".connectedSortable",
    connectWith:".connectedSortable",
    update:function (event, tr) {
      spider_sortt(tbody_id);
    }
  });
}

function spider_sortt(tbody_id) {
  var str = "";
  var counter = 0;
  jQuery("#" + tbody_id).children().each(function () {
    str += ((jQuery(this).attr("id")).substr(3) + ",");
    counter++;
  });
  jQuery("#albums_galleries").val(str);
  if (!counter) {
    document.getElementById("table_albums_galleries").style.display = "none";
  }
}

function spider_remove_row(tbody_id, event, obj) {
  var span = obj;
  var tr = jQuery(span).closest("tr");
  jQuery(tr).remove();
  spider_sortt(tbody_id);
}

function spider_jslider(idtaginp) {
  jQuery(function () {
    var inpvalue = jQuery("#" + idtaginp).val();
    if (inpvalue == "") {
      inpvalue = 50;
    }
    jQuery("#slider-" + idtaginp).slider({
      range:"min",
      value:inpvalue,
      min:1,
      max:100,
      slide:function (event, ui) {
        jQuery("#" + idtaginp).val("" + ui.value);
      }
    });
    jQuery("#" + idtaginp).val("" + jQuery("#slider-" + idtaginp).slider("value"));
  });
}

function bwg_check_checkboxes() {
  var flag = false;
  var ids_string = jQuery("#ids_string").val();
  ids_array = ids_string.split(",");
  for (var i in ids_array) {
    if (ids_array.hasOwnProperty(i) && ids_array[i]) {
      if (jQuery("#check_" + ids_array[i]).attr('checked') == 'checked') {
        flag = true;
      }
	}
  }
  if(flag) {
    if(jQuery(".buttons_div_right").find("a").hasClass( "thickbox" )) {
      return true;
	}
	else {
	  jQuery(".buttons_div_right").find("a").addClass( "thickbox thickbox-preview" );
	  jQuery('#draganddrop').hide();
	  return true;
	}
  }
  else {
	jQuery(".buttons_div_right").find("a").removeClass( "thickbox thickbox-preview" );
    jQuery('#draganddrop').html("<strong><p>You must select at least one item.</p></strong>");
    jQuery('#draganddrop').show();
	return false;
  }
}

function bwg_inputs() {
  jQuery(".spider_int_input").keypress(function (event) {
    var chCode1 = event.which || event.paramlist_keyCode;
    if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57) && (chCode1 != 46) && (chCode1 != 45)) {
      return false;
    }
    return true;
  });
}

function bwg_enable_disable(display, id, current) {
  jQuery("#" + current).attr('checked', 'checked');
  jQuery("#" + id).css('display', display);
  if(id == 'tr_slideshow_title_position') {
    jQuery("#tr_slideshow_full_width_title").css('display', display);
  }
}

function bwg_popup_fullscreen(num) {
  if (num) {
    jQuery("#ffwd_tr_popup_width_height").css('display', 'none');
  }
  else {
    jQuery("#ffwd_tr_popup_width_height").css('display', '');
  }
}

function bwg_change_album_view_type(type) {
  if (type == 'thumbnail') {
    jQuery("#album_thumb_dimensions").html('Album thumb dimensions: ');
	jQuery("#album_thumb_dimensions_x").css('display', '');
	jQuery("#album_thumb_height").css('display', '');
  }
  else {
    jQuery("#album_thumb_dimensions").html('Album thumb width: ');
    jQuery("#album_thumb_dimensions_x").css('display', 'none');
	jQuery("#album_thumb_height").css('display', 'none');
  }
}

function spider_check_isnum(e) {
  var chCode1 = e.which || e.paramlist_keyCode;
  if (chCode1 > 31 && (chCode1 < 48 || chCode1 > 57) && (chCode1 != 46) && (chCode1 != 45)) {
    return false;
  }
  return true;
}

function bwg_change_theme_type(type) {
  var button_name = jQuery("#button_name").val();
  jQuery("#Thumbnail").hide();
  jQuery("#Masonry").hide();
  jQuery("#Mosaic").hide();
  jQuery("#Slideshow").hide();
  jQuery("#Compact_album").hide();
  jQuery("#Extended_album").hide();
  jQuery("#Masonry_album").hide();
  jQuery("#Image_browser").hide();
  jQuery("#Blog_style").hide();
  jQuery("#Lightbox").hide();
  jQuery("#Navigation").hide();
  jQuery("#" + type).show();
  jQuery("#type_menu").show();
  jQuery(".spider_fieldset").show();
  jQuery("#current_type").val(type);

  jQuery("#type_Thumbnail").attr("style", "border: none;color:#23282D");
  jQuery("#type_Masonry").attr("style", "border: none;color:#23282D");
  jQuery("#type_Mosaic").attr("style", "border: none;color:#23282D");
  jQuery("#type_Slideshow").attr("style", "border: none;color:#23282D");
  jQuery("#type_Compact_album").attr("style", "border: none;color:#23282D");
  jQuery("#type_Extended_album").attr("style", "border: none;color:#23282D");
  jQuery("#type_Masonry_album").attr("style", "border: none;color:#23282D");
  jQuery("#type_Image_browser").attr("style", "border: none;color:#23282D");
  jQuery("#type_Blog_style").attr("style", "border: none;color:#23282D");
  jQuery("#type_Lightbox").attr("style", "border: none;color:#23282D");
  jQuery("#type_Navigation").attr("style", "border: none;color:#23282D");
  jQuery("#type_" + type).attr("style", "border-top: 1px solid #3B5A9A;border-left: 1px solid #3B5A9A;border-right: 1px solid #3B5A9A;color:#3b5a9a");
}

jQuery(document).ready(function () {
  jQuery(".ffwd_reset_cache").click(function (e) {
    jQuery(".ffwd_reset_cache_res").html("");
    e.preventDefault();
    jQuery.ajax({
      url: ffwd_ajax.ajaxurl,
      type: "POST",
      dataType: 'json',
      data: {
        action: 'ffwd_reset_cache',
        nonce: ffwd_ajax.ajaxnonce,
      },
      success: function (data)
      {
        if(data.success){
          jQuery(".ffwd_reset_cache_res").html("Success");
          jQuery(".ffwd_reset_cache_res").css({
            'color':'#0BF235'
          });
        }else{
          jQuery(".ffwd_reset_cache_res").html("Error");
          jQuery(".ffwd_reset_cache_res").css({
            'color':'#F30202'
          });
        }
      },
      error: function (data)
      {
        jQuery(".ffwd_reset_cache_res").html("Error");
        jQuery(".ffwd_reset_cache_res").css({
          'color':'#F30202'
        });
      }
    });

  });



  var selected_fb_type = jQuery("#ffwd_type").val();

  if(selected_fb_type !== "page"){
    jQuery("#page_access_token").closest("tr").css({
      'display':'none'
    });
  }

  jQuery("#ffwd_type").change(function () {
    var fb_type = jQuery("#ffwd_type").val();
    if(fb_type === "page"){
      jQuery("#page_access_token").closest("tr").css({
        'display':'table-row'
      });
    }else{
      jQuery("#page_access_token").closest("tr").css({
        'display':'none'
      });
    }
  });
  
   app_id_first = jQuery('#ffwd_app_id').val();
   app_secret_first = jQuery('#ffwd_app_secret').val();
   if(localStorage.getItem('show_menus') == "1"){
		localStorage.removeItem('show_menus');
		location.reload();
	}

    jQuery('.ffwd_login_button').on('click', function(e) {
        e.preventDefault();
        jQuery('#ffwd_login_popup').css('display', 'block');
        return false;
    });

    jQuery('#ffwd_login_popup_cancle_btn, #ffwd_login_popup').on('click', function () {
        jQuery('#ffwd_login_popup').css('display', 'none');
    });

    ffwd_fb_page_image();
    jQuery('#fb_page_id').on('change', ffwd_fb_page_image);

    function ffwd_fb_page_image(){
        var $select = jQuery('#fb_page_id');

        if($select.val() == ""){
            jQuery('#ffwd_page_img').css('display', 'none');
        }else{
            var src = "https://graph.facebook.com/" + $select.val() + "/picture/";
            jQuery('#ffwd_page_img').attr('src', src).css('display', 'inline-block');
        }
    }


});