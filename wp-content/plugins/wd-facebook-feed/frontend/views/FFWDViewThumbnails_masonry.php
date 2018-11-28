<?php
class FFWDViewThumbnails_masonry {
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
		$options_row = $this->model->get_ffwd_options();

    $ffwd_info = $this->model->get_ffwd_info($params['fb_id']);
    if ($ffwd_info == NULL || $ffwd_info["success"] === false) {
      echo WDW_FFWD_Library::message(__('There is no facebook feed selected or it was deleted.', 'ffwd'), 'error');
      return;
    }
    if(isset($params['from']) and $params['from']=='widget')
    {
    $ffwd_info['objects_per_page']= $params['objects_per_page'];
    $ffwd_info['theme']= $params['theme_id'];
    $ffwd_info['thumb_width']= $params['thumb_width'];
    $ffwd_info['thumb_height']= $params['thumb_height'];

    }


    $theme_row = $this->model->get_theme_row_data($ffwd_info['theme']);
    if (!$theme_row) {
      echo WDW_FFWD_Library::message(__('There is no theme selected or the theme was deleted.', 'ffwd'), 'error');
      return;
    }

    $ffwd_data = $this->model->get_ffwd_data($params['fb_id'], $ffwd_info['objects_per_page'], /*$ffwd_info['sort_by']*/'', $ffwd, /*$sort_direction*/ ' ASC');
    if ($ffwd_info['pagination_type'] && $ffwd_info['objects_per_page']) {
      $page_nav = $this->model->page_nav($params['fb_id'], $ffwd_info['objects_per_page'], $ffwd);
    }
    $rgb_page_nav_font_color = WDW_FFWD_Library::spider_hex2rgb($theme_row->page_nav_font_color);
    $rgb_thumbs_bg_color = WDW_FFWD_Library::spider_hex2rgb($theme_row->masonry_thumbs_bg_color);
		$is_video = ($ffwd_info['content'] == 'videos') ? true : false;
		$show_likes = ($ffwd_info['thumb_likes'] == '1') ? true : false;
		$show_comments = ($ffwd_info['thumb_comments'] == '1') ? true : false;
		$show_name = ($ffwd_info['thumb_name'] == '1') ? true : false;
    ?>
    <style>
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_masonry_thumbnails_<?php echo $ffwd; ?> * {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_masonry_thumb_<?php echo $ffwd; ?> {
        text-align: center;
        display: inline-block;
        vertical-align: middle;
        <?php
        if ($ffwd_info['masonry_hor_ver'] == 'vertical') {
          ?>
          width: <?php echo $ffwd_info['thumb_width']; ?>px !important;
          <?php
        }
        else {
          ?>
          height: <?php echo $ffwd_info['thumb_height']; ?>px !important;
          <?php
        }
        ?>
        border-radius: <?php   echo $theme_row->masonry_thumb_border_radius; ?>;
        border: <?php   echo $theme_row->masonry_thumb_border_width; ?>px <?php  echo $theme_row->masonry_thumb_border_style; ?> #<?php  echo $theme_row->masonry_thumb_border_color; ?>;
        background-color: #<?php  echo $theme_row->thumb_bg_color; ?>;
        margin: 0;
        padding: <?php  echo $theme_row->masonry_thumb_padding; ?>px !important;
        opacity: <?php echo number_format($theme_row->masonry_thumb_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php  echo $theme_row->masonry_thumb_transparent; ?>);
        <?php echo ($theme_row->masonry_thumb_transition) ? 'transition: transform 0.3s ease 0s;-webkit-transition: transform 0.3s ease 0s;' : ''; ?>
        z-index: 100;
				/*position: relative;*/
      }

			/*#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_masonry_thumb_<?php echo $ffwd; ?>:hover {
				opacity: 1;
        filter: Alpha(opacity=100);
        transform: <?php  echo $theme_row->masonry_thumb_hover_effect; ?>(<?php  echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        -ms-transform: <?php  echo $theme_row->masonry_thumb_hover_effect; ?>(<?php  echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        -webkit-transform: <?php  echo $theme_row->masonry_thumb_hover_effect; ?>(<?php  echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        z-index: 102;
      }*/
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> form {
				margin: 25px 0px 0px 0px;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_thumbs_header_container_<?php echo $ffwd; ?> {
				display: inline-block;
				width: 100%;
				max-width: 100%;
				text-align: <?php echo $theme_row->blog_style_fd_name_align ?>;
				padding: <?php echo $theme_row->blog_style_fd_name_padding ?>px;
				box-sizing: border-box;
				background-color: #<?php echo $theme_row->blog_style_fd_name_bg_color ?>;
				margin: 25px 0px 0px 0px;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_thumbs_header_container_<?php echo $ffwd; ?> .ffwd_thumbs_header_<?php echo $ffwd; ?> {
				display: inline-block;
				font-family: <?php echo $theme_row->blog_style_obj_font_family; ?>;
				font-size: <?php echo $theme_row->blog_style_fd_name_size ?>px;
				font-weight: <?php echo $theme_row->blog_style_fd_name_font_weight ?>;
				color: #<?php echo $theme_row->blog_style_fd_name_color ?>;
				margin: 0px 0px 0px 5px;
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_thumbs_header_container_<?php echo $ffwd; ?> .ffwd_thumbs_header_icon_<?php echo $ffwd; ?> {
				font-size: <?php echo $theme_row->blog_style_fd_icon_size ?>px;
				color: #<?php echo $theme_row->blog_style_fd_icon_color ?>;
				vertical-align: middle;
				font-family: FontAwesome
			}
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_page_plugin_<?php echo $ffwd; ?>{
				margin: 30px 0px 0px 0px;
				background-color: rgba(0, 0, 0, 0);
				text-align: <?php  echo $theme_row->masonry_thumb_align; ?>;
				width: 100%;
				position: relative;
      }
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_masonry_thumb_cont_<?php echo $ffwd; ?> {
        position: absolute;
        vertical-align: top;
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_masonry_thumb_cont_<?php echo $ffwd; ?>:hover {
        opacity: 1;
        filter: Alpha(opacity=100);
        transform: <?php  echo $theme_row->masonry_thumb_hover_effect; ?>(<?php  echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        -ms-transform: <?php  echo $theme_row->masonry_thumb_hover_effect; ?>(<?php  echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        -webkit-transform: <?php  echo $theme_row->masonry_thumb_hover_effect; ?>(<?php  echo $theme_row->masonry_thumb_hover_effect_value; ?>);
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        z-index: 102;
        position: absolute;
      }

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_masonry_thumb_cont_<?php echo $ffwd; ?> .ffwd_lightbox_<?php echo $ffwd; ?> {
        outline: none;
				text-decoration: none;
      }

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_masonry_thumb_cont_<?php echo $ffwd; ?> .ffwd_lightbox_<?php echo $ffwd; ?>:focus {
        outline: none;
				text-decoration: none;
      }

      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .tablenav-pages_<?php echo $ffwd; ?> {
        visibility: hidden;
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_masonry_thumbnails_<?php echo $ffwd; ?> {
        -moz-box-sizing: border-box;
        visibility: hidden;

        box-sizing: border-box;
        display: inline-block;
        font-size: 0;
        <?php
        if ($ffwd_info['masonry_hor_ver'] == 'vertical') {
          ?>
          width: <?php echo $ffwd_info['image_max_columns'] * ($ffwd_info['thumb_width'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px;
          <?php
        }
        else {
          ?>
          height: <?php echo $ffwd_info['image_max_columns'] * ($ffwd_info['thumb_height'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px !important;
          width: inherit;
          <?php
        }
        ?>
        position: relative;
        text-align: <?php  echo $theme_row->masonry_thumb_align; ?>;
      }

      .ffwd_masonry_thumb_cont_<?php echo $ffwd; ?>
      {
          background-color: rgba(<?php echo $rgb_thumbs_bg_color['red']; ?>, <?php  echo $rgb_thumbs_bg_color['green']; ?>, <?php echo $rgb_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->masonry_thumb_bg_transparent / 100, 2, ".", ""); ?>);

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
      @media only screen and (max-width : 320px) {
        #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .displaying-num_<?php echo $ffwd; ?> {
          display: none;
        }
      }
      <?php
      if ($ffwd_info['masonry_hor_ver'] == 'vertical') {
        ?>
        @media only screen and (max-width : <?php echo $ffwd_info['image_max_columns'] * ($ffwd_info['thumb_width'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px) {
          #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_masonry_thumbnails_<?php echo $ffwd; ?> {
            width: inherit;
          }
        }
        <?php
      }
      else {
        ?>
        @media only screen and (max-height : <?php echo $ffwd_info['image_max_columns'] * ($ffwd_info['thumb_height'] + 2 * ($theme_row->masonry_thumb_padding + $theme_row->masonry_thumb_border_width)); ?>px) {
          #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_masonry_thumbnails_<?php echo $ffwd; ?> {
            height: inherit;
          }
        }
        <?php
      }
      ?>
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .displaying-num_<?php echo $ffwd; ?> {
        font-size: <?php   echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php   echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php   echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php   echo $theme_row->page_nav_font_color; ?>;
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
        font-size: <?php   echo $theme_row->page_nav_font_size; ?>px;
        font-family: <?php   echo $theme_row->page_nav_font_style; ?>;
        font-weight: <?php   echo $theme_row->page_nav_font_weight; ?>;
        color: #<?php   echo $theme_row->page_nav_font_color; ?>;
        text-decoration: none;
        padding: <?php   echo $theme_row->page_nav_padding; ?>;
        margin: <?php   echo $theme_row->page_nav_margin; ?>;
        border-radius: <?php   echo $theme_row->page_nav_border_radius; ?>;
        border-style: <?php   echo $theme_row->page_nav_border_style; ?>;
        border-width: <?php   echo $theme_row->page_nav_border_width; ?>px;
        border-color: #<?php   echo $theme_row->page_nav_border_color; ?>;
        background-color: #<?php   echo $theme_row->page_nav_button_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->page_nav_button_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php   echo $theme_row->page_nav_button_bg_transparent; ?>);
        box-shadow: <?php   echo $theme_row->page_nav_box_shadow; ?>;
        <?php echo ($theme_row->page_nav_button_transition ) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .bwg_back_<?php echo $ffwd; ?> {
        background-color: rgba(0, 0, 0, 0);
        color: #<?php //  echo $theme_row->album_compact_back_font_color; ?> !important;
        cursor: pointer;
        display: block;
        font-family: <?php //  echo $theme_row->album_compact_back_font_style; ?>;
        font-size: <?php //  echo $theme_row->album_compact_back_font_size; ?>px;
        font-weight: <?php //  echo $theme_row->album_compact_back_font_weight; ?>;
        text-decoration: none;
        padding: <?php //  echo $theme_row->album_compact_back_padding; ?>;
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> #spider_popup_overlay_<?php echo $ffwd; ?> {
        background-color: #<?php  echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_masonry_thumb_description_<?php echo $ffwd; ?> {
        color: #<?php echo $theme_row->masonry_description_color; ?>;
			￼	line-height: 1.4;
				font-size: <?php   echo $theme_row->masonry_description_font_size; ?>px;
        font-family: <?php echo $theme_row->masonry_description_font_style; ?>;
        text-align: justify;
				padding: <?php  echo $theme_row->masonry_thumb_padding; ?>px !important;
				box-sizing: border-box;
        <?php
            if(isset($theme_row->masonry_thumb_bg_color)){
              echo "background-color: #".$theme_row->masonry_thumb_bg_color;
            }
        ?>
      }

      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_likes_comments_container_<?php echo $ffwd; ?> {
        display: table;
				position: absolute;
				top:0px;
				left:0px;
				height: 100%;
        margin: 0 auto;
        filter: Alpha(opacity=100);
        text-align: center;
        width: <?php echo $ffwd_info['thumb_width']; ?>px;
				padding: <?php  echo $theme_row->masonry_thumb_padding; ?>px;
      }

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_likes_comments_container_<?php echo $ffwd; ?> .ffwd_likes_comments_container_tab_<?php echo $ffwd; ?> {
				display: table;
				background-color: rgba(0, 0, 0, 0.46);
				height: 100%;
				width: 100%;
				opacity: 0;
				transition: opacity 0.5s;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_likes_comments_container_<?php echo $ffwd; ?> .ffwd_likes_comments_container_tab_<?php echo $ffwd; ?> .ffwd_likes_comments_<?php echo $ffwd; ?> {
				display: table-cell;
				vertical-align : <?php echo $theme_row->masonry_like_comm_pos; ?>;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_masonry_thumb_cont_<?php echo $ffwd; ?>:hover .ffwd_likes_comments_container_tab_<?php echo $ffwd; ?> {
				opacity: 1;
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
				color: #<?php echo $theme_row->masonry_like_comm_font_color; ?>;
        font-family: <?php echo $theme_row->masonry_like_comm_font_style; ?>;
        font-size: <?php echo $theme_row->masonry_like_comm_font_size; ?>px;
				box-shadow: <?php echo $theme_row->masonry_like_comm_shadow; ?>;
				font-weight: <?php echo $theme_row->masonry_like_comm_font_weight; ?>;
      }

      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> #spider_popup_overlay_<?php echo $ffwd; ?> {

        background-color: #000000;
        opacity: 0.70;
        filter: Alpha(opacity=70)
      }

		  .ffwd_play_icon_<?php echo $ffwd; ?> {
        display: table;
        position: absolute;
				top: 0px;
				left: 0px;
				background: url('<?php echo WD_FFWD_URL . '/images/feed/play_gray.png' ?>') no-repeat center center;
				background-size: 40px;
				z-index: 1;
				width: <?php echo $ffwd_info['thumb_width']; ?>px;
				padding: <?php  echo $theme_row->masonry_thumb_padding; ?>px;
				transition: all 0.5s;
      }

			.ffwd_play_icon_<?php echo $ffwd; ?>:hover {
				background: url('<?php echo WD_FFWD_URL . '/images/feed/play.png' ?>') no-repeat center center;
				background-size: 40px;
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
      @media only screen and (max-width : 520px) {

      }
    </style>
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
          <div id="bwg_masonry_thumbnails_div_<?php echo $ffwd; ?>" style="background-color: rgba(0, 0, 0, 0); text-align: <?php echo $theme_row->masonry_thumb_align; ?>; width: 100%; position: relative;">
						<?php if($ffwd_info["fb_name"]) { ?>
							<div class="ffwd_thumbs_header_container_<?php echo $ffwd; ?>">
								<i class="ffwd_thumbs_header_icon_<?php echo $ffwd; ?> <?php echo $theme_row->blog_style_fd_icon; ?>"></i>
								<div class="ffwd_thumbs_header_<?php echo $ffwd; ?>">
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
							if ($ffwd_info['pagination_type']  && $ffwd_info['objects_per_page'] && ($theme_row->page_nav_position == 'top')) {
								WDW_FFWD_Library::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'ffwd_front_form_' . $ffwd, $ffwd_info['objects_per_page'], $ffwd, 'ffwd_masonry_thumbnails_' . $ffwd, 0, 'album', /*$options_row->enable_seo*/true, $ffwd_info['pagination_type']);
							}
            ?>
            <div id="ffwd_masonry_thumbnails_<?php echo $ffwd; ?>" class="ffwd_masonry_thumbnails_<?php echo $ffwd; ?>">
              <?php
              foreach ($ffwd_data as $ffwd_data_row) {
                $thumb_url = $ffwd_data_row->thumb_url;
                $main_url = $ffwd_data_row->main_url;
                $link_to_facebook = ($ffwd_data_row->link != "" && $ffwd_data_row->type != "link" && $ffwd_data_row->type != "video") ? $ffwd_data_row->link : "https://www.facebook.com/".$ffwd_data_row->object_id;


                if($ffwd_info['image_onclick_action']=='facebook')
                {
                $main_url=$link_to_facebook;

                }
                if($ffwd_info['image_onclick_action']=='none')
                {
                $main_url='#';

                }

                ?>
                <div class="ffwd_masonry_thumb_cont_<?php echo $ffwd; ?>">
                  <a <?php echo (' class="ffwd_lightbox_' . $ffwd . '"' . (/*$options_row->enable_seo*/true ? ' href="' . $main_url . '"' : '') . ' data-image-id="' . $ffwd_data_row->id . '"'); ?>>
                    <img class="ffwd_masonry_thumb_<?php echo $ffwd; ?>" id="<?php echo $ffwd_data_row->id; ?>" src="<?php echo $thumb_url; ?>" alt="<?php // echo $ffwd_data_row->alt; ?>" style="max-height: none !important;  max-width: none !important;" />
										<?php
										if ($show_name && $ffwd_info['masonry_hor_ver'] == 'vertical' && $ffwd_data_row->name != "") {
											?>
											<div class="ffwd_masonry_thumb_description_<?php echo $ffwd; ?>">
												<div><?php echo $ffwd_data_row->name; ?></div>
											</div>
											<?php
										}
										if ($is_video) {
											?>
											<div class="ffwd_play_icon_<?php echo $ffwd; ?>" title="<?php echo __('Play', 'ffwd'); ?>">
											</div>
											<?php
										}
                    if ($show_likes || $show_comments) {
											?>
                      <div class="ffwd_likes_comments_container_<?php echo $ffwd; ?>">
												<div class="ffwd_likes_comments_container_tab_<?php echo $ffwd; ?>" >
													<div class="ffwd_likes_comments_<?php echo $ffwd; ?>" >
														<?php if($show_likes) { ?>
															<div id="ffwd_likes_<?php echo $ffwd . '_' . $ffwd_data_row->id; ?>" class="ffwd_likes_<?php echo $ffwd; ?>">
																<span></span>
															</div>
														<?php } if($show_comments) { ?>
															<div id="ffwd_comments_<?php echo $ffwd . '_' . $ffwd_data_row->id; ?>" class="ffwd_comments_<?php echo $ffwd; ?>">
																<span></span>
															</div>
														<?php } ?>
														<div style="clear:both"></div>
													</div>
                        </div>
                      </div>
											<?php
										}
										?>
                  </a>
                </div>
                <?php
              }
              ?>
							<script>
							  var id_object_id_<?php echo $ffwd; ?> = '<?php echo json_encode($this->model->id_object_id_json); ?>',
										graph_url_<?php echo $ffwd; ?> = '<?php echo $this->model->graph_url; ?>';
									  ffwd_fill_likes_thumnail(JSON.parse(id_object_id_<?php echo $ffwd; ?>), '<?php echo $ffwd; ?>', graph_url_<?php echo $ffwd; ?>);
						  </script>
            </div>
            <script>
              <?php
              if ($ffwd_info['masonry_hor_ver'] == 'vertical') {
                ?>
                function ffwd_masonry_<?php echo $ffwd; ?>() {
                  var image_width = <?php echo $ffwd_info['thumb_width']; ?>;
                  var masonry_thumbnails_div_width = jQuery("#bwg_masonry_thumbnails_div_<?php echo $ffwd; ?>").width();
                  var cont_div_width = <?php echo $ffwd_info['image_max_columns'] * ($ffwd_info['thumb_width'] + 2 * ($theme_row->thumb_padding + $theme_row->thumb_border_width)); ?>;
                  var img_padding = <?php  echo $theme_row->masonry_thumb_padding; ?>;
									if (cont_div_width > masonry_thumbnails_div_width) {
                    cont_div_width = masonry_thumbnails_div_width;
                  }
                  var col_count = parseInt(cont_div_width / image_width);
                  if (!col_count) {
                    col_count = 1;
                  }
                  var top = new Array();
                  var left = new Array();
                  for (var i = 0; i < col_count; i++) {
                    top[i] = 0;
                    left[i] = i * image_width;
                  }
                  var div_width = col_count * image_width;
                  if (div_width > masonry_thumbnails_div_width) {
                    div_width = masonry_thumbnails_div_width;
                    jQuery(".ffwd_masonry_thumb_<?php echo $ffwd; ?>").attr("style", "max-width: " + div_width + "px");
                  }
                  else {
                    div_width = col_count * image_width;
                  }
									<?php
								  if ($show_name) {
                    ?>
                    jQuery(".ffwd_masonry_thumb_description_<?php echo $ffwd; ?>").attr("style", "max-width: " + image_width + "px");
                    <?php
                  }
									?>
									var min_top, index_min_top;
                  jQuery(".ffwd_masonry_thumb_cont_<?php echo $ffwd; ?>").each(function() {
                    min_top = Math.min.apply(Math, top);
                    index_min_top = jQuery.inArray(min_top, top);
                    jQuery(this).css({left: left[index_min_top], top: top[index_min_top]});
                    top[index_min_top] += jQuery(this).height();

										jQuery(this).find('.ffwd_likes_comments_container_<?php echo $ffwd; ?>').css({height: jQuery(this).find(".ffwd_masonry_thumb_<?php echo $ffwd; ?>").height() + (2 * img_padding)});
										jQuery(this).find('.ffwd_play_icon_<?php echo $ffwd; ?>').css({height: jQuery(this).find(".ffwd_masonry_thumb_<?php echo $ffwd; ?>").height() + (2 * img_padding)});
                  });
                  jQuery(".ffwd_masonry_thumbnails_<?php echo $ffwd; ?>").width(div_width);
                  jQuery(".ffwd_masonry_thumbnails_<?php echo $ffwd; ?>").height(Math.max.apply(Math, top));
                  jQuery(".ffwd_masonry_thumbnails_<?php echo $ffwd; ?>").css({visibility: 'visible'});
                  jQuery(".tablenav-pages_<?php echo $ffwd; ?>").css({visibility: 'visible'});
                  jQuery("#ajax_loading_<?php echo $ffwd; ?>").css({display: 'none'});
                }
                <?php
              }
              else {
                ?>
                function ffwd_masonry_<?php echo $ffwd; ?>() {
                  var image_height = <?php echo $ffwd_info['thumb_height']; ?>;
                  var cont_div_height = <?php echo $ffwd_info['image_max_columns'] * ($ffwd_info['thumb_height'] + 2 * ($theme_row->thumb_padding + $theme_row->thumb_border_width)); ?>;
                  var col_count = parseInt(cont_div_height / image_height);
                  if (!col_count) {
                    col_count = 1;
                  }
                  var top = new Array();
                  var left = new Array();
                  for (var i = 0; i < col_count; i++) {
                    left[i] = 0;
                    top[i] = i * image_height;
                  }
                  jQuery(".ffwd_masonry_thumb_cont_<?php echo $ffwd; ?>").each(function() {
                    min_left = Math.min.apply(Math, left);
                    index_min_left = jQuery.inArray(min_left, left);
                    jQuery(this).css({top: top[index_min_left], left: left[index_min_left]});
                    left[index_min_left] += jQuery(this).width();

										jQuery(this).find('.ffwd_likes_comments_container_<?php echo $ffwd; ?>').css({width: jQuery(this).width(), height: jQuery(this).height()});
                    jQuery(this).find('.ffwd_play_icon_<?php echo $ffwd; ?>').css({width: jQuery(this).width(), height: jQuery(this).height()});
									});
                  jQuery(".ffwd_masonry_thumbnails_<?php echo $ffwd; ?>").css({maxWidth: Math.max.apply(Math, left)});
                  jQuery(".ffwd_masonry_thumbnails_<?php echo $ffwd; ?>").css({visibility: 'visible'});
                  jQuery(".tablenav-pages_<?php echo $ffwd; ?>").css({visibility: 'visible'});
                  jQuery("#ajax_loading_<?php echo $ffwd; ?>").css({display: 'none'});
                }
                <?php
              }
              ?>
              jQuery(window).load(function() {
                ffwd_masonry_<?php echo $ffwd; ?>();
              });
              jQuery(window).resize(function() {
                ffwd_masonry_<?php echo $ffwd; ?>();
              });
              jQuery(".ffwd_masonry_thumb_cont_<?php echo $ffwd; ?> img").error(function() {
                jQuery(this).height(100);
                jQuery(this).width(100);
              });
              function ffwd_masonry_ajax_<?php echo $ffwd; ?>(already_loaded) {
                var cccount_masonry_ajax = already_loaded;
                var tot_cccount_masonry_ajax = jQuery(".ffwd_masonry_thumb_cont_<?php echo $ffwd; ?> img").length;
                jQuery(".ffwd_masonry_thumb_cont_<?php echo $ffwd; ?>  img").on("load", function() {
                  if (++cccount_masonry_ajax == tot_cccount_masonry_ajax) {
                    window["ffwd_masonry_<?php echo $ffwd; ?>"]();
                  }
                });
                jQuery(".ffwd_masonry_thumb_cont_<?php echo $ffwd; ?> img").error(function() {
                  jQuery(this).height(100);
                  jQuery(this).width(100);
                  if (++cccount_masonry_ajax == tot_cccount_masonry_ajax) {
                    window["ffwd_masonry_<?php echo $ffwd; ?>"]();
                  }
                });
              }
              <?php
              if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'):
              ?>
                /* If page is called by AJAX use this instead of window.load.*/
                ffwd_masonry_ajax_<?php echo $ffwd; ?>(0);
              <?php
              endif;
              ?>
            </script>
            <?php
							if ($ffwd_info['pagination_type']  && $ffwd_info['objects_per_page'] && ($theme_row->page_nav_position == 'bottom')) {
								WDW_FFWD_Library::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'ffwd_front_form_' . $ffwd, $ffwd_info['objects_per_page'], $ffwd, 'ffwd_masonry_thumbnails_' . $ffwd, 0, 'album', /*$options_row->enable_seo*/true, $ffwd_info['pagination_type']);
							}
            ?>
          </div>
        </form>
				<?php if($ffwd_info['type'] == "page" && $ffwd_info["fb_plugin"] && $ffwd_info["page_plugin_pos"] == "bottom") { ?>
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
      <?php

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
          ?>
      function ffwd_gallery_box_<?php echo $ffwd; ?>(image_id) {
        ffwd_createpopup('<?php echo addslashes(add_query_arg($ffwd_info_array, admin_url('admin-ajax.php'))); ?>&image_id=' + image_id, '<?php echo $ffwd; ?>', '<?php echo $ffwd_info['popup_width']; ?>', '<?php echo $ffwd_info['popup_height']; ?>', 1, 'testpopup', 5);
      }


      function ffwd_document_ready_<?php echo $ffwd; ?>() {
        <?php if($ffwd_info['image_onclick_action']=='lightbox') { ?>
        jQuery('body').on('click', '.ffwd_lightbox_<?php echo $ffwd; ?>', function(e) {
          e.stopPropagation();

          ffwd_gallery_box_<?php echo $ffwd; ?>(jQuery(this).attr("data-image-id"));

          return false;
        });

        <?php  } ?>
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
