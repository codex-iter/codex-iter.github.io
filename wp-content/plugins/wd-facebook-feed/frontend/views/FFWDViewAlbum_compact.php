<?php

class FFWDViewAlbum_compact {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $model;


  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct($model) {
    $this->model = $model;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function display($params, $from_shortcode = 0, $ffwd = 0) {
    global $wp;
    $current_url = $wp->query_string;
    require_once(WD_FFWD_DIR . '/framework/WDW_FFWD_Library.php');
    //$options_row = $this->model->get_ffwd_options();
    $from = (isset($ffwd_info['from']) ? esc_html($ffwd_info['from']) : 0);
    $type = (isset($_REQUEST['type_' . $ffwd]) ? esc_html($_REQUEST['type_' . $ffwd]) : (isset($ffwd_info['type']) ? $ffwd_info['type'] : 'albums'));
    // $bwg_search = ((isset($_POST['bwg_search_' . $ffwd]) && esc_html($_POST['bwg_search_' . $ffwd]) != '') ? esc_html($_POST['bwg_search_' . $ffwd]) : '');
    $sort_direction = ' ASC ';

    $ffwd_info = $this->model->get_ffwd_info($params['fb_id']);
    if ($ffwd_info == NULL || $ffwd_info["success"] === false) {
      echo WDW_FFWD_Library::message(__('There is no facebook feed selected or it was deleted.', 'ffwd'), 'error');
      return;
    }
    if(isset($params['from']) and $params['from']=='widget')
    {
    $ffwd_info['objects_per_page']= $params['objects_per_page'];
    $ffwd_info['theme']= $params['theme_id'];
    $ffwd_info['album_image_thumb_width']= $params['thumb_width'];
    $ffwd_info['thumb_height']= $params['album_image_thumb_height'];

    }



    $theme_row = $this->model->get_theme_row_data($ffwd_info['theme']);
    if (!$theme_row) {
      echo WDW_FFWD_Library::message(__('There is no theme selected or the theme was deleted.', 'ffwd'), 'error');
      return;
    }
    $ffwd_data = $this->model->get_ffwd_data($params['fb_id'], $ffwd_info['objects_per_page'], /*$ffwd_info['sort_by']*/'', $ffwd, /*$sort_direction*/ ' ASC', $ffwd_info['pagination_type']);
    $ffwd_objects_count = count($ffwd_data);
    if ($ffwd_info == NULL) {
      echo WDW_FFWD_Library::message(__('There is no facebook feed selected or it was deleted.', 'ffwd'), 'error');
      return;
    }

    $album_id = (isset($_REQUEST['album_id_' . $ffwd]) ? esc_html($_REQUEST['album_id_' . $ffwd]) : 0);
		if ($type == 'gallery') {
      $items_col_num = $ffwd_info['album_image_max_columns'];
      $album_gallery_div_id = 'ffwd_album_compact_' . $ffwd;
      $album_gallery_div_class = 'ffwd_standart_thumbnails_' . $ffwd;

			$form_child_div_style = 'background-color:rgba(0, 0, 0, 0); position:relative; text-align:' . $theme_row->thumb_align . '; width:100%;';
			$form_child_div_id = '';
    }
    else {
      $items_per_page = $ffwd_info['objects_per_page'];
      $items_col_num = $ffwd_info['album_max_columns'];
      if ($ffwd_info['pagination_type'] && $ffwd_info['objects_per_page']) {
        $page_nav = $this->model->page_nav($ffwd_info['id'], $items_per_page, $ffwd);
      }
      $album_gallery_div_id = 'ffwd_album_compact_' . $ffwd;
      $album_gallery_div_class = 'ffwd_album_compact_' . $ffwd;

			$form_child_div_id = '';
      $form_child_div_style = 'background-color:rgba(0, 0, 0, 0); position:relative; text-align:' . $theme_row->album_compact_thumb_align . '; width:100%;';

    }
    $ffwd_previous_album_id = (isset($_REQUEST['ffwd_previous_album_id_' . $ffwd]) ? esc_html($_REQUEST['ffwd_previous_album_id_' . $ffwd]) : 0);
    $album_page_number_ = (isset($_REQUEST['album_page_number_' . $ffwd]) ? esc_html($_REQUEST['album_page_number_' . $ffwd]) : 0);

    $rgb_page_nav_font_color = WDW_FFWD_Library::spider_hex2rgb($theme_row->page_nav_font_color);
    $rgb_album_compact_thumbs_bg_color = WDW_FFWD_Library::spider_hex2rgb($theme_row->album_compact_thumbs_bg_color);
    $rgb_thumbs_bg_color = WDW_FFWD_Library::spider_hex2rgb($theme_row->thumbs_bg_color);

    $ffwd_info_array = array(
      'action' => 'PopupBox',
      'fb_id' => $params['fb_id'],
      'current_view' => $ffwd,
      'theme_id' => $ffwd_info['theme'],
      'thumb_width' => $ffwd_info['album_thumb_width'],
      'thumb_height' => $ffwd_info['album_thumb_height'],
      'open_with_fullscreen' => $ffwd_info['popup_fullscreen'],
      'open_with_autoplay' => $ffwd_info['popup_autoplay'],
      'image_width' => $ffwd_info['popup_width'],
      'image_height' => $ffwd_info['popup_height'],
      'image_effect' => $ffwd_info['popup_effect'],
      'enable_image_filmstrip' => $ffwd_info['popup_enable_filmstrip'],
      'image_filmstrip_height' => $ffwd_info['popup_filmstrip_height'],

      'enable_comments' => $ffwd_info['popup_comments'],
      'enable_likes' => $ffwd_info['popup_likes'],
      'enable_shares' => $ffwd_info['popup_shares'],
      'enable_author' => $ffwd_info['popup_author'],
      'enable_name' => $ffwd_info['popup_name'],
      'enable_place_name' => $ffwd_info['popup_place_name'],
      'enable_message_desc' => $ffwd_info['popup_message_desc'],

      'enable_image_ctrl_btn' => $ffwd_info['popup_enable_ctrl_btn'],
      'enable_image_fullscreen' => $ffwd_info['popup_enable_fullscreen'],
      'enable_object_info' => $ffwd_info['popup_enable_info_btn'],
      'slideshow_interval' => $ffwd_info['popup_interval'],
      'enable_image_facebook' => $ffwd_info['popup_enable_facebook'],
      'enable_image_twitter' => $ffwd_info['popup_enable_twitter'],
      'enable_image_google' => $ffwd_info['popup_enable_google'],
      'current_url' => $current_url
    );
    $ffwd_info_array_hash = $ffwd_info_array;
    ?>
    <style>
      /* Style for album thumbnail view.*/
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_album_thumb_<?php echo $ffwd; ?> {
        display: inline-block;
        text-align: center;
        vertical-align: top;
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .<?php echo $album_gallery_div_class; ?> * {
        -moz-box-sizing: content-box;
        box-sizing: content-box;
      }

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_page_plugin_<?php echo $ffwd; ?>{
				margin: 30px 0px 30px 0px;
				background-color: rgba(0, 0, 0, 0);
				text-align: <?php echo $theme_row->album_compact_thumb_align; ?>;
				width: 100%;
				position: relative;
      }
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_album_header_container_<?php echo $ffwd; ?> {
				display: inline-block;
				width: 100%;
				max-width: 100%;
				text-align: <?php echo $theme_row->blog_style_fd_name_align ?>;
				padding: <?php echo $theme_row->blog_style_fd_name_padding ?>px;
				box-sizing: border-box;
				background-color: #<?php echo $theme_row->blog_style_fd_name_bg_color ?>;
				margin: 25px 0px 0px 0px;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_album_header_container_<?php echo $ffwd; ?> .ffwd_album_header_<?php echo $ffwd; ?> {
				display: inline-block;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				font-size: <?php echo $theme_row->blog_style_fd_name_size ?>px;
				font-weight: <?php echo $theme_row->blog_style_fd_name_font_weight ?>;
				color: #<?php echo $theme_row->blog_style_fd_name_color ?>;
				margin: 0px 0px 0px 5px;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_album_header_container_<?php echo $ffwd; ?> .ffwd_album_header_icon_<?php echo $ffwd; ?> {
				font-size: <?php echo $theme_row->blog_style_fd_icon_size ?>px;
				color: #<?php echo $theme_row->blog_style_fd_icon_color ?>;
				vertical-align: middle;
				font-family: FontAwesome
			}
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_album_thumb_spun1_<?php echo $ffwd; ?> {
        background-color: #<?php echo $theme_row->album_compact_thumb_bg_color; ?>;
        display: inline-block;
        width: <?php echo $ffwd_info['album_thumb_width']; ?>px;
        height: <?php echo $ffwd_info['album_thumb_height']; ?>px;
        margin: <?php echo $theme_row->album_compact_thumb_margin; ?>px;
        opacity: <?php echo number_format($theme_row->album_compact_thumb_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->album_compact_thumb_transparent; ?>);
        <?php echo ($theme_row->album_compact_thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
        padding: <?php echo $theme_row->album_compact_thumb_padding; ?>px;
        text-align: center;
        vertical-align: middle;
        z-index: 100;
        -webkit-backface-visibility: visible;
        -ms-backface-visibility: visible;
      }

      .ffwd_album_thumb_<?php echo $ffwd; ?>
      {
          background-color: #<?php echo $theme_row->album_compact_thumb_bg_color; ?>;
          opacity: <?php echo number_format($theme_row->album_compact_thumb_transparent / 100, 2, ".", ""); ?>;
          margin: 2px;
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_album_thumb_spun1_<?php echo $ffwd; ?>:hover {
        opacity: 1;
        filter: Alpha(opacity=100);
        transform: <?php echo $theme_row->album_compact_thumb_hover_effect; ?>(<?php echo $theme_row->album_compact_thumb_hover_effect_value; ?>);
        -ms-transform: <?php echo $theme_row->album_compact_thumb_hover_effect; ?>(<?php echo $theme_row->album_compact_thumb_hover_effect_value; ?>);
        -webkit-transform: <?php echo $theme_row->album_compact_thumb_hover_effect; ?>(<?php echo $theme_row->album_compact_thumb_hover_effect_value; ?>);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        z-index: 102;
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_album_thumb_spun2_<?php echo $ffwd; ?> {
        border-radius: <?php echo $theme_row->album_compact_thumb_border_radius; ?>;
        border: <?php echo $theme_row->album_compact_thumb_border_width; ?>px <?php echo $theme_row->album_compact_thumb_border_style; ?> #<?php echo $theme_row->album_compact_thumb_border_color; ?>;
        box-shadow: <?php echo $theme_row->album_compact_thumb_box_shadow; ?>;
        display: inline-block;
        overflow: hidden;
        height: <?php echo $ffwd_info['album_thumb_height']; ?>px;
        width: <?php echo $ffwd_info['album_thumb_width']; ?>px;
      }

      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_album_thumb_spun2_<?php echo $ffwd; ?> > img {
        border-radius: 0px !important;
        box-shadow: none !important;
        border-style: none !important;
        padding: 0 !important;
        max-height: none !important;
        max-width: none !important;
        width: <?php echo $ffwd_info['album_thumb_width']; ?>px;
        height:<?php echo $ffwd_info['album_thumb_height']; ?>px;
      }

      <?php
      if ($ffwd_info['album_title'] == 'show') { /* Show album/gallery title at the bottom.*/
        ?>
        #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_title_spun1_<?php echo $ffwd; ?> {
          display: block;
          opacity: 1;
          filter: Alpha(opacity=100);
          text-align: center;
          width: <?php echo $ffwd_info['album_thumb_width']; ?>px;
        }
        <?php
      }
      elseif ($ffwd_info['album_title'] == 'hover') { /* Show album/gallery title on hover.*/
        ?>
        #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_title_spun1_<?php echo $ffwd; ?> {
          display: table;
          height: inherit;
          left: -3000px;
          opacity: 0;
          filter: Alpha(opacity=0);
          position: absolute;
          top: 0px;
          width: inherit;
        }
        <?php
      }
      ?>

		 .ffwd_standart_thumb_img_<?php echo $ffwd; ?> {
			 max-height: none !important;
			 max-width: none !important;
			 padding: 0 !important;
		 }

     #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_likes_comments_container_<?php echo $ffwd; ?> {
				display: <?php echo ($ffwd_info['album_image_thumb_width'] < 150) ? "none" : "block"; ?>;
				height: inherit;
				width: inherit;
				position: absolute;
				left: 0px;
				top: 0px;
				opacity: 0;
				filter: Alpha(opacity=0);
				transition: opacity 0.5s;
				background-color: rgba(0, 0, 0, 0.46);
				border-radius: <?php echo $theme_row->thumb_border_radius; ?>;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_likes_comments_container_<?php echo $ffwd; ?> .ffwd_likes_comments_container_tab_<?php echo $ffwd; ?> {
				display: table;
				height: inherit;
				width: inherit;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_likes_comments_container_<?php echo $ffwd; ?> .ffwd_likes_comments_container_tab_<?php echo $ffwd; ?> .ffwd_likes_comments_<?php echo $ffwd; ?> {
				display: table-cell;
				vertical-align : <?php echo $theme_row->thumb_like_comm_pos; ?>;
			}

      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_likes_<?php echo $ffwd; ?> {
        height: 30px;
        line-height: 30px;
        padding: 0px 0px 0px 22px;
        vertical-align: middle;
        word-wrap: break-word;
        float:left;
        background: url('<?php echo WD_FFWD_URL . '/images/feed/like_white.png' ?>') no-repeat left center;
        background-size: 14px;
        margin-left: 5px;
      }

      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_comments_<?php echo $ffwd; ?> {
        height: 30px;
        line-height: 30px;
        padding: 0px 0px 0px 26px;
        vertical-align: middle;
        word-wrap: break-word;
        float:right;
        background: url('<?php echo WD_FFWD_URL . '/images/feed/comment_white.png' ?>') no-repeat left center;
        background-size: 19px;
        margin-right: 5px;
      }

      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_shares_<?php echo $ffwd; ?> {
        height: 30px;
        line-height: 30px;
        padding: 0px 0px 0px 26px;
        vertical-align: middle;
        word-wrap: break-word;
        float:left;
        background: url('<?php echo WD_FFWD_URL . '/images/feed/share.png' ?>') no-repeat left center;
        background-size: 19px;
        margin-left: 25px;
      }

      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_comments_<?php echo $ffwd; ?> span, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_likes_<?php echo $ffwd; ?> span, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_shares_<?php echo $ffwd; ?> span {
        padding: 0px 3px 0px 3px;
        background-color: rgba(214, 214, 214, 0.07);
        border-radius: 0px;
				color: #<?php echo $theme_row->thumb_like_comm_font_color; ?>;
        font-family: <?php echo $theme_row->thumb_like_comm_font_style; ?>;
        font-size: <?php echo $theme_row->thumb_like_comm_font_size; ?>px;
				box-shadow: <?php echo $theme_row->thumb_like_comm_shadow; ?>;
				font-weight: <?php echo $theme_row->thumb_like_comm_font_weight; ?>;
      }

      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_album_thumb_spun1_<?php echo $ffwd; ?>:hover .ffwd_title_spun1_<?php echo $ffwd; ?> {
        left: <?php echo $theme_row->album_compact_thumb_padding; ?>px;
        top: <?php echo $theme_row->album_compact_thumb_padding; ?>px;
        opacity: 1;
        filter: Alpha(opacity=100);
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_title_spun2_<?php echo $ffwd; ?> {
        color: #<?php echo $theme_row->album_compact_title_font_color; ?>;
        display: table-cell;
        font-family: <?php echo $theme_row->album_compact_title_font_style; ?>;
        font-size: <?php echo $theme_row->album_compact_title_font_size; ?>px;
        font-weight: <?php echo $theme_row->album_compact_title_font_weight; ?>;
        height: inherit;
        padding: <?php echo $theme_row->album_compact_title_margin; ?>;
        text-shadow: <?php echo $theme_row->album_compact_title_shadow; ?>;
        vertical-align: middle;
        width: inherit;
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_album_compact_<?php echo $ffwd; ?> {
        display: inline-block;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        background-color: rgba(<?php echo $rgb_album_compact_thumbs_bg_color['red']; ?>, <?php echo $rgb_album_compact_thumbs_bg_color['green']; ?>, <?php echo $rgb_album_compact_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->album_compact_thumb_bg_transparent / 100, 2, ".", ""); ?>);
        font-size: 0;
        text-align: <?php echo $theme_row->album_compact_thumb_align; ?>;
        max-width: <?php echo $items_col_num * ($ffwd_info['album_thumb_width'] + 2 * (2 + $theme_row->album_compact_thumb_margin + $theme_row->album_compact_thumb_padding + $theme_row->album_compact_thumb_border_width)); ?>px;
      }
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_album_compact_<?php echo $ffwd; ?> a {
				cursor: pointer;
				outline: none;
				border-style: none;
			}
      /*Image thumbs styles.*/
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_standart_thumb_spun1_<?php echo $ffwd; ?> {
        background-color: #<?php echo $theme_row->thumb_bg_color; ?>;
        display: inline-block;
        position: relative;
        height: <?php echo $ffwd_info['album_image_thumb_height']; ?>px;
        width: <?php echo $ffwd_info['album_image_thumb_width']; ?>px;
        margin: <?php echo $theme_row->thumb_margin; ?>px;
        opacity: <?php echo number_format($theme_row->thumb_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->thumb_transparent; ?>);
        <?php echo ($theme_row->thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
        padding: <?php echo $theme_row->thumb_padding; ?>px;
        text-align: center;
        vertical-align: middle;
        z-index: 100;
				border-radius: <?php echo $theme_row->thumb_border_radius; ?>;
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_standart_thumb_spun1_<?php echo $ffwd; ?>:hover {
        -ms-transform: <?php echo $theme_row->thumb_hover_effect; ?>(<?php echo $theme_row->thumb_hover_effect_value; ?>);
        -webkit-transform: <?php echo $theme_row->thumb_hover_effect; ?>(<?php echo $theme_row->thumb_hover_effect_value; ?>);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        opacity: 1;
        filter: Alpha(opacity=100);
        transform: <?php echo $theme_row->thumb_hover_effect; ?>(<?php echo $theme_row->thumb_hover_effect_value; ?>);
        z-index: 102;
        position: relative;
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_standart_thumb_spun2_<?php echo $ffwd; ?> {
        border-radius: <?php echo $theme_row->thumb_border_radius; ?>;
        border: <?php echo $theme_row->thumb_border_width; ?>px <?php echo $theme_row->thumb_border_style; ?> #<?php echo $theme_row->thumb_border_color; ?>;
        box-shadow: <?php echo $theme_row->thumb_box_shadow; ?>;
        height: <?php echo $ffwd_info['album_image_thumb_height']; ?>px;
        width: <?php echo $ffwd_info['album_image_thumb_width']; ?>px;
        overflow: hidden;
				position: relative;
				display: inline-block;
      }

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_standart_thumb_spun2_<?php echo $ffwd; ?>:hover .ffwd_likes_comments_container_<?php echo $ffwd; ?> {
				opacity: 1;
			}

      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_standart_thumbnails_<?php echo $ffwd; ?> {
        -moz-box-sizing: border-box;
        display: inline-block;
        background-color: rgba(<?php echo $rgb_thumbs_bg_color['red']; ?>, <?php echo $rgb_thumbs_bg_color['green']; ?>, <?php echo $rgb_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->thumb_bg_transparent / 100, 2, ".", ""); ?>);
        box-sizing: border-box;
        font-size: 0;
        max-width: <?php echo $ffwd_info['album_image_max_columns'] * ($ffwd_info['album_image_thumb_width'] + 2 * (2 + $theme_row->thumb_margin + $theme_row->thumb_padding + $theme_row->thumb_border_width)); ?>px;
        text-align: <?php echo $theme_row->thumb_align; ?>;
      }

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_standart_thumbnails_<?php echo $ffwd; ?> a {
        outline: none;
				border-style: none;
			}

      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_standart_thumb_<?php echo $ffwd; ?> {
        display: inline-block;
        text-align: center;
        position: relative;
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_standart_thumb_spun1_<?php echo $ffwd; ?>:hover .ffwd_image_title_spun1_<?php echo $ffwd; ?> {
        left: <?php echo $theme_row->thumb_padding; ?>px;
        top: <?php echo $theme_row->thumb_padding; ?>px;
        opacity: 1;
        filter: Alpha(opacity=100);
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .bwg_image_title_spun2_<?php echo $ffwd; ?> {
        color: #<?php echo $theme_row->thumb_title_font_color; ?>;
        display: table-cell;
        font-family: <?php echo $theme_row->thumb_title_font_style; ?>;
        font-size: <?php echo $theme_row->thumb_title_font_size; ?>px;
        font-weight: <?php echo $theme_row->thumb_title_font_weight; ?>;
        height: inherit;
        margin: <?php echo $theme_row->thumb_title_margin; ?>;
        text-shadow: <?php echo $theme_row->thumb_title_shadow; ?>;
        vertical-align: middle;
        width: inherit;
        word-wrap: break-word;
      }
      /*Pagination styles.*/
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .tablenav-pages_<?php echo $ffwd; ?> {
        text-align: <?php echo $theme_row->page_nav_align; ?>;
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        margin: 6px 0 4px;
        display: block;
        height: 30px;
        line-height: 30px;
      }
      @media only screen and (max-width : 320px) {
        #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .displaying-num_<?php echo $ffwd; ?> {
          display: none;
        }
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .displaying-num_<?php echo $ffwd; ?> {
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        margin-right: 10px;
        vertical-align: middle;
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .paging-input_<?php echo $ffwd; ?> {
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        vertical-align: middle;
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .tablenav-pages_<?php echo $ffwd; ?> a.disabled,
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .tablenav-pages_<?php echo $ffwd; ?> a.disabled:hover,
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .tablenav-pages_<?php echo $ffwd; ?> a.disabled:focus {
        cursor: default;
        color: rgba(<?php echo $rgb_page_nav_font_color['red']; ?>, <?php echo $rgb_page_nav_font_color['green']; ?>, <?php echo $rgb_page_nav_font_color['blue']; ?>, 0.5);
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .tablenav-pages_<?php echo $ffwd; ?> a {
        cursor: pointer;
        font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php echo $theme_row->page_nav_font_color; ?>;
        text-decoration: none;
        padding: <?php echo $theme_row->page_nav_padding; ?>;
        margin: <?php echo $theme_row->page_nav_margin; ?>;
        border-radius: <?php echo $theme_row->page_nav_border_radius; ?>;
        border-style: <?php echo $theme_row->page_nav_border_style; ?>;
        border-width: <?php echo $theme_row->page_nav_border_width; ?>px;
        border-color: #<?php echo $theme_row->page_nav_border_color; ?>;
        background-color: #<?php echo $theme_row->page_nav_button_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->page_nav_button_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->page_nav_button_bg_transparent; ?>);
        box-shadow: <?php echo $theme_row->page_nav_box_shadow; ?>;
        <?php echo ($theme_row->page_nav_button_transition ) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> a.bwg_back_<?php echo $ffwd; ?> {
        background-color: rgba(0, 0, 0, 0);
        color: #<?php echo $theme_row->album_compact_back_font_color; ?> !important;
        cursor: pointer;
        display: block;
        font-family: <?php echo $theme_row->album_compact_back_font_style; ?>;
        font-size: <?php echo $theme_row->album_compact_back_font_size; ?>px;
        font-weight: <?php echo $theme_row->album_compact_back_font_weight; ?>;
        text-decoration: none;
        padding: <?php echo $theme_row->album_compact_back_padding; ?>;
				outline:none;
				border-style:none;
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> #spider_popup_overlay_<?php echo $ffwd; ?> {
        background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }
			#ajax_loading_<?php echo $ffwd; ?> {
				position:absolute;
				width: 100%;
				z-index: 115;
				text-align: center;
				height: 100%;
				vertical-align: middle;
			}
			#ajax_loading_tab_<?php echo $ffwd; ?> {
				display: table;
				vertical-align: middle;
				width: 100%;
				height: 100%;
				background-color: #FFFFFF;
				opacity: 0.7;
				filter: Alpha(opacity=70);
			}
			#ajax_loading_tab_cell_<?php echo $ffwd; ?> {
				display: table-cell;
				text-align: center;
				position: relative;
				vertical-align: middle;
			}
			#loading_div_<?php echo $ffwd; ?> {
				display: inline-block;
				text-align:center;
				position:relative;
				vertical-align: middle;
			}
      .bwg_play_icon_spun_<?php echo $ffwd; ?>	 {
        width: inherit;
        height: inherit;
        display: table;
        position: absolute;
      }
     .bwg_play_icon_<?php echo $ffwd; ?> {
        color: #<?php echo $theme_row->thumb_title_font_color; ?>;
        font-size: <?php echo 2 * $theme_row->thumb_title_font_size; ?>px;
        vertical-align: middle;
        display: table-cell !important;
        z-index: 1;
        text-align: center;
        margin: 0 auto;
      }
    </style>
    <script>
      var ffwd_dataaa_<?php echo $ffwd; ?> = [];
			var ffwd_album_info_<?php echo $ffwd; ?> = {};
			    ffwd_album_info_<?php echo $ffwd; ?>.album_thumb_width = parseInt('<?php echo $ffwd_info['album_thumb_width'] ?>');
			    ffwd_album_info_<?php echo $ffwd; ?>.album_thumb_height = parseInt('<?php echo $ffwd_info['album_thumb_height'] ?>');
			    ffwd_album_info_<?php echo $ffwd; ?>.thumb_width = parseInt('<?php echo $ffwd_info['album_image_thumb_width'] ?>');
			    ffwd_album_info_<?php echo $ffwd; ?>.thumb_height = parseInt('<?php echo $ffwd_info['album_image_thumb_height'] ?>');
			    ffwd_album_info_<?php echo $ffwd; ?>.data = [];
    </script>
		<div id="fb-root"></div>
		<script>(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
        <?php
        if(isset($ffwd_info["fb_plugin"]) && $ffwd_info["fb_plugin"] === "1"){
        ?>
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=1509500312675877";
        <?php
        }
        ?>
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		</script>
    <div id="ffwd_container1_<?php echo $ffwd; ?>">
      <div id="ffwd_container2_<?php echo $ffwd; ?>">
				<?php if($ffwd_info['type'] == "page" && $ffwd_info["fb_plugin"] && $ffwd_info['page_plugin_pos'] == "top") { ?>
					<div class="ffwd_page_plugin_<?php echo $ffwd; ?>">
						<div class="fb-page" data-href="https://www.facebook.com/<?php echo $ffwd_info['from']; ?>" data-width="<?php echo $ffwd_info['page_plugin_width']; ?>" data-small-header="<?php echo $options_row->page_plugin_header; ?>" data-adapt-container-width="true" data-hide-cover="<?php echo $options_row->page_plugin_cover; ?>" data-show-facepile="<?php echo $options_row->page_plugin_fans; ?>" data-show-posts="false">
							<div class="fb-xfbml-parse-ignore">
							</div>
						</div>
					</div>
				<?php } ?>
				<form id="ffwd_front_form_<?php echo $ffwd; ?>" method="post" action="#">
          <div id="<?php echo $form_child_div_id; ?>" style="<?php echo $form_child_div_style; ?>">
            <?php if($ffwd_info["fb_name"]) { ?>
							<div class="ffwd_album_header_container_<?php echo $ffwd; ?>">
								<i class="ffwd_album_header_icon_<?php echo $ffwd; ?> <?php echo $theme_row->blog_style_fd_icon; ?>"></i>
								<div class="ffwd_album_header_<?php echo $ffwd; ?>">
									<?php echo $ffwd_info['name']; ?>
								</div>
							</div>
						<?php } ?>
						<div id="ajax_loading_<?php echo $ffwd; ?>" style="display:none;">
              <div id="ajax_loading_tab_<?php echo $ffwd; ?>" >
                <div id="ajax_loading_tab_cell_<?php echo $ffwd; ?>" >
                  <div id="loading_div_<?php echo $ffwd; ?>">
                    <img src="<?php echo WD_FFWD_URL . '/images/ajax_loader.png'; ?>" class="spider_ajax_loading" style="float: none; width:50px;">
                  </div>
                </div>
              </div>
            </div>
						<?php
							if ($type != 'gallery' && $ffwd_info['pagination_type'] && $items_per_page && ($theme_row->page_nav_position == 'top') && $page_nav['total']) {
								WDW_FFWD_Library::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'ffwd_front_form_' . $ffwd, $items_per_page, $ffwd, $album_gallery_div_id, '', $type, /*$options_row->enable_seo*/true, $ffwd_info['pagination_type']);
							}
              if ($type == 'gallery') {
                ?>
                <a class="bwg_back_<?php echo $ffwd; ?>" onclick="ffwd_frontend_ajax('ffwd_front_form_<?php echo $ffwd; ?>', '<?php echo $ffwd; ?>', '', 'back', '', 'album')"><?php echo __('Back', 'ffwd'); ?></a>                   <?php
              }
            ?>
            <div id="<?php echo $album_gallery_div_id; ?>" class="<?php echo $album_gallery_div_class; ?>" >
              <input type="hidden" id="album_page_number_<?php echo $ffwd; ?>" name="album_page_number_<?php echo $ffwd; ?>" value="<?php echo $album_page_number_; ?>" />
              <?php
              if ($type != 'gallery') {
                if (isset($page_nav) && !$page_nav['total']) {
                  ?>
                  <span class="bwg_back_<?php echo $ffwd; ?>"><?php echo __('Album is empty.', 'ffwd'); ?></span>
                  <?php
                }
                foreach ($ffwd_data as $ffwd_data_row) {
                  $title = $ffwd_data_row->name;
                  ?>
                  <a class="ffwd_album_<?php echo $ffwd; ?>" ffwd_object_id="<?php echo $ffwd_data_row->object_id; ?>">
                    <span class="ffwd_album_thumb_<?php echo $ffwd; ?>">
                      <?php
                      if ($ffwd_info['album_title'] == 'show' && $theme_row->album_compact_thumb_title_pos == 'top') {
                        ?>
                        <span class="ffwd_title_spun1_<?php echo $ffwd; ?>">
                          <span class="ffwd_title_spun2_<?php echo $ffwd; ?>">
                            <?php echo $title; ?>
                          </span>
                        </span>
                        <?php
                      }
                      ?>
                      <span class="ffwd_album_thumb_spun1_<?php echo $ffwd; ?>">
                        <span class="ffwd_album_thumb_spun2_<?php echo $ffwd; ?>">
                          <img id="ffwd_album_cover_<?php echo $ffwd_data_row->id; ?>_<?php echo $ffwd; ?>" alt="<?php echo $title; ?>" />
                          <?php
                          if ($ffwd_info['album_title'] == 'hover') {
                            ?>
                            <span class="ffwd_title_spun1_<?php echo $ffwd; ?>">
                              <span class="ffwd_title_spun2_<?php echo $ffwd; ?>">
                                <?php echo $title; ?>
                              </span>
                            </span>
                            <?php
                          }
                          ?>
                        </span>
                      </span>
                      <?php
                      if ($ffwd_info['album_title'] == 'show' && $theme_row->album_compact_thumb_title_pos == 'bottom') {
                        ?>
                        <span class="ffwd_title_spun1_<?php echo $ffwd; ?>">
                          <span class="ffwd_title_spun2_<?php echo $ffwd; ?>">
                            <?php echo $title; ?>
                          </span>
                        </span>
                        <?php
                      }
                      ?>
                    </span>
                  </a>
                  <?php
                }
								?>
								<script>
									var id_object_id_<?php echo $ffwd; ?> = '<?php echo json_encode($this->model->id_object_id_json); ?>',
											graph_url_album_compact_<?php echo $ffwd; ?> = '<?php echo $this->model->graph_url; ?>';
											ffwd_fill_thum_srs_likes_compact_album(JSON.parse(id_object_id_<?php echo $ffwd; ?>), '<?php echo $ffwd; ?>', graph_url_album_compact_<?php echo $ffwd; ?>, ffwd_album_info_<?php echo $ffwd; ?>);
								</script>
								<?php
              }
              elseif ($type == 'gallery') {

                ?>
                <div id="ffwd_gallery_<?php echo $ffwd;?>" ></div>
                <script>
                  var album_id = '<?php echo $album_id; ?>',
											graph_url_album_compact_<?php echo $ffwd; ?> = '<?php echo $this->model->graph_url; ?>';
											ffwd_fill_thum_srs_likes_compact_album(album_id, '<?php echo $ffwd; ?>', graph_url_album_compact_<?php echo $ffwd; ?>, ffwd_album_info_<?php echo $ffwd; ?>,'<?php echo $ffwd_info['image_onclick_action'];  ?>');
                </script>
              <?php
            }
            ?>
            </div>
						<?php
							if ($type != 'gallery' && $ffwd_info['pagination_type'] && $items_per_page && ($theme_row->page_nav_position == 'bottom') && $page_nav['total']) {
								WDW_FFWD_Library::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'ffwd_front_form_' . $ffwd, $items_per_page, $ffwd, $album_gallery_div_id, '', $type, /*$options_row->enable_seo*/true, $ffwd_info['pagination_type']);
							}
						?>
          </div>
        </form>
				<?php if($ffwd_info['type'] == "page" && $ffwd_info["fb_plugin"] && $ffwd_info['page_plugin_pos'] == "bottom") { ?>
					<div class="ffwd_page_plugin_<?php echo $ffwd; ?>">
						<div class="fb-page" data-href="https://www.facebook.com/<?php echo $ffwd_info['from']; ?>" data-width="<?php echo $ffwd_info['page_plugin_width']; ?>" data-small-header="<?php echo $ffwd_info['page_plugin_header']; ?>" data-adapt-container-width="true" data-hide-cover="<?php echo $ffwd_info['page_plugin_cover']; ?>" data-show-facepile="<?php echo $ffwd_info['page_plugin_fans']; ?>" data-show-posts="false">
							<div class="fb-xfbml-parse-ignore">
							</div>
						</div>
					</div>
				<?php } ?>
        <div id="spider_popup_loading_<?php echo $ffwd; ?>" class="spider_popup_loading"></div>
        <div id="spider_popup_overlay_<?php echo $ffwd; ?>" class="spider_popup_overlay" onclick="ffwd_destroypopup(1000)"></div>
      </div>
    </div>
    <script>
      function ffwd_info_box_<?php echo $ffwd; ?>(image_id) {
        ffwd_createpopup('<?php echo addslashes(add_query_arg($ffwd_info_array, admin_url('admin-ajax.php'))); ?>&image_id=' + image_id + '&ffwd_album=' + JSON.stringify(ffwd_album_info_<?php echo $ffwd; ?>["data"]), '<?php echo $ffwd; ?>', '<?php echo $ffwd_info['popup_width']; ?>', '<?php echo $ffwd_info['popup_height']; ?>', 1, 'testpopup', 5);
      }
      function ffwd_document_ready_<?php echo $ffwd; ?>() {

        <?php if($ffwd_info['image_onclick_action']=='lightbox') { ?>
        jQuery('body').on("click", ".ffwd_lightbox_<?php echo $ffwd; ?>", function () {
          ffwd_info_box_<?php echo $ffwd; ?>(jQuery(this).attr("data-image-id"));
          return false;
        });
  <?php  } ?>

        jQuery(".ffwd_album_<?php echo $ffwd; ?>").on("click", function () {
           ffwd_frontend_ajax('ffwd_front_form_<?php echo $ffwd; ?>', '<?php echo $ffwd; ?>', 'ffwd_album_compact_<?php echo $ffwd; ?>', jQuery(this).attr("ffwd_object_id"), '<?php echo $album_id; ?>', 'gallery', '', 'title', 'default');
           return false;
        });
      }
      jQuery(document).ready(function () {
        ffwd_document_ready_<?php echo $ffwd; ?>();
      });
    </script>
    <?php
    if ($from_shortcode) {
      return;
    }
    else {
      die();
    }
  }

  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}
