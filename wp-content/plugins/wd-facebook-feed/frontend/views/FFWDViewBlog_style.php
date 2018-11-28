<?php

class FFWDViewBlog_style {
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

		$ffwd_info = $this->model->get_ffwd_info($params['fb_id']);



		if ($ffwd_info == NULL || $ffwd_info["success"] === false) {
			echo WDW_FFWD_Library::message(__('There is no facebook feed selected or it was deleted.', 'ffwd'), 'error');
			return;
		}


		if(isset($params['from']) and $params['from']=='widget')
		{
			$ffwd_info['objects_per_page']= $params['objects_per_page'];
			$ffwd_info['theme']= $params['theme_id'];
			$ffwd_info['blog_style_width']= $params['thumb_width'];
			$ffwd_info['blog_style_height']= $params['thumb_height'];

		}


		$theme_row = $this->model->get_theme_row_data($ffwd_info['theme']);
		if (!$theme_row) {
			echo WDW_FFWD_Library::message(__('There is no theme selected or the theme was deleted.', 'ffwd'), 'error');
			return;
		}

		$ffwd_data = $this->model->get_ffwd_data($params['fb_id'], $ffwd_info['objects_per_page'], /*$ffwd_info['sort_by']*/'', $ffwd, /*$order_by*/ 'DESC');
		if (!$ffwd_data) {
			echo WDW_FFWD_Library::message(__('There are no objects in this facebook feed.', 'ffwd'), 'error');
		}

		if ($ffwd_info['pagination_type'] && $ffwd_info['objects_per_page']) {
			$page_nav = $this->model->page_nav($params['fb_id'], $ffwd_info['objects_per_page'], $ffwd);
		}

		$rgb_page_nav_font_color = WDW_FFWD_Library::spider_hex2rgb($theme_row->page_nav_font_color);
		$options_row = $this->model->get_ffwd_options();
		$show_likes = (isset($ffwd_info['blog_style_likes']) && $ffwd_info['blog_style_likes'] == '1') ? true : false;
		$show_comments = (isset($ffwd_info['blog_style_comments']) && $ffwd_info['blog_style_comments'] == '1') ? true : false;
		$view_on_fb = isset($ffwd_info['view_on_fb']) ? $ffwd_info['view_on_fb'] : 1;
		$show_shares = (isset($ffwd_info['blog_style_shares']) && $ffwd_info['blog_style_shares'] == '1') ? true : false;
		$show_shares_butt = (isset($ffwd_info['blog_style_shares_butt']) && $ffwd_info['blog_style_shares_butt'] == '1') ? true : false;
		$blog_style_facebook = (isset($ffwd_info['blog_style_facebook']) && $ffwd_info['blog_style_facebook'] == '1') ? true : false;
		$blog_style_twitter = (isset($ffwd_info['blog_style_twitter']) && $ffwd_info['blog_style_twitter'] == '1') ? true : false;
		$blog_style_google = (isset($ffwd_info['blog_style_google']) && $ffwd_info['blog_style_google'] == '1') ? true : false;
		$message_desc = (isset($ffwd_info['blog_style_message_desc']) && $ffwd_info['blog_style_message_desc'] == '1') ? true : false;
		$ffwd_info_array = array(
			'action' => 'PopupBox',
			'current_view' => $ffwd,
			'fb_id' => $params['fb_id'],
			'theme_id' => $ffwd_info['theme'],
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
			'current_url' => urlencode($current_url)
		);

