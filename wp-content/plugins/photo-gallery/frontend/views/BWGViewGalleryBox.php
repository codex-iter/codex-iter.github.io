<?php
class BWGViewGalleryBox {

  private $model;

  public function __construct($model) {
    $this->model = $model;
  }

  public function display() {
    require_once(BWG()->plugin_dir . '/framework/WDWLibraryEmbed.php');

    $current_url = isset($_GET['current_url']) ? esc_html($_GET['current_url']) : '';
    $tag = (isset($_GET['tags']) ? esc_html($_GET['tags']) : 0);
    $gallery_id = WDWLibrary::esc_script('get', 'gallery_id', 0, 'int');
    $bwg = (isset($_GET['current_view']) ? esc_html($_GET['current_view']) : 0);
    $current_image_id = WDWLibrary::esc_script('get', 'image_id', 0, 'int');
    $theme_id = (isset($_GET['theme_id']) ? esc_html($_GET['theme_id']) : 1);
    $thumb_width  = BWG()->options->thumb_width;
    $thumb_height = BWG()->options->thumb_height;
    $open_with_fullscreen = WDWLibrary::esc_script('get', 'open_with_fullscreen', 0, 'int');
    $open_with_autoplay = WDWLibrary::esc_script('get', 'open_with_autoplay', 0, 'int');
    $image_width = WDWLibrary::esc_script('get', 'image_width', 800, 'int');
    $image_height = WDWLibrary::esc_script('get', 'image_height', 500, 'int');
    $image_effect = WDWLibrary::esc_script('get', 'image_effect', 'fade');
    $sort_by = (isset($_GET['wd_sor']) ? esc_html($_GET['wd_sor']) : 'order');
    $order_by = (isset($_GET['wd_ord']) ? esc_html($_GET['wd_ord']) : 'asc');
    $enable_image_filmstrip = BWG()->is_pro ? WDWLibrary::esc_script('get', 'enable_image_filmstrip', 0, 'int') : FALSE;
    $enable_image_fullscreen = (isset($_GET['enable_image_fullscreen']) ? esc_html($_GET['enable_image_fullscreen']) : 0);
    $popup_enable_info = (isset($_GET['popup_enable_info']) ? esc_html($_GET['popup_enable_info']) : 1);
    $popup_info_always_show = (isset($_GET['popup_info_always_show']) ? esc_html($_GET['popup_info_always_show']) : 0);
    $popup_info_full_width = (isset($_GET['popup_info_full_width']) ? esc_html($_GET['popup_info_full_width']) : 0);
    $popup_enable_rate = WDWLibrary::esc_script('get', 'popup_enable_rate', 0, 'int');
    $popup_hit_counter = (isset($_GET['popup_hit_counter']) ? esc_html($_GET['popup_hit_counter']) : 0);
    $open_ecommerce = WDWLibrary::esc_script('get', 'open_ecommerce', 0, 'int');
    $slideshow_effect_duration = (isset($_GET['slideshow_effect_duration']) ? floatval($_GET['slideshow_effect_duration']) : 1);

    $slideshow_interval = (isset($_GET['slideshow_interval']) ? (int) $_GET['slideshow_interval'] : 5);
    $enable_image_ctrl_btn = (isset($_GET['enable_image_ctrl_btn']) ? esc_html($_GET['enable_image_ctrl_btn']) : 0);
    $open_comment = (BWG()->is_pro && isset($_GET['open_comment']) ? esc_html($_GET['open_comment']) : 0);
    $enable_comment_social = (BWG()->is_pro && isset($_GET['enable_comment_social']) ? esc_html($_GET['enable_comment_social']) : 0);
    $enable_image_facebook = (BWG()->is_pro && isset($_GET['enable_image_facebook']) ? esc_html($_GET['enable_image_facebook']) : 0);
    $enable_image_twitter = (BWG()->is_pro && isset($_GET['enable_image_twitter']) ? esc_html($_GET['enable_image_twitter']) : 0);
    $enable_image_google = (BWG()->is_pro && isset($_GET['enable_image_google']) ? esc_html($_GET['enable_image_google']) : 0);
    $enable_image_ecommerce = BWG()->is_pro ? WDWLibrary::esc_script('get', 'enable_image_ecommerce', 0, 'int') : 0;
    $enable_image_pinterest = (BWG()->is_pro &&  isset($_GET['enable_image_pinterest']) ? esc_html($_GET['enable_image_pinterest']) : 0);
    $enable_image_tumblr = (BWG()->is_pro && isset($_GET['enable_image_tumblr']) ? esc_html($_GET['enable_image_tumblr']) : 0);

    $popup_enable_email = (BWG()->is_pro && isset($_GET['popup_enable_email']) ? esc_html($_GET['popup_enable_email']) : 0);
    $popup_enable_captcha = (BWG()->is_pro && isset($_GET['popup_enable_captcha']) ? esc_html($_GET['popup_enable_captcha']) : 0);
    $comment_moderation = (BWG()->is_pro && isset($_GET['comment_moderation']) ? esc_html($_GET['comment_moderation']) : 0);
    $autohide_lightbox_navigation = (isset($_GET['autohide_lightbox_navigation']) ? esc_html($_GET['autohide_lightbox_navigation']) : BWG()->options->autohide_lightbox_navigation);
    $popup_enable_fullsize_image = (isset($_GET['popup_enable_fullsize_image']) ? esc_html($_GET['popup_enable_fullsize_image']) : BWG()->options->popup_enable_fullsize_image);
    $popup_enable_download = (isset($_GET['popup_enable_download']) ? esc_html($_GET['popup_enable_download']) : BWG()->options->popup_enable_download);
    $show_image_counts = (isset($_GET['show_image_counts']) ? esc_html($_GET['show_image_counts']) : BWG()->options->show_image_counts);
    $enable_loop = (isset($_GET['enable_loop']) ? esc_html($_GET['enable_loop']) : BWG()->options->enable_loop);
    $enable_addthis = (BWG()->is_pro && isset($_GET['enable_addthis']) ? esc_html($_GET['enable_addthis']) : 0);
    $addthis_profile_id = (BWG()->is_pro && isset($_GET['addthis_profile_id']) ? esc_html($_GET['addthis_profile_id']) : '');

    $watermark_type = (isset($_GET['watermark_type']) ? esc_html($_GET['watermark_type']) : 'none');
    $watermark_text = (isset($_GET['watermark_text']) ? esc_html($_GET['watermark_text']) : '');
    $watermark_font_size = (isset($_GET['watermark_font_size']) ? (int) $_GET['watermark_font_size'] : 12);
    $watermark_font = (isset($_GET['watermark_font']) ? WDWLibrary::get_fonts(esc_html($_GET['watermark_font'])) : 'Arial');
    $watermark_color = (isset($_GET['watermark_color']) ? esc_html($_GET['watermark_color']) : 'FFFFFF');
    $watermark_opacity = (isset($_GET['watermark_opacity']) ? (int) $_GET['watermark_opacity'] : 30);
    $watermark_position = explode('-', (isset($_GET['watermark_position']) ? esc_html($_GET['watermark_position']) : 'bottom-right'));
    $watermark_link = (isset($_GET['watermark_link']) ? esc_html($_GET['watermark_link']) : '');
    $watermark_url = (isset($_GET['watermark_url']) ? esc_html($_GET['watermark_url']) : '');
    $watermark_width = (isset($_GET['watermark_width']) ? (int) $_GET['watermark_width'] : 90);
    $watermark_height = (isset($_GET['watermark_height']) ? (int) $_GET['watermark_height'] : 90);

	  $image_right_click =  isset(BWG()->options->image_right_click) ? BWG()->options->image_right_click : 0;

    require_once BWG()->plugin_dir . "/frontend/models/model.php";
	  $model_site = new BWGModelSite();
    $theme_row = $model_site->get_theme_row_data($theme_id);

    $filmstrip_direction = 'horizontal';
    if ($theme_row->lightbox_filmstrip_pos == 'right' || $theme_row->lightbox_filmstrip_pos == 'left') {
      $filmstrip_direction = 'vertical';
    }
	  $image_filmstrip_height = 0;
    $image_filmstrip_width = 0;
    if ( $enable_image_filmstrip ) {
      if ( $filmstrip_direction == 'horizontal' ) {
        $image_filmstrip_height = WDWLibrary::esc_script('get', 'image_filmstrip_height', 20, 'int');
        $thumb_ratio = $thumb_width / $thumb_height;
        $image_filmstrip_width = round($thumb_ratio * $image_filmstrip_height);
      }
      else {
        $image_filmstrip_width = WDWLibrary::esc_script('get', 'image_filmstrip_height', 50, 'int');
        $thumb_ratio = $thumb_height / $thumb_width;
        $image_filmstrip_height = round($thumb_ratio * $image_filmstrip_width);
      }
    }
    $image_rows = $this->model->get_image_rows_data($gallery_id, $bwg, $sort_by, $order_by, $tag);

    $image_id = (isset($_POST['image_id']) ? (int) $_POST['image_id'] : $current_image_id);

    if ( function_exists('BWGEC') && $enable_image_ecommerce == 1 ) {
      $image_pricelist = $this->model->get_image_pricelist($image_id);
      $pricelist_id = $image_pricelist ? $image_pricelist : 0;
    }
    $pricelist_data = $this->model->get_image_pricelists($pricelist_id);

    $params_array = array(
      'action' => 'GalleryBox',
      'image_id' => $image_id,
      'gallery_id' => $gallery_id,
      'tags' => $tag,
      'theme_id' => $theme_id,
      'thumb_width' => $thumb_width,
      'thumb_height' => $thumb_height,
      'open_with_fullscreen' => $open_with_fullscreen,
      'image_width' => $image_width,
      'image_height' => $image_height,
      'image_effect' => $image_effect,
      'wd_sor' => $sort_by,
      'wd_ord' => $order_by,
      'enable_image_filmstrip' => $enable_image_filmstrip,
      'image_filmstrip_height' => $image_filmstrip_height,
      'enable_image_ctrl_btn' => $enable_image_ctrl_btn,
      'enable_image_fullscreen' => $enable_image_fullscreen,
      'popup_enable_info' => $popup_enable_info,
      'popup_info_always_show' => $popup_info_always_show,
      'popup_info_full_width' => $popup_info_full_width,
      'popup_hit_counter' => $popup_hit_counter,
      'popup_enable_rate' => $popup_enable_rate,
      'slideshow_interval' => $slideshow_interval,
      'enable_comment_social' => $enable_comment_social,
      'enable_image_facebook' => $enable_image_facebook,
      'enable_image_twitter' => $enable_image_twitter,
      'enable_image_google' => $enable_image_google,
      'enable_image_ecommerce' => $enable_image_ecommerce,
      'enable_image_pinterest' => $enable_image_pinterest,
      'enable_image_tumblr' => $enable_image_tumblr,
      'watermark_type' => $watermark_type,
      'slideshow_effect_duration' => $slideshow_effect_duration,
      'popup_enable_email' => $popup_enable_email,
      'popup_enable_captcha' => $popup_enable_captcha,
      'comment_moderation' => $comment_moderation,
    );
    if ($watermark_type != 'none') {
      $params_array['watermark_link'] = $watermark_link;
      $params_array['watermark_opacity'] = $watermark_opacity;
      $params_array['watermark_position'] = $watermark_position;
    }
    if ($watermark_type == 'text') {
      $params_array['watermark_text'] = $watermark_text;
      $params_array['watermark_font_size'] = $watermark_font_size;
      $params_array['watermark_font'] = $watermark_font;
      $params_array['watermark_color'] = $watermark_color;
    }
    elseif ($watermark_type == 'image') {
      $params_array['watermark_url'] = $watermark_url;
      $params_array['watermark_width'] = $watermark_width;
      $params_array['watermark_height'] = $watermark_height;
    }
    $popup_url = add_query_arg(array($params_array), admin_url('admin-ajax.php'));

    $filmstrip_thumb_margin = trim($theme_row->lightbox_filmstrip_thumb_margin);

    $margins_split = explode(" ", $filmstrip_thumb_margin);
	$all_images_top_bottom_space = 0;
	$all_images_right_left_space = 0;
    $filmstrip_thumb_margin_top = 0;
    $filmstrip_thumb_margin_right = 0;
    $filmstrip_thumb_margin_bottom = 0;
    $filmstrip_thumb_margin_left = 0;
	if ( count($margins_split) == 1 ) {
		$filmstrip_thumb_margin_top = (int) $margins_split[0];
		$filmstrip_thumb_margin_right = (int) $margins_split[0];
		$filmstrip_thumb_margin_bottom = (int) $margins_split[0];
		$filmstrip_thumb_margin_left = (int) $margins_split[0];
	}
	if ( count($margins_split) == 2 ) {
		$filmstrip_thumb_margin_top = (int) $margins_split[0];
		$filmstrip_thumb_margin_right = (int) $margins_split[1];
		$filmstrip_thumb_margin_bottom = (int) $margins_split[0];
		$filmstrip_thumb_margin_left = (int) $margins_split[1];
	}
	if ( count($margins_split) == 3 ) {
		$filmstrip_thumb_margin_top = (int) $margins_split[0];
		$filmstrip_thumb_margin_right = (int) $margins_split[1];
		$filmstrip_thumb_margin_bottom = (int) $margins_split[2];
		$filmstrip_thumb_margin_left = (int) $margins_split[1];
	}
	if ( count($margins_split) == 4 ) {
		$filmstrip_thumb_margin_top = (int) $margins_split[0];
		$filmstrip_thumb_margin_right = (int) $margins_split[1];
		$filmstrip_thumb_margin_bottom = (int) $margins_split[2];
		$filmstrip_thumb_margin_left = (int) $margins_split[3];
	}
	$filmstrip_thumb_top_bottom_space =  $filmstrip_thumb_margin_top + $filmstrip_thumb_margin_bottom;
	$filmstrip_thumb_right_left_space =  $filmstrip_thumb_margin_right + $filmstrip_thumb_margin_left;
	$all_images_top_bottom_space = count($image_rows) * $filmstrip_thumb_top_bottom_space;
	$all_images_right_left_space = count($image_rows) * $filmstrip_thumb_right_left_space;
    $temp_iterator = ($filmstrip_direction == 'horizontal' ? 1 : 0);
    $rgb_bwg_image_info_bg_color = WDWLibrary::spider_hex2rgb($theme_row->lightbox_info_bg_color);
    $rgb_bwg_image_hit_bg_color = WDWLibrary::spider_hex2rgb($theme_row->lightbox_hit_bg_color);
    $rgb_lightbox_ctrl_cont_bg_color = WDWLibrary::spider_hex2rgb($theme_row->lightbox_ctrl_cont_bg_color);
    if (!$enable_image_filmstrip) {
      if ($theme_row->lightbox_filmstrip_pos == 'left') {
        $theme_row->lightbox_filmstrip_pos = 'top';
      }
      if ($theme_row->lightbox_filmstrip_pos == 'right') {
        $theme_row->lightbox_filmstrip_pos = 'bottom';
      }
    }
    $left_or_top = 'left';
    $width_or_height= 'width';
    $outerWidth_or_outerHeight = 'outerWidth';
    if (!($filmstrip_direction == 'horizontal')) {
      $left_or_top = 'top';
      $width_or_height = 'height';
      $outerWidth_or_outerHeight = 'outerHeight';
    }
    $lightbox_bg_transparent = (isset($theme_row->lightbox_bg_transparent)) ? $theme_row->lightbox_bg_transparent : 100;
    $lightbox_bg_color = WDWLibrary::spider_hex2rgb($theme_row->lightbox_bg_color);

    $current_filename = '';

    if (BWG()->is_pro && $enable_addthis && $addthis_profile_id) {
      ?>
      <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=<?php echo $addthis_profile_id; ?>" async="async"></script>
      <?php
    }
    ?>
    <style>
      .spider_popup_wrap .bwg-loading {
        background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
      }
      .bwg_inst_play_btn_cont {
        width: 100%;
        height: 100%;
        position: absolute;
        z-index: 1;
        cursor: pointer;
        top: 0;
      }
      .bwg_inst_play {
        position: absolute;
        width: 50px;
        height: 50px;
        background-image: url('<?php echo BWG()->plugin_url . '/images/play.png'; ?>');
        background-position: center center;
        background-repeat: no-repeat;
        background-size: cover;
        transition: background-image 0.2s ease-out;
        -ms-transition: background-image 0.2s ease-out;
        -moz-transition: background-image 0.2s ease-out;
        -webkit-transition: background-image 0.2s ease-out;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: auto;
      }
      .bwg_inst_play:hover {
          background: url(<?php echo BWG()->plugin_url . '/images/play_hover.png'; ?>) no-repeat;
          background-position: center center;
          background-repeat: no-repeat;
          background-size: cover;
      }

      .spider_popup_wrap * {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }
      .spider_popup_wrap {
        background-color: rgba(<?php echo $lightbox_bg_color['red']; ?>, <?php echo $lightbox_bg_color['green']; ?>, <?php echo $lightbox_bg_color['blue']; ?>, <?php echo number_format($lightbox_bg_transparent/ 100, 2, ".", ""); ?>);
        display: inline-block;
        left: 50%;
        outline: medium none;
        position: fixed;
        text-align: center;
        top: 50%;
        z-index: 100000;
      }
      .bwg_popup_image {
        max-width: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>px;
        max-height: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>px;
        vertical-align: middle;
        display: inline-block;
      }
      .bwg_popup_embed {
        /*width: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>px;
        height: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>px;*/
        width: 100%;
        height: 100%;
        vertical-align: middle;
        text-align: center;
        display: table;
      }
      .bwg_ctrl_btn {
        color: #<?php echo $theme_row->lightbox_ctrl_btn_color; ?>;
        font-size: <?php echo $theme_row->lightbox_ctrl_btn_height; ?>px;
        margin: <?php echo $theme_row->lightbox_ctrl_btn_margin_top; ?>px <?php echo $theme_row->lightbox_ctrl_btn_margin_left; ?>px;
        opacity: <?php echo number_format($theme_row->lightbox_ctrl_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_ctrl_btn_transparent; ?>);
      }
      .bwg_toggle_btn {
        color: #<?php echo $theme_row->lightbox_ctrl_btn_color; ?>;
        font-size: <?php echo $theme_row->lightbox_toggle_btn_height; ?>px;
        margin: 0;
        opacity: <?php echo number_format($theme_row->lightbox_ctrl_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_ctrl_btn_transparent; ?>);
        padding: 0;
      }
      .bwg_btn_container {
        bottom: 0;
        left: 0;
        overflow: hidden;
        position: absolute;
        right: 0;
        top: 0;
      }
      .bwg_ctrl_btn_container {
        background-color: rgba(<?php echo $rgb_lightbox_ctrl_cont_bg_color['red']; ?>, <?php echo $rgb_lightbox_ctrl_cont_bg_color['green']; ?>, <?php echo $rgb_lightbox_ctrl_cont_bg_color['blue']; ?>, <?php echo number_format($theme_row->lightbox_ctrl_cont_transparent / 100, 2, ".", ""); ?>);
        /*background: none repeat scroll 0 0 #<?php echo $theme_row->lightbox_ctrl_cont_bg_color; ?>;*/
        <?php
        if ($theme_row->lightbox_ctrl_btn_pos == 'top') {
          ?>
          border-bottom-left-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
          border-bottom-right-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
          <?php
        }
        else {
          ?>
          bottom: 0;
          border-top-left-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
          border-top-right-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
          <?php
        }?>
        height: <?php echo $theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top; ?>px;
        /*opacity: <?php echo number_format($theme_row->lightbox_ctrl_cont_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_ctrl_cont_transparent; ?>);*/
        position: absolute;
        text-align: <?php echo $theme_row->lightbox_ctrl_btn_align; ?>;
        width: 100%;
        z-index: 10150;
      }
      .bwg_toggle_container {
        background: none repeat scroll 0 0 #<?php echo $theme_row->lightbox_ctrl_cont_bg_color; ?>;
        <?php
        if ($theme_row->lightbox_ctrl_btn_pos == 'top') {
          ?>
          border-bottom-left-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
          border-bottom-right-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
          /*top: <?php echo $theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top; ?>px;*/
          <?php
        }
        else {
          ?>
          border-top-left-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
          border-top-right-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
          /*bottom: <?php echo $theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top; ?>px;*/
          <?php
        }?>
        cursor: pointer;
        left: 50%;
        line-height: 0;
        margin-left: -<?php echo $theme_row->lightbox_toggle_btn_width / 2; ?>px;
        opacity: <?php echo number_format($theme_row->lightbox_ctrl_cont_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_ctrl_cont_transparent; ?>);
        position: absolute;
        text-align: center;
        width: <?php echo $theme_row->lightbox_toggle_btn_width; ?>px;
        z-index: 10150;
      }
      .bwg_close_btn {
        opacity: <?php echo number_format($theme_row->lightbox_close_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_close_btn_transparent; ?>);
      }
      .spider_popup_close {
        background-color: #<?php echo $theme_row->lightbox_close_btn_bg_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_close_btn_border_radius; ?>;
        border: <?php echo $theme_row->lightbox_close_btn_border_width; ?>px <?php echo $theme_row->lightbox_close_btn_border_style; ?> #<?php echo $theme_row->lightbox_close_btn_border_color; ?>;
        box-shadow: <?php echo $theme_row->lightbox_close_btn_box_shadow; ?>;
        color: #<?php echo $theme_row->lightbox_close_btn_color; ?>;
        height: <?php echo $theme_row->lightbox_close_btn_height; ?>px;
        font-size: <?php echo $theme_row->lightbox_close_btn_size; ?>px;
        right: <?php echo $theme_row->lightbox_close_btn_right; ?>px;
        top: <?php echo $theme_row->lightbox_close_btn_top; ?>px;
        width: <?php echo $theme_row->lightbox_close_btn_width; ?>px;
      }
      .spider_popup_close_fullscreen {
        color: #<?php echo $theme_row->lightbox_close_btn_full_color; ?>;
        font-size: <?php echo $theme_row->lightbox_close_btn_size; ?>px;
        right: 7px;
      }
      .spider_popup_close span,
      #spider_popup_left-ico span,
      #spider_popup_right-ico span {
        display: table-cell;
        text-align: center;
        vertical-align: middle;
      }
      #spider_popup_left-ico,
      #spider_popup_right-ico {
        background-color: #<?php echo $theme_row->lightbox_rl_btn_bg_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_rl_btn_border_radius; ?>;
        border: <?php echo $theme_row->lightbox_rl_btn_border_width; ?>px <?php echo $theme_row->lightbox_rl_btn_border_style; ?> #<?php echo $theme_row->lightbox_rl_btn_border_color; ?>;
        box-shadow: <?php echo $theme_row->lightbox_rl_btn_box_shadow; ?>;
        color: #<?php echo $theme_row->lightbox_rl_btn_color; ?>;
        height: <?php echo $theme_row->lightbox_rl_btn_height; ?>px;
        font-size: <?php echo $theme_row->lightbox_rl_btn_size; ?>px;
        width: <?php echo $theme_row->lightbox_rl_btn_width; ?>px;
        opacity: <?php echo number_format($theme_row->lightbox_rl_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_rl_btn_transparent; ?>);
      }
      #spider_popup_left-ico {
        padding-right: <?php echo ($theme_row->lightbox_rl_btn_width - $theme_row->lightbox_rl_btn_size) / 3; ?>px;
      }
      #spider_popup_right-ico {
        padding-left: <?php echo ($theme_row->lightbox_rl_btn_width - $theme_row->lightbox_rl_btn_size) / 3; ?>px;
      }
      <?php
      if($autohide_lightbox_navigation){?>
      #spider_popup_left-ico{
        left: -9999px;
      }
      #spider_popup_right-ico{
        left: -9999px;
      }
      <?php }
      else { ?>
        #spider_popup_left-ico {
        left: 20px;
        }
        #spider_popup_right-ico {
          left: auto;
          right: 20px;
        }
      <?php } ?>
      .bwg_ctrl_btn:hover,
      .bwg_toggle_btn:hover,
      .spider_popup_close:hover,
      .spider_popup_close_fullscreen:hover,
      #spider_popup_left:hover #spider_popup_left-ico,
      #spider_popup_right:hover #spider_popup_right-ico {
        color: #<?php echo $theme_row->lightbox_close_rl_btn_hover_color; ?>;
        cursor: pointer;
      }
      .bwg_image_wrap {
        height: inherit;
        display: table;
        position: absolute;
        text-align: center;
        width: inherit;
      }
      .bwg_image_wrap * {
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      .bwg_comment_wrap, .bwg_ecommerce_wrap {
        bottom: 0;
        left: 0;
        overflow: hidden;
        position: absolute;
        right: 0;
        top: 0;
        z-index: -1;
      }
      .bwg_comment_container,  .bwg_ecommerce_container {
        -moz-box-sizing: border-box;
        background-color: #<?php echo $theme_row->lightbox_comment_bg_color; ?>;
        color: #<?php echo $theme_row->lightbox_comment_font_color; ?>;
        font-size: <?php echo $theme_row->lightbox_comment_font_size; ?>px;
        font-family: <?php echo $theme_row->lightbox_comment_font_style; ?>;
        height: 100%;
        overflow: hidden;
        position: absolute;
        <?php echo $theme_row->lightbox_comment_pos; ?>: -<?php echo $theme_row->lightbox_comment_width; ?>px;
        top: 0;
        width: <?php echo $theme_row->lightbox_comment_width; ?>px;
        z-index: 10103;
      }
      #bwg_ecommerce{
          padding:10px;
        }
        .bwg_ecommerce_body {
          background: none !important;
          border: none !important;
        }
        .bwg_ecommerce_body  p, .bwg_ecommerce_body span, .bwg_ecommerce_body div {
          color:#<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
        } 															
        .pge_tabs{
          list-style-type:none;
          margin: 0px;
          padding:0;
          background: none !important;
        }
        .pge_tabs li{
          float:left;
          border-top: 1px solid #<?php echo $theme_row->lightbox_bg_color; ?>!important;
          border-left: 1px solid #<?php echo $theme_row->lightbox_bg_color; ?>!important;
          border-right: 1px solid #<?php echo $theme_row->lightbox_bg_color; ?>!important;
          margin-right: 1px !important;
          border-radius: <?php echo $theme_row->lightbox_comment_button_border_radius; ?> <?php echo $theme_row->lightbox_comment_button_border_radius; ?> 0 0;
          position:relative;
        }

       .pge_tabs li a, .pge_tabs li a:hover, .pge_tabs li.pge_active a{		
         text-decoration:none;
         display:block;
         width:100%;
         outline:0 !important;
         padding:8px 5px !important;
         font-weight: bold;
         font-size: 13px;
       }
       .pge_tabs li a{
          color:#<?php echo $theme_row->lightbox_comment_bg_color; ?>!important;
        }

      .pge_tabs li.pge_active a, .pge_tabs li a:hover {
          border-radius: <?php echo $theme_row->lightbox_comment_button_border_radius; ?>;

        }
      .pge_tabs li.pge_active a>span, .pge_tabs li a>span:hover {
        color:#<?php echo $theme_row->lightbox_comment_button_bg_color; ?> !important;
        border-bottom: 1px solid #<?php echo $theme_row->lightbox_comment_button_bg_color; ?>;
        padding-bottom: 2px;
      }
       .pge_tabs_container{
          border:1px solid #<?php echo $theme_row->lightbox_comment_font_color; ?>;
          border-radius: 0 0 <?php echo $theme_row->lightbox_comment_button_border_radius; ?> <?php echo $theme_row->lightbox_comment_button_border_radius; ?>;
       }	  

      .pge_pricelist {
        padding:0 !important;
        color:#<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
      }
      .pge_add_to_cart{
         margin: 5px 0px 15px;
      }
      
      .pge_add_to_cart a{
        border: 1px solid #<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
        padding: 5px 10px;
        color:#<?php echo $theme_row->lightbox_comment_font_color; ?>!important;
        border-radius: <?php echo $theme_row->lightbox_comment_button_border_radius; ?>;
        text-decoration:none !important; 
        display:block;
      }
      .pge_add_to_cart_title{
        font-size:17px;
        padding: 5px;
      }
      .pge_add_to_cart div:first-child{
        float:left;
      }
      .pge_add_to_cart div:last-child{
        float:right;
        margin-top: 4px;
      }
      .pge_tabs:after,  .pge_add_to_cart:after{
        clear:both;
        content:"";
        display:table;
       }
      #downloads table tr td,   #downloads table tr th{
        padding: 6px 10px !important;
        text-transform:none !important;
      }
      .bwg_comments , .bwg_ecommerce_panel{
        bottom: 0;
        font-size: <?php echo $theme_row->lightbox_comment_font_size; ?>px;
        font-family: <?php echo $theme_row->lightbox_comment_font_style; ?>;
        height: 100%;
        left: 0;
        overflow-x: hidden;
        overflow-y: auto;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 10101;
      }
      .bwg_comments {
        height: 100%;
      }
      .bwg_comments p,
      .bwg_comment_body_p {
        margin: 5px !important;
        text-align: left;
        word-wrap: break-word;
        word-break: break-all;
      }
      .bwg_ecommerce_panel p{
        padding: 5px !important;
        text-align: left;
        word-wrap: break-word;
        word-break: break-all;
        margin:0 !important;
      }
      .bwg_comments input[type="submit"], .bwg_ecommerce_panel input[type="button"] {
        background: none repeat scroll 0 0 #<?php echo $theme_row->lightbox_comment_button_bg_color; ?>;
        border: <?php echo $theme_row->lightbox_comment_button_border_width; ?>px <?php echo $theme_row->lightbox_comment_button_border_style; ?> #<?php echo $theme_row->lightbox_comment_button_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_comment_button_border_radius; ?>;
        color: #<?php echo $theme_row->lightbox_comment_bg_color; ?>;
        cursor: pointer;
        font-size: 15px;
        padding: <?php echo $theme_row->lightbox_comment_button_padding; ?>;
        width: 100%;
      }
	  .bwg_comments .bwg-submit-disabled {
		opacity: 0.5;
	  }
	  .bwg_comments .bwg-submit-disabled:hover {
        padding: <?php echo $theme_row->lightbox_comment_button_padding; ?> !important;
        border-radius: <?php echo $theme_row->lightbox_comment_button_border_radius; ?> !important;
	  }
      .bwg_comments input[type="text"],
      .bwg_comments textarea,
      .bwg_ecommerce_panel input[type="text"],
      .bwg_ecommerce_panel input[type="number"],
      .bwg_ecommerce_panel textarea , .bwg_ecommerce_panel select {
        background: none repeat scroll 0 0 #<?php echo $theme_row->lightbox_comment_input_bg_color; ?>;
        border: <?php echo $theme_row->lightbox_comment_input_border_width; ?>px <?php echo $theme_row->lightbox_comment_input_border_style; ?> #<?php echo $theme_row->lightbox_comment_input_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_comment_input_border_radius; ?>;
        color: #<?php echo $theme_row->lightbox_comment_font_color; ?>;
        font-size: 12px;
        padding: <?php echo $theme_row->lightbox_comment_input_padding; ?>;
        width: 100%;
      }
      .bwg_comments textarea {
        height: 120px;
        resize: vertical;
      }
      .bwg_comment_header_p {
        border-top: <?php echo $theme_row->lightbox_comment_separator_width; ?>px <?php echo $theme_row->lightbox_comment_separator_style; ?> #<?php echo $theme_row->lightbox_comment_separator_color; ?>;
      }
      .bwg_comment_header {
        color: #<?php echo $theme_row->lightbox_comment_font_color; ?>;
        font-size: <?php echo $theme_row->lightbox_comment_author_font_size; ?>px;
      }
      .bwg_comment_date {
        color: #<?php echo $theme_row->lightbox_comment_font_color; ?>;
        float: right;
        font-size: <?php echo $theme_row->lightbox_comment_date_font_size; ?>px;
      }
      .bwg_comment_body {
        color: #<?php echo $theme_row->lightbox_comment_font_color; ?>;
        font-size: <?php echo $theme_row->lightbox_comment_body_font_size; ?>px;
      }
      .bwg_comment_delete_btn {
        color: #7A7A7A;
        cursor: pointer;
        float: right;
        font-size: 14px;
        margin: 2px;
      }
      .bwg_comments_close , .bwg_ecommerce_close{
        cursor: pointer;
        line-height: 0;
        position: relative;
        font-size: 13px;
        text-align: <?php echo (($theme_row->lightbox_comment_pos == 'left') ? 'right' : 'left'); ?>!important;
        margin: 5px;
        z-index: 10150;
      }
      .bwg_ecommerce_panel a:hover {
        text-decoration:underline;
      }
      .bwg_comment_textarea::-webkit-scrollbar {
        width: 4px;
      }
      .bwg_comment_textarea::-webkit-scrollbar-track {
      }
      .bwg_comment_textarea::-webkit-scrollbar-thumb {
        background-color: rgba(255, 255, 255, 0.55);
        border-radius: 2px;
      }
      .bwg_comment_textarea::-webkit-scrollbar-thumb:hover {
        background-color: #D9D9D9;
      }
      .bwg_ctrl_btn_container a,
      .bwg_ctrl_btn_container a:hover {
        text-decoration: none;
      }
      .bwg_rate:hover {
        color: #<?php echo $theme_row->lightbox_rate_color; ?>;
      }
      .bwg_facebook:hover {
        color: #3B5998;
      }
      .bwg_twitter:hover {
        color: #4099FB;
      }
      .bwg_google:hover {
        color: #DD4B39;
      }
      .bwg_pinterest:hover {
        color: #cb2027;
      }
      .bwg_tumblr:hover {
        color: #2F5070;
      }
      .bwg_facebook,
      .bwg_twitter,
      .bwg_google,
      .bwg_pinterest,
      .bwg_tumblr {
        color: #<?php echo $theme_row->lightbox_comment_share_button_color; ?>;
      }
      .bwg_image_container {
        display: table;
        position: absolute;
        text-align: center;
        <?php echo $theme_row->lightbox_filmstrip_pos; ?>: <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : $image_filmstrip_width); ?>px;
        vertical-align: middle;
        width: 100%;
      }
      .bwg_filmstrip_container {
        display: <?php echo ($filmstrip_direction == 'horizontal'? 'table' : 'block'); ?>;
        height: <?php echo ($filmstrip_direction == 'horizontal'? $image_filmstrip_height : $image_height); ?>px;
        position: absolute;
        width: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width : $image_filmstrip_width); ?>px;
        z-index: 10150;
        <?php echo $theme_row->lightbox_filmstrip_pos; ?>: 0;
      }
      .bwg_filmstrip {
        <?php echo $left_or_top; ?>: 20px;
        overflow: hidden;
        position: absolute;
        <?php echo $width_or_height; ?>: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width - 40 : $image_height - 40); ?>px;
        z-index: 10106;
      }
      .bwg_filmstrip_thumbnails {
        height: <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : ($image_filmstrip_height + $filmstrip_thumb_right_left_space) * count($image_rows)); ?>px;
        <?php echo $left_or_top; ?>: 0px;
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        width: <?php echo ($filmstrip_direction == 'horizontal' ? ($image_filmstrip_width + $filmstrip_thumb_right_left_space) * count($image_rows) : $image_filmstrip_width); ?>px;
      }
      .bwg_filmstrip_thumbnail {
		position: relative;
		background: none;
        float: left;
        height: <?php echo $image_filmstrip_height; ?>px;
        width: <?php echo $image_filmstrip_width; ?>px;
	    padding: <?php echo $theme_row->lightbox_filmstrip_thumb_margin; ?>;
		cursor: pointer;
        overflow: hidden;
      }
      .bwg_filmstrip_thumbnail .bwg_filmstrip_thumbnail_img_wrap {
        width:<?php echo $image_filmstrip_width - $filmstrip_thumb_right_left_space ?>px;
        height:<?php echo $image_filmstrip_height - $filmstrip_thumb_top_bottom_space;?>px;
        border: <?php echo $theme_row->lightbox_filmstrip_thumb_border_width; ?>px <?php echo $theme_row->lightbox_filmstrip_thumb_border_style; ?> #<?php echo $theme_row->lightbox_filmstrip_thumb_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_filmstrip_thumb_border_radius; ?>;
        overflow: hidden;
      }
      .bwg_thumb_active {
        opacity: 1;
        filter: Alpha(opacity=100);
      }
      .bwg_thumb_active .bwg_filmstrip_thumbnail_img_wrap {
        border: <?php echo $theme_row->lightbox_filmstrip_thumb_active_border_width; ?>px solid #<?php echo $theme_row->lightbox_filmstrip_thumb_active_border_color; ?>;
      }
      .bwg_thumb_deactive {
        opacity: <?php echo number_format($theme_row->lightbox_filmstrip_thumb_deactive_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_filmstrip_thumb_deactive_transparent; ?>);
      }
      .bwg_filmstrip_thumbnail_img {
        display: block;
        opacity: 1;
        filter: Alpha(opacity=100);
      }
      .bwg_filmstrip_left {
        background-color: #<?php echo $theme_row->lightbox_filmstrip_rl_bg_color; ?>;
        cursor: pointer;
        display: <?php echo ($filmstrip_direction == 'horizontal' ? 'table-cell' : 'block') ?>;
        vertical-align: middle;
        <?php echo $width_or_height; ?>: 20px;
        z-index: 10106;
        <?php echo $left_or_top; ?>: 0;
        <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'position: absolute;') ?>
        <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'width: 100%;') ?> 
      }
      .bwg_filmstrip_right {
        background-color: #<?php echo $theme_row->lightbox_filmstrip_rl_bg_color; ?>;
        cursor: pointer;
        <?php echo($filmstrip_direction == 'horizontal' ? 'right' : 'bottom') ?>: 0;
        <?php echo $width_or_height; ?>: 20px;
        display: <?php echo ($filmstrip_direction == 'horizontal' ? 'table-cell' : 'block') ?>;
        vertical-align: middle;
        z-index: 10106;
        <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'position: absolute;') ?>
        <?php echo ($filmstrip_direction == 'horizontal' ? '' : 'width: 100%;') ?>
      }
      .bwg_filmstrip_left i,
      .bwg_filmstrip_right i {
        color: #<?php echo $theme_row->lightbox_filmstrip_rl_btn_color; ?>;
        font-size: <?php echo $theme_row->lightbox_filmstrip_rl_btn_size; ?>px;
      }
      .bwg_none_selectable {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      .bwg_watermark_container {
        display: table-cell;
        margin: 0 auto;
        position: relative;
        vertical-align: middle;
      }
      .bwg_watermark_spun {
        display: table-cell;
        overflow: hidden;
        position: relative;
        text-align: <?php echo $watermark_position[1]; ?>;
        vertical-align: <?php echo $watermark_position[0]; ?>;
        /*z-index: 10140;*/
      }
      .bwg_watermark_image {
        margin: 4px;
        max-height: <?php echo $watermark_height; ?>px;
        max-width: <?php echo $watermark_width; ?>px;
        opacity: <?php echo number_format($watermark_opacity / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $watermark_opacity; ?>);
        position: relative;
        z-index: 10141;
      }
      .bwg_watermark_text,
      .bwg_watermark_text:hover {
        text-decoration: none;
        margin: 4px;
        font-size: <?php echo $watermark_font_size; ?>px;
        font-family: <?php echo $watermark_font; ?>;
        color: #<?php echo $watermark_color; ?> !important;
        opacity: <?php echo number_format($watermark_opacity / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $watermark_opacity; ?>);
        position: relative;
        z-index: 10141;
      }
      .bwg_slide_container {
        display: table-cell;
        position: absolute;
        vertical-align: middle;
        width: 100%;
        height: 100%;
      }
      .bwg_slide_bg {
        margin: 0 auto;
        width: inherit;
        height: inherit;
      }
      .bwg_slider {
        height: inherit;
        width: inherit;
      }
      .bwg_popup_image_spun {
        height: inherit;
        display: table-cell;
        filter: Alpha(opacity=100);
        opacity: 1;
        position: absolute;
        vertical-align: middle;
        width: inherit;
        z-index: 2;
      }
      .bwg_popup_image_second_spun {
        width: inherit;
        height: inherit;
        display: table-cell;
        filter: Alpha(opacity=0);
        opacity: 0;
        position: absolute;
        vertical-align: middle;
        z-index: 1;
      }
      .bwg_grid {
        display: none;
        height: 100%;
        overflow: hidden;
        position: absolute;
        width: 100%;
      }
      .bwg_gridlet {
        opacity: 1;
        filter: Alpha(opacity=100);
        position: absolute;
      }
      .bwg_image_info_container1 {
        display: <?php echo $popup_info_always_show ? 'table-cell' : 'none'; ?>;
      }
      .bwg_image_hit_container1 {
        display: <?php echo $popup_hit_counter ? 'table-cell' : 'none'; ?>;;
      }
      .bwg_image_info_spun {
        text-align: <?php echo $theme_row->lightbox_info_align; ?>;
        vertical-align: <?php echo $theme_row->lightbox_info_pos; ?>;
      }
      .bwg_image_hit_spun {
        text-align: <?php echo $theme_row->lightbox_hit_align; ?>;
        vertical-align: <?php echo $theme_row->lightbox_hit_pos; ?>;
      }
      .bwg_image_hit {
        background: rgba(<?php echo $rgb_bwg_image_hit_bg_color['red']; ?>, <?php echo $rgb_bwg_image_hit_bg_color['green']; ?>, <?php echo $rgb_bwg_image_hit_bg_color['blue']; ?>, <?php echo number_format($theme_row->lightbox_hit_bg_transparent / 100, 2, ".", ""); ?>);
        border: <?php echo $theme_row->lightbox_hit_border_width; ?>px <?php echo $theme_row->lightbox_hit_border_style; ?> #<?php echo $theme_row->lightbox_hit_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_info_border_radius; ?>;
        <?php echo ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_hit_pos == 'bottom') ? 'bottom: ' . ($theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top) . 'px;' : '' ?>
        margin: <?php echo $theme_row->lightbox_hit_margin; ?>;
        padding: <?php echo $theme_row->lightbox_hit_padding; ?>;
        <?php echo ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_hit_pos == 'top') ? 'top: ' . ($theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top) . 'px;' : '' ?>
      }
      .bwg_image_hits,
      .bwg_image_hits * {
        color: #<?php echo $theme_row->lightbox_hit_color; ?> !important;
        font-family: <?php echo $theme_row->lightbox_hit_font_style; ?>;
        font-size: <?php echo $theme_row->lightbox_hit_font_size; ?>px;
        font-weight: <?php echo $theme_row->lightbox_hit_font_weight; ?>;
      }
      .bwg_image_info {
        /* overflow-y: auto;
        height:58px;*/
        background: rgba(<?php echo $rgb_bwg_image_info_bg_color['red']; ?>, <?php echo $rgb_bwg_image_info_bg_color['green']; ?>, <?php echo $rgb_bwg_image_info_bg_color['blue']; ?>, <?php echo number_format($theme_row->lightbox_info_bg_transparent / 100, 2, ".", ""); ?>);
        border: <?php echo $theme_row->lightbox_info_border_width; ?>px <?php echo $theme_row->lightbox_info_border_style; ?> #<?php echo $theme_row->lightbox_info_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_info_border_radius; ?>;
        <?php echo ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_info_pos == 'bottom') ? 'bottom: ' . ($theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top) . 'px;' : '' ?>
        <?php if($params_array['popup_info_full_width']) { ?>
        width: 100%;
        <?php } else { ?>
        width: 33%;
        margin: <?php echo $theme_row->lightbox_info_margin; ?>;
        <?php } ?>
        padding: <?php echo $theme_row->lightbox_info_padding; ?>;
        <?php echo ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_info_pos == 'top') ? 'top: ' . ($theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top) . 'px;' : '' ?>
        word-break : break-word;
      }
      .bwg_image_info::-webkit-scrollbar {
         width: 4px;
      }
      .bwg_image_info::-webkit-scrollbar-thumb {
         background-color: rgba(255, 255, 255, 0.55);
         border-radius: 2px;
      }
      .bwg_image_info::-webkit-scrollbar-thumb:hover {
         background-color: #D9D9D9;
      }
      .bwg_image_title,
      .bwg_image_title * {
        color: #<?php echo $theme_row->lightbox_title_color; ?> !important;
        font-family: <?php echo $theme_row->lightbox_title_font_style; ?>;
        font-size: <?php echo $theme_row->lightbox_title_font_size; ?>px;
        font-weight: <?php echo $theme_row->lightbox_title_font_weight; ?>;
        word-wrap: break-word;
      }
      .bwg_image_description,
      .bwg_image_description * {
        color: #<?php echo $theme_row->lightbox_description_color; ?> !important;
        font-family: <?php echo $theme_row->lightbox_description_font_style; ?>;
        font-size: <?php echo $theme_row->lightbox_description_font_size; ?>px;
        font-weight: <?php echo $theme_row->lightbox_description_font_weight; ?>;
        word-break: break-word;
      }
      .bwg_image_rate_spun {
        text-align: <?php echo $theme_row->lightbox_rate_align; ?>;
        vertical-align: <?php echo $theme_row->lightbox_rate_pos; ?>;
      }
      .bwg_image_rate {
        <?php echo ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_rate_pos == 'bottom') ? 'bottom: ' . ($theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top) . 'px;' : '' ?>
        padding: <?php echo $theme_row->lightbox_rate_padding; ?>;
        <?php echo ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_rate_pos == 'top') ? 'top: ' . ($theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top) . 'px;' : '' ?>
      }
      #bwg_rate_form .bwg_hint,
      #bwg_rate_form .fa-<?php echo $theme_row->lightbox_rate_icon; ?>,
      #bwg_rate_form .fa-<?php echo $theme_row->lightbox_rate_icon; ?>-half-o,
      #bwg_rate_form .fa-<?php echo $theme_row->lightbox_rate_icon; ?>-o,
      #bwg_rate_form .fa-minus-square-o {
        color: #<?php echo $theme_row->lightbox_rate_color; ?>;
        font-size: <?php echo $theme_row->lightbox_rate_size; ?>px;
      }
      #bwg_rate_form .bwg_hint {
        margin: 0 5px;
        display: none;
      }
      .bwg_rate_hover {
        color: #<?php echo $theme_row->lightbox_rate_hover_color; ?> !important;
      }
      .bwg_star {
        display: inline-block;
      }
      .bwg_rated {
        color: #<?php echo $theme_row->lightbox_rate_color; ?>;
        display: none;
        font-size: <?php echo $theme_row->lightbox_rate_size - 2; ?>px;
      }
			@media (max-width: 480px) {
				.bwg_image_count_container {
					display: none;
				}
        .bwg_image_title,
        .bwg_image_title * {
					font-size: 12px;
				}
        .bwg_image_description,
        .bwg_image_description * {
					font-size: 10px;
				}
			}
      .bwg_image_count_container {
        left: 0;
        line-height: 1;
        position: absolute;
        vertical-align: middle;
      }
      #bwg_comment_form label {
        color: #<?php echo $theme_row->lightbox_comment_font_color; ?>;
        display: block;
        font-weight: bold;
        margin-top: 17px;
        text-transform: uppercase;
      }
      #bwg_comment_form .bwg-privacy-policy-box label {
        text-transform: unset;
        word-break: break-word;
      }
    </style>
    <script>
      var data = [];
      var event_stack = [];
      <?php
      $image_id_exist = FALSE;
      $has_embed = FALSE;
      foreach ($image_rows as $key => $image_row) {
        if ($image_row->id == $image_id) {
          $current_avg_rating = $image_row->avg_rating;
          $current_rate = $image_row->rate;
          $current_rate_count = $image_row->rate_count;
          $current_image_key = $key;
        }
        if ($image_row->id == $current_image_id) {
          $current_image_alt = $image_row->alt;
          $current_image_hit_count = $image_row->hit_count;
          $current_image_description = str_replace(array("\r\n", "\n", "\r"), esc_html('<br />'), $image_row->description);
          $current_image_url = $image_row->pure_image_url;
          $current_thumb_url = $image_row->pure_thumb_url;
          $current_filetype = $image_row->filetype;
          $current_filename = $image_row->filename;
          $image_id_exist = TRUE;
        }
		    $has_embed = $has_embed || preg_match('/EMBED/',$image_row->filetype) == 1;
        if ( BWG()->is_pro ) {
          $current_pricelist_id = $this->model->get_image_pricelist($image_row->id);
          $current_pricelist_id = $current_pricelist_id ? $current_pricelist_id : 0;
          $_pricelist = $pricelist_data["pricelist"];
        }
        ?>
        data["<?php echo $key; ?>"] = [];
        data["<?php echo $key; ?>"]["number"] = <?php echo $key + 1; ?>;
        data["<?php echo $key; ?>"]["id"] = "<?php echo $image_row->id; ?>";
        data["<?php echo $key; ?>"]["alt"] = "<?php echo addslashes(str_replace(array("\r\n", "\n", "\r"), esc_html('<br />'), $image_row->alt)); ?>";
        data["<?php echo $key; ?>"]["description"] = "<?php echo addslashes(str_replace(array("\r\n", "\n", "\r"), esc_html('<br />'), $image_row->description)); ?>";
        <?php
        $image_resolution = explode(' x ', $image_row->resolution);
        if (is_array($image_resolution)) {
          $instagram_post_width = $image_resolution[0];
          $instagram_post_height = explode(' ', $image_resolution[1]);
          $instagram_post_height = $instagram_post_height[0];
        }
        ?>
        data["<?php echo $key; ?>"]["image_width"] = "<?php echo $instagram_post_width; ?>";
        data["<?php echo $key; ?>"]["image_height"] = "<?php echo $instagram_post_height; ?>";
        data["<?php echo $key; ?>"]["pure_image_url"] = "<?php echo $image_row->pure_image_url; ?>";
        data["<?php echo $key; ?>"]["pure_thumb_url"] = "<?php echo $image_row->pure_thumb_url; ?>";
        data["<?php echo $key; ?>"]["image_url"] = "<?php echo $image_row->image_url; ?>";
        data["<?php echo $key; ?>"]["thumb_url"] = "<?php echo $image_row->thumb_url; ?>";
        data["<?php echo $key; ?>"]["date"] = "<?php echo $image_row->date; ?>";
        data["<?php echo $key; ?>"]["comment_count"] = "<?php echo $image_row->comment_count; ?>";
        data["<?php echo $key; ?>"]["filetype"] = "<?php echo $image_row->filetype; ?>";
        data["<?php echo $key; ?>"]["filename"] = "<?php echo $image_row->filename; ?>";
        data["<?php echo $key; ?>"]["avg_rating"] = "<?php echo $image_row->avg_rating; ?>";
        data["<?php echo $key; ?>"]["rate"] = "<?php echo $image_row->rate; ?>";
        data["<?php echo $key; ?>"]["rate_count"] = "<?php echo $image_row->rate_count; ?>";
        data["<?php echo $key; ?>"]["hit_count"] = "<?php echo $image_row->hit_count; ?>";
        <?php if ( BWG()->is_pro ) { ?>
        data["<?php echo $key; ?>"]["pricelist"] = "<?php echo $current_pricelist_id ? $current_pricelist_id : 0; ?>";
        data["<?php echo $key; ?>"]["pricelist_manual_price"] = "<?php echo isset($_pricelist->price) ? $_pricelist->price : 0; ?>";
        data["<?php echo $key; ?>"]["pricelist_sections"] = "<?php echo isset($_pricelist->sections) ? $_pricelist->sections : ""; ?>";
      <?php }
      }
      ?>
    </script>
    <?php
    if (!$image_id_exist) {
      echo WDWLibrary::message(__('The image has been deleted.', BWG()->prefix), 'wd_error');
      die();
    }
    ?>
    <div class="bwg_image_wrap">
      <?php
      $current_pos = 0;
      if ( $enable_image_filmstrip ) {
        ?>
        <div class="bwg_filmstrip_container">
          <div class="bwg_filmstrip_left"><i class="fa <?php echo ($filmstrip_direction == 'horizontal'? 'fa-angle-left' : 'fa-angle-up'); ?> "></i></div>
          <div class="bwg_filmstrip">
            <div class="bwg_filmstrip_thumbnails" data-all-images-right-left-space="<?php echo $all_images_right_left_space; ?>" data-all-images-top-bottom-space="<?php echo $all_images_top_bottom_space; ?>">
              <?php
              foreach ($image_rows as $key => $image_row) {
                if ($image_row->id == $current_image_id) {
                  $current_pos = $key * (($filmstrip_direction == 'horizontal' ? $image_filmstrip_width : $image_filmstrip_height) + $filmstrip_thumb_right_left_space);
                  $current_key = $key;
                }
                
                $is_embed = preg_match('/EMBED/',$image_row->filetype)==1 ? true : false;
                $is_embed_instagram = preg_match('/EMBED_OEMBED_INSTAGRAM/', $image_row->filetype ) == 1 ? true : false;
                if ( !$is_embed ) {
                  $thumb_path_url = htmlspecialchars_decode(BWG()->upload_dir . $image_row->thumb_url, ENT_COMPAT | ENT_QUOTES);
                  $thumb_path_url = explode('?bwg', $thumb_path_url);
                  list($image_thumb_width, $image_thumb_height) = getimagesize($thumb_path_url[0]);
                }
                else {
                  if ($image_row->resolution != '') {
                    if (!$is_embed_instagram) {
                      $resolution_arr = explode(" ", $image_row->resolution);
                      $resolution_w = intval($resolution_arr[0]);
                      $resolution_h = intval($resolution_arr[2]);
                      if($resolution_w != 0 && $resolution_h != 0){
                        $scale = $scale = max($image_filmstrip_width / $resolution_w, $image_filmstrip_height / $resolution_h);
                        $image_thumb_width = $resolution_w * $scale;
                        $image_thumb_height = $resolution_h * $scale;
                      }
                      else{
                        $image_thumb_width = $image_filmstrip_width;
                        $image_thumb_height = $image_filmstrip_height;
                      }
                    }
                    else {
                      // this will be ok while instagram thumbnails width and height are the same
                      $image_thumb_width = min($image_filmstrip_width, $image_filmstrip_height);
                      $image_thumb_height = $image_thumb_width;
                    }
                  }
                  else {
                    $image_thumb_width = $image_filmstrip_width;
                    $image_thumb_height = $image_filmstrip_height;
                  }
                }

				$_image_filmstrip_width  = $image_filmstrip_width - $filmstrip_thumb_right_left_space;
                $_image_filmstrip_height = $image_filmstrip_height - $filmstrip_thumb_top_bottom_space;
                $scale = max($image_filmstrip_width / $image_thumb_width, $image_filmstrip_height / $image_thumb_height);
                $image_thumb_width *= $scale;
                $image_thumb_height *= $scale;
				$thumb_left = ($_image_filmstrip_width - $image_thumb_width) / 2;
                $thumb_top = ($_image_filmstrip_height - $image_thumb_height) / 2;
              ?>
              <div id="bwg_filmstrip_thumbnail_<?php echo $key; ?>" class="bwg_filmstrip_thumbnail <?php echo (($image_row->id == $current_image_id) ? 'bwg_thumb_active' : 'bwg_thumb_deactive'); ?>">
                <div class="bwg_filmstrip_thumbnail_img_wrap">
					<img style="width:<?php echo $image_thumb_width; ?>px; height:<?php echo $image_thumb_height; ?>px; margin-left: <?php echo $thumb_left; ?>px; margin-top: <?php echo $thumb_top; ?>px;" class="bwg_filmstrip_thumbnail_img hidden" data-url="<?php echo ($is_embed ? "" : BWG()->upload_url) . $image_row->thumb_url; ?>" src="" onclick="bwg_change_image(parseInt(jQuery('#bwg_current_image_key').val()), '<?php echo $key; ?>', data)" ontouchend="bwg_change_image(parseInt(jQuery('#bwg_current_image_key').val()), '<?php echo $key; ?>', data,'')" image_id="<?php echo $image_row->id; ?>" image_key="<?php echo $key; ?>" alt="<?php echo $image_row->alt; ?>" />
				</div>
              </div>
              <?php
              }
              ?>
            </div>
          </div>
          <div class="bwg_filmstrip_right"><i class="fa <?php echo ($filmstrip_direction == 'horizontal'? 'fa-angle-right' : 'fa-angle-down'); ?>"></i></div>
        </div>
        <?php
      }
      if ($watermark_type != 'none') {
      ?>
      <div class="bwg_image_container">
        <div class="bwg_watermark_container">
          <div style="display:table; margin:0 auto;">
            <span class="bwg_watermark_spun" id="bwg_watermark_container">
              <?php
              $watermark_link = urldecode($watermark_link);
              if ($watermark_type == 'image') {
              ?>
              <a href="<?php echo esc_js($watermark_link); ?>" target="_blank">
                <img class="bwg_watermark_image bwg_watermark" src="<?php echo $watermark_url; ?>" />
              </a>
              <?php
              }
              elseif ($watermark_type == 'text') {
              ?>
              <a class="bwg_none_selectable bwg_watermark_text bwg_watermark" target="_blank" href="<?php echo esc_js($watermark_link); ?>"><?php echo stripslashes($watermark_text); ?></a>
              <?php
              }
              ?>
            </span>
          </div>
        </div>
      </div>
      <?php
      }
      ?>
      <div id="bwg_image_container" class="bwg_image_container">
      <?php
		echo $this->loading();
		if ($enable_image_ctrl_btn) {
			$share_url = add_query_arg(array('curr_url' => $current_url, 'image_id' => $current_image_id), WDWLibrary::get_share_page()) . '#bwg' . $gallery_id . '/' . $current_image_id;
      ?>
      <div class="bwg_btn_container">
        <div class="bwg_ctrl_btn_container">
					<?php
          if ($show_image_counts) {
            ?>
            <span class="bwg_image_count_container bwg_ctrl_btn">
              <span class="bwg_image_count"><?php echo $current_image_key + 1; ?></span> / 
              <span><?php echo count($image_rows); ?></span>
            </span>
            <?php
          }
					?>
          <i title="<?php echo __('Play', BWG()->prefix); ?>" class="bwg_ctrl_btn bwg_play_pause fa fa-play"></i>
          <?php if ($enable_image_fullscreen) {
                  if (!$open_with_fullscreen) {
          ?>
          <i title="<?php echo __('Maximize', BWG()->prefix); ?>" class="bwg_ctrl_btn bwg_resize-full fa fa-resize-full "></i>
          <?php
          }
          ?>
          <i title="<?php echo __('Fullscreen', BWG()->prefix); ?>" class="bwg_ctrl_btn bwg_fullscreen fa fa-fullscreen"></i>
          <?php } if ($popup_enable_info) { ?>
          <i title="<?php echo __('Show info', BWG()->prefix); ?>" class="bwg_ctrl_btn bwg_info fa fa-info"></i>
          <?php } if ($enable_comment_social) { ?>
          <i title="<?php echo __('Show comments', BWG()->prefix); ?>" class="bwg_ctrl_btn bwg_comment fa fa-comment"></i>
          <?php } if ($popup_enable_rate) { ?>
          <i title="<?php echo __('Show rating', BWG()->prefix); ?>" class="bwg_ctrl_btn bwg_rate fa fa-star"></i>
          <?php }
          $is_embed = preg_match('/EMBED/', $current_filetype) == 1 ? TRUE : FALSE;
          $share_image_url = str_replace('%252F', '%2F', urlencode( $is_embed ? $current_thumb_url : BWG()->upload_url . rawurlencode($current_image_url)));
          if ($enable_image_facebook) {
            ?>
            <a id="bwg_facebook_a" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Facebook', BWG()->prefix); ?>">
              <i title="<?php echo __('Share on Facebook', BWG()->prefix); ?>" class="bwg_ctrl_btn bwg_facebook fa fa-facebook"></i>
            </a>
            <?php
          }
          if ($enable_image_twitter) {
            ?>
            <a id="bwg_twitter_a" href="https://twitter.com/share?url=<?php echo urlencode($current_url . '#bwg' . $gallery_id . '/' . $current_image_id); ?>" target="_blank" title="<?php echo __('Share on Twitter', BWG()->prefix); ?>">
              <i title="<?php echo __('Share on Twitter', BWG()->prefix); ?>" class="bwg_ctrl_btn bwg_twitter fa fa-twitter"></i>
            </a>
            <?php
          }
          if ($enable_image_google) {
            ?>
            <a id="bwg_google_a" href="https://plus.google.com/share?url=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Google+', BWG()->prefix); ?>">
              <i title="<?php echo __('Share on Google+', BWG()->prefix); ?>" class="bwg_ctrl_btn bwg_google fa fa-google-plus"></i>
            </a>
            <?php
          }
          if ($enable_image_pinterest) {
            ?>
            <a id="bwg_pinterest_a" href="http://pinterest.com/pin/create/button/?s=100&url=<?php echo urlencode($share_url); ?>&media=<?php echo $share_image_url; ?>&description=<?php echo $current_image_alt . '%0A' . $current_image_description; ?>" target="_blank" title="<?php echo __('Share on Pinterest', BWG()->prefix); ?>">
              <i title="<?php echo __('Share on Pinterest', BWG()->prefix); ?>" class="bwg_ctrl_btn bwg_pinterest fa fa-pinterest"></i>
            </a>
            <?php
          }
          if ($enable_image_tumblr) {
            ?>
            <a id="bwg_tumblr_a" href="https://www.tumblr.com/share/photo?source=<?php echo $share_image_url; ?>&caption=<?php echo urlencode($current_image_alt); ?>&clickthru=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Tumblr', BWG()->prefix); ?>">
              <i title="<?php echo __('Share on Tumblr', BWG()->prefix); ?>" class="bwg_ctrl_btn bwg_tumblr fa fa-tumblr"></i>
            </a>
            <?php
          }
          if ($popup_enable_fullsize_image) {
            ?>
            <a id="bwg_fullsize_image" href="<?php echo !$is_embed ? BWG()->upload_url . $current_image_url : $current_image_url; ?>" target="_blank">
              <i title="<?php echo __('Open image in original size.', BWG()->prefix); ?>" class="bwg_ctrl_btn fa fa-external-link"></i>
            </a>
            <?php
          }
          if ( $popup_enable_download ) {
            $style = 'none';
            $current_image_arr = explode('/', $current_image_url);
            if ( !$is_embed ) {
              $download_dir = BWG()->upload_dir . str_replace('/thumb/', '/.original/', $current_thumb_url);
              WDWLibrary::repair_image_original($download_dir);
              $download_href = BWG()->upload_url . str_replace('/thumb/', '/.original/', $current_thumb_url);
              $style = 'inline-block';
            }
              ?>
              <a id="bwg_download" <?php if ($is_embed) { ?> class="hidden" <?php } ?>  href="<?php echo $download_href; ?>" target="_blank" download="<?php echo end($current_image_arr); ?>">
                <i title="<?php echo __('Download original image', BWG()->prefix); ?>" class="bwg_ctrl_btn fa fa-download"></i>
              </a>
              <?php

          }
          if ( function_exists('BWGEC') && $enable_image_ecommerce == 1 ) {
    		   ?>
				  <i title="<?php echo __('Ecommerce', BWG()->prefix); ?>" style="<?php echo $pricelist_id == 0 ? "display:none;": "";?>" class="bwg_ctrl_btn bwg_ecommerce fa fa-shopping-cart" ></i>
		       <?php
		      }
          ?>
        </div>
        <div class="bwg_toggle_container">
          <i class="bwg_toggle_btn fa <?php echo (($theme_row->lightbox_ctrl_btn_pos == 'top') ? 'fa-angle-up' : 'fa-angle-down'); ?>"></i>
        </div>
      </div>
      <?php
      }?>
        <div class="bwg_image_info_container1">
          <div class="bwg_image_info_container2">
            <span class="bwg_image_info_spun">
              <div class="bwg_image_info" <?php if(trim($current_image_alt) == '' && trim($current_image_description) == '') { echo 'style="opacity: 0;"'; } ?>>
                <div class="bwg_image_title"><?php echo html_entity_decode($current_image_alt); ?></div>
                <div class="bwg_image_description"><?php echo html_entity_decode($current_image_description); ?></div>
              </div>
            </span>
          </div>
        </div>
        <div class="bwg_image_hit_container1">
          <div class="bwg_image_hit_container2">
            <span class="bwg_image_hit_spun">
              <div class="bwg_image_hit">
                <div class="bwg_image_hits"><?php echo __('Hits: ', BWG()->prefix); ?><span><?php echo $current_image_hit_count; ?></span></div>
              </div>
            </span>
          </div>
        </div>
        <div class="bwg_image_rate_container1">
          <div class="bwg_image_rate_container2">
            <span class="bwg_image_rate_spun">
              <span class="bwg_image_rate">
                <form id="bwg_rate_form" method="post" action="<?php echo $popup_url; ?>">
                  <span id="bwg_star" class="bwg_star" data-score="<?php echo $current_avg_rating; ?>"></span>
                  <span id="bwg_rated" class="bwg_rated"><?php echo __('Rated.', BWG()->prefix); ?></span>
                  <span id="bwg_hint" class="bwg_hint"></span>
                  <script>
                    function bwg_rating(current_rate, rate_count, avg_rating, cur_key) {
                      var avg_rating_message = "<?php echo __('Not rated yet.', BWG()->prefix); ?>";
                      if (avg_rating != 0) {
                        if (avg_rating != "") {
                          avg_rating_message = parseFloat(avg_rating).toFixed(1) + "\n<?php echo __('Votes: ', BWG()->prefix); ?>" + rate_count;
                        }
                      }
                      if (typeof jQuery().raty !== 'undefined') {
                        if (jQuery.isFunction(jQuery().raty)) {
                          jQuery("#bwg_star").raty({
                            score: function() {
                              return jQuery(this).attr("data-score");
                            },
                            starType: 'i',
                            number : <?php echo $theme_row->lightbox_rate_stars_count; ?>,
                            size : <?php echo $theme_row->lightbox_rate_size; ?>,
                            readOnly : function() {
                              return (current_rate ? true : false);
                            },
                            noRatedMsg : "<?php echo __('Not rated yet.', BWG()->prefix); ?>",
                            click : function(score, evt) {
                              jQuery("#bwg_star").hide();
                              jQuery("#bwg_rated").show();
                              spider_set_input_value('rate_ajax_task', 'save_rate');
                              spider_rate_ajax_save('bwg_rate_form');
                              data[cur_key]["rate"] = score;
                              ++data[cur_key]["rate_count"];
                              var curr_score = parseFloat(jQuery("#bwg_star").attr("data-score"));
                              data[cur_key]["avg_rating"] = curr_score ? ((curr_score + score) / 2).toFixed(1) : score.toFixed(1);
                            },
                            starHalf : 'fa fa-<?php echo $theme_row->lightbox_rate_icon . (($theme_row->lightbox_rate_icon == 'star') ? '-half' : ''); ?>-o',
                            starOff : 'fa fa-<?php echo $theme_row->lightbox_rate_icon; ?>-o',
                            starOn : 'fa fa-<?php echo $theme_row->lightbox_rate_icon; ?>',
                            cancelOff : 'fa fa-minus-square-o',
                            cancelOn : 'fa fa-minus-square-o',
                            cancel : false,
                            /*target : '#bwg_hint',
                            targetType : 'number',
                            targetKeep : true,*/
                            cancelHint : '<?php echo __('Cancel your rating.', BWG()->prefix); ?>',
                            hints : [avg_rating_message, avg_rating_message, avg_rating_message, avg_rating_message, avg_rating_message],
                            alreadyRatedMsg : parseFloat(avg_rating).toFixed(1) + "\n" + "<?php echo __('You have already rated.', BWG()->prefix); ?>\n<?php echo __('Votes: ', BWG()->prefix); ?>" + rate_count,
                          });
                        }
                      }
                    }
                    jQuery(document).ready(function () {
                      bwg_rating("<?php echo $current_rate; ?>", "<?php echo $current_rate_count; ?>", "<?php echo $current_avg_rating; ?>", "<?php echo $current_image_key; ?>");
                    });
                  </script>
                  <input id="rate_ajax_task" name="ajax_task" type="hidden" value="" />
                  <input id="rate_image_id" name="image_id" type="hidden" value="<?php echo $image_id; ?>" />
                </form>
              </span>
            </span>
          </div>
        </div>
        <div class="bwg_slide_container">
          <div class="bwg_slide_bg">
            <div class="bwg_slider">
          <?php
          $current_key = -6;
          foreach ( $image_rows as $key => $image_row ) {
            $is_embed = preg_match('/EMBED/',$image_row->filetype)==1 ? true :false;
            $is_embed_instagram_post = preg_match('/INSTAGRAM_POST/',$image_row->filetype)==1 ? true : false;
            $is_embed_instagram_video = preg_match('/INSTAGRAM_VIDEO/', $image_row->filetype) == 1 ? true : false;
			$is_ifrem = ( in_array($image_row->filetype, array('EMBED_OEMBED_YOUTUBE_VIDEO', 'EMBED_OEMBED_VIMEO_VIDEO', 'EMBED_OEMBED_FACEBOOK_VIDEO', 'EMBED_OEMBED_DAILYMOTION_VIDEO') ) ) ? true : false;
			if ($image_row->id == $current_image_id) {
              $current_key = $key;
              ?>
              <span class="bwg_popup_image_spun" id="bwg_popup_image" image_id="<?php echo $image_row->id; ?>">
                <span class="bwg_popup_image_spun1" style="display: <?php echo ( !$is_embed ? 'table' : 'block' ); ?>; width: inherit; height: inherit;">
                  <span class="bwg_popup_image_spun2" style="display: <?php echo ( !$is_embed ? 'table-cell' : 'block' ); ?>; vertical-align: middle; text-align: center; height: 100%;">
                    <?php 
                      if (!$is_embed) {
                      ?>
                      <img class="bwg_popup_image bwg_popup_watermark" src="<?php echo BWG()->upload_url . $image_row->image_url; ?>" alt="<?php echo $image_row->alt; ?>" />
                      <?php 
                      }
                      else { /*$is_embed*/ ?>
                        <span id="embed_conteiner" class="bwg_popup_embed bwg_popup_watermark" style="display: <?php echo ( $is_ifrem ? 'block' : 'table' ); ?>; table-layout: fixed; height: 100%;">
                        <?php echo $is_embed_instagram_video ? '<span class="bwg_inst_play_btn_cont" onclick="bwg_play_instagram_video(this)" ><span class="bwg_inst_play"></span></span>' : '';
                        if ($is_embed_instagram_post) {
                          $post_width = $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0);
                          $post_height = $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0);
                          if ($post_height < $post_width + 88) {
                            $post_width = $post_height - 88;
                          }
                          else {
                           $post_height = $post_width + 88;
                          }

                          $instagram_post_width = $post_width;
                          $instagram_post_height = $post_height;
                          $image_resolution = explode(' x ', $image_row->resolution);
                          if (is_array($image_resolution)) {
                            $instagram_post_width = $image_resolution[0];
                            $instagram_post_height = explode(' ', $image_resolution[1]);
                            $instagram_post_height = $instagram_post_height[0];
                          }
                          
                          WDWLibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array('class' => "bwg_embed_frame", 'data-width' => $instagram_post_width, 'data-height' => $instagram_post_height, 'frameborder' => "0", 'style' => "width:" . $post_width . "px; height:" . $post_height . "px; vertical-align:middle; display:inline-block; position:relative;"));
                        }
                        else{
                          WDWLibraryEmbed::display_embed($image_row->filetype, $image_row->image_url, $image_row->filename, array('class'=>"bwg_embed_frame", 'frameborder'=>"0", 'allowfullscreen'=>"allowfullscreen", 'style'=> "display: " . ( $is_ifrem ? 'block' : 'table-cell' ) . "; width:inherit; height:inherit; vertical-align:middle;"));
                        }
                        ?>
                      </span>
                      <?php
                      }
                    ?>                    
                  </span>
                </span>
              </span>
              <span class="bwg_popup_image_second_spun">                
              </span>
              <input type="hidden" id="bwg_current_image_key" value="<?php echo $key; ?>" />
              <?php
              break;
            }
          }
          ?>
            </div>
          </div>
        </div>
        <a id="spider_popup_left" <?php echo ($enable_loop == 0 && $current_key == 0) ? 'style="display: none;"' : ''; ?>><span id="spider_popup_left-ico"><span><i class="bwg_prev_btn fa <?php echo $theme_row->lightbox_rl_btn_style; ?>-left"></i></span></span></a>
        <a id="spider_popup_right" <?php echo ($enable_loop == 0 && $current_key == count($image_rows) - 1) ? 'style="display: none;"' : ''; ?>><span id="spider_popup_right-ico"><span><i class="bwg_next_btn fa <?php echo $theme_row->lightbox_rl_btn_style; ?>-right"></i></span></span></a>
      </div>
    </div>
    <?php if ( $enable_comment_social ) {
      $bwg_name = (isset($_POST['bwg_name']) ? esc_html(stripslashes($_POST['bwg_name'])) : '');
      $bwg_email = (isset($_POST['bwg_email']) ? esc_html(stripslashes($_POST['bwg_email'])) : '');
      ?>
    <div class="bwg_comment_wrap bwg_popup_sidebar_wrap">
      <div class="bwg_comment_container bwg_popup_sidebar_container bwg_close">
        <div id="ajax_loading" style="position:absolute;">
          <div id="opacity_div" style="display:none; background-color:rgba(255, 255, 255, 0.2); position:absolute; z-index:10150;"></div>
          <span id="loading_div" class="bwg_spider_ajax_loading" style="display:none; text-align:center; position:relative; vertical-align:middle; z-index:10170; background-image:url(<?php echo BWG()->plugin_url . '/images/ajax_loader.png'; ?>); float: none; width:30px;height:30px;background-size:30px 30px; background-repeat: no-repeat; background-position: 50% 50%;">
          </span>
        </div>
        <div class="bwg_comments bwg_popup_sidebar">
            <div title="<?php echo __('Hide Comments', BWG()->prefix); ?>" class="bwg_comments_close bwg_popup_sidebar_close">
              <i class="bwg_comments_close_btn bwg_popup_sidebar_close_btn fa fa-arrow-<?php echo $theme_row->lightbox_comment_pos; ?>"></i>
            </div>
            <form id="bwg_comment_form" method="post" action="<?php echo $popup_url; ?>">
				<p><label for="bwg_name"><?php echo __('Name', BWG()->prefix); ?> </label></p>
				<p><input class="bwg-validate" type="text" name="bwg_name" id="bwg_name" <?php echo ((get_current_user_id() != 0) ? 'readonly="readonly"' : ''); ?>
                        value="<?php echo ((get_current_user_id() != 0) ? get_userdata(get_current_user_id())->display_name : $bwg_name); ?>" />
				</p>
				<p><span class="bwg_comment_error bwg_comment_name_error"></span></p>
              <?php if ($popup_enable_email) { ?>
				<p><label for="bwg_email"><?php echo __('Email', BWG()->prefix); ?> </label></p>
				<p><input class="bwg-validate" type="text" name="bwg_email" id="bwg_email"
                        value="<?php echo ((get_current_user_id() != 0) ? get_userdata(get_current_user_id())->user_email : $bwg_email); ?>" /></p>
				<p><span class="bwg_comment_error bwg_comment_email_error"></span></p>
              <?php } ?>
				<p><label for="bwg_comment"><?php echo __('Comment', BWG()->prefix); ?> </label></p>
				<p><textarea class="bwg-validate bwg_comment_textarea" name="bwg_comment" id="bwg_comment"></textarea></p>
				<p><span class="bwg_comment_error bwg_comment_textarea_error"></span></p>
              <?php if ( $popup_enable_captcha ) { ?>
				<p><label for="bwg_captcha_input"><?php echo __('Verification Code', BWG()->prefix); ?></label></p>
				<p>
					<input id="bwg_captcha_input" name="bwg_captcha_input" class="bwg_captcha_input" type="text" autocomplete="off">
					<img id="bwg_captcha_img" class="bwg_captcha_img" type="captcha" digit="6" src="<?php echo add_query_arg(array('action' => 'bwg_captcha', 'digit' => 6, 'i' => ''), admin_url('admin-ajax.php')); ?>" onclick="bwg_captcha_refresh('bwg_captcha')" ontouchend="bwg_captcha_refresh('bwg_captcha')" />
					<span id="bwg_captcha_refresh" class="bwg_captcha_refresh" onclick="bwg_captcha_refresh('bwg_captcha')" ontouchend="bwg_captcha_refresh('bwg_captcha')"></span>
				</p>
				<p><span class="bwg_comment_error bwg_comment_captcha_error"></span></p>
              <?php } ?>

			  <?php
			  $privacy_policy_url = false;
			  if ( WDWLibrary::get_privacy_policy_url() ) {
				  $privacy_policy_url = true;
			  ?>
			  <p class="bwg-privacy-policy-box">
				  <label for="bwg_comment_privacy_policy">
                  <input id="bwg_comment_privacy_policy"
						name="bwg_comment_privacy_policy"
						onclick="comment_check_privacy_policy()"
						ontouchend="comment_check_privacy_policy()"
						type="checkbox"
						value="1" <?php echo (isset($_POST['bwg_comment_privacy_policy']) ? 'checked' : ''); ?> />
				  <?php
					$privacy_policy_text = __('I consent collecting this data and processing it according to %s of this website.', BWG()->prefix);
					$privacy_policy_link = ' <a href="' . WDWLibrary::get_privacy_policy_url() . '" target="_blank">' . __('Privacy Policy', BWG()->prefix) . '</a>';
					echo sprintf($privacy_policy_text, $privacy_policy_link);
				  ?>
				  </label>
			  </p>
			  <p><span class="bwg_comment_error bwg_comment_privacy_policy_error"></span></p>
			  <?php } ?>
			  <p>
				<input <?php echo ($privacy_policy_url) ? 'disabled="disabled"' : ''; ?> onclick="bwg_add_comment(); return false;" ontouchend="bwg_add_comment(); return false;" class="bwg_submit <?php echo ($privacy_policy_url) ? 'bwg-submit-disabled' : ''; ?>" type="submit"
					 name="bwg_submit" id="bwg_submit" value="<?php echo __('Submit', BWG()->prefix); ?>" />
			  </p>
			  <p class="bwg_comment_waiting_message"><?php _e('Your comment is awaiting moderation', BWG()->prefix); ?></p>
			  <input id="ajax_task" name="ajax_task" type="hidden" value="" />
			  <input id="image_id"id="image_id" name="image_id" type="hidden" value="<?php echo $image_id; ?>" />
              <input id="comment_id" name="comment_id" type="hidden" value="" />
              <input type="hidden" value="<?php echo $comment_moderation ?>" id="bwg_comment_moderation">
            </form>
          <div id="bwg_added_comments">
            <?php
            $comment_rows = $this->model->get_comment_rows_data($image_id);
            foreach ( $comment_rows as $comment_row ) {
				      echo $this->html_comments_block($comment_row);
            }
            ?>
          </div>
        </div>
      </div>
    </div>
    <?php }
    if ( function_exists('BWGEC') ) {
      $pricelist = $pricelist_data["pricelist"];
      $download_items = $pricelist_data["download_items"];
      $parameters = $pricelist_data["parameters"];
      $options = $pricelist_data["options"];
      $products_in_cart = $pricelist_data["products_in_cart"];
      $pricelist_sections = $pricelist->sections ? explode(",", $pricelist->sections) : array();
      ?>
			<div class="bwg_ecommerce_wrap bwg_popup_sidebar_wrap" id="bwg_ecommerce_wrap">
				<div class="bwg_ecommerce_container bwg_popup_sidebar_container bwg_close">
					<div id="ecommerce_ajax_loading" style="position:absolute;">
						<div id="ecommerce_opacity_div" style="display:none; background-color:rgba(255, 255, 255, 0.2); position:absolute; z-index:10150;"></div>
						<span id="ecommerce_loading_div" class="bwg_spider_ajax_loading" style="display:none; text-align:center; position:relative; vertical-align:middle; z-index:10170; background-image:url(<?php echo BWG()->plugin_url . '/images/ajax_loader.png'; ?>); float: none; width:30px;height:30px;background-size:30px 30px; background-repeat: no-repeat; background-position: 50% 50%;"></span>
					</div>
					<div class="bwg_ecommerce_panel bwg_popup_sidebar_panel bwg_popup_sidebar" style="text-align:left;">
						<div id="bwg_ecommerce">
							<p title="<?php echo __('Hide Ecommerce', BWG()->prefix); ?>" class="bwg_ecommerce_close bwg_popup_sidebar_close" >
								<i class="bwg_ecommerce_close_btn bwg_popup_sidebar_close_btn  fa fa-arrow-<?php echo $theme_row->lightbox_comment_pos; ?>" ></i>
							</p>
							<form id="bwg_ecommerce_form" method="post" action="<?php echo $popup_url; ?>">
								<div class="pge_add_to_cart">
									<div>
										<span class="pge_add_to_cart_title"><?php echo (__('Add to cart', BWG()->prefix)); ?></span>
									</div>
									<div>
										<a href="<?php echo get_permalink($options->checkout_page);?>"><?php echo "<span class='products_in_cart'>".$products_in_cart ."</span> ". __('items', BWG()->prefix); ?></a>
									</div>
								</div>
								<div class="bwg_ecommerce_body">
									<ul class="pge_tabs" <?php if(count($pricelist_sections)<=1) echo "style='display:none;'"; ?>>
										<li id="manual_li" <?php if(!in_array("manual",$pricelist_sections)) { echo "style='display:none;'"; } ?> class="pge_active">
											<a href= "#manual">
												<span class="manualh4" >	
													<?php echo __('Prints and products', BWG()->prefix); ?>
												</span>
											</a>											
										</li>
										<li id="downloads_li" <?php if(!in_array("downloads",$pricelist_sections)) echo "style='display:none;'"; ?> >
											<a href= "#downloads">
											<span class="downloadsh4" >	
												<?php echo __('Downloads', BWG()->prefix); ?>
											</span>
											</a>											
										</li>
									</ul>
									<div class="pge_tabs_container" >
									<!-- manual -->
									<div class="manual pge_pricelist" id="manual" <?php if( count($pricelist_sections) == 2  || (count($pricelist_sections) == 1 && end($pricelist_sections) == "manual")) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?>  >																	
										<div>
											<div class="product_manual_price_div">
												<p><?php echo $pricelist->manual_title ? __('Name', BWG()->prefix).': '.$pricelist->manual_title : "";?></p>
                                               <?php if ($pricelist->price) {
                                                 ?>
												<p>
													<span><?php echo __('Price', BWG()->prefix).': '.$options->currency_sign;?></span>
													<span class="_product_manual_price"><?php echo number_format((float)$pricelist->price,2)?></span>
												</p>
                                                  <?php
                                                }
                                              ?>
											</div>
                                          <?php if($pricelist->manual_description){
                                          ?>
											<div class="product_manual_desc_div">
												<p>
													<span><?php echo __('Description', BWG()->prefix);?>:</span>
													<span class="product_manual_desc"><?php echo $pricelist->manual_description;?></span>
												</p>
											</div>
											<?php
                                              }
                                              ?>
											<div class="image_count_div">
												<p>
													<?php echo __('Count', BWG()->prefix).': ';?>
													<input type="number" min="1" class="image_count" value="1" onchange="changeMenualTotal(this);">
												</p>
											</div>
											<?php if ( empty($parameters) == false ) { ?>
											<div class="image_parameters">
												<p><?php //echo __('Parameters', BWG()->prefix); ?></p>
												<?php
													$i = 0;
													foreach($parameters as $parameter_id => $parameter){	
														echo '<div class="parameter_row">';
														switch($parameter["type"]){
															case "1" :
																echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';
																echo $parameter["title"].": <span class='parameter_single'>". $parameter["values"][0]["parameter_value"]."</span>";
																echo '</div>';
																break;
															case "2" :
																echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';															
																echo '<label for="parameter_input">'.$parameter["title"].'</label>';
																echo '<input type="text" name="parameter_input'.$parameter_id.'" id="parameter_input"  value="'. $parameter["values"][0]["parameter_value"] .'">';																	
																echo '</div>';
																break;
															case "3" :
																echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';															
																echo '<label for="parameter_textarea">'.$parameter["title"].'</label>';															
																echo '<textarea  name="parameter_textarea'.$parameter_id.'" id="parameter_textarea"  >'. $parameter["values"][0]["parameter_value"] .'</textarea>';																	
																echo '</div>';
																break;
															case "4" :
																echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';											
																echo '<label for="parameter_select">'.$parameter["title"].'</label>';		
																echo '<select name="parameter_select'.$parameter_id.'" id="parameter_select" onchange="onSelectableParametersChange(this)">';
																echo '<option value="+*0*">-Select-</option>';
																foreach($parameter["values"] as $values){
                                                                    $price_addon = $values["parameter_value_price"] == "0" ? "" : ' ('.$values["parameter_value_price_sign"].$options->currency_sign.number_format((float)$values["parameter_value_price"],2).')';
																	echo '<option value="'.$values["parameter_value_price_sign"].'*'.$values["parameter_value_price"].'*'.$values["parameter_value"].'">'.$values["parameter_value"].$price_addon.'</option>';	
																}
																echo '</select>';
																echo '<input type="hidden" class="already_selected_values">';
																echo '</div>';
																break;	
															case "5" :
																echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';															
																echo '<label>'.$parameter["title"].'</label>';
																foreach($parameter["values"] as $values){	
                                                                    $price_addon = $values["parameter_value_price"] == "0"	? "" : 	' ('.$values["parameter_value_price_sign"].$options->currency_sign.number_format((float)$values["parameter_value_price"],2).')';													
																	echo '<div>';
																	echo '<input type="radio" name="parameter_radio'.$parameter_id.'"  id="parameter_radio'.$i.'" value="'.$values["parameter_value_price_sign"].'*'.$values["parameter_value_price"].'*'.$values["parameter_value"].'"  onchange="onSelectableParametersChange(this)">';	
																	echo '<label for="parameter_radio'.$i.'">'.$values["parameter_value"].$price_addon.'</label>';
																	echo '</div>';
																	$i++;
																}
																echo '<input type="hidden" class="already_selected_values">';
																echo '</div>';
																break;
															case "6" :
																echo '<div class="image_selected_parameter" data-parameter-id="'.$parameter_id.'" data-parameter-type = "'.$parameter["type"].'">';															
																echo '<label>'.$parameter["title"].'</label>';
																foreach($parameter["values"] as $values){
                                                                    $price_addon = $values["parameter_value_price"] == "0" ? "" : ' ('.$values["parameter_value_price_sign"].$options->currency_sign.number_format((float)$values["parameter_value_price"],2).')';
																	echo '<div>';
																	echo '<input type="checkbox" name="parameter_checkbox'.$parameter_id.'" id="parameter_checkbox'.$i.'" value="'.$values["parameter_value_price_sign"].'*'.$values["parameter_value_price"].'*'.$values["parameter_value"].'"  onchange="onSelectableParametersChange(this)">';	
																	echo '<label for="parameter_checkbox'.$i.'">'.$values["parameter_value"].$price_addon.'</label>';
																	echo '</div>';
																	$i++;
																}
																echo '<input type="hidden" class="already_selected_values">';
																echo '</div>';
																break;	
															default:
																break;
														}
														echo '</div>';
													}
												?>
												
											</div>
											<?php } ?>
											<p>
												<span><b><?php echo __('Total', BWG()->prefix).': '.$options->currency_sign;?></b></span>
												<b><span class="product_manual_price" data-price="<?php echo $pricelist->price; ?>" data-actual-price="<?php echo $pricelist->price; ?>"><?php echo number_format((float)$pricelist->price,2)?></span></b>
											</p>
										</div>
									</div>
									<!-- downloads -->
									<div class="downloads pge_pricelist" id="downloads" <?php if( (count($pricelist_sections) == 1 && end($pricelist_sections) == "downloads")) echo 'style="display: block;"'; else echo 'style="display: none;"'; ?> >
										<table>
											<thead>
												<tr>
													<th><?php echo __('Name', BWG()->prefix); ?></th>
													<th><?php echo __('Dimensions', BWG()->prefix); ?></th>
													<th><?php echo __('Price', BWG()->prefix); ?></th>
												  <th><?php echo __('Choose', BWG()->prefix); ?></th>
												</tr>
											</thead>
											<tbody>
												<?php	
													if(empty($download_items) === false){
														foreach($download_items as $download_item){
														?>
															<tr data-price="<?php echo $download_item->item_price; ?>" data-id="<?php echo $download_item->id; ?>">
																<td><?php echo $download_item->item_name; ?></td>
																<td><?php echo $download_item->item_longest_dimension.'px'; ?></td>
																<td class="item_price"><?php echo $options->currency_sign. number_format((float)$download_item->item_price, 2); ?></td>
																<?php if($options->show_digital_items_count == 0){
																  ?>
																  <td><input type="checkbox"  name="selected_download_item" value="<?php echo $download_item->id; ?>" onchange="changeDownloadsTotal(this);"></td>
																  <?php
																}
																else{
																  ?>
																  <td><input type="number" min="0" class="digital_image_count" value="0" onchange="changeDownloadsTotal(this);"></td>
																  <?php
																}
																?>
															  </tr>
														<?php
														}
													}													
												?>
											</tbody>
										</table>	
										<p>
											<span><b><?php echo __('Total', BWG()->prefix).': '.$options->currency_sign;?></b></span>
											<b><span class="product_downloads_price">0</span></b>
										</p>										
									</div>
									</div>
								</div>
								<div style="margin-top:10px;">	
									<input type="button" class="bwg_submit" value="<?php echo __('Add to cart', BWG()->prefix); ?>" onclick="onBtnClickAddToCart();">
									<input type="button" class="bwg_submit" value="<?php echo __('View cart', BWG()->prefix); ?>" onclick="onBtnViewCart()">
									&nbsp;<span class="add_to_cart_msg"></span>
								</div>
								<input id="ajax_task" name="ajax_task" type="hidden" value="" />
								<input id="type" name="type" type="hidden" value="<?php echo isset($pricelist_sections[0]) ? $pricelist_sections[0] : ""  ?>" />
								<input id="image_id" name="image_id" type="hidden" value="<?php echo $image_id; ?>" />
								<div class="pge_options">
									<input type="hidden" name="option_checkout_page" value="<?php  echo get_permalink($options->checkout_page);?>">
									<input type="hidden" name="option_show_digital_items_count" value="<?php echo $options->show_digital_items_count;?>">								
								</div>
							</form>	
						</div>
					</div>
				</div>
			</div>
	<script>
        function changeDownloadsTotal(obj) {
			var totalPrice = 0;
			var showdigitalItemsCount = jQuery("[name=option_show_digital_items_count]").val();
			if( showdigitalItemsCount == 0 ){
				jQuery("[name=selected_download_item]:checked").each(function(){
					totalPrice += Number(jQuery(this).closest("tr").attr("data-price"));
				});
			}
			else{
				jQuery(".digital_image_count").each(function(){
					if(Number(jQuery(this).val()) != 0){
						totalPrice += Number(jQuery(this).closest("tr").attr("data-price")) * Number(jQuery(this).val());
					}
				});
			}
			totalPrice = totalPrice.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
			jQuery(".product_downloads_price").html(totalPrice);
		}

		function changeMenualTotal(obj) {
			if(Number(jQuery(obj).val()) <= 0){
				jQuery(obj).val("1");
			}
			var count =  Number(jQuery(obj).val());
			var totalPrice = Number(jQuery(".product_manual_price").attr("data-actual-price"));
			totalPrice = count*totalPrice;

			totalPrice = totalPrice.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
			jQuery(".product_manual_price").html(totalPrice);
		}

		function onSelectableParametersChange(obj) {
			var parametersPrise = 0;

			var productPrice = data[jQuery('#bwg_current_image_key').val()]["pricelist_manual_price"] ? data[jQuery('#bwg_current_image_key').val()]["pricelist_manual_price"] : '0';
			productPrice = parseFloat(productPrice.replace(",",""));

			var type = jQuery(obj).closest('.image_selected_parameter').attr("data-parameter-type");
			var priceInfo = jQuery(obj).val();
				priceInfo = priceInfo.split("*");
			var priceValue = parseFloat(priceInfo[1]);
			var sign = priceInfo[0];
			var alreadySelectedValues = Number(jQuery(obj).closest('.image_selected_parameter').find(".already_selected_values").val());
			if ( type == "4" || type == "5" ) {
				var newPriceVlaueSelectRadio = parseFloat( sign + priceValue );
				jQuery(obj).closest('.image_selected_parameter').find(".already_selected_values").val(newPriceVlaueSelectRadio);
			}
			else if ( type == "6" ) {
				if ( jQuery(obj).is(":checked") == false ) {
					var  newPriceVlaueCheckbox = alreadySelectedValues - parseFloat( sign + priceValue );
				}
				else {
					 var newPriceVlaueCheckbox = alreadySelectedValues + parseFloat( sign + priceValue );
				}
				jQuery(obj).closest('.image_selected_parameter').find(".already_selected_values").val(newPriceVlaueCheckbox);
			}

			jQuery(".already_selected_values").each( function() {
				parametersPrise += Number(jQuery(this).val());
			});
			productPrice = productPrice + parametersPrise;
			jQuery(".product_manual_price").attr("data-actual-price",productPrice);
			var count = Number(jQuery(".image_count").val()) <= 0 ? 1 : Number(jQuery(".image_count").val());
			productPrice = count * productPrice;
			productPrice = productPrice.toFixed(2).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
			jQuery(".product_manual_price").html(productPrice);
		}

		function onBtnClickAddToCart() {
			var type = jQuery("[name=type]").val();
			if(type != ""){
				var data = {};
				if(type == "manual"){
					var count = jQuery(".image_count").val();
					var parameters = {};
					jQuery(".manual").find(".image_selected_parameter").each(function () {
						var parameterId = jQuery(this).attr("data-parameter-id");
						var parameterTypeId = jQuery(this).attr("data-parameter-type");
						var parameterValue = "";
						switch (parameterTypeId) {
							// input
							case '2':
								parameterValue = jQuery(this).find("input").val();
								break;
							case '3':
								parameterValue = jQuery(this).find("textarea").val();
								break;
							// Select
							case '4':
								parameterValue = jQuery(this).find('select :selected').val();
								break;
							// Radio
							case '5':
								parameterValue = jQuery(this).find('[type=radio]:checked').val();
								break;
							// Checkbox
							case '6':
								var checkbox_parameter_values = [];;
								jQuery(this).find("[type=checkbox]:checked").each(function () {
									checkbox_parameter_values.push(jQuery(this).val());
								});
								parameterValue = checkbox_parameter_values;
								break;
						}

						parameters[parameterId] = parameterValue;
					});
					data.count = count;
					data.parameters = parameters;
					data.price = jQuery(".product_manual_price").attr("data-price").replace(",","");
				}
				else{
					var downloadItems = [];
					var showdigitalItemsCount = jQuery("[name=option_show_digital_items_count]").val();
					if( showdigitalItemsCount == 0 ){
						if(jQuery("[name=selected_download_item]:checked").length == 0){
							jQuery(".add_to_cart_msg").html("You must select at least one item.");
							return;
						}
						jQuery("[name=selected_download_item]:checked").each(function () {
							var downloadItem = {};
							downloadItem.id = jQuery(this).val();
							downloadItem.count = 1;
							downloadItem.price = jQuery(this).closest("tr").attr("data-price");
							downloadItems.push(downloadItem);
						});
					}
					else{
						jQuery(".digital_image_count").each(function () {
							var downloadItem = {};
							if(jQuery(this).val() > 0){
								downloadItem.id = jQuery(this).closest("tr").attr("data-id");
								downloadItem.price = jQuery(this).closest("tr").attr("data-price");
								downloadItem.count = jQuery(this).val();
								downloadItems.push(downloadItem);
							}
						});
					}
					data.downloadItems = downloadItems;
					if(downloadItems.length == 0)	{
						jQuery(".add_to_cart_msg").html("<?php echo __("Please select at least one item", BWG()->prefix);?>");
						return ;
					}
				}

				var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
				var post_data = {
					'action': 'add_cart',
					'task': 'add_cart',
					'controller': 'checkout',
					"image_id": jQuery('#bwg_popup_image').attr('image_id'),
					"type": type,
					"data": JSON.stringify(data)
				};

				jQuery.ajax({
					type: "POST",
					url: ajaxurl,
					data: post_data,
					success: function (response) {
						responseData = JSON.parse(response);
						jQuery(".add_to_cart_msg").html(responseData["msg"]);
						jQuery(".products_in_cart").html(responseData["products_in_cart"]);
						if(responseData["redirect"] == 1){
							window.location.href = "<?php echo get_permalink($options->checkout_page);?>";
						}
					},
					beforeSend: function(){
					},
					complete:function(){
					}
				});
			}
			else {
				jQuery(".add_to_cart_msg").html("<?php echo __("Please select Prints and products or Downloads", BWG()->prefix);?>");
			}
		}

		function onBtnViewCart(){
			var checkoutPage = jQuery("[name=option_checkout_page]").val();
			jQuery("#bwg_ecommerce_form").attr("action",checkoutPage)
			jQuery("#bwg_ecommerce_form").submit();
		}
	</script>
	<?php
	}
    if ( BWG()->options->use_inline_stiles_and_scripts ) {
      if ( $has_embed ) {
        ?>
        <script language="javascript" type="text/javascript" src="<?php echo BWG()->plugin_url . '/js/bwg_embed.js?ver=' . BWG()->plugin_version; ?>"></script>
        <?php
      }
    }
	?>
    <a class="spider_popup_close" onclick="spider_destroypopup(1000); return false;" ontouchend="spider_destroypopup(1000); return false;"><span><i class="bwg_close_btn fa fa-times"></i></span></a>
    <script>
      var filmstrip_width;
      var preloadCount;
      var filmstrip_thumbnail_width = jQuery(".bwg_filmstrip_thumbnail").width();
      var filmstrip_thumbnail_height = jQuery(".bwg_filmstrip_thumbnail").height();

      <?php if ($open_with_fullscreen) { ?>
      filmstrip_width = jQuery( window ).width();
      filmstrip_height = jQuery( window ).height();
      <?php } else { ?>
      filmstrip_width = jQuery(".bwg_filmstrip_container").width();
      filmstrip_height = jQuery(".bwg_filmstrip_container").height();
      <?php } ?>

      <?php if ($filmstrip_direction == 'horizontal') { ?>
      preloadCount = parseInt(filmstrip_width/filmstrip_thumbnail_width) + <?php echo BWG()->options->preload_images_count; ?>;
      <?php } else { ?>
      preloadCount = parseInt(filmstrip_height/filmstrip_thumbnail_height) + <?php echo BWG()->options->preload_images_count; ?>;
      <?php } ?>
      var total_thumbnail_count = jQuery(".bwg_filmstrip_thumbnail").length;

      var key = parseInt(jQuery("#bwg_current_image_key").val());
      var startPoint = 0;
      var endPoint = key+preloadCount;

      jQuery(document).ready(function () {
        wds_load_visible_images();
        jQuery(".pge_tabs li a").on("click", function(){
          jQuery(".pge_tabs_container > div").hide();
          jQuery(".pge_tabs li").removeClass("pge_active");
          jQuery(jQuery(this).attr("href")).show();
          jQuery(this).closest("li").addClass("pge_active");
          jQuery("[name=type]").val(jQuery(this).attr("href").substr(1));
          return false;
        });
      });

      // load visible images in filmstrip
      function wds_load_visible_images() {
        if((key - preloadCount) >= 0) {
          startPoint = key - preloadCount;
        }
        if((key + preloadCount) > total_thumbnail_count) {
          endPoint = total_thumbnail_count;
        }
        for( var i = startPoint; i <= endPoint; i++) {
          var filmstrip_image = jQuery("#bwg_filmstrip_thumbnail_" + i + " img");
          filmstrip_image.removeClass('hidden');
          filmstrip_image.attr('src', filmstrip_image.data('url'));
        }
      }
        var bwg_param = {
            bwg : '<?php echo $bwg; ?>',
            bwg_current_key : '<?php echo $current_key; ?>',
            enable_loop : <?php echo $enable_loop; ?>,
            ecommerceACtive : '<?php echo (function_exists('BWGEC') ) == true ? 1 : 0 ; ?>',
            enable_image_ecommerce : <?php echo $enable_image_ecommerce; ?>,
            lightbox_ctrl_btn_pos : '<?php echo $theme_row->lightbox_ctrl_btn_pos ; ?>',
            lightbox_close_btn_top : '<?php $theme_row->lightbox_close_btn_top; ?>',
            lightbox_close_btn_right : '<?php $theme_row->lightbox_close_btn_right; ?>',
            popup_enable_rate : <?php echo $popup_enable_rate ?>,
            lightbox_filmstrip_thumb_border_width : <?php echo $theme_row->lightbox_filmstrip_thumb_border_width ?>,
            width_or_height: '<?php echo $width_or_height ?>',
            preload_images : <?php echo BWG()->options->preload_images; ?>,
            preload_images_count : <?php echo (int) BWG()->options->preload_images_count; ?>,
            bwg_image_effect : '<?php echo $image_effect; ?>',
            enable_image_filmstrip : <?php echo ($enable_image_filmstrip == '') ? 0 : $enable_image_filmstrip; ?>,
            gallery_id : <?php echo $gallery_id; ?>,
            site_url : '<?php echo BWG()->upload_url; ?>',
            lightbox_comment_width : <?php echo $theme_row->lightbox_comment_width; ?>,
            watermark_width : <?php echo $watermark_width; ?>,
            image_width : <?php echo $image_width; ?>,
            image_height : <?php echo $image_height; ?>,
            outerWidth_or_outerHeight : '<?php echo $outerWidth_or_outerHeight; ?>',
            left_or_top : '<?php echo $left_or_top; ?>',
            lightbox_comment_pos : '<?php echo $theme_row->lightbox_comment_pos; ?>',
            filmstrip_direction : '<?php echo $filmstrip_direction; ?>',
            image_filmstrip_width : <?php echo $image_filmstrip_width; ?>,
            image_filmstrip_height : <?php echo $image_filmstrip_height; ?>,
            lightbox_info_margin : '<?php echo $theme_row->lightbox_info_margin; ?>',
            bwg_share_url : '<?php echo add_query_arg(array('curr_url' => $current_url, 'image_id' => ''), WDWLibrary::get_share_page()); ?>',
            bwg_share_image_url : "<?php echo urlencode(BWG()->upload_url); ?>",
            slideshow_interval : <?php echo $slideshow_interval; ?>,
            open_with_fullscreen : <?php echo $open_with_fullscreen; ?>,
            event_stack : event_stack,
            bwg_playInterval : 0,
            data : data,
        };
		<?php if (BWG()->is_pro && $enable_addthis && $addthis_profile_id) { ?>
			var addthis_share = {
			  url: "<?php echo urlencode($share_url); ?>"
			}
		<?php } ?>
      var lightbox_comment_pos = bwg_param['lightbox_comment_pos'];
      var bwg_image_info_pos = (jQuery(".bwg_ctrl_btn_container").length) ? jQuery(".bwg_ctrl_btn_container").height() : 0;
      setTimeout(function(){
        if(jQuery(".bwg_image_info_container1").height() < (jQuery(".bwg_image_info").height() + jQuery(".bwg_toggle_container").height() + bwg_image_info_pos + 2*(parseInt("<?php echo $theme_row->lightbox_info_margin; ?>")))) {
          if("<?php echo $theme_row->lightbox_ctrl_btn_pos ; ?>" == 'top') {
             jQuery(".bwg_image_info").css("top",bwg_image_info_pos + "px");
          }
           jQuery(".bwg_image_info").height(jQuery(".bwg_image_info_container1").height()- jQuery(".bwg_toggle_container").height()- bwg_image_info_pos - 2*(parseInt("<?php echo $theme_row->lightbox_info_margin; ?>")));
          }
        }, 100);
      var bwg_transition_duration = <?php echo (($slideshow_interval < 4 * $slideshow_effect_duration) && ($slideshow_interval != 0)) ? ($slideshow_interval * 1000) / 4 : ($slideshow_effect_duration * 1000); ?>;
      bwg_param['bwg_transition_duration'] = bwg_transition_duration;
      bwg_param['bwg_trans_in_progress'] = false;

      var bwg_playInterval;
      if ((jQuery("#spider_popup_wrap").width() >= jQuery(window).width()) || (jQuery("#spider_popup_wrap").height() >= jQuery(window).height())) {
        jQuery(".spider_popup_close").attr("class", "bwg_ctrl_btn spider_popup_close_fullscreen");
      }
      /* Stop autoplay.*/
      window.clearInterval(bwg_playInterval);
      /* Set watermark container size.*/

      var bwg_current_filmstrip_pos = <?php echo $current_pos; ?>;
      /* Set filmstrip initial position.*/
      jQuery(document).on('keydown', function (e) {
        if (jQuery("#bwg_name").is(":focus") || jQuery("#bwg_email").is(":focus") || jQuery("#bwg_comment").is(":focus") || jQuery("#bwg_captcha_input").is(":focus")) {
          return;
        }
        if (e.keyCode === 39) { /* Right arrow.*/
          bwg_change_image(parseInt(jQuery('#bwg_current_image_key').val()), parseInt(jQuery('#bwg_current_image_key').val()) + 1, bwg_param['data'])
        }
        else if (e.keyCode === 37) { /* Left arrow.*/
          bwg_change_image(parseInt(jQuery('#bwg_current_image_key').val()), parseInt(jQuery('#bwg_current_image_key').val()) - 1, bwg_param['data'])
        }
        else if (e.keyCode === 27) { /* Esc.*/
          spider_destroypopup(1000);
        }
        else if (e.keyCode === 32) { /* Space.*/
          jQuery(".bwg_play_pause").trigger('click');
        }
      });
      jQuery(window).resize(function() {
        if (typeof jQuery().fullscreen !== 'undefined') {
          if (jQuery.isFunction(jQuery().fullscreen)) {
            if (!jQuery.fullscreen.isFullScreen()) {
              bwg_popup_resize();
            }
          }
        }
      });
      /* Popup current width/height.*/
      var bwg_popup_current_width = <?php echo $image_width; ?>;
      var bwg_popup_current_height = <?php echo $image_height; ?>;

      /* jQuery(document).ready(function () { */
        <?php
        if ( BWG()->is_pro ) {
          if ($enable_addthis && $addthis_profile_id) {
            ?>
          jQuery(".at4-share-outer").show();
            <?php
          }
          ?>
          /* Increase image hit counter.*/
          spider_set_input_value('rate_ajax_task', 'save_hit_count');
          spider_rate_ajax_save('bwg_rate_form');
          jQuery(".bwg_image_hits span").html(++data["<?php echo $current_image_key; ?>"]["hit_count"]);
          var bwg_hash = window.location.hash;
          if (!bwg_hash || bwg_hash.indexOf("bwg") == "-1") {
            location.replace("#bwg<?php echo $gallery_id; ?>/<?php echo $current_image_id; ?>");
            history.replaceState(undefined, undefined, "#bwg<?php echo $gallery_id; ?>/<?php echo $current_image_id; ?>");
          }
		<?php }	?>
		<?php if ($image_right_click) { ?>
          /* Disable right click.*/
          jQuery(".bwg_image_wrap").bind("contextmenu", function (e) {
            return false;
          });
           jQuery(".bwg_image_wrap").css('webkitTouchCallout','none');
		<?php } ?>
        jQuery('#spider_popup_wrap').bind('touchmove', function (event) {
          event.preventDefault();
        });
        if (typeof jQuery().swiperight !== 'undefined') {
          if (jQuery.isFunction(jQuery().swiperight)) {
            jQuery('#spider_popup_wrap .bwg_image_wrap').swiperight(function () {
            bwg_change_image(parseInt(jQuery('#bwg_current_image_key').val()), (parseInt(jQuery('#bwg_current_image_key').val()) + bwg_param['data'].length - 1) % bwg_param['data'].length, bwg_param['data']);
              return false;
            });
          }
        }
        if (typeof jQuery().swipeleft !== 'undefined') {
          if (jQuery.isFunction(jQuery().swipeleft)) {
            jQuery('#spider_popup_wrap .bwg_image_wrap').swipeleft(function () {
            bwg_change_image(parseInt(jQuery('#bwg_current_image_key').val()), (parseInt(jQuery('#bwg_current_image_key').val()) + 1) % bwg_param['data'].length, bwg_param['data']);
              return false;
            });
          }
        }
        bwg_reset_zoom();
        var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
        var bwg_click = isMobile ? 'touchend' : 'click';
        jQuery("#spider_popup_left").on(bwg_click, function () {
          bwg_change_image(parseInt(jQuery('#bwg_current_image_key').val()), (parseInt(jQuery('#bwg_current_image_key').val()) + bwg_param['data'].length - 1) % bwg_param['data'].length, bwg_param['data']);
          return false;
        });
        jQuery("#spider_popup_right").on(bwg_click, function () {
          bwg_change_image(parseInt(jQuery('#bwg_current_image_key').val()), (parseInt(jQuery('#bwg_current_image_key').val()) + 1) % bwg_param['data'].length, bwg_param['data']);
          return false;
        });
        if (navigator.appVersion.indexOf("MSIE 10") != -1 || navigator.appVersion.indexOf("MSIE 9") != -1) {
          setTimeout(function () {
            bwg_popup_resize();
          }, 1);
        }
        else {
          bwg_popup_resize();
        }
        jQuery(".bwg_watermark").css({display: 'none'});
        setTimeout(function () {
          bwg_change_watermark_container();
        }, 500);
        /* If browser doesn't support Fullscreen API.*/
        if (typeof jQuery().fullscreen !== 'undefined') {
          if (jQuery.isFunction(jQuery().fullscreen)) {
            if (!jQuery.fullscreen.isNativelySupported()) {
              jQuery(".bwg_fullscreen").hide();
            }
          }
        }
        /* Set image container height.*/
        <?php if ($filmstrip_direction == 'horizontal') { ?>
          jQuery(".bwg_image_container").height(jQuery(".bwg_image_wrap").height() - <?php echo $image_filmstrip_height; ?>);
          jQuery(".bwg_image_container").width(jQuery(".bwg_image_wrap").width());
        <?php }
        else {
          ?>
          jQuery(".bwg_image_container").height(jQuery(".bwg_image_wrap").height());
          jQuery(".bwg_image_container").width(jQuery(".bwg_image_wrap").width() - <?php echo $image_filmstrip_width; ?>);
          <?php
        } ?>
        /* Change default scrollbar in comments, ecommerce.*/
        if (typeof jQuery().mCustomScrollbar !== 'undefined' && jQuery.isFunction(jQuery().mCustomScrollbar)) {
          jQuery(".bwg_comments").mCustomScrollbar({scrollInertia: 150});
          jQuery(".bwg_ecommerce_panel").mCustomScrollbar({
				scrollInertia: 150,    
				advanced:{
                  updateOnContentResize: true
                }
			});
		
        }
        var mousewheelevt = (/Firefox/i.test(navigator.userAgent)) ? "DOMMouseScroll" : "mousewheel" /*FF doesn't recognize mousewheel as of FF3.x*/
        jQuery('.bwg_filmstrip').on(mousewheelevt, function(e) {
          var evt = window.event || e; /* Equalize event object.*/
          evt = evt.originalEvent ? evt.originalEvent : evt; /* Convert to originalEvent if possible.*/
          var delta = evt.detail ? evt.detail*(-40) : evt.wheelDelta; /* Check for detail first, because it is used by Opera and FF.*/
          var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
          if (delta > 0) {
            /* Scroll up.*/
            jQuery(".bwg_filmstrip_left").trigger(isMobile ? 'touchend' : 'click');
          }
          else {
            /* Scroll down.*/
            jQuery(".bwg_filmstrip_right").trigger(isMobile ? 'touchend' : 'click');
          }
        });
        jQuery(".bwg_filmstrip_right").on(bwg_click, function () {
          jQuery( ".bwg_filmstrip_thumbnails" ).stop(true, false);
          if ( jQuery(".bwg_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> >= - (jQuery(".bwg_filmstrip_thumbnails").<?php echo $width_or_height; ?>() - jQuery(".bwg_filmstrip").<?php echo $width_or_height; ?>()) ) {
            jQuery(".bwg_filmstrip_left").css({ opacity: 1, filter: "Alpha(opacity=100)" });
            if ( (jQuery(".bwg_filmstrip_thumbnails").position().<?php echo $left_or_top; ?>) < ( - (jQuery(".bwg_filmstrip_thumbnails").<?php echo $width_or_height; ?>() - jQuery(".bwg_filmstrip").<?php echo $width_or_height; ?>() - <?php echo $filmstrip_thumb_right_left_space + $image_filmstrip_width + $all_images_right_left_space ?>))) {
			  jQuery(".bwg_filmstrip_thumbnails").animate({
				  <?php echo $left_or_top; ?>: -( jQuery(".bwg_filmstrip_thumbnails").<?php echo $width_or_height; ?>() - jQuery(".bwg_filmstrip").<?php echo $width_or_height ?>() - <?php echo $all_images_right_left_space; ?>)
				  }, 500, 'linear');
            }
            else {
              jQuery(".bwg_filmstrip_thumbnails").animate({
				<?php echo $left_or_top; ?>: (jQuery(".bwg_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> - <?php echo $filmstrip_thumb_right_left_space + $image_filmstrip_width; ?>)
				}, 500, 'linear');
            }
          }
          /* Disable right arrow.*/
          window.setTimeout(function(){
            if (jQuery(".bwg_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> == -(jQuery(".bwg_filmstrip_thumbnails").<?php echo $width_or_height; ?>() - jQuery(".bwg_filmstrip").<?php echo $width_or_height; ?>())) {
              jQuery(".bwg_filmstrip_right").css({opacity: 0.3, filter: "Alpha(opacity=30)"});
            }
          }, 500);
        });
        jQuery(".bwg_filmstrip_left").on(bwg_click, function () {
          jQuery( ".bwg_filmstrip_thumbnails" ).stop(true, false);
          if ((jQuery(".bwg_filmstrip_thumbnails").position().<?php echo $left_or_top; ?>) < 0) {
            jQuery(".bwg_filmstrip_right").css({opacity: 1, filter: "Alpha(opacity=100)"});
            if (jQuery(".bwg_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> > - <?php echo $filmstrip_thumb_right_left_space + $image_filmstrip_width; ?>) {
              jQuery(".bwg_filmstrip_thumbnails").animate({<?php echo $left_or_top; ?>: 0}, 500, 'linear');
            }
            else {
              jQuery(".bwg_filmstrip_thumbnails").animate({<?php echo $left_or_top; ?>: (jQuery(".bwg_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> + <?php echo $image_filmstrip_width + $filmstrip_thumb_right_left_space; ?>)}, 500, 'linear');
            }
          }
          /* Disable left arrow.*/
          window.setTimeout(function(){
            if (jQuery(".bwg_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> == 0) {
              jQuery(".bwg_filmstrip_left").css({opacity: 0.3, filter: "Alpha(opacity=30)"});
            }
          }, 500);
        });
        /* Set filmstrip initial position.*/
        bwg_set_filmstrip_pos(jQuery(".bwg_filmstrip").<?php echo $width_or_height; ?>());
        /* Show/hide image title/description.*/
        jQuery(".bwg_info").on(bwg_click, function() {
          if (jQuery(".bwg_image_info_container1").css("display") == 'none') {
            jQuery(".bwg_image_info_container1").css("display", "table-cell");
            jQuery(".bwg_info").attr("title", "<?php echo __('Hide info', BWG()->prefix); ?>");
             var bwg_image_info_pos = (jQuery(".bwg_ctrl_btn_container").length) ? jQuery(".bwg_ctrl_btn_container").height() : 0;
            setTimeout(function(){
              if(jQuery(".bwg_image_info_container1").height() < (jQuery(".bwg_image_info").height() + bwg_image_info_pos + 2*(parseInt("<?php echo $theme_row->lightbox_info_margin; ?>")))) {
                if("<?php echo $theme_row->lightbox_ctrl_btn_pos ; ?>" == 'top') {
                   jQuery(".bwg_image_info").css("top",bwg_image_info_pos + "px");
                }
                 jQuery(".bwg_image_info").height(jQuery(".bwg_image_info_container1").height()- bwg_image_info_pos - 2*(parseInt("<?php echo $theme_row->lightbox_info_margin; ?>")));
                }
              }, 100);
          }
          else {
            jQuery(".bwg_image_info_container1").css("display", "none");
            jQuery(".bwg_info").attr("title", "<?php echo __('Show info', BWG()->prefix); ?>");
          }
        });
        /* Show/hide image rating.*/
        jQuery(".bwg_rate").on(bwg_click, function() {
          if (jQuery(".bwg_image_rate_container1").css("display") == 'none') {
            jQuery(".bwg_image_rate_container1").css("display", "table-cell");
            jQuery(".bwg_rate").attr("title", "<?php echo __('Hide rating', BWG()->prefix); ?>");
          }
          else {
            jQuery(".bwg_image_rate_container1").css("display", "none");
            jQuery(".bwg_rate").attr("title", "<?php echo __('Show rating', BWG()->prefix); ?>");
          }
        });
        /* Open/close comments.*/
        jQuery(".bwg_comment, .bwg_comments_close_btn").on(bwg_click, function() { bwg_comment() });
		/* Open/close ecommerce.*/
        jQuery(".bwg_ecommerce, .bwg_ecommerce_close_btn").on(bwg_click, function() { bwg_ecommerce() });
        /* Open/close control buttons.*/
        jQuery(".bwg_toggle_container").on(bwg_click, function () {
          var bwg_open_toggle_btn_class = "<?php echo ($theme_row->lightbox_ctrl_btn_pos == 'top') ? 'fa-angle-up' : 'fa-angle-down'; ?>";
          var bwg_close_toggle_btn_class = "<?php echo ($theme_row->lightbox_ctrl_btn_pos == 'top') ? 'fa-angle-down' : 'fa-angle-up'; ?>";

          var bwg_image_info_height = jQuery(".bwg_image_info_container1").height()-jQuery(".bwg_ctrl_btn_container").height()-2*(parseInt("<?php echo $theme_row->lightbox_info_margin; ?>"));

          var image_info= jQuery(".bwg_image_description").outerHeight() + jQuery(".bwg_image_title").outerHeight() + 2*(parseInt("<?php echo $theme_row->lightbox_info_margin; ?>"));

          if (jQuery(".bwg_toggle_container i").hasClass(bwg_open_toggle_btn_class)) {
            /* Close controll buttons.*/
            var info_height = bwg_image_info_height + jQuery(".bwg_ctrl_btn_container").height();
            var top = parseInt(jQuery(".bwg_image_info").css("top")) - jQuery(".bwg_ctrl_btn_container").height();
            var bottom = jQuery(".bwg_ctrl_btn_container").height();
            <?php
             if ($theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_info_pos == 'top') {
                ?>
               if (jQuery(".bwg_image_info_container1").height() < image_info)  {
                   jQuery(".bwg_image_info").animate({top: top + "px", height: info_height}, 500);
                }
                else {
                  jQuery(".bwg_image_info").animate({top: top + "px"}, 500);
                }
			<?php
              }
              elseif ($theme_row->lightbox_ctrl_btn_pos == 'bottom') {
                ?>
               if (jQuery(".bwg_image_info_container1").height() < image_info)  {
                    jQuery(".bwg_image_info").animate({bottom: 0, height: info_height}, 500);
                } else {
                    <?php
                      if ($theme_row->lightbox_info_pos ==  'bottom') {
                      ?>
                        jQuery(".bwg_image_info").animate({top: -top + "px"}, 500);
                      <?php
                      } else {
                      ?>
                        jQuery(".bwg_image_info").animate({top: 0}, 500);
                      <?php
                      } ?>
                }
                <?php
              }
              if ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_info_pos == 'bottom') {
                ?>
                jQuery(".bwg_image_info").animate({bottom: 0}, 500);
                <?php
              }
              elseif ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_info_pos == 'top') {
                ?>
                jQuery(".bwg_image_info").animate({top: 0}, 500);
                <?php
              }
              if ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_rate_pos == 'bottom') {
                ?>
                 jQuery(".bwg_image_rate").animate({bottom: 0}, 500);
                <?php
              }
              elseif ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_rate_pos == 'top') {
                ?>
                jQuery(".bwg_image_rate").animate({top: 0}, 500);
                <?php
              }
              if ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_hit_pos == 'bottom') {
                ?>
                jQuery(".bwg_image_hit").animate({bottom: 0}, 500);
                <?php
              }
              elseif ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_hit_pos == 'top') {
                ?>
                jQuery(".bwg_image_hit").animate({top: 0}, 500);
                <?php
              }
            ?>
            jQuery(".bwg_ctrl_btn_container").animate({<?php echo $theme_row->lightbox_ctrl_btn_pos; ?>: '-' + jQuery(".bwg_ctrl_btn_container").height()}, 500);
            jQuery(".bwg_toggle_container").animate({
                <?php echo $theme_row->lightbox_ctrl_btn_pos; ?>: 0
              }, {
                duration: 500,
                complete: function () { jQuery(".bwg_toggle_container i").attr("class", "bwg_toggle_btn fa " + bwg_close_toggle_btn_class) }
              });
          }
          else {
            var bwg_image_info_height = jQuery(".bwg_image_info_container1").height()-2*(parseInt("<?php echo $theme_row->lightbox_info_margin; ?>")) - jQuery(".bwg_toggle_container").height();

            var image_info= jQuery(".bwg_image_description").outerHeight() + jQuery(".bwg_image_title").outerHeight() + 2*(parseInt("<?php echo $theme_row->lightbox_info_margin; ?>")) + jQuery(".bwg_toggle_container").height();

            var info_height = bwg_image_info_height;
            var top = parseInt(jQuery(".bwg_image_info").css("top")) + jQuery(".bwg_ctrl_btn_container").height();
            /* Open controll buttons.*/
            <?php
            if ($theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_info_pos == 'top') {

             ?>
               if(jQuery(".bwg_image_info_container1").height() < image_info)  {
                  jQuery(".bwg_image_info").animate({top: top + "px", height: info_height}, 500);
               }
               else {
                  jQuery(".bwg_image_info").animate({top: top + "px"}, 500);
               }
             <?php
             }
             elseif ($theme_row->lightbox_ctrl_btn_pos == 'bottom') {
             ?>
               if(jQuery(".bwg_image_info_container1").height() < image_info)  {
                  jQuery(".bwg_image_info").animate({bottom: 0, height: info_height}, 500);
               }
               else {
                  jQuery(".bwg_image_info").animate({top: 0}, 500);
               }
             <?php
             }
              if ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_info_pos == 'bottom') {
                ?>
                jQuery(".bwg_image_info").animate({bottom: jQuery(".bwg_ctrl_btn_container").height()}, 500);
                <?php
              }
              elseif ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_info_pos == 'top') {
                ?>
                jQuery(".bwg_image_info").animate({top: jQuery(".bwg_ctrl_btn_container").height()}, 500);
                <?php
              }
              if ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_rate_pos == 'bottom') {
                ?>
                jQuery(".bwg_image_rate").animate({bottom: jQuery(".bwg_ctrl_btn_container").height()}, 500);
                <?php
              }
              elseif ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_rate_pos == 'top') {
                ?>
                jQuery(".bwg_image_rate").animate({top: jQuery(".bwg_ctrl_btn_container").height()}, 500);
                <?php
              }
              if ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'bottom') && $theme_row->lightbox_ctrl_btn_pos == 'bottom' && $theme_row->lightbox_hit_pos == 'bottom') {
                ?>
                jQuery(".bwg_image_hit").animate({bottom: jQuery(".bwg_ctrl_btn_container").height()}, 500);
                <?php
              }
              elseif ((!$enable_image_filmstrip || $theme_row->lightbox_filmstrip_pos != 'top') && $theme_row->lightbox_ctrl_btn_pos == 'top' && $theme_row->lightbox_hit_pos == 'top') {
                ?>
                jQuery(".bwg_image_hit").animate({top: jQuery(".bwg_ctrl_btn_container").height()}, 500);
                <?php
              }
            ?>
            jQuery(".bwg_ctrl_btn_container").animate({<?php echo $theme_row->lightbox_ctrl_btn_pos; ?>: 0}, 500);
            jQuery(".bwg_toggle_container").animate({
                <?php echo $theme_row->lightbox_ctrl_btn_pos; ?>: jQuery(".bwg_ctrl_btn_container").height()
              }, {
                duration: 500,
                complete: function () { jQuery(".bwg_toggle_container i").attr("class", "bwg_toggle_btn fa " + bwg_open_toggle_btn_class) }
              });
          }
        });
        /* Set window height not full screen */
        var bwg_windowheight = window.innerHeight;
        /* Maximize/minimize.*/
        jQuery(".bwg_resize-full").on(bwg_click, function () {
            bwg_resize_full();
        });
        /* Fullscreen.*/
        /*Toggle with mouse click*/
        jQuery(".bwg_fullscreen").on(bwg_click, function () {
          jQuery(".bwg_watermark").css({display: 'none'});
          var comment_container_width = 0;
          if (jQuery(".bwg_comment_container").hasClass("bwg_open") || jQuery(".bwg_ecommerce_container").hasClass("bwg_open")) {
            comment_container_width = jQuery(".bwg_comment_container").width() || jQuery(".bwg_ecommerce_container").width();
          }
          function bwg_exit_fullscreen(windowheight) {
            if (jQuery(window).width() > <?php echo $image_width; ?>) {
              bwg_popup_current_width = <?php echo $image_width; ?>;
            }
            if (window.innerHeight > <?php echo $image_height; ?>) {
              bwg_popup_current_height = <?php echo $image_height; ?>;
            }
            <?php
            /* "Full width lightbox" sets yes.*/
            if ($open_with_fullscreen) {
              ?>
            bwg_popup_current_width = jQuery(window).width();
            bwg_popup_current_height = windowheight;
              <?php
            }
            ?>
            jQuery("#spider_popup_wrap").on("fscreenclose", function() {
              jQuery("#spider_popup_wrap").css({
                width: bwg_popup_current_width,
                height: bwg_popup_current_height,
                left: '50%',
                top: '50%',
                marginLeft: -bwg_popup_current_width / 2,
                marginTop: -bwg_popup_current_height / 2,
                zIndex: 100000
              });
              jQuery(".bwg_image_wrap").css({width: bwg_popup_current_width - comment_container_width});
              jQuery(".bwg_image_container").css({height: bwg_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>, width: bwg_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>});
              jQuery(".bwg_popup_image").css({
                maxWidth: bwg_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
                maxHeight: bwg_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
              });
              jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").css({
                maxWidth: bwg_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
                maxHeight: bwg_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
              });
              bwg_resize_instagram_post();
              /* Set watermark container size.*/
              bwg_change_watermark_container();
              jQuery(".bwg_filmstrip_container").css({<?php echo $width_or_height; ?>: bwg_popup_current_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>});
              jQuery(".bwg_filmstrip").css({<?php echo $width_or_height; ?>: bwg_popup_current_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>- 40});
              /* Set filmstrip initial position.*/
              bwg_set_filmstrip_pos(bwg_popup_current_<?php echo $width_or_height; ?> - 40);
              jQuery(".bwg_resize-full").show();
              jQuery(".bwg_resize-full").attr("class", "bwg_ctrl_btn bwg_resize-full fa fa-resize-full");
              jQuery(".bwg_resize-full").attr("title", "<?php echo __('Maximize', BWG()->prefix); ?>");
              jQuery(".bwg_fullscreen").attr("class", "bwg_ctrl_btn bwg_fullscreen fa fa-fullscreen");
              jQuery(".bwg_fullscreen").attr("title", "<?php echo __('Fullscreen', BWG()->prefix); ?>");
              if (jQuery("#spider_popup_wrap").width() < jQuery(window).width()) {
                if (jQuery("#spider_popup_wrap").height() < window.innerHeight) {
                  jQuery(".spider_popup_close_fullscreen").attr("class", "spider_popup_close");
                }
              }
            });
          }
          if (typeof jQuery().fullscreen !== 'undefined') {
            if (jQuery.isFunction(jQuery().fullscreen)) {
              if (jQuery.fullscreen.isFullScreen()) {
                /* Exit Fullscreen.*/
                jQuery.fullscreen.exit();
                bwg_exit_fullscreen(bwg_windowheight);
              }
              else {
                /* Fullscreen.*/
                jQuery("#spider_popup_wrap").fullscreen();
                /*jQuery("#spider_popup_wrap").on("fscreenopen", function() {
                if (jQuery.fullscreen.isFullScreen()) {*/
                  var screen_width = screen.width;
                  var screen_height = screen.height;
                  jQuery("#spider_popup_wrap").css({
                    width: screen_width,
                    height: screen_height,
                    left: 0,
                    top: 0,
                    margin: 0,
                    zIndex: 100000
                  });
                  jQuery(".bwg_image_wrap").css({width: screen_width - comment_container_width});
                  jQuery(".bwg_image_container").css({height: (screen_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>), width: screen_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>});
                  jQuery(".bwg_popup_image").css({
                    maxWidth: (screen_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>),
                    maxHeight: (screen_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>)
                  });

                  jQuery(".bwg_popup_embed > .bwg_embed_frame > img, .bwg_popup_embed > .bwg_embed_frame > video").css({
                    maxWidth: (screen_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>),
                    maxHeight: (screen_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>)
                  });

                  bwg_resize_instagram_post();

                  /* Set watermark container size.*/
                  bwg_change_watermark_container();
                  jQuery(".bwg_filmstrip_container").css({<?php echo $width_or_height; ?>: (screen_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>)});
                  jQuery(".bwg_filmstrip").css({<?php echo $width_or_height; ?>: (screen_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40)});
                  /* Set filmstrip initial position.*/
                  bwg_set_filmstrip_pos(screen_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40);
                  jQuery(".bwg_resize-full").hide();
                  jQuery(".bwg_fullscreen").attr("class", "bwg_ctrl_btn bwg_fullscreen fa fa-resize-small");
                  jQuery(".bwg_fullscreen").attr("title", "<?php echo __('Exit Fullscreen', BWG()->prefix); ?>");
                  jQuery(".spider_popup_close").attr("class", "bwg_ctrl_btn spider_popup_close_fullscreen");
                /*});
                }*/
              }
            }
          }
          return false;
        });
        /* Play/pause.*/
        jQuery(".bwg_play_pause, .bwg_popup_image").on(bwg_click, function () {
          if (jQuery(".bwg_play_pause").length && jQuery(".bwg_play_pause").hasClass("fa-play") && !jQuery(".bwg_comment_container").hasClass("bwg_open")) {
            /* PLay.*/
            bwg_play( bwg_param['data'] );
            jQuery(".bwg_play_pause").attr("title", "<?php echo __('Pause', BWG()->prefix); ?>");
            jQuery(".bwg_play_pause").attr("class", "bwg_ctrl_btn bwg_play_pause fa fa-pause");
          }
          else {
            /* Pause.*/
            window.clearInterval(bwg_playInterval);
            jQuery(".bwg_play_pause").attr("title", "<?php echo __('Play', BWG()->prefix); ?>");
            jQuery(".bwg_play_pause").attr("class", "bwg_ctrl_btn bwg_play_pause fa fa-play");
          }
        });
        /* Open with autoplay.*/
        <?php if ($open_with_autoplay) { ?>
          bwg_play( bwg_param['data'] );
          jQuery(".bwg_play_pause").attr("title", "<?php echo __('Pause', BWG()->prefix); ?>");
          jQuery(".bwg_play_pause").attr("class", "bwg_ctrl_btn bwg_play_pause fa fa-pause");
	    <?php } ?>
        /* Open with fullscreen.*/
        <?php if ($open_with_fullscreen) { ?>
          bwg_open_with_fullscreen();
		<?php } ?>

        /* load  filmstrip not visible images */
        function bwg_load_filmstrip() {
          for(var i = 1; i <= total_thumbnail_count; i++) {
            leftIndex = startPoint - i;
            rightIndex = endPoint + i;

            if ( rightIndex < total_thumbnail_count ) {  /* check if right index is greater than max index */
              var filmstrip_image = jQuery("#bwg_filmstrip_thumbnail_" + rightIndex + " img");
              filmstrip_image.removeClass('hidden');
              filmstrip_image.attr('src', filmstrip_image.data('url'));
            }
            /* Left from key indexes */
            if ( leftIndex >= 0 ) {
              var filmstrip_image = jQuery("#bwg_filmstrip_thumbnail_" + leftIndex + " img");
              filmstrip_image.removeClass('hidden');
              filmstrip_image.attr('src', filmstrip_image.data('url'));
            }

          }

        }
        jQuery(".bwg_popup_image").removeAttr("width");
        jQuery(".bwg_popup_image").removeAttr("height");
      /* }); */

      jQuery(window).focus(function() {
        /* event_stack = [];*/
        if (jQuery(".bwg_play_pause").length && !jQuery(".bwg_play_pause").hasClass("fa-play")) {
          bwg_play( bwg_param['data'] );
        }
        /*var i = 0;
        jQuery(".bwg_slider").children("span").each(function () {
          if (jQuery(this).css('opacity') == 1) {
            jQuery("#bwg_current_image_key").val(i);
          }
          i++;
        });*/
      });
      jQuery(window).blur(function() {
        event_stack = [];
        window.clearInterval(bwg_playInterval);
      });
      var lightbox_ctrl_btn_pos = "<?php echo $theme_row->lightbox_ctrl_btn_pos ;?>";
	  if ( <?php echo $open_ecommerce;?> == 1) {
		setTimeout(function(){ bwg_ecommerce();  }, 400);
	  }
	  <?php if ( $open_comment ) { ?>
		  bwg_comment();
	  <?php } ?>
    </script>
    <?php
    die();
  }

  private function loading() {
    ?>
    <div class="bwg-loading hidden"></div>
    <?php
  }

  public function html_comments_block( $row = array() ) {
    ob_start();
	?>
	<div id="bwg_comment_block_<?php echo $row->id; ?>" class="bwg_single_comment">
		<p class="bwg_comment_header_p">
		  <span class="bwg_comment_header"><?php echo $row->name; ?></span>
			<?php if ( current_user_can('manage_options') ) { ?>
				<i onclick="bwg_remove_comment(<?php echo $row->id; ?>); return false;"
					ontouchend="bwg_remove_comment(<?php echo $row->id; ?>); return false;"
					title="<?php _e('Delete Comment', BWG()->prefix); ?>" class="bwg_comment_delete_btn fa fa-times"></i>
			<?php } ?>
		  <span class="bwg_comment_date"><?php echo $row->date; ?></span>
		</p>
		<div class="bwg_comment_body_p">
		  <span class="bwg_comment_body"><?php echo wpautop($row->comment); ?></span>
		</div>
	  </div>
    <?php
    return ob_get_clean();
  }
}
