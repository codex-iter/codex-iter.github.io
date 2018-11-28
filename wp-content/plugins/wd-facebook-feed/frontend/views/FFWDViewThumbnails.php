<?php
class FFWDViewThumbnails {
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
$ffwd_info['thumb_width']= $params['thumb_width'];
$ffwd_info['thumb_height']= $params['thumb_height'];

}

    $theme_row = $this->model->get_theme_row_data($ffwd_info['theme']);


    if (!$theme_row) {
      echo WDW_FFWD_Library::message(__('There is no theme selected or the theme was deleted.', 'ffwd'), 'error');
      return;
    }

    $ffwd_data = $this->model->get_ffwd_data($params['fb_id'], $ffwd_info['objects_per_page'], /*$ffwd_info['sort_by']*/'', $ffwd, /*$sort_direction*/ ' ASC');
    $ffwd_data_count = count($ffwd_data);
    if (!$ffwd_data) {
      echo WDW_FFWD_Library::message(__('There are no objects in this facebook feed.', 'ffwd'), 'error');
    }
    $options_row = $this->model->get_ffwd_options();
    if ($ffwd_info['pagination_type'] && $ffwd_info['objects_per_page']) {
      $page_nav = $this->model->page_nav($params['fb_id'], $ffwd_info['objects_per_page'], $ffwd);
    }

    $rgb_page_nav_font_color = WDW_FFWD_Library::spider_hex2rgb($theme_row->page_nav_font_color);
    $rgb_thumbs_bg_color = WDW_FFWD_Library::spider_hex2rgb($theme_row->thumbs_bg_color);
		$is_video = ($ffwd_info['content'] == 'videos') ? true : false;
		$show_likes = ($ffwd_info['thumb_likes'] == '1') ? true : false;
		$show_comments = ($ffwd_info['thumb_comments'] == '1') ? true : false;
		$show_name = ($ffwd_info['thumb_name'] == '1') ? true : false;
    ?>
    <style>
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
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_standart_thumb_<?php echo $ffwd; ?> {
        display: inline-block;
        text-align: center;
        margin: <?php echo $theme_row->thumb_margin; ?>px;
        position: relative;
				vertical-align: <?php echo ($theme_row->thumb_title_pos == 'top') ? 'bottom' : 'top'; ?>;
				max-width: 100%;
      }

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_standart_thumb_<?php echo $ffwd; ?> {
        display: inline-block;
        text-align: center;
        margin: <?php echo $theme_row->thumb_margin; ?>px;
        position: relative;
				vertical-align: <?php echo ($theme_row->thumb_title_pos == 'top') ? 'bottom' : 'top'; ?>;
				max-width: 100%;
      }
			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_title_spun1_<?php echo $ffwd; ?> {
				width: <?php echo $ffwd_info['thumb_width']; ?>px;
				cursor: default;
				max-width: 100%;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_page_plugin_<?php echo $ffwd; ?>{
				margin: 30px 0px 0px 0px;
				background-color: rgba(0, 0, 0, 0);
				text-align: <?php echo $theme_row->thumb_align; ?>;
				width: 100%;
				position: relative;
      }

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_title_spun1_<?php echo $ffwd; ?> .ffwd_title_spun2_<?php echo $ffwd; ?> {
        color: #<?php echo $theme_row->thumb_title_font_color; ?>;
        font-family: <?php echo $theme_row->thumb_title_font_style; ?>;
        font-size: <?php echo $theme_row->thumb_title_font_size; ?>px;
        font-weight: <?php echo $theme_row->thumb_title_font_weight; ?>;
        height: inherit;
        padding: <?php echo $theme_row->thumb_title_margin; ?>px;
        text-shadow: <?php echo $theme_row->thumb_title_shadow; ?>;
        vertical-align: middle;
        width: inherit;
				max-width: 100%;
        word-wrap: break-word;
				box-sizing: border-box;
				text-align: left;
                line-height: 19px;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_likes_comments_container_<?php echo $ffwd; ?> {
				display: <?php echo ($ffwd_info['thumb_width'] < 150) ? "none" : "block"; ?>;
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
				max-width: 100%;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_likes_comments_container_<?php echo $ffwd; ?> .ffwd_likes_comments_container_tab_<?php echo $ffwd; ?> {
				display: table;
				height: inherit;
				width: 100%;
				max-width: 100%
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

      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_likes_<?php echo $ffwd; ?> span, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_comments_<?php echo $ffwd; ?> span, #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_shares_<?php echo $ffwd; ?> span {
        padding: 0px 3px 0px 3px;
        background-color: rgba(214, 214, 214, 0.07);
        border-radius: 0px;
				color: #<?php echo $theme_row->thumb_like_comm_font_color; ?>;
        font-family: <?php echo $theme_row->thumb_like_comm_font_style; ?>;
        font-size: <?php echo $theme_row->thumb_like_comm_font_size; ?>px;
				box-shadow: <?php echo $theme_row->thumb_like_comm_shadow; ?>;
				font-weight: <?php echo $theme_row->thumb_like_comm_font_weight; ?>;
      }

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_standart_thumb_spun1_<?php echo $ffwd; ?> {
        -moz-box-sizing: content-box;
        box-sizing: content-box;
        background-color: #<?php echo $theme_row->thumb_bg_color; ?>;
        display: inline-block;
        height: <?php echo $ffwd_info['thumb_height']; ?>px;
        padding: <?php echo $theme_row->thumb_padding; ?>px;
        opacity: <?php echo number_format($theme_row->thumb_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->thumb_transparent; ?>);
        text-align: center;
        vertical-align: middle;
				border: <?php echo $theme_row->thumb_border_width; ?>px <?php echo $theme_row->thumb_border_style; ?> #<?php echo $theme_row->thumb_border_color; ?>;
        border-radius: <?php echo $theme_row->thumb_border_radius; ?>;
        box-shadow: <?php echo $theme_row->thumb_box_shadow; ?>;
        <?php echo ($theme_row->thumb_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
        width: <?php echo $ffwd_info['thumb_width']; ?>px;
				max-width: 100%;
        z-index: 100;
      }
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_standart_thumb_spun1_<?php echo $ffwd; ?>:hover {
				-ms-transform: <?php echo $theme_row->thumb_hover_effect; ?>(<?php echo $theme_row->thumb_hover_effect_value; ?>);
				-webkit-transform: <?php echo $theme_row->thumb_hover_effect; ?>(<?php echo $theme_row->thumb_hover_effect_value; ?>);
				transform: <?php echo $theme_row->thumb_hover_effect; ?>(<?php echo $theme_row->thumb_hover_effect_value; ?>);
				backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        -ms-backface-visibility: hidden;
        opacity: 1;
        filter: Alpha(opacity=100);
        z-index: 102;
        position: relative;
      }

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_standart_thumb_spun2_<?php echo $ffwd; ?> {
        display: inline-block;
        height: <?php echo $ffwd_info['thumb_height']; ?>px;
        overflow: hidden;
        width: <?php echo $ffwd_info['thumb_width']; ?>px;
				max-width: 100%;
				position: relative;
				box-sizing: border-box;
				border-radius: <?php echo $theme_row->thumb_border_radius; ?>;
      }


			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_standart_thumb_spun2_<?php echo $ffwd; ?>:hover .ffwd_likes_comments_container_<?php echo $ffwd; ?> {
				opacity: 1;
			}

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_standart_thumbnails_<?php echo $ffwd; ?> {
				background-color: rgba(<?php echo $rgb_thumbs_bg_color['red']; ?>, <?php echo $rgb_thumbs_bg_color['green']; ?>, <?php echo $rgb_thumbs_bg_color['blue']; ?>, <?php echo number_format($theme_row->thumb_bg_transparent / 100, 2, ".", ""); ?>);

				display: inline-block;
        font-size: 0;
        /*max-width: <?php echo $ffwd_info['image_max_columns'] * ($ffwd_info['thumb_width'] + 2 * (2 + $theme_row->thumb_margin + $theme_row->thumb_padding + $theme_row->thumb_border_width)); ?>px;*/
        max-width: 100%;
        text-align: <?php echo $theme_row->thumb_align; ?>;
      }

			#ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> .ffwd_standart_thumbnails_<?php echo $ffwd; ?> > a {
        outline: none;
				border-style: none;
      }


			.ffwd_standart_thumb_<?php echo $ffwd; ?>
			{
				background-color: #<?php echo $theme_row->thumb_bg_color ?>;
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
      #ffwd_container1_<?php echo $ffwd; ?> #ffwd_container2_<?php echo $ffwd; ?> #spider_popup_overlay_<?php echo $ffwd; ?> {

        background-color: #000000;
        opacity: 0.70;
        filter: Alpha(opacity=70)
      }

		  .ffwd_play_icon_<?php echo $ffwd; ?> {
        width: inherit;
        height: inherit;
        position: absolute;
				background: url('<?php echo WD_FFWD_URL . '/images/feed/play_gray.png' ?>') no-repeat center center;
				background-size: <?php echo ($ffwd_info['thumb_width'] < 150) ? 20 : 40; ?>px;
				z-index: 1;
				transition: all 0.5s;
				border-radius: <?php echo $theme_row->thumb_border_radius; ?>;
				max-width: 100%;
      }
			.ffwd_play_icon_<?php echo $ffwd; ?>:hover {
				background: url('<?php echo WD_FFWD_URL . '/images/feed/play.png' ?>') no-repeat center center;
				background-size: <?php echo ($ffwd_info['thumb_width'] < 150) ? 20 : 40; ?>px;
				border-radius: <?php echo $theme_row->thumb_border_radius; ?>;
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
			.ffwd_more_dotes, .ffwd_less_dotes {
				color: #A7A7A7;
				font-size: 11px;
				font-weight: normal;
				cursor: pointer;
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
          <div style="background-color:rgba(0, 0, 0, 0); text-align: <?php echo $theme_row->thumb_align; ?>; width:100%; position: relative;">
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
              WDW_FFWD_Library::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'ffwd_front_form_' . $ffwd, $ffwd_info['objects_per_page'], $ffwd, 'ffwd_standart_thumbnails_' . $ffwd, 0, 'album', /*$options_row->enable_seo*/true, $ffwd_info['pagination_type']);
            }
            ?>
            <div id="ffwd_standart_thumbnails_<?php echo $ffwd; ?>" class="ffwd_standart_thumbnails_<?php echo $ffwd; ?>">
              <?php
              foreach ($ffwd_data as $ffwd_data_row) {
                $thumb_url = isset($ffwd_data_row->thumb_url) && $ffwd_data_row->thumb_url!='' ? $ffwd_data_row->thumb_url : plugins_url('../../images/ffwd/no-image.png', __FILE__ );

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

                $image_thumb_width = $ffwd_info['thumb_width'];
                if($ffwd_data_row->width != '' && $ffwd_data_row->height != ''){
									$resolution_w = intval($ffwd_data_row->width);
									$resolution_h = intval($ffwd_data_row->height);
									if($resolution_w != 0 && $resolution_h != 0){
										$scale = $scale = max($ffwd_info['thumb_width'] / $resolution_w, $ffwd_info['thumb_height'] / $resolution_h);
										$image_thumb_width = $resolution_w * $scale;
										$image_thumb_height = $resolution_h * $scale;
									}
									else{
										$image_thumb_width = $ffwd_info['thumb_width'];
										$image_thumb_height = $ffwd_info['thumb_height'];
									}
								}
								else{
									$image_thumb_width = $ffwd_info['thumb_width'];
									$image_thumb_height = $ffwd_info['thumb_height'];
								}
                $scale = max($ffwd_info['thumb_width'] / $image_thumb_width, $ffwd_info['thumb_height'] / $image_thumb_height);
                $image_thumb_width *= $scale;
                $image_thumb_height *= $scale;
                $thumb_left = ($ffwd_info['thumb_width'] - $image_thumb_width) / 2;
                $thumb_top = ($ffwd_info['thumb_height'] - $image_thumb_height) / 2;
                ?>
                <a <?php echo 'class="ffwd_lightbox_' . $ffwd . '"' . (/*$options_row->enable_seo*/ true ? ' href="' . $main_url . '"' : '') . ' data-image-id="' . $ffwd_data_row->id . '"'; ?> >
                  <div class="ffwd_standart_thumb_<?php echo $ffwd; ?>">
                    <?php
										if ($theme_row->thumb_title_pos == 'top' && $show_name) {
											?>
											<div class="ffwd_title_spun1_<?php echo $ffwd; ?>">
												<div class="ffwd_title_spun2_<?php echo $ffwd; ?>">
													<?php
												  $name = $this->model->see_less_more($ffwd_data_row->name);
													echo $name;
													?>
												</div>
											</div>
											<?php
										}
										?>
										<div class="ffwd_standart_thumb_spun1_<?php echo $ffwd; ?>">
                      <div class="ffwd_standart_thumb_spun2_<?php echo $ffwd; ?>">
                        <?php
                        if ($is_video) {
                          ?>
                          <div class="ffwd_play_icon_<?php echo $ffwd; ?>" title="<?php echo __('Play', 'ffwd'); ?>">
                          </div>
                          <?php
                        }
												if ($show_likes || $show_comments || $show_shares) {
													?>
													<div class="ffwd_likes_comments_container_<?php echo $ffwd; ?>" >
														<div class="ffwd_likes_comments_container_tab_<?php echo $ffwd; ?>" >
															<div class="ffwd_likes_comments_<?php echo $ffwd; ?>" >
																<?php if($show_likes && $ffwd_info['content'] != "events") { ?>
																	<div id="ffwd_likes_<?php echo $ffwd . '_' . $ffwd_data_row->id; ?>" class="ffwd_likes_<?php echo $ffwd; ?>" >
																		<span></span>
																	</div>
																<?php } if($show_comments) { ?>
																	<div id="ffwd_comments_<?php echo $ffwd . '_' . $ffwd_data_row->id; ?>" class="ffwd_comments_<?php echo $ffwd; ?>" >
																		<span></span>
																	</div>
																<?php }?>
															  <div style="clear:both"></div>
															</div>
														</div>
													</div>
													<?php
												}
                        ?>
                        <img class="ffwd_standart_thumb_img_<?php echo $ffwd; ?>" style="max-height: none !important;  max-width: none !important; padding: 0 !important; width:<?php echo $image_thumb_width; ?>px; height:<?php echo $image_thumb_height; ?>px; margin-left: <?php echo $thumb_left; ?>px; margin-top: <?php echo $thumb_top; ?>px;" id="<?php echo $ffwd_data_row->id; ?>" src="<?php echo $thumb_url; ?>" alt="<?php echo $ffwd_data_row->name; ?>" />
                      </div>
                    </div>
										<?php
										if ($theme_row->thumb_title_pos == 'bottom' && $show_name) {
											?>
											<div class="ffwd_title_spun1_<?php echo $ffwd; ?>">
												<div class="ffwd_title_spun2_<?php echo $ffwd; ?>">
													<?php
														$name = $this->model->see_less_more($ffwd_data_row->name);
														echo $name;
													?>
												</div>
											</div>
											<?php
										}
										?>
                  </div>
                </a>
                <?php
              }
              ?>
							<script>
							  var id_object_id_<?php echo $ffwd; ?> = '<?php echo json_encode($this->model->id_object_id_json); ?>';
									  graph_url_<?php echo $ffwd; ?> = '<?php echo $this->model->graph_url; ?>';
										ffwd_fill_likes_thumnail(JSON.parse(id_object_id_<?php echo $ffwd; ?>), '<?php echo $ffwd; ?>', graph_url_<?php echo $ffwd; ?>);
						  </script>
            </div>
            <?php
            if ($ffwd_info['pagination_type']  && $ffwd_info['objects_per_page'] && ($theme_row->page_nav_position == 'bottom')) {
              WDW_FFWD_Library::ajax_html_frontend_page_nav($theme_row, $page_nav['total'], $page_nav['limit'], 'ffwd_front_form_' . $ffwd, $ffwd_info['objects_per_page'], $ffwd, 'ffwd_standart_thumbnails_' . $ffwd, 0, 'album', /*$options_row->enable_seo*/true, $ffwd_info['pagination_type']);
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

        $aaaa = json_encode($this->model->id_object_id_json);
        $query_url = wp_nonce_url( admin_url('admin-ajax.php'), '', 'bwg_nonce' );
      ?>


    <script>
      jQuery(document).ready(function () {
        ffwd_document_ready_<?php echo $ffwd; ?>();
        jQuery('body').on('click', '.ffwd_title_spun2_<?php echo $ffwd; ?>', function(e) {
					e.stopPropagation();
          e.preventDefault();
					var name_hide = jQuery(this).parent().find('.ffwd_thumbnail_name_hide');
					if(name_hide.css('display') == "inline")
						name_hide.css('display', 'none');
					else
						name_hide.css('display', 'inline');
        });
				jQuery('body').on('click', '.ffwd_title_spun1_<?php echo $ffwd; ?>', function(e) {
					e.stopPropagation();
					e.preventDefault();
        });
      });
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