        $linkify=new \Misd\Linkify\Linkify();
		?>
		<style>
			#ffwd_container1_<?php echo $ffwd; ?> {
				margin: 0px 0px 10px 0px;
			}

			#ffwd_container1_<?php echo $ffwd; ?> a {
				box-shadow: none;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_objects_conteiner_<?php echo $ffwd; ?>{
				background-color: rgba(0, 0, 0, 0);
				text-align: <?php echo $theme_row->blog_style_align ?>;
				width: 100%;
				position: relative;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_page_plugin_<?php echo $ffwd; ?>{
				margin: 30px 0px 0px 0px;
				background-color: rgba(0, 0, 0, 0);
				text-align: <?php echo $theme_row->blog_style_align ?>;
				width: 100%;
				position: relative;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_objects_conteiner_<?php echo $ffwd; ?> .blog_style_objects_conteiner_1_<?php echo $ffwd; ?>{
				display: inline-block;
				-moz-box-sizing: border-box;
				box-sizing: border-box;
				max-width: 100%;
				width: <?php echo $ffwd_info['blog_style_width']; ?>px;
				text-align: left;
			<?php if($ffwd_info['blog_style_height'] != '') { ?>
				max-height: <?php echo $ffwd_info['blog_style_height']; ?>px;
				overflow: auto;
			<?php } ?>
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_objects_conteiner_<?php echo $ffwd; ?> .ffwd_blog_style_header_container_<?php echo $ffwd; ?> {
				display: inline-block;
				width: <?php echo $ffwd_info['blog_style_width']; ?>px;
				max-width: 100%;
				text-align: <?php echo $theme_row->blog_style_fd_name_align ?>;
				padding: <?php echo $theme_row->blog_style_fd_name_padding ?>px;
				box-sizing: border-box;
				background-color: #<?php echo $theme_row->blog_style_fd_name_bg_color ?>;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_objects_conteiner_<?php echo $ffwd; ?> .ffwd_blog_style_header_container_<?php echo $ffwd; ?> .ffwd_blog_style_header_<?php echo $ffwd; ?> {
				display: inline-block;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				font-size: <?php echo $theme_row->blog_style_fd_name_size ?>px;
				font-weight: <?php echo $theme_row->blog_style_fd_name_font_weight ?>;
				color: #<?php echo $theme_row->blog_style_fd_name_color ?>;
				margin: 0px 0px 0px 5px;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_objects_conteiner_<?php echo $ffwd; ?> .ffwd_blog_style_header_container_<?php echo $ffwd; ?> .ffwd_blog_style_header_icon_<?php echo $ffwd; ?> {
				font-size: <?php echo $theme_row->blog_style_fd_icon_size ?>px;
				color: #<?php echo $theme_row->blog_style_fd_icon_color ?>;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_objects_<?php echo $ffwd; ?> {
				display: inline-block;
				-moz-box-sizing: border-box;
				box-sizing: border-box;
				max-width: 100%;
				width: <?php echo $ffwd_info['blog_style_width']; ?>px;
				text-align: left;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> {
				width: 100%;
				max-width: 100%;
				padding: <?php echo $theme_row->blog_style_margin; ?>px;
				box-shadow: <?php echo $theme_row->blog_style_box_shadow; ?>;
				margin: 0;
				box-sizing: border-box;
				border-width: <?php  echo $theme_row->blog_style_border_width; ?>px;
				border-<?php  echo ($theme_row->blog_style_border_type != 'all') ? $theme_row->blog_style_border_type . '-' : ''; ?>style: <?php  echo $theme_row->blog_style_border_style; ?>;
				border-color: #<?php  echo $theme_row->blog_style_border_color; ?>;
				background-color: #<?php echo $theme_row->blog_style_bg_color; ?>;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_image_container_<?php echo $ffwd; ?> {
			<?php if(!$ffwd_info['blog_style_view_type']) echo 'float: left;width: 55%;'; else echo 'padding: 0px;'; ?>
				position: relative;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_ver_al_<?php echo $ffwd; ?> {
				display: table-cell;
				vertical-align: top;
				text-align: <?php echo $theme_row->blog_style_obj_img_align; ?>;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_ver_<?php echo $ffwd; ?> {
				text-align: center;
				display: table;
				vertical-align: middle;
				height: 100%;
				width:100%;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_play_icon_<?php echo $ffwd; ?> {
				position: absolute;
				width: 100%;
				height: 100%;
				top: 0px;
				background: url('<?php echo WD_FFWD_URL . '/images/feed/play_gray.png' ?>') no-repeat center center;
				background-size: 40px;
				z-index: 1;
				transition: all 0.5s;
				cursor: pointer;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_play_icon_<?php echo $ffwd; ?>:hover {
				background: url('<?php echo WD_FFWD_URL . '/images/feed/play.png' ?>') no-repeat center center;
				background-size: 40px;
				cursor: pointer;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_ver_al_<?php echo $ffwd; ?> .ffwd_lightbox_<?php echo $ffwd; ?> {
				outline: none;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_ver_al_<?php echo $ffwd; ?> .ffwd_lightbox_<?php echo $ffwd; ?>:focus {
				outline: none;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_ver_al_<?php echo $ffwd; ?> .ffwd_link_<?php echo $ffwd; ?> {
				outline: none;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_ver_al_<?php echo $ffwd; ?> .ffwd_link_<?php echo $ffwd; ?>:focus {
				outline: none;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .bwg_blog_style_img_cont_<?php echo $ffwd; ?> {
				position: relative;
				display: inline-block;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .bwg_blog_style_img_cont_<?php echo $ffwd; ?> .ffwd_blog_style_img_<?php echo $ffwd; ?> {
				padding: 0 !important;
				width: auto;
				border-radius: 0px;
			}
			/*pagination styles*/
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .tablenav-pages_<?php echo $ffwd; ?> {
				text-align: <?php   echo $theme_row->page_nav_align; ?>;
				font-size: <?php   echo $theme_row->page_nav_font_size; ?>px;
				font-family: <?php   echo $theme_row->page_nav_font_style; ?>;
				font-weight: <?php   echo $theme_row->page_nav_font_weight; ?>;
				color: #<?php   echo $theme_row->page_nav_font_color; ?>;
				margin: 6px 0 4px;
				display: block;
				height: 30px;
				line-height: 30px;
			}
			@media only screen and (max-width : 520px) {
				#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .displaying-num_<?php echo $ffwd; ?> {
					display: none;
				}
				#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> {
					float: none !important;
					width: 100% !important;
				}

				#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> {
					float: none;
					width: 100%;
					height: auto;
					margin: 4px 0px 0px 0px;
					padding: 4px 10px;
					box-sizing: border-box;
				}
				#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_ver_al_<?php echo $ffwd; ?> {
					text-align: center !important;
				}
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .displaying-num_<?php echo $ffwd; ?> {
				font-size: <?php   echo $theme_row->page_nav_font_size; ?>px;
				font-family: <?php   echo $theme_row->page_nav_font_style; ?>;
				font-weight: <?php   echo $theme_row->page_nav_font_weight; ?>;
				color: #<?php  echo $theme_row->page_nav_font_color; ?>;
				margin-right: 10px;
				vertical-align: middle;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .paging-input_<?php echo $ffwd; ?> {
				font-size: <?php   echo $theme_row->page_nav_font_size; ?>px;
				font-family: <?php   echo $theme_row->page_nav_font_style; ?>;
				font-weight: <?php   echo $theme_row->page_nav_font_weight; ?>;
				color: #<?php   echo $theme_row->page_nav_font_color; ?>;
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
				font-size: 12px;
				font-family: <?php   echo $theme_row->page_nav_font_style; ?>;
				font-weight: <?php   echo $theme_row->page_nav_font_weight; ?>;
				color: #000000;
				text-decoration: none;
				padding: <?php   echo $theme_row->page_nav_padding; ?>;
				margin: <?php   echo $theme_row->page_nav_margin; ?>;
				border-radius: <?php   echo $theme_row->page_nav_border_radius; ?>;
				border-style: <?php   echo $theme_row->page_nav_border_style; ?>;
				border-width: <?php   echo $theme_row->page_nav_border_width; ?>px;
				border-color: #<?php   echo $theme_row->page_nav_border_color; ?>;
				background-color: #<?php   echo $theme_row->page_nav_button_bg_color; ?>;
				opacity: <?php  echo number_format($theme_row->page_nav_button_bg_transparent / 100, 2, ".", ""); ?>;
				filter: Alpha(opacity=<?php  echo $theme_row->page_nav_button_bg_transparent; ?>);
				box-shadow: <?php   echo $theme_row->page_nav_box_shadow; ?>;
			<?php  echo ($theme_row->page_nav_button_transition ) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> #spider_popup_overlay_<?php echo $ffwd; ?> {
				background-color: #<?php  echo $theme_row->lightbox_overlay_bg_color; ?>;
				opacity: <?php  echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
				filter: Alpha(opacity=<?php  echo $theme_row->lightbox_overlay_bg_transparent; ?>);
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> {
			<?php if(!$ffwd_info['blog_style_view_type']) echo 'float: right;width: 45%;padding: 0px 10px;'; else echo 'margin-bottom: 15px;padding: 0px;'; ?>
				text-align: justify;
				background-color: #<?php   echo $theme_row->blog_style_obj_info_bg_color; ?>;
				height: 100%;
				box-sizing:border-box;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_blog_style_object_from_pic_container_<?php echo $ffwd; ?> img {
				border-radius: 0px;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_from_time_post_<?php echo $ffwd; ?> {
				font-size: 13px;
				font-weight: normal;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
                padding: 0 0px 3px 18px;
                margin: 0;
                min-width: 72px;
				background: url('<?php echo WD_FFWD_URL . '/images/feed/time_'. $theme_row->blog_style_obj_icons_color .'.png' ?>') no-repeat 0px center;
				background-size: 12px;
				color: #<?php echo $theme_row->blog_style_obj_story_color; ?>;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_blog_style_object_from_pic_container_<?php echo $ffwd; ?> {
				float:left;
				margin:0px 16px 0px 0px
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_hashtag_<?php echo $ffwd; ?>, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_message_tag_<?php echo $ffwd; ?> {
				color: #<?php echo $theme_row->blog_style_obj_hashtags_color; ?>;
				font-size: <?php echo $theme_row->blog_style_obj_hashtags_size; ?>px;
				font-weight: <?php echo $theme_row->blog_style_obj_hashtags_font_weight; ?>;
				text-decoration:none;
				outline: none;
				border-style: none;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_hashtag_<?php echo $ffwd; ?>:hover, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_message_tag_<?php echo $ffwd; ?>:hover {
				text-decoration:underline;
				color: #21759b;
				outline: none;
				border-style: none;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .bwg_blog_style_full_width {
				float: none !important;
				width: 100% !important;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_blog_style_object_from_name_<?php echo $ffwd; ?> {
				font-size: <?php   echo $theme_row->blog_style_obj_page_name_size; ?>px;
				font-weight: <?php   echo $theme_row->blog_style_obj_page_name_font_weight; ?>;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				color: #<?php echo $theme_row->blog_style_page_name_color; ?>;
				margin: 0px;
				text-decoration: none;
				outline: none;
				border-style: none;
				transition: 0.1s color linear;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_blog_style_object_from_name_<?php echo $ffwd; ?>:hover, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_blog_style_object_from_name_<?php echo $ffwd; ?>:focus  {
				font-size: <?php   echo $theme_row->blog_style_obj_page_name_size; ?>px;
				font-weight: <?php   echo $theme_row->blog_style_obj_page_name_font_weight; ?>;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				color: #<?php echo $theme_row->blog_style_page_name_color; ?>;
				margin: 0px;
				text-decoration: none;
				outline: none;
				border-style: none;
				transition: 0.1s color linear;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_blog_style_object_story_<?php echo $ffwd; ?> {
				color: #<?php echo $theme_row->blog_style_obj_story_color; ?>;
				font-size: <?php   echo $theme_row->blog_style_obj_story_size; ?>px;
				font-weight: <?php   echo $theme_row->blog_style_obj_story_font_weight; ?>;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_see_more_message, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_see_more_description {
				font-weight: <?php   echo $theme_row->blog_style_obj_message_font_weight; ?>;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				font-size: <?php echo $theme_row->blog_style_obj_likes_social_size; ?>px;
				outline: none;
				border-style: none;
				color: #<?php echo $theme_row->blog_style_obj_likes_social_color; ?>;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_see_less_message, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_see_less_description {
				font-size: <?php echo $theme_row->blog_style_obj_likes_social_size; ?>px;
				font-weight: <?php   echo $theme_row->blog_style_obj_message_font_weight; ?>;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				color: #<?php echo $theme_row->blog_style_obj_likes_social_color; ?>;
				outline: none;
				border-style: none;
			}
			.ffwd_see_less_message:hover, .ffwd_see_less_message:focus, .ffwd_see_more_message:hover, .ffwd_see_more_message:focus,  .ffwd_see_more_description:hover, .ffwd_see_more_description:focus, .ffwd_see_less_description:hover, .ffwd_see_less_description:focus {
				text-decoration: underline;
				font-size: <?php echo $theme_row->blog_style_obj_likes_social_size; ?>px;
				font-weight: <?php   echo $theme_row->blog_style_obj_message_font_weight; ?>;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				color: #<?php echo $theme_row->blog_style_obj_likes_social_color; ?>;
				outline: none;
				border-style: none;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_place_name_<?php echo $ffwd; ?> {
				color: #<?php echo $theme_row->blog_style_obj_place_color; ?>;
				font-size: <?php   echo $theme_row->blog_style_obj_place_size; ?>px;
				font-weight: <?php   echo $theme_row->blog_style_obj_place_font_weight; ?>;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				text-decoration: none;
				outline: none;
				border: none;
				margin: 0px;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_info_container_<?php echo $ffwd; ?> .ffwd_place_name_<?php echo $ffwd; ?>:hover {
				color: #<?php echo $theme_row->blog_style_obj_place_color; ?>;
				font-size: <?php   echo $theme_row->blog_style_obj_place_size; ?>px;
				font-weight: <?php   echo $theme_row->blog_style_obj_place_font_weight; ?>;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				text-decoration: underline;
				outline: none;
				border: none;
				margin: 0px;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> a.ffwd_blog_style_object_name_<?php echo $ffwd; ?> {
				outline: none;
				border-style: none;
				color: #<?php echo $theme_row->blog_style_obj_name_color; ?>;
				font-weight: <?php   echo $theme_row->blog_style_obj_name_font_weight; ?>;
				font-size: <?php   echo $theme_row->blog_style_obj_name_size; ?>px;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				font-style: normal;
				margin: 10px 0px 7px 0px;
				text-align: left;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> a.ffwd_blog_style_object_name_<?php echo $ffwd; ?>:hover, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> a.ffwd_blog_style_object_name_<?php echo $ffwd; ?>:focus {
				outline: none;
				border-style: none;
				text-decoration: underline;
				color: #<?php echo $theme_row->blog_style_obj_name_color; ?>;
				font-weight: <?php   echo $theme_row->blog_style_obj_name_font_weight; ?>;
				font-size: <?php   echo $theme_row->blog_style_obj_name_size; ?>px;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				font-style: normal;
				margin: 10px 0px 7px 0px;
				text-align: left;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .bwg_blog_style_object_description_<?php echo $ffwd; ?> {
				color: #<?php echo $theme_row->blog_style_obj_message_color; ?>;
				font-size: <?php   echo $theme_row->blog_style_obj_message_size; ?>px;
				font-weight: <?php   echo $theme_row->blog_style_obj_message_font_weight; ?>;
				font-style: normal;
				font-variant: normal;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_description_hide_<?php echo $ffwd; ?> {
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_blog_style_object_messages_<?php echo $ffwd; ?> {
				color: #<?php echo $theme_row->blog_style_obj_message_color; ?>;
				font-size: <?php echo $theme_row->blog_style_obj_message_size; ?>px;
				font-weight: <?php echo $theme_row->blog_style_obj_message_font_weight; ?>;
				font-style: normal;
				font-variant: normal;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
                margin-bottom: 4px;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .fa {
				vertical-align: baseline;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_title_spun1_<?php echo $ffwd; ?> {
				display: block;
				background-color: #<?php echo $theme_row->blog_style_obj_likes_social_bg_color; ?>;
				margin: 8px 0px 0px 0px;
				text-align: center;
				padding: 3px 0px;
				border-bottom-style: solid;
				/*border-top-style: solid;*/
				border-width: 1px;
				border-color: #E8E8E8;
				cursor: pointer;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_title_spun1_<?php echo $ffwd; ?> .ffwd_comments_likes_<?php echo $ffwd; ?> {
				display: block;
				opacity: 1;
				filter: Alpha(opacity=100);
				text-align: center;
				/*width: 180px;*/
				padding: 0px;
				position: relative;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_likes_names_count_<?php echo $ffwd; ?> {
				margin: 4px 0px 0px 0px;
				text-align: left;
				border-bottom-style: solid;
				/*border-top-style: solid;*/
				border-width: 1px;
				border-color: #E8E8E8;
				background-color: #<?php echo $theme_row->blog_style_obj_likes_social_bg_color; ?>;
				box-sizing: border-box;
				padding: 3px 0px;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comments_content_<?php echo $ffwd; ?> {
				margin: 4px 0px 0px 0px;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comments_content_<?php echo $ffwd; ?> .ffwd_comment_author_pic_<?php echo $ffwd; ?> > img {
				width:32px;
				height:32px;
				margin: 0px;
				padding: 0px;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comments_content_<?php echo $ffwd; ?> .ffwd_view_more_comments_cont_<?php echo $ffwd; ?> {
				margin: 10px 0px 0px 0px;
				text-align: left;
				padding: 0px 0px 0px 6px;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comments_content_<?php echo $ffwd; ?> .ffwd_view_more_comments_cont_<?php echo $ffwd; ?> a.ffwd_view_more_comments {
				outline: none;
				border-style: none;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comments_content_<?php echo $ffwd; ?> .ffwd_view_more_comments_cont_<?php echo $ffwd; ?> a.ffwd_view_more_comments:hover {
				outline: none;
				border-style: none;
				text-decoration: underline;
				color: #<?php echo $theme_row->blog_style_obj_likes_social_color; ?>;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comments_content_<?php echo $ffwd; ?> .ffwd_view_more_comments_cont_<?php echo $ffwd; ?> a.ffwd_view_more_comments > span {
				font-weight: <?php   echo $theme_row->blog_style_obj_message_font_weight; ?>;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				font-size: <?php echo $theme_row->blog_style_obj_likes_social_size; ?>px;
				color: #<?php echo $theme_row->blog_style_obj_likes_social_color; ?>;
				outline: none;
				border-style: none;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?>, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_reply_<?php echo $ffwd; ?> {
				padding: 6px;
				box-sizing: border-box;
				background-color: #<?php echo $theme_row->blog_style_obj_comments_bg_color; ?>;
				border-<?php  echo ($theme_row->blog_style_obj_comment_border_type != 'all') ? $theme_row->blog_style_obj_comment_border_type . '-' : ''; ?>style: <?php  echo $theme_row->blog_style_obj_comment_border_style; ?>;
				border-width: <?php echo $theme_row->blog_style_obj_comment_border_width; ?>px;
				border-color: #<?php echo $theme_row->blog_style_obj_comment_border_color; ?>;
				margin: 0px 0px 3px 0px;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_content_<?php echo $ffwd; ?>, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_reply_<?php echo $ffwd; ?> .ffwd_comment_reply_content_<?php echo $ffwd; ?> {
				float: left;
				margin-left: 5px;
				max-width: <?php echo $ffwd_info['blog_style_width'] * 0.80; ?>px;
				text-align: justify;
				line-height: 17px;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_reply_<?php echo $ffwd; ?> .ffwd_comment_reply_content_<?php echo $ffwd; ?> {
				float: left;
				margin-left: 5px;
				max-width: <?php echo $ffwd_info['blog_style_width'] * 0.70; ?>px;
				text-align: justify;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_content_<?php echo $ffwd; ?> .ffwd_comment_replies_<?php echo $ffwd; ?> .ffwd_comment_replies_label_<?php echo $ffwd; ?> {
				cursor: pointer;
				padding: 0px 0px 0px 18px;
				/*background: url('<?php echo WD_FFWD_URL . '/images/feed/time_'. $theme_row->blog_style_obj_icons_color .'.png' ?>') no-repeat 3px center;*/
				background-size: 10px;
				font-weight: <?php   echo $theme_row->blog_style_obj_comments_social_font_weight; ?>;
				font-size: <?php   echo $theme_row->blog_style_obj_comments_font_size; ?>px;
				font-family: <?php echo $theme_row->blog_style_obj_comments_font_family; ?>;
				font-variant: initial;
				color: rgb(165, 165, 165);
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_content_<?php echo $ffwd; ?> .ffwd_comment_replies_<?php echo $ffwd; ?> .ffwd_comment_replies_content_<?php echo $ffwd; ?> {
				display: none;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_content_<?php echo $ffwd; ?> .ffwd_comment_replies_<?php echo $ffwd; ?> .ffwd_comment_replies_content_<?php echo $ffwd; ?> .ffwd_comment_reply_author_pic_<?php echo $ffwd; ?> > img {
				width:25px;
				height:25px;
				margin: 0px;
				padding: 0px;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_content_<?php echo $ffwd; ?> .ffwd_comment_message_<?php echo $ffwd; ?>, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_reply_<?php echo $ffwd; ?> .ffwd_comment_reply_content_<?php echo $ffwd; ?> .ffwd_comment_reply_message_<?php echo $ffwd; ?> {
				color: #<?php echo $theme_row->blog_style_obj_comments_color; ?>;
				font-weight: <?php   echo $theme_row->blog_style_obj_comments_social_font_weight; ?>;
				font-size: <?php   echo $theme_row->blog_style_obj_comments_font_size; ?>px;
				font-family: <?php echo $theme_row->blog_style_obj_comments_font_family; ?>;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_content_<?php echo $ffwd; ?> .ffwd_comment_author_name_<?php echo $ffwd; ?>, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_reply_content_<?php echo $ffwd; ?> .ffwd_comment_reply_author_name_<?php echo $ffwd; ?> {
				text-decoration:none;
				outline: none;
				border-style: none;
				color: #<?php echo $theme_row->blog_style_obj_users_font_color; ?>;
				font-weight: <?php   echo $theme_row->blog_style_obj_comments_social_font_weight; ?>;
				font-size: <?php   echo $theme_row->blog_style_obj_comments_font_size; ?>px;
				font-family: <?php echo $theme_row->blog_style_obj_comments_font_family; ?>;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_content_<?php echo $ffwd; ?> .ffwd_comment_author_name_<?php echo $ffwd; ?>:hover, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_reply_content_<?php echo $ffwd; ?> .ffwd_comment_reply_author_name_<?php echo $ffwd; ?>:hover {
				text-decoration:underline;
				outline: none;
				border-style: none;
				cursor: pointer;
				color: #<?php echo $theme_row->blog_style_obj_users_font_color; ?>;
				font-weight: <?php   echo $theme_row->blog_style_obj_comments_social_font_weight; ?>;
				font-size: <?php   echo $theme_row->blog_style_obj_comments_font_size; ?>px;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_content_<?php echo $ffwd; ?> .ffwd_comment_date_<?php echo $ffwd; ?>, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_reply_content_<?php echo $ffwd; ?> .ffwd_comment_reply_date_<?php echo $ffwd; ?> {
				padding: 0px 0px 0px 18px;
				background: url('<?php echo WD_FFWD_URL . '/images/feed/time_'. $theme_row->blog_style_obj_icons_color .'.png' ?>') no-repeat 3px center;
				background-size: 10px;
				font-weight: <?php   echo $theme_row->blog_style_obj_comments_social_font_weight; ?>;
				font-size: <?php   echo $theme_row->blog_style_obj_comments_font_size; ?>px;
				font-family: <?php echo $theme_row->blog_style_obj_comments_font_family; ?>;
				font-variant: initial;
				color: rgb(165, 165, 165);
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_content_<?php echo $ffwd; ?> .ffwd_comment_likes_count_<?php echo $ffwd; ?>, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_comment_<?php echo $ffwd; ?> .ffwd_comment_reply_content_<?php echo $ffwd; ?> .ffwd_comment_reply_likes_count_<?php echo $ffwd; ?> {
				padding: 0px 0px 0px 13px;
				margin: 0px 0px 0px 5px;
				background: url('<?php echo WD_FFWD_URL . '/images/feed/like_'. $theme_row->blog_style_obj_icons_color .'.png' ?>') no-repeat 3px center;
				background-size: 10px;
				font-weight: <?php   echo $theme_row->blog_style_obj_comments_social_font_weight; ?>;
				font-size: <?php   echo $theme_row->blog_style_obj_comments_font_size; ?>px;
				font-family: <?php echo $theme_row->blog_style_obj_comments_font_family; ?>;
				font-variant: initial;
				color: rgb(165, 165, 165);
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_likes_names_count_<?php echo $ffwd; ?> .ffwd_likes_names_<?php echo $ffwd; ?> .ffwd_like_name_cont_<?php echo $ffwd; ?> .ffwd_like_name_<?php echo $ffwd; ?> {
				text-decoration: none;
				outline: none;
				border-style: none;
				color: #<?php echo $theme_row->blog_style_obj_users_font_color; ?>;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				font-weight: <?php echo $theme_row->blog_style_obj_likes_social_font_weight; ?>;
				font-size: <?php echo $theme_row->blog_style_obj_likes_social_size; ?>px;
				float: left;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_likes_names_count_<?php echo $ffwd; ?> .ffwd_likes_names_<?php echo $ffwd; ?> .ffwd_like_name_cont_<?php echo $ffwd; ?> .ffwd_like_name_<?php echo $ffwd; ?>:hover,#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_likes_names_count_<?php echo $ffwd; ?> .ffwd_likes_names_<?php echo $ffwd; ?> .ffwd_like_name_cont_<?php echo $ffwd; ?> .ffwd_like_name_<?php echo $ffwd; ?>:focus {
				text-decoration: underline;
				outline: none;
				border-style: none;
				color: #<?php echo $theme_row->blog_style_obj_users_font_color; ?>;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				font-weight: <?php echo $theme_row->blog_style_obj_likes_social_font_weight; ?>;
				font-size: <?php echo $theme_row->blog_style_obj_likes_social_size; ?>px;
				float: left;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_likes_names_count_<?php echo $ffwd; ?> .ffwd_likes_names_<?php echo $ffwd; ?> .ffwd_like_name_cont_<?php echo $ffwd; ?> {
				float: left;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_likes_names_count_<?php echo $ffwd; ?> .ffwd_likes_names_<?php echo $ffwd; ?> .ffwd_almost_<?php echo $ffwd; ?> {
				text-decoration: none;
				outline: none;
				color: #<?php echo $theme_row->blog_style_obj_likes_social_color; ?>;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				font-weight: <?php echo $theme_row->blog_style_obj_likes_social_font_weight; ?>;
				font-size: <?php echo $theme_row->blog_style_obj_likes_social_size; ?>px;
				float: left;
				margin: 0px 0px 0px 5px;
			}


			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_likes_names_count_<?php echo $ffwd; ?> .ffwd_likes_names_<?php echo $ffwd; ?> {
				float: left;

				min-height: 16px;
			}


			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_likes_names_count_<?php echo $ffwd; ?> .ffwd_react_icons_like_<?php echo $ffwd; ?> {
				float: left;
				display: block;
				height: 22px;
				background: url('<?php echo WD_FFWD_URL . '/images/feed/like_'. $theme_row->blog_style_obj_icons_color .'.png' ?>') no-repeat 3px center;
				background-size: 12px;
				width: 20px;
			}


			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_likes_names_count_<?php echo $ffwd; ?> .ffwd_react_icons_love_<?php echo $ffwd; ?> {
				float: left;
				display: block;
				height: 22px;
				background: url('<?php echo WD_FFWD_URL . '/images/feed/love_gray.png' ?>') no-repeat 3px center;
				background-size: 16px;
				width: 20px;
				margin-left: -2px;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_likes_names_count_<?php echo $ffwd; ?> .ffwd_react_icons_haha_<?php echo $ffwd; ?> {
				float: left;
				display: block;
				height: 22px;
				background: url('<?php echo WD_FFWD_URL . '/images/feed/haha_gray.png' ?>') no-repeat 3px center;
				background-size: 16px;
				width: 20px;
				margin-left: -2px;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_likes_names_count_<?php echo $ffwd; ?> .ffwd_react_icons_wow_<?php echo $ffwd; ?> {
				float: left;
				display: block;
				height: 22px;
				background: url('<?php echo WD_FFWD_URL . '/images/feed/wow_gray.png' ?>') no-repeat 3px center;
				background-size: 16px;
				width: 20px;
				margin-left: -2px;
			}


			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_likes_names_count_<?php echo $ffwd; ?> .ffwd_react_icons_sad_<?php echo $ffwd; ?> {
				float: left;
				display: block;
				height: 22px;
				background: url('<?php echo WD_FFWD_URL . '/images/feed/sad_gray.png' ?>') no-repeat 3px center;
				background-size: 17px;
				width: 20px;
				margin-left: -2px;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_likes_names_count_<?php echo $ffwd; ?> .ffwd_react_icons_angry_<?php echo $ffwd; ?> {
				float: left;
				display: block;
				height: 22px;
				background: url('<?php echo WD_FFWD_URL . '/images/feed/angry_gray.png' ?>') no-repeat 3px center;
				background-size: 17px;
				width: 20px;
				margin-left: -2px;
			}


			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_likes_<?php echo $ffwd; ?> {
				float: left;
				background: url('<?php echo WD_FFWD_URL . '/images/feed/like_'. $theme_row->blog_style_obj_icons_color .'.png' ?>') no-repeat 3px center;
				background-size: 12px;
				color: #<?php echo $theme_row->blog_style_obj_likes_social_color; ?>;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				font-weight: <?php echo $theme_row->blog_style_obj_likes_social_font_weight; ?>;
				font-size: <?php echo $theme_row->blog_style_obj_likes_social_size; ?>px;
				box-sizing: border-box;
				min-height: 20px;
				line-height: 20px;
				padding: 0px 0px 0px 24px;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_view_on_<?php echo $ffwd; ?> {
				margin: 0px 4px 0px 0px;
				float: right;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_share_<?php echo $ffwd; ?> {
				float: right;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_view_on_facebook_<?php echo $ffwd; ?> {
				color: #<?php echo $theme_row->blog_style_obj_likes_social_color; ?>;
				margin: 0px 0px 0px 0px;
				text-decoration: none;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				font-weight: <?php echo $theme_row->blog_style_obj_likes_social_font_weight; ?>;
				font-size: <?php echo $theme_row->blog_style_obj_likes_social_size; ?>px;
				box-sizing: border-box;
				line-height: 20px;
				float: left;
				border-style: none;
				outline: none;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_view_on_facebook_<?php echo $ffwd; ?>:hover,  #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_view_on_facebook_<?php echo $ffwd; ?>:focus {
				color: #<?php echo $theme_row->blog_style_obj_likes_social_color; ?>;
				text-decoration: underline;
				outline: none;
				border-style: none;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_shares_<?php echo $ffwd; ?> {
				background: url('<?php echo WD_FFWD_URL . '/images/feed/share_'. $theme_row->blog_style_obj_icons_color .'.png' ?>') no-repeat 3px center;
				background-size: 15px;
				color: #<?php echo $theme_row->blog_style_obj_likes_social_color; ?>;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				font-weight: <?php echo $theme_row->blog_style_obj_likes_social_font_weight; ?>;
				font-size: <?php echo $theme_row->blog_style_obj_likes_social_size; ?>px;
				min-height: 20px;
				line-height: 20px;
				padding: 0px 0px 0px 24px;
				float: left;
				margin: 0px 0px 0px 4px;
				box-sizing: border-box;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_comments_count_<?php echo $ffwd; ?> {
				float: left;
				background: url('<?php echo WD_FFWD_URL . '/images/feed/comment_'. $theme_row->blog_style_obj_icons_color .'.png' ?>') no-repeat 3px center;
				background-size: 16px;
				margin: 0px 0px 0px 4px;
				color: #<?php echo $theme_row->blog_style_obj_likes_social_color; ?>;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				font-weight: <?php echo $theme_row->blog_style_obj_likes_social_font_weight; ?>;
				font-size: <?php echo $theme_row->blog_style_obj_likes_social_size; ?>px;
				min-height: 20px;
				line-height: 20px;
				padding: 0px 0px 0px 24px;
				box-sizing: border-box;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> #spider_popup_overlay_<?php echo $ffwd; ?> {
				background-color: #000000;
				opacity: 0.70;
				filter: Alpha(opacity=70)
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_share_<?php echo $ffwd; ?> .ffwd_share_button_<?php echo $ffwd; ?> {
				float: right;
				transition: 0.2s all linear;
				vertical-align: middle;
				min-height: 20px;
				min-width: 25px;
				margin: 0px 4px 0px 0px;
				padding: 0px;
				border-style: none;
				outline: none;
			}

			.ffwd_share_button_<?php echo $ffwd; ?> i {
				font-weight: bold;

			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_share_<?php echo $ffwd; ?> .ffwd_share_button_<?php echo $ffwd; ?>:hover, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_share_<?php echo $ffwd; ?> .ffwd_share_button_<?php echo $ffwd; ?>:focus  {
				border-style: none;
				outline: none;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> a.ffwd_facebook_<?php echo $ffwd; ?> {
			//  background: url('<?php echo WD_FFWD_URL . '/images/feed/facebook_'. $theme_row->blog_style_obj_icons_color .'.png' ?>') no-repeat center center;
				background-size: 18px;
				color:#3A548C;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> a.ffwd_facebook_<?php echo $ffwd; ?>:hover {
			//  background: url('<?php echo WD_FFWD_URL . '/images/feed/facebook_white.png' ?>') no-repeat center center;
				background-size: 18px;
			}


			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> a.ffwd_twitter_<?php echo $ffwd; ?> {
			//background: url('<?php echo WD_FFWD_URL . '/images/feed/twitter_'. $theme_row->blog_style_obj_icons_color .'.png' ?>') no-repeat center center;
				background-size: 22px;
				color:#55ACEE;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> a.ffwd_twitter_<?php echo $ffwd; ?>:hover {
			//  background: url('<?php echo WD_FFWD_URL . '/images/feed/twitter_white.png' ?>') no-repeat center center;
				background-size: 22px;
			}


			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> a.ffwd_google_<?php echo $ffwd; ?> {
			//background: url('<?php echo WD_FFWD_URL . '/images/feed/google_'. $theme_row->blog_style_obj_icons_color .'.png' ?>') no-repeat center center;
				background-size: 18px;
				color:#D73D32;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> a.ffwd_google_<?php echo $ffwd; ?>:hover {
			//background: url('<?php echo WD_FFWD_URL . '/images/feed/google_white.png' ?>') no-repeat center center;
				background-size: 18px;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_place_street_<?php echo $ffwd; ?> {
				color: #<?php echo $theme_row->blog_style_evt_str_color; ?>;
				font-family: <?php echo $theme_row->blog_style_evt_info_font_family; ?>;
				font-weight: <?php echo $theme_row->blog_style_evt_str_font_weight; ?>;
				font-size: <?php echo $theme_row->blog_style_evt_str_size; ?>px;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_place_city_state_country_<?php echo $ffwd; ?> {
				color: #<?php echo $theme_row->blog_style_evt_ctzpcn_color; ?>;
				font-family: <?php echo $theme_row->blog_style_evt_info_font_family; ?>;
				font-weight: <?php echo $theme_row->blog_style_evt_ctzpcn_font_weight; ?>;
				font-size: <?php echo $theme_row->blog_style_evt_ctzpcn_size; ?>px;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_place_map_<?php echo $ffwd; ?> {
				color: #<?php echo $theme_row->blog_style_evt_map_color; ?>;
				font-family: <?php echo $theme_row->blog_style_evt_info_font_family; ?>;
				font-weight: <?php echo $theme_row->blog_style_evt_map_font_weight; ?>;
				font-size: <?php echo $theme_row->blog_style_evt_map_size; ?>px;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .blog_style_object_container_<?php echo $ffwd; ?> .ffwd_from_time_event_<?php echo $ffwd; ?> {
				color: #<?php echo $theme_row->blog_style_evt_date_color; ?>;
				font-family: <?php echo $theme_row->blog_style_evt_info_font_family; ?>;
				font-weight: <?php echo $theme_row->blog_style_evt_date_font_weight; ?>;
				font-size: <?php echo $theme_row->blog_style_evt_date_size; ?>px;
			}
			#ffwd_ajax_loading_<?php echo $ffwd; ?> {
				position:absolute;
				width: 100%;
				z-index: 115;
				text-align: center;
				height: 100%;
				vertical-align: middle;
			}
			#ffwd_ajax_loading_tab_<?php echo $ffwd; ?> {
				display: table;
				vertical-align: middle;
				width: 100%;
				height: 100%;
				background-color: #FFFFFF;
				opacity: 0.7;
				filter: Alpha(opacity=70);
			}
			#ffwd_ajax_loading_tab_cell_<?php echo $ffwd; ?> {
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

			.ffwd_reacts_<?php echo $ffwd; ?> img
			{
				height: 39px;
				display:none;
				-webkit-transition: transform  0.2s;
				transition: transform  0.2s;
			}

			.ffwd_reacts_<?php echo $ffwd; ?> img:hover
			{

				transform: scale(1.2);
				-ms-transform: scale(1.2);
				-webkit-transform: scale(1.2);
			}



			.ffwd_reacts_<?php echo $ffwd; ?>
			{
				/* display: none;*/

				height: 50px;
				background-color: #<?php echo $theme_row->blog_style_obj_likes_social_bg_color; ?>;
				position: absolute;
				z-index: 2;
				top: -63px;
				left: 0px;
				border-radius: 50px;
				padding: 4px 18px;
			}

			.ffwd_tooltip_<?php echo $ffwd; ?> {
				position: relative;
				display: inline-block;

			}

			.ffwd_tooltip_<?php echo $ffwd; ?> .ffwd_tooltiptext_<?php echo $ffwd; ?> {
				visibility: hidden;
				width: 120px;
				background-color: #232323;
				color: #fff;
				text-align: center;
				border-radius: 6px;
				padding: 5px 0;
				position: absolute;
				z-index: 1;
				bottom: 125%;
				left: 50%;
				margin-left: -60px;
				opacity: 0;
				transition: opacity 0.2s;
			}

			.ffwd_tooltip_<?php echo $ffwd; ?> .ffwd_tooltiptext_<?php echo $ffwd; ?>::after {
				content: "";
				position: absolute;
				top: 100%;
				left: 50%;
				margin-left: -5px;
				border-width: 5px;
				border-style: solid;
				border-color: #232323 transparent transparent transparent;
			}

			.ffwd_tooltip_<?php echo $ffwd; ?>:hover .ffwd_tooltiptext_<?php echo $ffwd; ?> {
				visibility: visible;
				opacity: 1;
			}







			.ffwd_reactions-toggle_<?php echo $ffwd; ?> {
				display: block;
				position: relative;
			}



			.ffwd_reactions-container_<?php echo $ffwd; ?>  .ffwd_reactions_slideup_<?php echo $ffwd; ?> {

				visibility: hidden; /* hides sub-menu */
				opacity: 0;

				transform: translateY(2em);
				transition: all 0.3s ease-in-out 0s, visibility 0s linear 0.3s, z-index 0s linear 0.01s;
				transition-delay: 1s, 0s, 0.3s;
			}

			.ffwd_reactions-container_<?php echo $ffwd; ?>:hover  .ffwd_reactions_slideup_<?php echo $ffwd; ?> {

				visibility: visible; /* shows sub-menu */
				opacity: 1;
				z-index: 1;
				transform: translateY(0%);
				transition-delay: 0s, 0s, 0.3s;

			}









		</style>
		<script>
			var client_side_today = new Date(),
				client_server_date_difference = (Date.parse(client_side_today) / 1000) - <?php echo time(); ?>,
				owner_info_<?php echo $ffwd; ?> = JSON.parse('<?php echo addslashes($this->model->page_user_group); ?>');
			ffwd_options = JSON.parse('<?php echo stripslashes($this->model->get_option_json_data()); ?>');
			ffwd_params_<?php echo $ffwd; ?> = JSON.parse('<?php echo json_encode($ffwd_info); ?>');
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
						<div class="fb-page" data-href="https://www.facebook.com/<?php echo $ffwd_info['from']; ?>" data-width="<?php echo $ffwd_info['page_plugin_width']; ?>" data-small-header="<?php echo $ffwd_info['page_plugin_header']; ?>" data-adapt-container-width="true" data-hide-cover="<?php echo $ffwd_info['page_plugin_cover']; ?>" data-show-facepile="<?php echo $ffwd_info['page_plugin_fans']; ?>" data-show-posts="false">
							<div class="fb-xfbml-parse-ignore">
							</div>
						</div>
					</div>
				<?php } ?>
				<form id="ffwd_front_form_<?php echo $ffwd; ?>" method="post" action="#">
					<div class="blog_style_objects_conteiner_<?php echo $ffwd; ?>">
						<div class="blog_style_objects_conteiner_1_<?php echo $ffwd; ?>">
							<?php if($ffwd_info["fb_name"]) { ?>
								<div class="ffwd_blog_style_header_container_<?php echo $ffwd; ?>">

									<div class="ffwd_blog_style_header_<?php echo $ffwd; ?>">
										<?php echo $ffwd_info['name']; ?>
									</div>
								</div>
							<?php } ?>
							<div id="ffwd_ajax_loading_<?php echo $ffwd; ?>" style="display:none;">
								<div id="ffwd_ajax_loading_tab_<?php echo $ffwd; ?>" >
									<div id="ffwd_ajax_loading_tab_cell_<?php echo $ffwd; ?>" >
										<div id="loading_div_<?php echo $ffwd; ?>">
											<img src="<?php echo WD_FFWD_URL . '/images/ajax_loader.png'; ?>" class="spider_ajax_loading" style="float: none; width:50px;">
										</div>
									</div>
								</div>
							</div>
							<?php
							if ($ffwd_info['pagination_type'] && $ffwd_info['objects_per_page'] && $theme_row->page_nav_position == 'top') {
								WDW_FFWD_Library::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'ffwd_front_form_' . $ffwd, $ffwd_info['objects_per_page'], $ffwd, 'ffwd_standart_objects_' . $ffwd, 0, 'album', /*$options_row->enable_seo*/true, $ffwd_info['pagination_type']);
							}
							?>
							<div class="blog_style_objects_<?php echo $ffwd; ?>" id="ffwd_standart_objects_<?php echo $ffwd; ?>" >
								<div class="blog_style_objects_cont_<?php echo $ffwd; ?>" id="ffwd_standart_objcets_cont_<?php echo $ffwd; ?>" >
									<?php
									foreach ($ffwd_data as $ffwd_data_row) {
										$ffwd_info_array['image_id'] = (isset($_POST['image_id']) ? esc_html($_POST['image_id']) : $ffwd_data_row->id);
										$link = ($ffwd_data_row->type != 'events') ? $ffwd_data_row->link : 'https://facebook.com/events/' . $ffwd_data_row->object_id;
										?>
										<div class="blog_style_object_container_<?php echo $ffwd; ?>">
											<div class="ffwd_blog_style_object_info_container_<?php echo $ffwd; ?><?php echo ($ffwd_data_row->type == 'status') ? ' bwg_blog_style_full_width' : ''; ?>" >
												<div style="margin-bottom: 4px;" class="ffwd_blog_style_object_from_<?php echo $ffwd; ?>" >
													<?php
													if($theme_row->blog_style_obj_date_pos == "before")
														echo $this->model->ffwd_time($ffwd_data_row, $ffwd,$ffwd_info['event_date']);
													?>
													<div class="ffwd_blog_style_object_from_pic_container_<?php echo $ffwd; ?>">
														<a id="ffwd_blog_style_object_from_pic_<?php echo $ffwd . '_' . $ffwd_data_row->id; ?>" class="ffwd_blog_style_object_from_pic_<?php echo $ffwd; ?>" href="" target="_blank">
														</a>
													</div>
													<div style="float: left; max-width: 76%;   color: #ADADAD; font-size: 13px;">
														<span id="ffwd_blog_style_object_story_<?php echo $ffwd . '_' . $ffwd_data_row->id; ?>" class="ffwd_blog_style_object_story_<?php echo $ffwd; ?>" >
														</span>
														<?php
														if($theme_row->blog_style_obj_date_pos == "after")
															echo $this->model->ffwd_time($ffwd_data_row, $ffwd,$ffwd_info['event_date']);
														?>
													</div>
													<div style="clear:both"></div>
												</div>
												<?php if($ffwd_info['blog_style_name']): ?>
													<a href="<?php echo $link; ?>" id="ffwd_blog_style_object_name_<?php echo $ffwd_data_row->id; ?>_<?php echo $ffwd; ?>" class="ffwd_blog_style_object_name_<?php echo $ffwd; ?>" target="_blank"><?php echo nl2br($ffwd_data_row->name); ?></a>
												<?php endif;
												if($message_desc):
													?>
													<p class="ffwd_blog_style_object_messages_<?php echo $ffwd; ?>">
														<?php

														$message = $this->model->see_less_more($ffwd_data_row->message, 'message', $ffwd_data_row->type,$ffwd_info['post_text_length'],$ffwd_info['event_desp_length']);
														$message = $this->model->fill_hashtags($message, $ffwd);
														$message = $this->model->fill_tags($message, $ffwd_data_row->message_tags, $ffwd);
														echo nl2br($linkify->process($message));
														?>
													</p>
                                                  <?php
                                                  $description = $this->model->see_less_more($ffwd_data_row->description, 'description', $ffwd_data_row->type, $ffwd_info['post_text_length'], $ffwd_info['event_desp_length']);
                                                  $description = $this->model->fill_hashtags($description, $ffwd);
                                                  $blog_style_object_description = nl2br($linkify->process($description));
                                                  if(!empty($blog_style_object_description)) {
                                                    ?>
                                                      <p class="bwg_blog_style_object_description_<?php echo $ffwd; ?>">
                                                        <?php echo $blog_style_object_description; ?>
                                                      </p>
                                                    <?php
                                                  }
												endif;
												if($theme_row->blog_style_obj_date_pos == "bottom")
													echo $this->model->ffwd_time($ffwd_data_row, $ffwd,$ffwd_info['event_date']);
												?>
											</div>
											<?php if($ffwd_data_row->type != 'status') :
												$src = ($ffwd_data_row->type != 'events') ? '' : $ffwd_data_row->source;
												$class_name = ($ffwd_data_row->type == 'link') ? 'ffwd_link_' . $ffwd : 'ffwd_lightbox_' . $ffwd;
												$href = ($ffwd_data_row->type == 'link') ? $ffwd_data_row->link : "";
												$link_to_facebook = ($ffwd_data_row->link != "" && $ffwd_data_row->type != "link" && $ffwd_data_row->type != "video") ? $ffwd_data_row->link : "https://www.facebook.com/".$ffwd_data_row->object_id;

												if($ffwd_info['image_onclick_action']=='facebook' || $ffwd_data_row->type=='video')
												{
													$href=$link_to_facebook;

												}
												if($ffwd_info['image_onclick_action']=='none')
												{
													$href='#';

												}

												?>

												<div class="blog_style_image_container_<?php echo $ffwd;?>">
													<div class="ffwd_blog_style_object_ver_<?php echo $ffwd; ?>" >
														<div class="ffwd_blog_style_object_ver_al_<?php echo $ffwd; ?>">
															<a style="position:relative;" class="<?php echo $class_name; ?>" href="<?php echo $href; ?>" <?php echo $ffwd_data_row->type=='video' ? 'data-type="video"' : '' ?> target="_blank" data-id="<?php echo $ffwd_data_row->id; ?>" >
																<div class="bwg_blog_style_img_cont_<?php echo $ffwd; ?>">
																	<img id="ffwd_blog_style_img_<?php echo $ffwd_data_row->id; ?>_<?php echo $ffwd; ?>" class="ffwd_blog_style_img_<?php echo $ffwd; ?>" src="<?php echo $src; ?>"  />
																	<?php if($ffwd_data_row->type == 'video'): ?>
																		<div class="ffwd_play_icon_<?php echo $ffwd; ?>" data-id="<?php echo $ffwd_data_row->id; ?>" >
																		</div>
																	<?php endif; ?>
																</div>
															</a>
														</div>
													</div>
												</div>
											<?php endif; ?>
											<div style="clear:both">
											</div>
											<?php
											if($show_comments || $show_likes || $show_shares || $show_shares_butt) { ?>
												<div class="ffwd_title_spun1_<?php echo $ffwd; ?>">
													<?php
													if($show_comments || $show_likes || $show_shares) { ?>
														<div class="ffwd_comments_likes_<?php echo $ffwd; ?>" style="float:left">
															<?php
															if($show_likes) {
																?>
																<span class="ffwd_reactions-container_<?php echo $ffwd; ?>">
																<div  id="ffwd_reacts_<?php echo $ffwd_data_row->id ?>_<?php echo $ffwd; ?>" class="ffwd_reactions_slideup_<?php echo $ffwd; ?> ffwd_reacts_<?php echo $ffwd; ?>">
																<div class="ffwd_tooltip_<?php echo $ffwd; ?>">
																<img id="ffwd_reactions_like_<?php echo $ffwd_data_row->id ?>_<?php echo $ffwd; ?>" src="<?php echo WD_FFWD_URL . '/images/feed/like_'. $theme_row->blog_style_obj_icons_color .'.png' ?>"	/>
																<span id="ffwd_tootlip_text_like_<?php echo $ffwd_data_row->id ?>_<?php echo $ffwd; ?>" class="ffwd_tooltiptext_<?php echo $ffwd; ?>" >LIKE</span>
                                                                </div>

                                                                    <div class="ffwd_tooltip_<?php echo $ffwd; ?>">

																<img id="ffwd_reactions_love_<?php echo $ffwd_data_row->id ?>_<?php echo $ffwd; ?>" src="<?php echo WD_FFWD_URL . '/images/feed/love_gray.png' ?>"	/>
                                                                        <span id="ffwd_tootlip_text_love_<?php echo $ffwd_data_row->id ?>_<?php echo $ffwd; ?>" class="ffwd_tooltiptext_<?php echo $ffwd; ?>">LOVE</span>
																	</div>
                                                                    <div class="ffwd_tooltip_<?php echo $ffwd; ?>">

																		<img id="ffwd_reactions_haha_<?php echo $ffwd_data_row->id ?>_<?php echo $ffwd; ?>"  src="<?php echo WD_FFWD_URL . '/images/feed/haha_gray.png' ?>"	/>
                                                                    <span id="ffwd_tootlip_text_haha_<?php echo $ffwd_data_row->id ?>_<?php echo $ffwd; ?>" class="ffwd_tooltiptext_<?php echo $ffwd; ?>">HAHA</span>
                                                                    </div>
                                                                    <div class="ffwd_tooltip_<?php echo $ffwd; ?>">
                                                                    <img id="ffwd_reactions_wow_<?php echo $ffwd_data_row->id ?>_<?php echo $ffwd; ?>"  src="<?php echo WD_FFWD_URL . '/images/feed/wow_gray.png' ?>"	/>
                                                                        <span id="ffwd_tootlip_text_wow_<?php echo $ffwd_data_row->id ?>_<?php echo $ffwd; ?>" class="ffwd_tooltiptext_<?php echo $ffwd; ?>">WOW</span>
                                                                        </div>
                                                                    <div class="ffwd_tooltip_<?php echo $ffwd; ?>">
                                                                        <img id="ffwd_reactions_sad_<?php echo $ffwd_data_row->id ?>_<?php echo $ffwd; ?>"  src="<?php echo WD_FFWD_URL . '/images/feed/sad_gray.png' ?>"	/>
                                                                        <span id="ffwd_tootlip_text_sad_<?php echo $ffwd_data_row->id ?>_<?php echo $ffwd; ?>" class="ffwd_tooltiptext_<?php echo $ffwd; ?>">SAD</span>
                                                                        </div>
                                                                    <div class="ffwd_tooltip_<?php echo $ffwd; ?>">
                                                                        <img id="ffwd_reactions_angry_<?php echo $ffwd_data_row->id ?>_<?php echo $ffwd; ?>"  src="<?php echo WD_FFWD_URL . '/images/feed/angry_gray.png' ?>"	/>
                                                                        <span id="ffwd_tootlip_text_angry_<?php echo $ffwd_data_row->id ?>_<?php echo $ffwd; ?>" class="ffwd_tooltiptext_<?php echo $ffwd; ?>">ANGRY</span>
                                                                    </div>
                                                                    </div>
																<div  id="ffwd_likes_<?php echo $ffwd . '_' . $ffwd_data_row->id; ?>" class="ffwd_likes_<?php echo $ffwd; ?> ffwd_reactions-toggle_<?php echo $ffwd; ?>" style="display : <?php echo ($ffwd_data_row->type == "events") ? 'none': 'block'; ?>">
																</div>
                                                                </span>
																<?php
															}
															if($show_shares) {
																?>
																<div id="ffwd_shares_<?php echo $ffwd . '_' . $ffwd_data_row->id; ?>" class="ffwd_shares_<?php echo $ffwd; ?>" style="display : <?php echo ($ffwd_data_row->type == "events") ? 'none': 'block'; ?>">
																</div>
																<?php
															}
															if($show_comments) {
																?>
																<div id="ffwd_comments_count_<?php echo $ffwd . '_' . $ffwd_data_row->id; ?>" class="ffwd_comments_count_<?php echo $ffwd; ?>">
																</div>
																<?php
															}
															?>
															<div style="clear:both"></div>
														</div>
													<?php }
													if ($show_shares_butt) {
														?>
														<div class="ffwd_share_<?php echo $ffwd; ?>" >
															<?php
															$share_url = $this->model->get_share_url($ffwd_data_row);
															if ($blog_style_facebook) {
																?>
																<a class="ffwd_facebook_<?php echo $ffwd; ?> ffwd_share_button_<?php echo $ffwd; ?>" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Facebook', 'ffwd'); ?>">
																	<i title="Share on Facebook" class="ffwd_ctrl_btn ffwd_facebook fa fa-facebook"></i>
																</a>
																<?php
															}
															if ($blog_style_google) {
																?>
																<a class="ffwd_google_<?php echo $ffwd; ?> ffwd_share_button_<?php echo $ffwd; ?>" href="https://plus.google.com/share?url=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Google+', 'ffwd'); ?>">
																	<i title="Share on Google+" class="bwg_ctrl_btn bwg_google fa fa-google-plus"></i>
																</a>
																<?php
															}
															if ($blog_style_twitter) {
																?>
																<a class="ffwd_twitter_<?php echo $ffwd; ?> ffwd_share_button_<?php echo $ffwd; ?>" href="https://twitter.com/share?url=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Twitter', 'ffwd'); ?>" style="background-size: 22px !important;" >
																	<i title="Share on Twitter" class="bwg_ctrl_btn bwg_twitter fa fa-twitter"></i>
																</a>
																<?php
															}
															?>
														</div>
														<?php
													}
													if ($view_on_fb) {
														?>
														<div class="ffwd_view_on_<?php echo $ffwd; ?>" >
															<?php
															$link_to_facebook = ($ffwd_data_row->link != "" && $ffwd_data_row->type != "link" && $ffwd_data_row->type != "video") ? $ffwd_data_row->link : "https://www.facebook.com/".$ffwd_data_row->object_id;
															?>
															<a class="ffwd_view_on_facebook_<?php echo $ffwd; ?>" href="<?php echo $link_to_facebook; ?>" target="_blank" title="<?php echo __('View on facebook', 'ffwd'); ?>"><?php echo __('View on facebook', 'ffwd'); ?></a>
														</div>
														<?php
													}
													?>
													<div style="clear:both"></div>
												</div>
												<?php
												if($show_comments || $show_likes) { ?>
													<div class="ffwd_comments_<?php echo $ffwd; ?>" style="display:none">
														<?php
														if($show_likes) {
															?>
															<div id="ffwd_likes_names_count_<?php echo $ffwd_data_row->id; ?>_<?php echo $ffwd; ?>" class="ffwd_likes_names_count_<?php echo $ffwd; ?>"  style="display : <?php echo ($ffwd_data_row->type == "events") ? 'none': 'block'; ?>" >
															</div>
															<?php
														}
														if($show_comments) {
															?>
															<div id="ffwd_comments_content_<?php echo $ffwd_data_row->id; ?>_<?php echo $ffwd; ?>" class="ffwd_comments_content_<?php echo $ffwd; ?>" >
															</div>
															<?php
														} ?>
													</div>
													<?php
												}
											}
											?>
										</div>
										<?php
									}
									?>
									<script>

										var id_object_id_<?php echo $ffwd; ?> = '<?php echo addslashes(json_encode($this->model->id_object_id_json)); ?>',
											graph_url_<?php echo $ffwd; ?> = '<?php echo $this->model->graph_url; ?>';
										ffwd_fill_likes_blog_style(JSON.parse(id_object_id_<?php echo $ffwd; ?>), '<?php echo $ffwd; ?>', owner_info_<?php echo $ffwd; ?>, ffwd_params_<?php echo $ffwd; ?>, graph_url_<?php echo $ffwd; ?>);
									</script>
								</div>
							</div>
							<?php
							if ($ffwd_info['pagination_type']  && $ffwd_info['objects_per_page'] && ($theme_row->page_nav_position == 'bottom')) {
								WDW_FFWD_Library::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'ffwd_front_form_' . $ffwd, $ffwd_info['objects_per_page'], $ffwd, 'ffwd_standart_objcets_cont_' . $ffwd, 0, 'album', /*$options_row->enable_seo*/true, $ffwd_info['pagination_type']);
							}
							?>
						</div>
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
			function ffwd_gallery_box_<?php echo $ffwd; ?>(image_id) {
				ffwd_createpopup('<?php echo addslashes(add_query_arg($ffwd_info_array, admin_url('admin-ajax.php'))); ?>&image_id=' + image_id + '&content_type=<?php echo $ffwd_info['content_type']; ?>', '<?php echo $ffwd; ?>', '<?php echo $ffwd_info['popup_width']; ?>', '<?php echo $ffwd_info['popup_height']; ?>', 1, 'testpopup', 5);
			}

			function ffwd_document_ready_<?php echo $ffwd; ?>() {
				<?php if($ffwd_info['image_onclick_action']=='lightbox') { ?>

				jQuery("body").on("click", ".ffwd_lightbox_<?php echo $ffwd; ?>", function () {
				    if(jQuery(this).attr("data-type")=='video')
                    {
                        return true;
                    }

					ffwd_gallery_box_<?php echo $ffwd; ?>(jQuery(this).attr("data-id"));
					return false;
				});
				<?php  } ?>

			}
			jQuery(document).ready(function () {
				ffwd_document_ready_<?php echo $ffwd; ?>();
				jQuery('body').on('click', '.ffwd_see_more_message', function(e) {
					e.preventDefault();
					jQuery(this).parent().find('.ffwd_blog_style_object_message_hide').css('display', 'inline');
					jQuery(this).html('<?php echo __('See less','ffwd') ?>').removeClass('ffwd_see_more_message').addClass('ffwd_see_less_message');
					jQuery(this).parent().find('.more_dotes').css('display', 'none');
				});

				jQuery('body').on('click', '.ffwd_see_less_message', function(e) {
					e.preventDefault();
					jQuery(this).parent().find('.ffwd_blog_style_object_message_hide').css('display', 'none');
					jQuery(this).html('<?php echo __('See more','ffwd') ?>').removeClass('ffwd_see_less_message').addClass('ffwd_see_more_message');
					jQuery(this).parent().find('.more_dotes').css('display', 'inline');
				});

				jQuery('body').on('click', '.ffwd_see_more_description', function(e) {
					e.preventDefault();
					jQuery(this).parent().find('.ffwd_blog_style_object_description_hide').css('display', 'inline');
					jQuery(this).html('<?php echo __('See less','ffwd') ?>').removeClass('ffwd_see_more_description').addClass('ffwd_see_less_description');
					jQuery(this).parent().find('.more_dotes').css('display', 'none');
				});

				jQuery('body').on('click', '.ffwd_see_less_description', function(e) {
					e.preventDefault();
					jQuery(this).parent().find('.ffwd_blog_style_object_description_hide').css('display', 'none');
					jQuery(this).html('<?php echo __('See more','ffwd') ?>').removeClass('ffwd_see_less_description').addClass('ffwd_see_more_description');
					jQuery(this).parent().find('.more_dotes').css('display', 'inline');
				});

				jQuery('body').on('click', '.ffwd_view_more_comments', function(e) {
					e.preventDefault();
					jQuery(this).parent().parent().parent().find('.ffwd_comment_<?php echo $ffwd; ?>').css('display', 'block');
					jQuery(this).html('');
				});

				jQuery('body').on('click', '.ffwd_comment_replies_label_<?php echo $ffwd; ?>', function(e) {
					e.preventDefault();
					jQuery(this).parent().find('.ffwd_comment_replies_content_<?php echo $ffwd; ?>').css('display', 'block');
					jQuery(this).remove();
				});

				jQuery('body').on('click', '.ffwd_view_on_facebook_<?php echo $ffwd; ?>', function(e) {
					e.stopPropagation();
				});

				jQuery('body').on('click', '.ffwd_title_spun1_<?php echo $ffwd; ?>', function(e) {
					e.preventDefault();
					var ffwd_comments = jQuery(this).parent().find('.ffwd_comments_<?php echo $ffwd; ?>');
					if(ffwd_comments.children().length == 0) return;
					if(ffwd_comments.css('display') == 'none')
						ffwd_comments.slideDown(500);
					else
						ffwd_comments.slideUp(500);
				});

				jQuery('body').on('click', '.ffwd_share_button_<?php echo $ffwd; ?>', function(e) {
					e.stopPropagation();
				});

				jQuery(window).resize(function() {
					ffwd_blog_style_resize(ffwd_params_<?php echo $ffwd; ?>, '<?php echo $ffwd; ?>')
				});
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
