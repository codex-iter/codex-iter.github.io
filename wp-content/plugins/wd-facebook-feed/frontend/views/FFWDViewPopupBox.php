<?php

class FFWDViewPopupBox {
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
  /**
   *
   */
  public function display() {
    global $wp;
    require_once(WD_FFWD_DIR . '/framework/WDFacebookFeed.php');
    $current_url = (isset($_GET['current_url']) ? add_query_arg(esc_html($_GET['current_url']), '', home_url($wp->request)) : '');
    $tag_id = (isset($_GET['tag_id']) ? esc_html($_GET['tag_id']) : 0);
    $fb_id = (isset($_GET['fb_id']) ? esc_html($_GET['fb_id']) : 0);
    $ffwd = (isset($_GET['current_view']) ? esc_html($_GET['current_view']) : 0);
    $current_image_id = (isset($_GET['image_id']) ? esc_html($_GET['image_id']) : 0);
    $ffwd_album = (isset($_GET['ffwd_album']) ? stripslashes($_GET['ffwd_album']) : 0);
    $from_album = (isset($ffwd_album) && $ffwd_album !== 0) ? 1 : 0;
    //print_r(json_decode($ffwd_album));
    //die();
    $content_type = (isset($_GET['content_type']) ? esc_html($_GET['content_type']) : 'specific');
    $theme_id = (isset($_GET['theme_id']) ? esc_html($_GET['theme_id']) : 1);
    $thumb_width = (isset($_GET['thumb_width']) ? esc_html($_GET['thumb_width']) : 120);
    $thumb_height = (isset($_GET['thumb_height']) ? esc_html($_GET['thumb_height']) : 90);
    $open_with_fullscreen = (isset($_GET['open_with_fullscreen']) ? esc_html($_GET['open_with_fullscreen']) : 0);
    $open_with_autoplay = (isset($_GET['open_with_autoplay']) ? esc_html($_GET['open_with_autoplay']) : 0);
    $image_width = (isset($_GET['image_width']) ? esc_html($_GET['image_width']) : 800);
    $image_height = (isset($_GET['image_height']) ? esc_html($_GET['image_height']) : 500);
    $image_effect = ((isset($_GET['image_effect']) && esc_html($_GET['image_effect'])) ? esc_html($_GET['image_effect']) : 'fade');
    $sort_by = (isset($_GET['wd_sor']) ? esc_html($_GET['wd_sor']) : 'order');
    $order_by = (isset($_GET['wd_ord']) ? esc_html($_GET['wd_ord']) : 'asc');
    $enable_image_filmstrip = (isset($_GET['enable_image_filmstrip']) ? esc_html($_GET['enable_image_filmstrip']) : 0);


    $enable_image_fullscreen = (isset($_GET['enable_image_fullscreen']) ? esc_html($_GET['enable_image_fullscreen']) : 0);
    $enable_object_info = (isset($_GET['enable_object_info']) ? esc_html($_GET['enable_object_info']) : 0);
    if ($enable_image_filmstrip) {
      $image_filmstrip_height = (isset($_GET['image_filmstrip_height']) ? esc_html($_GET['image_filmstrip_height']) : '20');
      $thumb_ratio = $thumb_width / $thumb_height;
      $image_filmstrip_width = round($thumb_ratio * $image_filmstrip_height);
    }
    else {
      $image_filmstrip_height = 0;
      $image_filmstrip_width = 0;
    }
    $slideshow_interval = (isset($_GET['slideshow_interval']) ? (int) $_GET['slideshow_interval'] : 5);
    $enable_image_ctrl_btn = (isset($_GET['enable_image_ctrl_btn']) ? esc_html($_GET['enable_image_ctrl_btn']) : 0);
    $enable_comments = (isset($_GET['enable_comments']) ? esc_html($_GET['enable_comments']) : 0);

    $enable_likes = (isset($_GET['enable_likes']) ? esc_html($_GET['enable_likes']) : 0);
    $enable_shares = (isset($_GET['enable_shares']) ? esc_html($_GET['enable_shares']) : 0);
    $enable_author = (isset($_GET['enable_author']) ? esc_html($_GET['enable_author']) : 0);
    $enable_name = (isset($_GET['enable_name']) ? esc_html($_GET['enable_name']) : 0);
    $enable_place_name = (isset($_GET['enable_place_name']) ? esc_html($_GET['enable_place_name']) : 0);
    $enable_message_desc = (isset($_GET['enable_message_desc']) ? esc_html($_GET['enable_message_desc']) : 0);
    $enable_image_facebook = (isset($_GET['enable_image_facebook']) ? esc_html($_GET['enable_image_facebook']) : 0);
    $enable_image_twitter = (isset($_GET['enable_image_twitter']) ? esc_html($_GET['enable_image_twitter']) : 0);
    $enable_image_google = (isset($_GET['enable_image_google']) ? esc_html($_GET['enable_image_google']) : 0);
    $enable_image_pinterest = (isset($_GET['enable_image_pinterest']) ? esc_html($_GET['enable_image_pinterest']) : 0);
    $enable_image_tumblr = (isset($_GET['enable_image_tumblr']) ? esc_html($_GET['enable_image_tumblr']) : 0);

    $theme_row = $this->model->get_theme_row_data($theme_id);
    $option_row = $this->model->get_option_row_data();



    $this->model->ffwd_set_date_timezone_offset();
    $view_on_fb = isset($option_row->view_on_fb) ? $option_row->view_on_fb : 1;
    $ffwd_info = $this->model->get_ffwd_info_data($fb_id);
    $content = $ffwd_info->content;

    $option_row->comments_order=$ffwd_info->comments_order;
    $option_row->comments_filter=$ffwd_info->comments_filter;
    $option_row->comments_replies=$ffwd_info->comments_replies;
    $option_row->post_text_length=$ffwd_info->post_text_length;
    $option_row->view_on_fb=$ffwd_info->view_on_fb;
    $option_row->page_plugin_pos=$ffwd_info->page_plugin_pos;
    $option_row->page_plugin_fans=$ffwd_info->page_plugin_fans;
    $option_row->page_plugin_width=$ffwd_info->page_plugin_width;
    $option_row->page_plugin_cover=$ffwd_info->page_plugin_cover;
    $option_row->page_plugin_header=$ffwd_info->page_plugin_header;
    $option_row->event_street=$ffwd_info->event_street;
    $option_row->event_city=$ffwd_info->event_city;
    $option_row->event_country=$ffwd_info->event_country;
    $option_row->event_zip=$ffwd_info->event_zip;
    $option_row->event_map=$ffwd_info->event_map;
    $option_row->event_date=$ffwd_info->event_date;
    $option_row->event_desp_length=$ffwd_info->event_desp_length;


    $filmstrip_direction = 'horizontal';
    if ($theme_row->lightbox_filmstrip_pos == 'right' || $theme_row->lightbox_filmstrip_pos == 'left') {
      $filmstrip_direction = 'vertical';
    }
    if ($enable_image_filmstrip) {
      if ($filmstrip_direction == 'horizontal') {
        $image_filmstrip_height = (isset($_GET['image_filmstrip_height']) ? esc_html($_GET['image_filmstrip_height']) : '20');
        $thumb_ratio = $thumb_width / $thumb_height;
        $image_filmstrip_width = round($thumb_ratio * $image_filmstrip_height);
      }
      else {
        $image_filmstrip_width = (isset($_GET['image_filmstrip_height']) ? esc_html($_GET['image_filmstrip_height']) : '50');
        $thumb_ratio = $thumb_height / $thumb_width;
        $image_filmstrip_height = round($thumb_ratio * $image_filmstrip_width);
      }
    }
    else {
      $image_filmstrip_height = 0;
      $image_filmstrip_width = 0;
    }
    $image_rows = $this->model->get_image_rows_data($fb_id, $sort_by, $order_by);
    $image_rows = ($from_album) ? json_decode($ffwd_album) : $image_rows;
    $image_id = (isset($_POST['image_id']) ? (int) $_POST['image_id'] : $current_image_id);
    $filmstrip_thumb_margin = $theme_row->lightbox_filmstrip_thumb_margin;
    $margins_split = explode(" ", $filmstrip_thumb_margin);
    $filmstrip_thumb_margin_right = 0;
    $filmstrip_thumb_margin_left = 0;
    $temp_iterator = ($filmstrip_direction == 'horizontal' ? 1 : 0);
    if (isset($margins_split[$temp_iterator])) {
      $filmstrip_thumb_margin_right = (int) $margins_split[$temp_iterator];
      if (isset($margins_split[$temp_iterator + 2])) {
        $filmstrip_thumb_margin_left = (int) $margins_split[$temp_iterator + 2];
      }
      else {
        $filmstrip_thumb_margin_left = $filmstrip_thumb_margin_right;
      }
    }
    elseif (isset($margins_split[0])) {
      $filmstrip_thumb_margin_right = (int) $margins_split[0];
      $filmstrip_thumb_margin_left = $filmstrip_thumb_margin_right;
    }
    $filmstrip_thumb_margin_hor = $filmstrip_thumb_margin_right + $filmstrip_thumb_margin_left;
    $rgb_lightbox_ctrl_cont_bg_color = WDW_FFWD_Library::spider_hex2rgb($theme_row->lightbox_ctrl_cont_bg_color);
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
    ?>
    <style>
      .spider_popup_wrap * {
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
      }
      .spider_popup_wrap {
        background-color: #<?php echo $theme_row->lightbox_bg_color; ?>;
        /*background-color: #FFFFFF;*/
        display: inline-block;
        left: 50%;
        outline: medium none;
        position: fixed;
        text-align: center;
        top: 50%;
        z-index: 100000;
      }
      .ffwd_popup_image {
        max-width: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>px;
        max-height: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>px;
        vertical-align: middle;
        display: inline-block;
      }
      .ffwd_video {
        width: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>px;
        height: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>px;
        vertical-align: middle;
        text-align: center;
        /*display: table;*/
        display: table-cell;
      }
      .ffwd_video > video {
      <?php if($ffwd_info->content_type == 'timeline') echo 'width: 100%;height: 100%;'; ?>
      }
      .ffwd_ctrl_btn {
        color: #<?php echo $theme_row->lightbox_ctrl_btn_color; ?>;
        font-size: <?php echo $theme_row->lightbox_ctrl_btn_height; ?>px;
        margin: <?php echo $theme_row->lightbox_ctrl_btn_margin_top; ?>px <?php echo $theme_row->lightbox_ctrl_btn_margin_left; ?>px;
        opacity: <?php echo number_format($theme_row->lightbox_ctrl_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_ctrl_btn_transparent; ?>);
      }

      .ffwd_play_pause, .ffwd_comment, .ffwd_facebook, .ffwd_twitter, .ffwd_google, .ffwd_resize-full, .ffwd_fullscreen {
        height: 20px;
        display: inline-block;
        width: 20px;
        height: 20px;
        background-size: 18px;
        transition: 0.2s all linear;
        vertical-align: middle;
        padding: 12px;
        box-shadow: 0px 0px 1px #FFFFFF;
      }
      .ffwd_play {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/play_gray.png' ?>') no-repeat center center;
        background-size: 16px 18px !important;
      }

      .ffwd_play:hover {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/play.png' ?>') no-repeat center center;
        background-size: 16px 18px !important;
      }

      .ffwd_pause {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/pause_gray.png' ?>') no-repeat center center;
        background-size: 16px 18px !important;
      }

      .ffwd_pause:hover {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/pause.png' ?>') no-repeat center center;
        background-size: 16px 18px !important;
      }

      .ffwd_facebook {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/facebook_hover.png' ?>') no-repeat center center;
        background-size: 18px;
      }

      .ffwd_facebook:hover {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/facebook_white.png' ?>') no-repeat center center;
        background-size: 18px;
      }
      .ffwd_comment {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/comment_lightboxgray.png' ?>') no-repeat center center;

      }
      .ffwd_comment:hover {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/comment_lightbox.png' ?>') no-repeat center center;
      }
      .ffwd_twitter {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/twitter_hover.png' ?>') no-repeat center center;

      }
      .ffwd_twitter:hover {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/twitter_white.png' ?>') no-repeat center center;
      }
      .ffwd_google {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/google_hover.png' ?>') no-repeat center center;
        background-size: 18px;

      }
      .ffwd_google:hover {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/google_white.png' ?>') no-repeat center center;
        background-size: 18px;
      }
      .ffwd_resize_in_full {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/resize_in_gray.png' ?>') no-repeat center center;

      }
      .ffwd_resize_in_full:hover {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/resize_in.png' ?>') no-repeat center center;
      }
      .ffwd_resize_out_full {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/resize_out_gray.png' ?>') no-repeat center center;

      }
      .ffwd_resize_out_full:hover {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/resize_out.png' ?>') no-repeat center center;
      }
      .ffwd_fullscreen {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/fullscreen_hover.png' ?>') no-repeat center center;

      }
      .ffwd_fullscreen:hover {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/fullscreen.png' ?>') no-repeat center center;
      }
      .ffwd_toggle_btn {
        color: #fff;
        font-size: <?php echo $theme_row->lightbox_toggle_btn_height; ?>px;
        margin: 0;
        opacity: <?php echo number_format($theme_row->lightbox_ctrl_btn_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_ctrl_btn_transparent; ?>);
        padding: 0;
      }
      .ffwd_btn_container {
        bottom: 0;
        left: 0;
        overflow: hidden;
        position: absolute;
        right: 0;
        top: 0;
      }
      .ffwd_sub_attachmenst_cont {
        display: table;
        height: <?php echo  $image_filmstrip_height ?>px;
        position: absolute;
        width: 100%;
        z-index: 10105;
        top: -<?php echo  $image_filmstrip_height ?>px;
        background-color: rgba(255, 255, 255, 0.35);
      }
      .ffwd_sub_attachmenst_cont_left {
        background-color: #FFFFFF;
        cursor: pointer;
        display: table-cell;
        vertical-align: middle;
        width:20px;
        z-index: 10107;
        position: absolute;
        height: <?php echo  $image_filmstrip_height ?>px;
        left: 0;
        border-left-style: none;
        border-top-style: none;
        background-color:#<?php echo $theme_row->lightbox_filmstrip_rl_bg_color ?>;
      }
      .ffwd_sub_attachmenst_cont_right {
        background-color: #FFFFFF;
        cursor: pointer;
        position: absolute;
        height: <?php echo  $image_filmstrip_height ?>px;
        right: 0;
        width:20px;
        background-color:#<?php echo $theme_row->lightbox_filmstrip_rl_bg_color ?>;

        display: table-cell;
        vertical-align: middle;
        z-index: 10106;
        border-right-style: none;
        border-top-style: none;
      }
      .ffwd_sub_attachmenst_cont_right i {
        line-height: <?php echo  $image_filmstrip_height ?>px !important;
        color: #<?php echo $theme_row->lightbox_filmstrip_rl_btn_color; ?>;
        font-size: <?php echo $theme_row->lightbox_filmstrip_rl_btn_size; ?>px;

      }
      .ffwd_sub_attachmenst_cont_left i {
        line-height: <?php echo  $image_filmstrip_height ?>px !important;
        color: #<?php echo $theme_row->lightbox_filmstrip_rl_btn_color; ?>;
        font-size: <?php echo $theme_row->lightbox_filmstrip_rl_btn_size; ?>px;

      }
      .ffwd_sub_attachmenst_button_container {
        height: 10px;
        width: 100%;
        background-color: rgba(255, 255, 255, 0);
        position: absolute;
        top: 58px;
        left: 0px;
        text-align : center;
      }
      .ffwd_sub_attachmenst_button_container > .ffwd_sub_attachmenst_button {
        height: inherit;
        width: 30px;
        background-color: rgba(255, 255, 255, 1);
        margin: 0 auto;
      }
      .ffwd_sub_attachmenst {
        left: 20px;
        overflow: hidden;
        position: absolute;
        z-index: 10106;
        text-align: left;
        height: 100%;

        width: <?php echo $image_width - 40; ?>px;
        z-index: 10106;
      }
      .ffwd_sub_attachmenst_thumbnails {
        height: <?php echo $image_filmstrip_height  ?>px;
      <?php echo $left_or_top; ?>: 0px;
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        width: <?php echo ($image_filmstrip_width + $filmstrip_thumb_margin_hor) * count($image_rows); ?>px;
      }
      .ffwd_filmstrip_subattach_thumbnail {
        position: relative;
        background: none;
        border: <?php echo $theme_row->lightbox_filmstrip_thumb_border_width; ?>px <?php echo $theme_row->lightbox_filmstrip_thumb_border_style; ?> #<?php echo $theme_row->lightbox_filmstrip_thumb_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_filmstrip_thumb_border_radius; ?>;
        cursor: pointer;
        float: left;
        height: <?php echo $image_filmstrip_height; ?>px;
        margin: <?php echo $theme_row->lightbox_filmstrip_thumb_margin; ?>;
        width: <?php echo $image_filmstrip_width; ?>px;
        overflow: hidden;
      }
      .ffwd_ctrl_btn_container {
        background-color: rgba(<?php echo $rgb_lightbox_ctrl_cont_bg_color['red']; ?>, <?php echo $rgb_lightbox_ctrl_cont_bg_color['green']; ?>, <?php echo $rgb_lightbox_ctrl_cont_bg_color['blue']; ?>, <?php echo number_format($theme_row->lightbox_ctrl_cont_transparent / 100, 2, ".", ""); ?>);
        /*background: none repeat scroll 0 0 #<?php echo $theme_row->lightbox_ctrl_cont_bg_color; ?>;*/
        /*background: url('<?php echo WD_FFWD_URL . '/images/feed/cb_cont.png' ?>') no-repeat 0px center;*/
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
      .ffwd_toggle_container {
        background: none repeat scroll 0 0 #<?php echo $theme_row->lightbox_ctrl_cont_bg_color; ?>;
      <?php
      if ($theme_row->lightbox_ctrl_btn_pos == 'top') {
        ?>
        border-bottom-left-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
        border-bottom-right-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
        top: <?php echo $theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top; ?>px;
      <?php
    }
    else {
      ?>
        border-top-left-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
        border-top-right-radius: <?php echo $theme_row->lightbox_ctrl_cont_border_radius; ?>px;
        bottom: <?php echo $theme_row->lightbox_ctrl_btn_height + 2 * $theme_row->lightbox_ctrl_btn_margin_top; ?>px;
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
      .ffwd_close_btn {
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
        right: 15px;
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

      <?php
    if(/*$option_row->autohide_lightbox_navigation*/false){?>
      #spider_popup_left-ico{
        left: -9999px;
      }
      #spider_popup_right-ico{
        left: -9999px;
      }
      <?php
      }
   else { ?>
      #spider_popup_left-ico{
        left: 20px;
      }
      #spider_popup_right-ico{
        left: auto;
        right: 20px;
      }
      <?php
  }
  ?>

      .ffwd_ctrl_btn:hover,
      .ffwd_toggle_btn:hover,
      .spider_popup_close:hover,
      .spider_popup_close_fullscreen:hover,
      #spider_popup_left-ico:hover,
      #spider_popup_right-ico:hover {
        color: #<?php echo $theme_row->lightbox_close_rl_btn_hover_color; ?>;
        cursor: pointer;
      }
      .ffwd_image_wrap {
        height: inherit;
        position: absolute;
        text-align: center;
        width: inherit;
        overflow: hidden;
      }
      .ffwd_image_wrap * {
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      .ffwd_object_info_wrap {
        bottom: 0;
        left: 0;
        overflow: hidden;
        position: absolute;
        right: 0;
        top: 0;
        z-index: -1;
      }
      .ffwd_object_info_container {
        -moz-box-sizing: border-box;
        background-color: #<?php echo $theme_row->lightbox_obj_comments_bg_color; ?>;
        height: 100%;
        overflow: hidden;
        position: absolute;
      <?php echo $theme_row->lightbox_obj_pos; ?>: -<?php echo $theme_row->lightbox_obj_width; ?>px;
        top: 0;
        width: <?php echo $theme_row->lightbox_obj_width; ?>px;
        z-index: 10103;
      }

      .ffwd_info_header
      {
        background-color: #<?php echo $theme_row->lightbox_obj_info_bg_color; ?>;

      }


      .ffwd_object_info {
        bottom: 0;
        height: 100%;
        left: 0;
        overflow-x: hidden;
        overflow-y: auto;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 10101;
      }
      .ffwd_object_info * {
        font-weight: normal;
        font-family: helvetica, arial, sans-serif;
      }
      .ffwd_comments_close {
        cursor: pointer;
        line-height: 0;
        position: relative;
        font-size: 13px;
        text-align: <?php echo (($theme_row->lightbox_obj_pos == 'left') ? 'right' : 'left'); ?>;
        padding: 5px 10px;
        z-index: 10150;
      }
      .ffwd_comments_close_btn {
        float: <?php echo (($theme_row->lightbox_obj_pos == 'left') ? 'right' : 'left'); ?>;
        padding: 10px;
      }
      .ffwd_comments_close:after {
        content: '';
        display: block;
        clear: both;
      }
      .ffwd_ctrl_btn_container a,
      .ffwd_ctrl_btn_container a:hover {
        text-decoration: none;
        display: inline-block;
      }
      .ffwd_facebook:hover {
        color: #3B5998;
      }
      .ffwd_twitter:hover {
        color: #4099FB;
      }
      .ffwd_google:hover {
        color: #DD4B39;
      }
      .ffwd_pinterest:hover {
        color: #cb2027;
      }
      .ffwd_tumblr:hover {
        color: #2F5070;
      }
      .ffwd_facebook,
      .ffwd_twitter,
      .ffwd_google,
      .ffwd_pinterest,
      .ffwd_tumblr {
        color: #<?php //echo $theme_row->lightbox_comment_share_button_color; ?>;
      }
      .ffwd_image_container {
        display: table;
        position: absolute;
        text-align: center;
      <?php if($enable_image_filmstrip && $content_type!='timeline'){ ?>
      <?php echo $theme_row->lightbox_filmstrip_pos; ?>: <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : $image_filmstrip_width); ?>px;
      <?php } ?>
        vertical-align: middle;
        width: 100%;
      }
      .ffwd_filmstrip_container {
        display: <?php echo ($filmstrip_direction == 'horizontal'? 'table' : 'block'); ?>;
        height: <?php echo ($filmstrip_direction == 'horizontal'? $image_filmstrip_height : $image_height); ?>px;
        position: absolute;
        width: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width : $image_filmstrip_width); ?>px;
        z-index: 10105;
      <?php echo $theme_row->lightbox_filmstrip_pos; ?>: 0;
      }
      .ffwd_filmstrip {
      <?php echo $left_or_top; ?>: 20px;
        overflow: hidden;
        position: absolute;
      <?php echo $width_or_height; ?>: <?php echo ($filmstrip_direction == 'horizontal' ? $image_width - 40 : $image_height - 40); ?>px;
        z-index: 10106;
      }
      .ffwd_filmstrip_thumbnails {
        height: <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : ($image_filmstrip_height + $filmstrip_thumb_margin_hor) * count($image_rows)); ?>px;
      <?php echo $left_or_top; ?>: 0px;
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        width: <?php echo ($filmstrip_direction == 'horizontal' ? ($image_filmstrip_width + $filmstrip_thumb_margin_hor) * count($image_rows) : $image_filmstrip_width); ?>px;
      }
      .ffwd_filmstrip_thumbnail {
        position: relative;
        background: none;
        border: <?php echo $theme_row->lightbox_filmstrip_thumb_border_width; ?>px <?php echo $theme_row->lightbox_filmstrip_thumb_border_style; ?> #<?php echo $theme_row->lightbox_filmstrip_thumb_border_color; ?>;
        border-radius: <?php echo $theme_row->lightbox_filmstrip_thumb_border_radius; ?>;
        cursor: pointer;
        float: left;
        height: <?php echo $image_filmstrip_height; ?>px;
        margin: <?php echo $theme_row->lightbox_filmstrip_thumb_margin; ?>;
        width: <?php echo $image_filmstrip_width; ?>px;
        overflow: hidden;
      }
      .ffwd_thumb_active {
        opacity: 1;
        filter: Alpha(opacity=100);
        border: <?php echo $theme_row->lightbox_filmstrip_thumb_active_border_width; ?>px solid #<?php echo $theme_row->lightbox_filmstrip_thumb_active_border_color; ?>;
      }
      .ffwd_thumb_deactive {
        opacity: <?php echo number_format($theme_row->lightbox_filmstrip_thumb_deactive_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->lightbox_filmstrip_thumb_deactive_transparent; ?>);
      }
      .ffwd_filmstrip_thumbnail_img {
        display: block;
        opacity: 1;
        filter: Alpha(opacity=100);
      }
      .ffwd_filmstrip_left {
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
      .ffwd_filmstrip_right {
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
      .ffwd_filmstrip_left i,
      .ffwd_filmstrip_right i {
        color: #<?php echo $theme_row->lightbox_filmstrip_rl_btn_color; ?>;
        font-size: <?php echo $theme_row->lightbox_filmstrip_rl_btn_size; ?>px;
      }
      .ffwd_none_selectable {
        -webkit-touch-callout: none;
        -webkit-user-select: none;
        -khtml-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }
      .ffwd_slide_container {
        display: table-cell;
        position: absolute;
        vertical-align: middle;
        width: 100%;
        height: 100%;
      }
      .ffwd_slide_bg {
        margin: 0 auto;
        width: inherit;
        height: inherit;
      }
      .ffwd_slider {
        height: inherit;
        width: inherit;
      }
      .ffwd_popup_image_spun {
        height: inherit;
        display: table-cell;
        filter: Alpha(opacity=100);
        opacity: 1;
        position: absolute;
        vertical-align: middle;
        width: inherit;
        z-index: 2;
      }
      .ffwd_popup_image_second_spun {
        width: inherit;
        height: inherit;
        display: table-cell;
        filter: Alpha(opacity=0);
        opacity: 0;
        position: absolute;
        vertical-align: middle;
        z-index: 1;
      }
      .ffwd_grid {
        display: none;
        height: 100%;
        overflow: hidden;
        position: absolute;
        width: 100%;
      }
      .ffwd_gridlet {
        opacity: 1;
        filter: Alpha(opacity=100);
        position: absolute;
      }
      .ffwd_object_info_big_container {
        padding: 0px 0px;
        box-sizing: border-box;
      }
      .ffwd_object_info_main_container {
        text-align: left;
        background-color: #<?php   echo $theme_row->lightbox_obj_info_bg_color; ?>;
        padding:0px 20px;
        margin-bottom: 12px;
      }
      .ffwd_object_from {
        margin: 0px 0px 7px 0px;
      }
      .ffwd_object_from_pic_cont {
        float: left;
        margin: 0px 16px 0px 0px;
      }
      .ffwd_object_from_pic {
        display: inline-block;
      }
      .ffwd_from_name {
        font-size: <?php echo $theme_row->lightbox_obj_page_name_size; ?>px;
        font-weight: <?php echo $theme_row->lightbox_obj_page_name_font_weight; ?>;
        font-family: <?php echo $theme_row->lightbox_obj_font_family; ?>;
        color: #<?php echo $theme_row->lightbox_page_name_color; ?> !important;
        margin: 0px;
        text-decoration: none;
        outline: none;
      }
      .ffwd_from_name:hover {
        text-decoration: underline;
        outline: none;
      }
      .ffwd_place_name {
        font-size: <?php echo $theme_row->lightbox_obj_place_size; ?>px;
        font-weight: <?php echo $theme_row->lightbox_obj_place_font_weight; ?>;
        font-family: <?php echo $theme_row->lightbox_obj_font_family; ?>;
        color: #<?php echo $theme_row->lightbox_obj_place_color; ?> !important;
        margin: 0px;
        text-decoration: none;
        outline: none;
      }
      .ffwd_place_name:hover {
        text-decoration: underline;
        outline: none;
      }
      .ffwd_story {
        color: #<?php echo $theme_row->lightbox_obj_story_color; ?>;
        font-size: <?php   echo $theme_row->lightbox_obj_story_size; ?>px;
        font-weight: <?php   echo $theme_row->lightbox_obj_story_font_weight; ?>;
        font-family: <?php echo $theme_row->lightbox_obj_font_family; ?>;
      }
      .ffwd_object_from_name_time_cont {
        float: left;
        max-width: 76%;
        color: #ADADAD;
        font-size: 13px;
        line-height: 16px;
      }
      .ffwd_popup_from_time_post {
        font-size: 11px;
        font-weight: normal;
        font-family: helvetica, arial, sans-serif;
        padding: 3px 0px 3px 18px;
        margin: 2px 0px 5px 0px;
        min-width: 72px;
        background: url('<?php echo WD_FFWD_URL . '/images/feed/time_'. $theme_row->lightbox_obj_icons_color .'.png' ?>') no-repeat 0px center;
        background-size: 12px;
        color: #<?php echo $theme_row->lightbox_obj_story_color; ?>;
      }

      .ffwd_popup_from_time_event {
        font-size: <?php echo $theme_row->lightbox_evt_date_size; ?>px;
        font-weight: <?php echo $theme_row->lightbox_evt_date_font_weight; ?>;
        font-family: <?php echo $theme_row->lightbox_evt_info_font_family; ?>;
        padding: 3px 0px 3px 18px;
        margin: 2px 0px 5px 0px;
        min-width: 72px;
        background: url('<?php echo WD_FFWD_URL . '/images/feed/time_'. $theme_row->lightbox_obj_icons_color .'.png' ?>') no-repeat 0px center;
        background-size: 12px;
        color: #<?php echo $theme_row->lightbox_evt_date_color; ?>;
      }

      .ffwd_object_name {
        text-align: left;
        text-decoration: none;
        margin-bottom: 5px;
        line-height: 16px;
        color: #<?php echo $theme_row->lightbox_obj_name_color; ?> !important;
        font-size: <?php echo $theme_row->lightbox_obj_name_size; ?>px;
        font-weight: <?php echo $theme_row->lightbox_obj_name_font_weight; ?>;
        font-family: <?php echo $theme_row->lightbox_obj_font_family; ?>;
      }

      .ffwd_object_name:hover {
        color: #<?php echo $theme_row->lightbox_obj_name_color; ?> !important;
        text-decoration: underline;
      }
      .ffwd_object_messages {
        color: #<?php echo $theme_row->lightbox_obj_message_color; ?>;
        font-size: <?php echo $theme_row->lightbox_obj_message_size; ?>px;
        font-weight: <?php echo $theme_row->lightbox_obj_message_font_weight; ?>;
        font-style: normal;
        font-variant: normal;
        font-family: <?php echo $theme_row->lightbox_obj_font_family; ?>;
        line-height: 21px;
        margin-top: 12px;
      }
      .ffwd_comments_likes_container {
        padding: 5px 20px;
        border-bottom-style: solid;
        border-width: 1px;
        border-color: #E8E8E8;
        background-color: #<?php echo $theme_row->lightbox_obj_likes_social_bg_color; ?>;
      }
      .ffwd_comments_likes_container > .ffwd_comments_likes {
        float: left;
      }
      .ffwd_comments_likes_container > .ffwd_view_on {
        margin: 0px 4px 0px 0px;
        float: right;
      }
      .ffwd_comments_likes_container > .ffwd_view_on > .ffwd_view_on_facebook {
        color: #<?php echo $theme_row->lightbox_obj_likes_social_color; ?>;
        text-decoration: none;
        font-family: <?php echo $theme_row->lightbox_obj_font_family; ?>;
        font-weight: <?php echo $theme_row->lightbox_obj_likes_social_font_weight; ?>;
        font-size: <?php echo $theme_row->lightbox_obj_likes_social_size; ?>px;
        line-height: 20px;
        float: left;
      }
      .ffwd_comments_likes_container > .ffwd_view_on > .ffwd_view_on_facebook:hover,  .ffwd_comments_likes_container > .ffwd_view_on > .ffwd_view_on_facebook:focus {
        color: #<?php echo $theme_row->lightbox_obj_likes_social_color; ?>;
        text-decoration: underline;
        outline: none;
      }
      .ffwd_likes {
        background: url('<?php echo WD_FFWD_URL . '/images/feed/like_'. $theme_row->lightbox_obj_icons_color_likes_comments_count .'.png' ?>') no-repeat 3px center;
        background-size: 12px;
        color: #<?php echo $theme_row->lightbox_obj_likes_social_color; ?>;
        font-family: <?php echo $theme_row->lightbox_obj_font_family; ?>;
        font-weight: <?php echo $theme_row->lightbox_obj_likes_social_font_weight; ?>;
        font-size: <?php echo $theme_row->lightbox_obj_likes_social_size; ?>px;
      }
      .ffwd_shares {
        margin: 0px 0px 0px 4px;
        background: url('<?php echo WD_FFWD_URL . '/images/feed/share_'. $theme_row->lightbox_obj_icons_color .'.png' ?>') no-repeat 3px center;
        background-size: 16px;
        color: #<?php echo $theme_row->lightbox_obj_likes_social_color; ?>;
        font-family: <?php echo $theme_row->lightbox_obj_font_family; ?>;
        font-weight: <?php echo $theme_row->lightbox_obj_likes_social_font_weight; ?>;
        font-size: <?php echo $theme_row->lightbox_obj_likes_social_size; ?>px;
      }
      .ffwd_comments_count {
        color: #<?php echo $theme_row->lightbox_obj_likes_social_color; ?>;
        font-family: <?php echo $theme_row->lightbox_obj_font_family; ?>;
        font-weight: <?php echo $theme_row->lightbox_obj_likes_social_font_weight; ?>;
        font-size: <?php echo $theme_row->lightbox_obj_likes_social_size; ?>px;
        background: url('<?php echo WD_FFWD_URL . '/images/feed/comment_'. $theme_row->lightbox_obj_icons_color_likes_comments_count .'.png' ?>') no-repeat 3px center;
        background-size: 16px;
        margin: 0px 0px 0px 4px;
      }

      .ffwd_likes, .ffwd_shares, .ffwd_comments_count{
        float: left;
        padding: 0px 0px 0px 24px;
        min-height: 20px;
        line-height: 20px;
        box-sizing: border-box;
      }
      .ffwd_comments {
        margin: 0px 0px 0px 0px;
      }
      .ffwd_likes_names_count {
        padding: 2px 20px;
        margin: -1px 0px 0px 0px;
        text-align: left;
        border-bottom-style: solid;
        border-width: 1px;
        border-color: #E8E8E8;

      }
      .ffwd_likes_names {
        float: left;
        padding: 2px 0px 2px 24px;
        background: url('<?php echo WD_FFWD_URL . '/images/feed/like_'. $theme_row->lightbox_obj_icons_color .'.png' ?>') no-repeat 3px center;
        background-size: 12px;
        min-height: 16px;
        line-height: 16px;
      }
      .ffwd_likes_name_cont {
        float: left;
      }
      .ffwd_likes_name {
        text-decoration: none;
        outline: none;
        color: #<?php echo $theme_row->lightbox_obj_users_font_color; ?> !important;
        font-family: <?php echo $theme_row->lightbox_obj_font_family; ?>;
        font-weight: <?php echo $theme_row->lightbox_obj_likes_social_font_weight; ?>;
        font-size: <?php echo $theme_row->lightbox_obj_likes_social_size; ?>px;
        float: left;
      }
      .ffwd_likes_name:hover {
        text-decoration: underline;
        outline: none;
        font-weight: <?php echo $theme_row->lightbox_obj_likes_social_font_weight; ?>;
        font-size: <?php echo $theme_row->lightbox_obj_likes_social_size; ?>px;
      }
      .ffwd_likes_count_last_part {
        text-decoration: none;
        outline: none;
        color: #ADADAD;
        font-family: <?php echo $theme_row->lightbox_obj_font_family; ?>;
        font-weight: <?php echo $theme_row->lightbox_obj_likes_social_font_weight; ?>;
        font-size: <?php echo $theme_row->lightbox_obj_likes_social_size; ?>px;
        float: left;
        margin: 0px 0px 0px 5px;
      }
      .ffwd_comments_content {
        padding: 0px 20px;
        margin: 16px 0px 0px 0px;
      }
      .ffwd_single_comment, .ffwd_comment_reply {
        padding: 6px;
        box-sizing: border-box;
        background-color: #<?php echo $theme_row->lightbox_obj_comments_bg_color; ?>;
        border-<?php  echo ($theme_row->lightbox_obj_comment_border_type != 'all') ? $theme_row->lightbox_obj_comment_border_type . '-' : ''; ?>style: <?php  echo $theme_row->lightbox_obj_comment_border_style; ?>;
        border-width: <?php echo $theme_row->lightbox_obj_comment_border_width; ?>px;
        border-color: #<?php echo $theme_row->lightbox_obj_comment_border_color; ?>;
        margin: 0px 0px 3px 0px;
      }
      .ffwd_comment_replies_content {
        display: none;
        margin: 4px 0px 0px 0px;
      }
      .ffwd_comment_replies_label {
        cursor: pointer;
        padding: 0px 0px 0px 18px;
        /*background: url('<?php echo WD_FFWD_URL . '/images/feed/time_'. $theme_row->blog_style_obj_icons_color .'.png' ?>') no-repeat 3px center;*/
        background-size: 10px;
        font-size: 11px;
        font-weight: 400;
        font-variant: initial;
        color: rgb(165, 165, 165);
      }
      .ffwd_comment_author_pic, .ffwd_comment_reply_author_pic {
        float: left;
      }
      .ffwd_comment_content, .ffwd_comment_reply_content {
        float: left;
        margin: 0px 0px 0px 5px !important;
        max-width: 80%;
        text-align: justify;
        line-height: 14px;
      }

      .ffwd_comment_author_name, .ffwd_comment_reply_author_name {
        text-decoration:none;
        outline: none;
        color: #<?php echo $theme_row->lightbox_obj_users_font_color; ?> !important;
        font-family: <?php echo $theme_row->lightbox_obj_comments_font_family; ?>;
        font-weight: bold;
        font-size: <?php echo $theme_row->lightbox_obj_comments_font_size; ?>px;
      }
      .ffwd_view_more_comments, .ffwd_view_more_comments:hover {
        text-decoration:none;
        outline: none;
        color: #<?php echo $theme_row->lightbox_obj_comments_color; ?>; !important;
        font-family: <?php echo $theme_row->lightbox_obj_font_family; ?>;
        font-weight: <?php echo $theme_row->lightbox_obj_likes_social_font_weight; ?>;
        font-size: <?php echo $theme_row->lightbox_obj_likes_social_size; ?>px;
      }
      .ffwd_view_more_comments:hover {
        text-decoration:underline;
      }
      .ffwd_comment_author_name:hover, .ffwd_comment_reply_author_name:hover {
        text-decoration:underline;
        outline: none;
        color: #<?php echo $theme_row->lightbox_obj_users_font_color; ?>;
        font-size: <?php echo $theme_row->lightbox_obj_comments_font_size; ?>px;
        font-weight: <?php echo $theme_row->lightbox_obj_comments_social_font_weight; ?>;
        font-family: <?php echo $theme_row->lightbox_obj_comments_font_family; ?>;
      }
      .ffwd_comment_message, .ffwd_comment_reply_message {
        color: #<?php echo $theme_row->lightbox_obj_comments_color; ?>;
        font-size: <?php echo $theme_row->lightbox_obj_comments_font_size; ?>px;
        font-weight: <?php echo $theme_row->lightbox_obj_comments_social_font_weight; ?>;
        font-family: <?php echo $theme_row->lightbox_obj_comments_font_family; ?>;
        font-style: normal;
        font-variant: normal;
      }
      .ffwd_comment_content > .ffwd_comment_date_likes {
        margin: 10px 0px 0px 0px;
      }
      .ffwd_comment_content > .ffwd_comment_replies {
        margin: 4px 0px 0px 0px;
      }
      .ffwd_comment_date, .ffwd_comment_reply_date {
        padding: 0px 0px 0px 18px;
        background: url('<?php echo WD_FFWD_URL . '/images/feed/time_'. $theme_row->lightbox_obj_icons_color .'.png' ?>') no-repeat 3px center;
        background-size: 10px;
        font-size: 11px;
        font-weight: 400;
        font-variant: initial;
        color: rgb(165, 165, 165);
      }
      .ffwd_comment_likes_count, .ffwd_comment_reply_likes_count {
        padding: 0px 0px 0px 13px;
        margin: 0px 0px 0px 5px;
        background: url('<?php echo WD_FFWD_URL . '/images/feed/like_'. $theme_row->lightbox_obj_icons_color .'.png' ?>') no-repeat 3px center;
        background-size: 10px;
        font-size: 11px;
        font-weight: 400;
        font-variant: initial;
        color: rgb(165, 165, 165);
      }
      .ffwd_object_name_mess_desp_cont  {
        margin: 0px 0px 7px 0px!important;
      }
      .ffwd_object_description {
        margin: 0px !important;
        line-height: 16px;
        color: #<?php echo $theme_row->lightbox_obj_message_color; ?>;
        font-size: <?php   echo $theme_row->lightbox_obj_message_size; ?>px;
        font-weight: <?php   echo $theme_row->lightbox_obj_message_font_weight; ?>;
        font-style: normal;
        font-variant: normal;
        font-family: <?php echo $theme_row->lightbox_obj_font_family; ?>;
      }
      .ffwd_hashtag, .ffwd_message_tag {
        color: #<?php echo $theme_row->lightbox_obj_hashtags_color; ?> !important;
        font-size: <?php echo $theme_row->lightbox_obj_hashtags_size; ?>px;
        font-weight: <?php echo $theme_row->lightbox_obj_hashtags_font_weight; ?>;
        text-decoration:none;
      }
      .ffwd_hashtag:hover, .ffwd_hashtag:active, .ffwd_hashtag:focus, .ffwd_message_tag:focus, .ffwd_message_tag:hover, .ffwd_message_tag:active {
        text-decoration:underline !important;
        outline: none;
      }
      .ffwd_see_more, .ffwd_see_less {
        color: #<?php echo $theme_row->lightbox_obj_likes_social_color; ?> !important;
        font-family: <?php echo $theme_row->lightbox_obj_font_family; ?>;
        font-weight: <?php echo $theme_row->lightbox_obj_likes_social_font_weight; ?>;
        font-size: <?php echo $theme_row->lightbox_obj_likes_social_size; ?>px;
        text-decoration: none;
        outline: none !important;
        border: none !important;
      }
      .ffwd_see_more:hover, .ffwd_see_less:hover, .ffwd_see_more:focus, .ffwd_see_less:focus, {
        color: #<?php echo $theme_row->lightbox_obj_likes_social_color; ?> !important;
        font-family: <?php echo $theme_row->lightbox_obj_font_family; ?>;
        font-weight: <?php echo $theme_row->lightbox_obj_likes_social_font_weight; ?>;
        font-size: <?php echo $theme_row->lightbox_obj_likes_social_size; ?>px;
        text-decoration: underline;
        outline: none !important;
        border: none !important;
      }
      .ffwd_view_more_comments_cont {
        text-align: left;
        padding: 0px 0px 0px 6px;
        margin: 6px 0px 10px 0px;
      }

      .ffwd_place_street {
        color: #<?php echo $theme_row->lightbox_evt_str_color; ?>;
        font-family: <?php echo $theme_row->lightbox_evt_info_font_family; ?>;
        font-weight: <?php echo $theme_row->lightbox_evt_str_font_weight; ?>;
        font-size: <?php echo $theme_row->lightbox_evt_str_size; ?>px;
      }
      .ffwd_place_city_state_country {
        color: #<?php echo $theme_row->lightbox_evt_ctzpcn_color; ?>;
        font-family: <?php echo $theme_row->lightbox_evt_info_font_family; ?>;
        font-weight: <?php echo $theme_row->lightbox_evt_ctzpcn_font_weight; ?>;
        font-size: <?php echo $theme_row->lightbox_evt_ctzpcn_size; ?>px;
      }
      .ffwd_place_map {
        color: #<?php echo $theme_row->lightbox_evt_map_color; ?> !important;
        font-family: <?php echo $theme_row->lightbox_evt_info_font_family; ?>;
        font-weight: <?php echo $theme_row->lightbox_evt_map_font_weight; ?>;
        font-size: <?php echo $theme_row->lightbox_evt_map_size; ?>px;
        text-decoration: none;
      }
      .ffwd_place_map:hover {
        text-decoration: underline;
      }
      .ffwd_from_time_event {
        color: #<?php echo $theme_row->lightbox_evt_date_color; ?>;
        font-family: <?php echo $theme_row->lightbox_evt_info_font_family; ?>;
        font-weight: <?php echo $theme_row->lightbox_evt_date_font_weight; ?>;
        font-size: <?php echo $theme_row->lightbox_evt_date_size; ?>px;
      }

      .ffwd_next_btn,
      .ffwd_prev_btn
      {
        position: relative;
        top: 10px;
        color:#fff;
      }
      .spider_popup_close i
      {
        color:#fff;
      }
    </style>
    <script>
      var data = [],
          ffwd_event_stack = [],
          ffwd_event_stack_for_attachments = [],
          popup_graph_url = '<?php echo $this->model->graph_url; ?>',
          client_side_today_popup = new Date(),
          client_server_date_difference_popup = (Date.parse(client_side_today_popup) / 1000) - <?php echo time(); ?>,
          ffwd_content_type = '<?php echo $ffwd_info->content_type; ?>',
          ffwd_content = '<?php echo $content; ?>',
          ffwd_from_album = <?php echo $from_album; ?>,
          ffwd_date_timezone_offset = <?php echo $this->model->date_offset; ?>,
          owner_info = JSON.parse('<?php echo $this->model->page_user_group; ?>'),
          ffwd_options = JSON.parse('<?php echo stripslashes($this->model->get_option_json_data()); ?>'),
          ffwd_enable_author = '<?php echo $enable_author; ?>';
      ffwd_enable_place_name = '<?php echo $enable_place_name; ?>';

      <?php
      $image_id_exist = FALSE;
      $j = 0;
      $id_object_id_json=array();
      foreach ($image_rows as $key => $image_row) {
      $type = isset($image_row->type) ? $image_row->type : '';
      $message = isset($image_row->message) ? str_replace(array("\r\n", "\n", "\r", "\n\n", '"'), array(esc_html("<br/><br/>"), esc_html("<br/>"), esc_html("<br/>"), esc_html("<br/><br/>"), esc_html('"')), $image_row->message) : '';
      $story = isset($image_row->story) ? str_replace(array('"'), array(esc_html('"')), $image_row->story) : '';
      $from = isset($image_row->from) ? $image_row->from : '';
      $link = isset($image_row->link) ? $image_row->link : '';
      $source = isset($image_row->source) ? $image_row->source : '';
      $status_type = isset($image_row->status_type) ?  $image_row->status_type : '';
      $place = isset($image_row->place) ?  str_replace(array("'"), array(esc_html("'")), $image_row->place) : '';
      $story_tags = isset($image_row->story_tags) ?  str_replace(array("'"), array(esc_html("'")), $image_row->story_tags) : '';
      $message_tags = isset($image_row->message_tags) ?  str_replace(array("'"), array(esc_html("'")), $image_row->message_tags) : '';
      $created_time = isset($image_row->created_time) ?  $image_row->created_time : '';
      $updated_time = isset($image_row->updated_time) ?  $image_row->updated_time : '';
      $description = isset($image_row->description) ?  str_replace(array("\r\n", "\n", '\r', "\n\n", '"'), array(esc_html("<br/>"), esc_html("<br/>"), esc_html("<br/>"), esc_html("<br/><br/>"), esc_html('"')), $image_row->description) : '';
      $main_url = isset($image_row->main_url) && $image_row->main_url!='' ?  $image_row->main_url : plugins_url('../../images/ffwd/no-image.png', __FILE__ );
      $thumb_url = isset($image_row->thumb_url) && $image_row->thumb_url!='' ?  $image_row->thumb_url : plugins_url('../../images/ffwd/no-image.png', __FILE__ );
      $name = isset($image_row->name) ?  str_replace(array("\r\n", "\n", '\r', "\n\n", '"'), array(esc_html("<br/>"), esc_html("<br/>"), esc_html("<br/>"), esc_html("<br/><br/>"), esc_html('"')), $image_row->name) : '';
      $comments = isset($image_row->comments) ? $image_row->comments : '';
      $attachments = isset($image_row->attachments) ? $image_row->attachments : '';
      $who_post = isset($image_row->who_post) ? $image_row->who_post : '';
      $shares = isset($image_row->shares) ? $image_row->shares : '';





      $alt = $name;
      if($type == 'status' || $type == 'link') {
        continue;
      }

      if ($image_row->id == $image_id) {
        $current_image_key = $j;
      }
      if ($image_row->id == $current_image_id) {
        $current_object_id = $image_row->object_id;
        $current_image_alt = $name;
        $current_image_description = $description;
        $current_image_url = $main_url;
        $current_thumb_url = $thumb_url;
        $current_obj_link = $link;
        $image_id_exist = TRUE;
      }
      ?>
      data["<?php echo $j; ?>"] = [];
      data["<?php echo $j; ?>"]["number"] = <?php echo $j + 1; ?>;
      data["<?php echo $j; ?>"]["id"] = "<?php echo $image_row->id; ?>";
      data["<?php echo $j; ?>"]["from"] = "<?php echo $from; ?>";
      data["<?php echo $j; ?>"]["object_id"] = "<?php echo $image_row->object_id; ?>";
      data["<?php echo $j; ?>"]["type"] = "<?php echo $type; ?>";
      data["<?php echo $j; ?>"]["message"] = "<?php echo $message; ?>";
      data["<?php echo $j; ?>"]["story"] = "<?php echo $story; ?>";
      data["<?php echo $j; ?>"]["link"] = "<?php echo $link; ?>";
      data["<?php echo $j; ?>"]["source"] ="<?php echo $source; ?>";
      data["<?php echo $j; ?>"]["status_type"] = "<?php echo $status_type; ?>";
      data["<?php echo $j; ?>"]["place"] = jQuery.parseJSON('<?php echo addslashes($place); ?>');
      data["<?php echo $j; ?>"]["story_tags"] = jQuery.parseJSON('<?php echo addslashes($story_tags); ?>');
      data["<?php echo $j; ?>"]["message_tags"] = jQuery.parseJSON('<?php echo addslashes($message_tags); ?>');
      data["<?php echo $j; ?>"]["created_time"] = "<?php echo $created_time; ?>";
      data["<?php echo $j; ?>"]["updated_time"] = "<?php echo $updated_time; ?>";
      data["<?php echo $j; ?>"]["description"] = "<?php echo $description; ?>";
      data["<?php echo $j; ?>"]["main_url"] = "<?php echo $main_url; ?>";
      data["<?php echo $j; ?>"]["thumb_url"] = "<?php echo $thumb_url; ?>";
      data["<?php echo $j; ?>"]["alt"] = "<?php echo $name; ?>";
      data["<?php echo $j; ?>"]["name"] = "<?php echo $name; ?>";;
      data["<?php echo $j; ?>"]["comments"] = jQuery.parseJSON('<?php echo addslashes($comments); ?>');
      data["<?php echo $j; ?>"]["attachments"] = jQuery.parseJSON('<?php echo addslashes($attachments); ?>');
      data["<?php echo $j; ?>"]["who_post"] = jQuery.parseJSON('<?php echo addslashes($who_post); ?>');
      data["<?php echo $j; ?>"]["shares"] = jQuery.parseJSON('<?php echo addslashes($shares); ?>');
      <?php
      $j++;
      }
      ?>
    </script>
    <?php
    if (!$image_id_exist) {
      echo WDW_FFWD_Library::message(__('The image has been deleted.', 'bwg'), 'error');
      die();
    }
    ?>
    <div class="ffwd_image_wrap">
      <?php
      if ($enable_image_ctrl_btn) {
        ?>
        <div class="ffwd_btn_container">
          <div class="ffwd_ctrl_btn_container">
            <span title="<?php echo __('Play', 'bwg'); ?>" class="ffwd_ctrl_btn ffwd_play_pause ffwd_play"></span>
            <?php
            if ($enable_image_fullscreen) {
              if (!$open_with_fullscreen) {
                ?>
                <span title="<?php echo __('Maximize', 'bwg'); ?>" class="ffwd_ctrl_btn ffwd_resize-full ffwd_resize_out_full" style="background-size: 20px !important;"></span>
                <?php
              }
              ?>
              <span title="<?php echo __('Fullscreen', 'bwg'); ?>" class="ffwd_ctrl_btn ffwd_fullscreen" style="background-size: 20px !important;"></span>
              <?php
            }
            if ($enable_object_info) {
              ?>
              <span title="<?php echo __('Show info and comments', 'bwg'); ?>" class="ffwd_ctrl_btn ffwd_comment" style="background-size: 23px !important;"></span>
              <?php
            }
            if($ffwd_info->content_type == 'specific' && ($content == 'videos' || $content == 'events'))
              $share_url = 'https://www.facebook.com/' . $current_object_id;
            else
              $share_url = $current_obj_link;
            $share_image_url = urlencode($current_image_url);
            if ($enable_image_facebook) {
              ?>
              <a id="ffwd_facebook_a" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Facebook', 'bwg'); ?>">
                <span title="<?php echo __('Share on Facebook', 'bwg'); ?>" class="ffwd_ctrl_btn ffwd_facebook"></span>
              </a>
              <?php
            }
            if ($enable_image_twitter) {
              ?>
              <a id="ffwd_twitter_a" href="https://twitter.com/share?url=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Twitter', 'bwg'); ?>">
                <span title="<?php echo __('Share on Twitter', 'bwg'); ?>" class="ffwd_ctrl_btn ffwd_twitter" style="background-size: 22px !important;"></span>
              </a>
              <?php
            }
            if ($enable_image_google) {
              ?>
              <a id="ffwd_google_a" href="https://plus.google.com/share?url=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Google+', 'bwg'); ?>">
                <span title="<?php echo __('Share on Google+', 'bwg'); ?>" class="ffwd_ctrl_btn ffwd_google"></span>
              </a>
              <?php
            }
            if (/*$enable_image_pinterest && */false) {
              ?>
              <a id="ffwd_pinterest_a" href="http://pinterest.com/pin/create/button/?s=100&url=<?php echo urlencode($share_url); ?>&media=<?php echo $share_image_url; ?>&description=<?php echo $current_image_description; ?>" target="_blank" title="<?php echo __('Share on Pinterest', 'bwg'); ?>">
                <span title="<?php echo __('Share on Pinterest', 'bwg'); ?>" class="ffwd_ctrl_btn ffwd_pinterest fa fa-pinterest"></span>
              </a>
              <?php
            }
            if (/*$enable_image_tumblr && */false) {
              ?>
              <a id="ffwd_tumblr_a" href="https://www.tumblr.com/share/photo?source=<?php echo $share_image_url; ?>&caption=<?php echo urlencode($current_image_alt); ?>&clickthru=<?php echo urlencode($share_url); ?>" target="_blank" title="<?php echo __('Share on Tumblr', 'bwg'); ?>">
                <span title="<?php echo __('Share on Tumblr', 'bwg'); ?>" class="ffwd_ctrl_btn ffwd_tumblr fa fa-tumblr"></span>
              </a>
              <?php
            }
            if (/*$option_row->popup_enable_fullsize_image*/false) {
              ?>
              <a id="ffwd_fullsize_image" href="<?php echo $current_image_url; ?>" target="_blank">
                <span title="<?php echo __('Open image in original size.', 'bwg'); ?>" class="ffwd_ctrl_btn ffwd_fullsize"></span>
              </a>
              <?php
            }
            if (/*$option_row->popup_enable_download*/false) {
              $current_image_arr = explode('/', $current_image_url);
              ?>
              <a id="ffwd_download" href="<?php echo $current_image_url; ?>" target="_blank" download="<?php echo end($current_image_arr); ?>">
                <span title="<?php echo __('Download original image', 'bwg'); ?>" class="ffwd_ctrl_btn fa fa-download"></span>
              </a>
              <?php
            }
            ?>
          </div>
          <div class="ffwd_toggle_container">
            <i class="ffwd_toggle_btn fa <?php echo (($theme_row->lightbox_ctrl_btn_pos == 'top') ? 'fa-angle-up' : 'fa-angle-down'); ?>"></i>
          </div>
        </div>
        <?php
        if ($enable_image_filmstrip && $content_type== "timeline") {
          ?>
          <div class="ffwd_sub_attachmenst_cont">
            <div class="ffwd_sub_attachmenst_cont_left"><i class="fa <?php echo ($filmstrip_direction == 'horizontal'? 'fa-angle-left' : 'fa-angle-up'); ?> " style="line-height: <?php echo  $image_filmstrip_height ?>px !important;"></i></div>
            <div class="ffwd_sub_attachmenst">
              <div class="ffwd_sub_attachmenst_thumbnails">
              </div>
            </div>
            <div class="ffwd_sub_attachmenst_cont_right"><i class="fa <?php echo ($filmstrip_direction == 'horizontal'? 'fa-angle-right' : 'fa-angle-down'); ?>" style="line-height: <?php echo  $image_filmstrip_height ?>px !important;"></i></div>

          </div>
        <?php } ?>
        <?php
      }
      $current_pos = 0;
      if ($enable_image_filmstrip && $content_type != "timeline") {
        ?>
        <div class="ffwd_filmstrip_container">
          <div class="ffwd_filmstrip_left"><i class="fa <?php echo ($filmstrip_direction == 'horizontal'? 'fa-angle-left' : 'fa-angle-up'); ?> "></i></div>
          <div class="ffwd_filmstrip">
            <div class="ffwd_filmstrip_thumbnails">
              <?php
              foreach ($image_rows as $key => $image_row) {
                if ($image_row->id == $current_image_id) {
                  $current_pos = $key * (($filmstrip_direction == 'horizontal' ? $image_filmstrip_width : $image_filmstrip_height) + $filmstrip_thumb_margin_hor);
                  $current_key = $key;
                }
                if((isset($image_row->width) && $image_row->width != '') && (isset($image_row->height) && $image_row->height != '')){
                  $image_thumb_width = intval($image_row->width);
                  $image_thumb_height = intval($image_row->height);

                  $scale = max($image_filmstrip_width / $image_thumb_width, $image_filmstrip_height / $image_thumb_height);
                  $image_thumb_width *= $scale;
                  $image_thumb_height *= $scale;
                }
                else {
                  $image_thumb_width = $image_filmstrip_width;
                  $image_thumb_height = $image_filmstrip_height;
                }
                $thumb_left = ($image_filmstrip_width - $image_thumb_width) / 2;
                $thumb_top = ($image_filmstrip_height - $image_thumb_height) / 2;
                ?>
                <div id="ffwd_filmstrip_thumbnail_<?php echo $key; ?>" class="ffwd_filmstrip_thumbnail <?php echo (($image_row->id == $current_image_id) ? 'ffwd_thumb_active' : 'ffwd_thumb_deactive'); ?>">
                  <img style="width:<?php echo $image_thumb_width; ?>px; height:<?php echo $image_thumb_height; ?>px; margin-left: <?php echo $thumb_left; ?>px; margin-top: <?php echo $thumb_top; ?>px;" class="ffwd_filmstrip_thumbnail_img" src="<?php echo $image_row->thumb_url; ?>" onclick="ffwd_change_image(parseInt(jQuery('#ffwd_current_image_key').val()), '<?php echo $key; ?>', data)" ontouchend="ffwd_change_image(parseInt(jQuery('#ffwd_current_image_key').val()), '<?php echo $key; ?>', data)" image_id="<?php echo $image_row->id; ?>" image_key="<?php echo $key; ?>" alt="" />
                </div>
                <?php
              }
              ?>
            </div>
          </div>
          <div class="ffwd_filmstrip_right"><i class="fa <?php echo ($filmstrip_direction == 'horizontal'? 'fa-angle-right' : 'fa-angle-down'); ?>"></i></div>
        </div>
        <?php
      }
      ?>
      <div id="ffwd_image_container" class="ffwd_image_container">
        <div class="ffwd_slide_container">
          <div class="ffwd_slide_bg">
            <div class="ffwd_slider">
              <?php
              $current_key = -6;
              $i = 0;
              foreach ($image_rows as $key => $image_row) {
                $object_id = isset($image_row->object_id) ? $image_row->object_id : '';
                $type = isset($image_row->type) ? $image_row->type : '';
                $message = isset($image_row->message) ? $image_row->message : '';
                $story = isset($image_row->story) ? $image_row->story : '';
                $link = isset($image_row->link) ? $image_row->link : '';
                $view_on_facebook = ($link != "" && $type != "link" && $type != "video") ? $link : "https://www.facebook.com/".$object_id;
                $source = isset($image_row->source) ? $image_row->source : '';
                $status_type = isset($image_row->status_type) ?  $image_row->status_type : '';
                $place = isset($image_row->place) ?  $image_row->place : '';
                $story_tags = isset($image_row->story_tags) ?  $image_row->story_tags : '';
                $message_tags = isset($image_row->message_tags) ?  $image_row->message_tags : '';
                $created_time = isset($image_row->created_time) ?  $image_row->created_time : '';
                $updated_time = isset($image_row->updated_time) ?  $image_row->updated_time : '';
                $description = isset($image_row->description) ? $image_row->description : '';

                $main_url = isset($image_row->main_url) && $image_row->main_url!='' ?  $image_row->main_url : plugins_url('../../images/ffwd/no-image.png', __FILE__ );

                $thumb_url = isset($image_row->thumb_url) && $image_row->thumb_url!='' ?  $image_row->thumb_url : plugins_url('../../images/ffwd/no-image.png', __FILE__ );
                $name = isset($image_row->name) ?  $image_row->name : '';
                $alt = $name;
                if($type == 'status' || $type == 'link') {
                  continue;
                }
                if ($image_row->id == $current_image_id) {
                  $current_key = $i;
                  ?>
                  <span class="ffwd_popup_image_spun" id="ffwd_popup_image" image_id="<?php echo $image_row->id; ?>">
                    <span class="ffwd_popup_image_spun1" style="display: table; width: inherit; height: inherit;">
                      <span class="ffwd_popup_image_spun2" style="display: table-cell; vertical-align: middle; text-align: center;">
                        <?php
                        if (strpos($type, "photo") !== false || strpos($type, "events") !== false) {
                          if ($ffwd_info->content_type == "specific") {
                            ?>
                            <img class="ffwd_popup_image" src="<?php echo $main_url; ?>" alt="<?php echo $name; ?>" />
                            <?php
                          }
                        }
                        elseif(strpos($type, "video") !== false) {  /*Videos*/
                          ?>
                          <span class="ffwd_video">
                              <?php
                              if($ffwd_info->content_type != 'timeline'):
                                if($status_type == 'shared_story') { ?>
                                  <iframe class="ffwd_popup_iframe" src="<?php echo $source. '&enablejsapi=1&wmode=transparent'; ?>" style="width: inherit; height: inherit; margin:0;" frameborder="0" scrolling="no" allowtransparency="false" allowfullscreen></iframe>
                                <?php } else { ?>
                                  <video class="ffwd_popup_image" src="<?php echo $source; ?>" controls autoplay="autoplay"></video>
                                <?php } endif; ?>
														</span>
                          <?php
                        }
                        ?>
                      </span>
                    </span>
                  </span>
                  <span class="ffwd_popup_image_second_spun">
                  </span>
                  <input type="hidden" id="ffwd_current_image_key" value="<?php echo $i; ?>" />
                  <?php
                  break;
                }
                $i++;
              }
              ?>
            </div>
          </div>
        </div>
        <a id="spider_popup_left"><span id="spider_popup_left-ico"><i class="ffwd_prev_btn fa fa-chevron-left"></i></span></a>
        <a id="spider_popup_right"><span id="spider_popup_right-ico"><i class="ffwd_next_btn fa fa-chevron-right"></i></span></a>
      </div>
    </div>
    <?php
    if ($enable_object_info) {
      ?>
      <div class="ffwd_object_info_wrap">
        <div class="ffwd_object_info_container ffwd_close">
          <div id="ajax_loading" style="position:absolute;">
            <div id="opacity_div" style="display:none; background-color:rgba(255, 255, 255, 0.2); position:absolute; z-index:10150;"></div>
            <span id="loading_div" style="display:none; text-align:center; position:relative; vertical-align:middle; z-index:10170">
							<img src="<?php echo WD_FFWD_URL . '/images/ajax_loader.png'; ?>" class="spider_ajax_loading" style="width:50px;">
						</span>
          </div>
          <div class="ffwd_object_info">
            <div id="ffwd_object_info">


              <div class="ffwd_object_info_big_container">
                <div class="ffwd_info_header">
                  <div title="<?php echo __('Hide Comments', 'bwg'); ?>" class="ffwd_comments_close">
                    <div class="ffwd_comments_close_btn"><i class="fa fa-arrow-left"></i></div>
                  </div>
                  <div class="ffwd_object_info_main_container">
                    <div class="ffwd_object_from" >
                      <?php
                      if($theme_row->lightbox_obj_date_pos == "before")
                        $this->model->ffwd_time($created_time, $updated_time, $type);
                      ?>
                      <div class="ffwd_object_from_pic_cont">
                        <a class="ffwd_object_from_pic" href="" target="_blank">
                        </a>
                      </div>
                      <div class="ffwd_object_from_name_time_cont" style="">
											<span class="ffwd_story" >
											</span>
                        <?php
                        if($theme_row->lightbox_obj_date_pos == "after")
                          $this->model->ffwd_time($created_time, $updated_time, $type);
                        ?>
                      </div>
                      <div style="clear:both"></div>
                    </div>
                    <div class="ffwd_object_name_mess_desp_cont" >
                      <?php if($enable_name): ?>
                        <a class="ffwd_object_name" href="<?php echo nl2br($link); ?>" id="ffwd_object_name" target="_blank">
                          <?php echo nl2br($name); ?>
                        </a>
                      <?php endif;
                      if($enable_message_desc):
                        ?>
                        <p class="ffwd_object_messages">
                          <?php
                          $message = $this->model->see_less_more($message, 'message', $type);
                          $message = $this->model->fill_hashtags($message);
                          $message = $this->model->fill_tags($message, $message_tags);
                          echo nl2br($message);
                          ?>
                        </p>
                        <p class="ffwd_object_description">
                          <?php
                          $description = $this->model->see_less_more($description, 'description', $type);
                          $description = $this->model->fill_hashtags($description);
                          echo nl2br($description);
                          ?>
                        </p>
                        <?php
                      endif;
                      if($theme_row->lightbox_obj_date_pos == "bottom")
                        $this->model->ffwd_time($created_time, $updated_time, $type);
                      ?>
                    </div>
                  </div>
                  <div style="clear:both">
                  </div>
                  <?php if($enable_likes || $enable_shares || $enable_comments): ?>
                  <div class="ffwd_comments_likes_container">
                    <div class="ffwd_comments_likes">
                      <?php
                      if(($content_type == 'timeline' || ($content_type == 'specific' && $content != 'events'))&& $enable_likes):
                        ?>
                        <div id="ffwd_likes" class="ffwd_likes">
                        </div>
                        <?php
                      endif;
                      if($content_type == 'timeline' && $enable_shares):
                        ?>
                        <div id="ffwd_shares" class="ffwd_shares">
                        </div>
                        <?php
                      endif;
                      if($enable_comments):
                        ?>
                        <div id="ffwd_comments_count" class="ffwd_comments_count">
                        </div>
                        <?php
                      endif; ?>
                      <div style="clear:both"></div>
                    </div>
                    <?php if($view_on_fb): ?>
                      <div class="ffwd_view_on" >
                        <a class="ffwd_view_on_facebook" id="ffwd_view_on_facebook" href="<?php echo $view_on_facebook; ?>" target="_blank" title="<?php echo __('View on facebook', 'bwg'); ?>"><?php echo __('View on facebook', 'bwg'); ?></a>
                      </div>
                    <?php endif; ?>
                    <div style="clear:both"></div>
                  </div>

                </div>
                <?php
                endif;
                if($enable_comments || $enable_likes):
                  ?>
                  <div class="ffwd_comments">
                    <?php
                    if($content != 'events' && $enable_likes):
                      ?>
                      <div id="ffwd_likes_names_count" class="ffwd_likes_names_count">
                      </div>
                      <?php
                    endif;
                    if($enable_comments):
                      ?>
                      <div id="ffwd_comments_content" class="ffwd_comments_content">
                      </div>
                      <?php
                    endif;
                    ?>
                  </div>
                  <?php
                endif;
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php
    }
    ?>
    <a class="spider_popup_close" onclick="ffwd_destroypopup(1000); return false;" ontouchend="ffwd_destroypopup(1000); return false;"><span><i class="ffwd_close_btn fa fa-times"></i></span></a>
    <script>
      var ffwd_trans_in_progress = false;
      ffwd_transition_duration = <?php echo (($slideshow_interval < 4) && ($slideshow_interval != 0)) ? ($slideshow_interval * 1000) / 4 : 800; ?>,
          ffwd_current_key = '<?php echo $current_key; ?>',
          ffwd_current_filmstrip_pos = <?php echo $current_pos; ?>;
      var ffwd_playInterval;
      if ((jQuery("#spider_popup_wrap").width() >= jQuery(window).width()) || (jQuery("#spider_popup_wrap").height() >= jQuery(window).height())) {
        jQuery(".spider_popup_close").attr("class", "ffwd_ctrl_btn spider_popup_close_fullscreen");
      }
      /* Stop autoplay.*/
      window.clearInterval(ffwd_playInterval);
      function ffwd_set_filmstrip_pos(filmStripWidth) {
        var selectedImagePos = -ffwd_current_filmstrip_pos - (jQuery(".ffwd_filmstrip_thumbnail").<?php echo $outerWidth_or_outerHeight; ?>(true)) / 2;
        var imagesContainerLeft = Math.min(0, Math.max(filmStripWidth - jQuery(".ffwd_filmstrip_thumbnails").<?php echo $width_or_height; ?>(), selectedImagePos + filmStripWidth / 2));
        jQuery(".ffwd_filmstrip_thumbnails").animate({
        <?php echo $left_or_top; ?>: imagesContainerLeft
      }, {
          duration: 500,
              complete: function () { ffwd_filmstrip_arrows(); }
        });
      }
      function ffwd_move_filmstrip() {
        var image_left = jQuery(".ffwd_thumb_active").position().<?php echo $left_or_top; ?>,
            image_right = jQuery(".ffwd_thumb_active").position().<?php echo $left_or_top; ?> + jQuery(".ffwd_thumb_active").<?php echo $outerWidth_or_outerHeight; ?>(true),
            ffwd_filmstrip_width = jQuery(".ffwd_filmstrip").<?php echo $outerWidth_or_outerHeight; ?>(true),
            ffwd_filmstrip_thumbnails_width = jQuery(".ffwd_filmstrip_thumbnails").<?php echo $outerWidth_or_outerHeight; ?>(true),
            long_filmstrip_cont_left = jQuery(".ffwd_filmstrip_thumbnails").position().<?php echo $left_or_top; ?>,
            long_filmstrip_cont_right = Math.abs(jQuery(".ffwd_filmstrip_thumbnails").position().<?php echo $left_or_top; ?>) + ffwd_filmstrip_width;
        if (ffwd_filmstrip_width > ffwd_filmstrip_thumbnails_width) return;
        if (image_left < Math.abs(long_filmstrip_cont_left)) {
          jQuery(".ffwd_filmstrip_thumbnails").animate({
          <?php echo $left_or_top; ?>: -image_left
        }, {
            duration: 500,
                complete: function () { ffwd_filmstrip_arrows(); }
          });
        }
        else if (image_right > long_filmstrip_cont_right) {
          jQuery(".ffwd_filmstrip_thumbnails").animate({
          <?php echo $left_or_top; ?>: -(image_right - ffwd_filmstrip_width)
        }, {
            duration: 500,
                complete: function () { ffwd_filmstrip_arrows(); }
          });
        }
      }
      /* Show/hide filmstrip arrows.*/
      function ffwd_filmstrip_arrows() {
        if (jQuery(".ffwd_filmstrip_thumbnails").<?php echo $width_or_height; ?>() < jQuery(".ffwd_filmstrip").<?php echo $width_or_height; ?>()) {
          jQuery(".ffwd_filmstrip_left").hide();
          jQuery(".ffwd_filmstrip_right").hide();
        }
        else {
          jQuery(".ffwd_filmstrip_left").show();
          jQuery(".ffwd_filmstrip_right").show();
        }
      }
      /*Change subattachment*/
      function ffwd_change_subattachment(object) {

        if (ffwd_trans_in_progress) {

          ffwd_event_stack_for_attachments.push(object);

          return;
        }

        var object_id = object.attr('object_id'),
            src = object.find('img').attr('src'),
            obj_class_name = object.attr('class'),
            current_image_class = jQuery(".ffwd_popup_image_spun").css("zIndex") == 2 ? ".ffwd_popup_image_spun" : ".ffwd_popup_image_second_spun",
            next_image_class = current_image_class == ".ffwd_popup_image_second_spun" ? ".ffwd_popup_image_spun" : ".ffwd_popup_image_second_spun",
            cur_height = jQuery(current_image_class).height(),
            cur_width = jQuery(current_image_class).width(),
            innhtml = '<span class="ffwd_popup_image_spun1" style="display: table; width: inherit; height: inherit;"><span class="ffwd_popup_image_spun2" style="display: table-cell; vertical-align: middle; text-align: center;">';
        innhtml += '<img style="max-height: ' + cur_height + 'px; max-width: ' + cur_width + 'px;" class="ffwd_popup_image" src="'+ jQuery('<div />').html(src).text() + '" alt="" />';
        innhtml += '</span></span>';
        jQuery(next_image_class).html(innhtml);

        jQuery("."+obj_class_name).css("opacity", 0.6);
        object.css("opacity", 1);

        var direction = 'right',
            cur_img = jQuery(next_image_class).find('img');
        cur_img.one('load', function() {
          ffwd_afterload_attachments();
        }).each(function() {
          if(this.complete) jQuery(this).load();
        });
        function ffwd_afterload_attachments() {
          ffwd_<?php echo $image_effect; ?>(current_image_class, next_image_class, direction);
          /* Change image social networks urls.*/
          var view_on_facebook = 'https://www.facebook.com/' + object_id,
              ffwd_share_url = encodeURIComponent(view_on_facebook);
          jQuery("#ffwd_facebook_a").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + ffwd_share_url);
          jQuery("#ffwd_twitter_a").attr("href", "https://twitter.com/share?url=" + ffwd_share_url);
          jQuery("#ffwd_google_a").attr("href", "https://plus.google.com/share?url=" + ffwd_share_url);
          /* Change view on facebook link */
          jQuery("#ffwd_view_on_facebook").attr("href", view_on_facebook);
          /* Change likes comments and all info */

          ffwd_fill_likes_comments(object_id);
        }
      }

      var subattachment_key=0;
      function ffwd_change_image(current_key, key, data, from_effect) {




        if (typeof data[key] != 'undefined' && typeof data[current_key] != 'undefined') {
          if (jQuery('.ffwd_play_pause').hasClass('ffwd_pause')) {
            ffwd_play();
          }
          if (!from_effect) {
            /* Change image key.*/
            jQuery("#ffwd_current_image_key").val(key);
          }
          if (ffwd_trans_in_progress) {
            /*console.log('transInprogress');*/
            ffwd_event_stack.push(current_key + '-' + key);
            return;
          }
          var direction = 'right';
          if (ffwd_current_key > key) {
            var direction = 'left';
          }
          else if (ffwd_current_key == key) {
            return;
          }
          <?php   if ($enable_image_filmstrip && $content_type== "timeline"  ) {?>
//////////////////////////CHANGE subattachment
          if(jQuery('.ffwd_sub_attachmenst_thumbnails').html()!='')
          {

            var subattachment_last_key = jQuery('.ffwd_filmstrip_subattach_thumbnail').length
            if(direction=='right')
            {
              subattachment_key+=1;
              ffwd_change_subattachment(jQuery('.ffwd_sub_attachmenst_thumbnails div[object_index='+subattachment_key+']'))

              if(subattachment_last_key!=subattachment_key)
              {
                jQuery("#ffwd_current_image_key").val(key-1);
                key-=1;
                return false;
              }

            }

            if(direction=='left')
            {

              subattachment_key-=1;
              ffwd_change_subattachment(jQuery('.ffwd_sub_attachmenst_thumbnails div[object_index='+subattachment_key+']'))

              if(subattachment_key>=0)
              {
                jQuery("#ffwd_current_image_key").val(key+1);
                key+=1;

                return false;
              }

            }


          }

          subattachment_key=0;

///////////////////////////////END CHANGE subattachment
          <?php }?>

          jQuery("#spider_popup_left").hover().css({"display": "inline"});
          jQuery("#spider_popup_right").hover().css({"display": "inline"});
          /* Set filmstrip initial position.*/
          /* Set active thumbnail position.*/
          ffwd_current_filmstrip_pos = key * (jQuery(".ffwd_filmstrip_thumbnail").<?php echo $width_or_height; ?>() + 2 + 2 * <?php echo $theme_row->lightbox_filmstrip_thumb_border_width; ?>);
          ffwd_current_key = key;
          jQuery("#ffwd_popup_image").attr('image_id', data[key]["id"]);
          var current_image_class = jQuery(".ffwd_popup_image_spun").css("zIndex") == 2 ? ".ffwd_popup_image_spun" : ".ffwd_popup_image_second_spun";
          var next_image_class = current_image_class == ".ffwd_popup_image_second_spun" ? ".ffwd_popup_image_spun" : ".ffwd_popup_image_second_spun";
          if(ffwd_content_type != 'timeline' && ffwd_from_album == '0' && data[key]["type"].indexOf('video') === -1) {
            /*content type is specific*/
            var is_video = data[key]['type'] == 'videos' ? true : false,
                cur_height = jQuery(current_image_class).height(),
                cur_width = jQuery(current_image_class).width(),
                innhtml = '<span class="ffwd_popup_image_spun1" style="display: table; width: inherit; height: inherit;"><span class="ffwd_popup_image_spun2" style="display: table-cell; vertical-align: middle; text-align: center;">';
            if (!is_video) {
              innhtml += '<img style="max-height: ' + cur_height + 'px; max-width: ' + cur_width + 'px;" class="ffwd_popup_image" src="'+ jQuery('<div />').html(data[key]["main_url"]).text() + '" alt="' + data[key]["alt"] + '" />';
            }
            else { /*videos*/
              innhtml += '<span style="height: ' + cur_height + 'px; width: ' + cur_width + 'px;" class="ffwd_video">';
              if(data[key]['status_type'] == 'shared_story') {
                innhtml += '<iframe src="'+data[key]['source']+'&enablejsapi=1&wmode=transparent"' +
                    'style="'+
                    'max-width:'+'100%'+" !important; "+
                    'max-height:'+'100%'+" !important; "+
                    'width:'+'100%; '+
                    'height:'+ '100%; ' +
                    'margin:0; '+
                    'display:table-cell; vertical-align:middle;"'+
                    'frameborder="0" scrolling="no" allowtransparency="false" allowfullscreen'+
                    ' class="ffwd_popup_iframe"></iframe>';
              }else {
                innhtml += '<video class="ffwd_popup_image" src="'+data[key]['source']+'" controls autoplay="autoplay"></video>';
              }
              innhtml += "</span>";
            }
            innhtml += '</span></span>';
            jQuery(next_image_class).html(innhtml);

            if (!is_video) {
              var cur_img = jQuery(next_image_class).find('img');
              cur_img.one('load', function() {
                ffwd_afterload(current_image_class, next_image_class, direction, key);
              }).each(function() {
                if(this.complete) jQuery(this).load();
              });
            }
            else {
              var cur_video = document.querySelector(next_image_class + ' video');
              if(typeof cur_video != 'undefined' && cur_video != null)
                cur_video.onloadeddata = function() {
                  ffwd_afterload(current_image_class, next_image_class, direction, key);
                };
              else
                ffwd_afterload(current_image_class, next_image_class, direction, key);
            }
          }
          else {
            /*content type is timeline or gallery from album*/
            ffwd_fill_src('<?php echo $ffwd_info->from; ?>', false, current_image_class, next_image_class, key, direction, ffwd_from_album)
          }
        }
      }

      function ffwd_afterload(current_image_class, next_image_class, direction, key, from_album, link) {
        from_album = (typeof(from_album) === 'undefined') ? false : from_album;
        ffwd_<?php echo $image_effect; ?>(current_image_class, next_image_class, direction);
        /* Pause videos facebook video.*/
        var curent_video = document.querySelector(current_image_class + ' video');
        if(curent_video != null && typeof curent_video != 'undefined') {
          curent_video.pause();
        }
        /* Pause youtube or other videos.*/
        jQuery(current_image_class + " .ffwd_popup_iframe").each(function () {
          jQuery(this)[0].contentWindow.postMessage('{"event":"command","func":"pauseVideo","args":""}', '*');
          jQuery(this)[0].contentWindow.postMessage('{ "method": "pause" }', "*");
          jQuery(this)[0].contentWindow.postMessage('pause', '*');
        });
        /* Change image social networks urls.*/
        if(ffwd_content_type == 'specific' && (ffwd_content == 'videos' || ffwd_content == 'events')){
          var ffwd_share_url = encodeURIComponent('https://www.facebook.com/' + data[key]['object_id']);
        }
        else
          var ffwd_share_url = encodeURIComponent((typeof link != 'undefined') ? link : data[key]['link']);

        jQuery("#ffwd_facebook_a").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + ffwd_share_url);
        jQuery("#ffwd_twitter_a").attr("href", "https://twitter.com/share?url=" + ffwd_share_url);
        jQuery("#ffwd_google_a").attr("href", "https://plus.google.com/share?url=" + ffwd_share_url);

        /* Change view on facebook link */
        var view_on_facebook = (typeof data[key]['link'] != 'undefined' && data[key]['link'] != "" && data[key]['type'] != "video") ? data[key]['link'] : 'https://www.facebook.com/'+data[key]['object_id'];
        jQuery("#ffwd_view_on_facebook").attr("href", view_on_facebook);
        if(!from_album) {
          /*change object info*/
          ffwd_change_info(key);
          /*change likes comments and all info */
          ffwd_fill_likes_comments(key);
        }
        <?php
        if ($enable_image_filmstrip  && $content_type!= "timeline" ) {
        ?>
        ffwd_move_filmstrip();
        <?php
        }
        ?>
      }
      jQuery(document).on('keydown', function (e) {
        if (jQuery("#ffwd_name").is(":focus") || jQuery("#ffwd_email").is(":focus") || jQuery("#ffwd_comment").is(":focus") || jQuery("#ffwd_captcha_input").is(":focus")) {
          return;
        }
        if (e.keyCode === 39) { /* Right arrow.*/
          ffwd_change_image(parseInt(jQuery('#ffwd_current_image_key').val()), parseInt(jQuery('#ffwd_current_image_key').val()) + 1, data)
        }
        else if (e.keyCode === 37) { /* Left arrow.*/
          ffwd_change_image(parseInt(jQuery('#ffwd_current_image_key').val()), parseInt(jQuery('#ffwd_current_image_key').val()) - 1, data)
        }
        else if (e.keyCode === 27) { /* Esc.*/
          ffwd_destroypopup(1000);
        }
        else if (e.keyCode === 32) { /* Space.*/
          jQuery(".ffwd_play_pause").trigger('click');
        }
        if (e.preventDefault) {
          e.preventDefault();
        }
        else {
          e.returnValue = false;
        }
      });
      function ffwd_popup_resize() {
        if (typeof jQuery().fullscreen !== 'undefined' && jQuery.isFunction(jQuery().fullscreen) && !jQuery.fullscreen.isFullScreen()) {
          jQuery(".ffwd_resize-full").show();
          jQuery(".ffwd_resize-full").attr("class", "ffwd_ctrl_btn ffwd_resize-full ffwd_resize_out_full");
          jQuery(".ffwd_resize-full").attr("title", "<?php echo __('Maximize', 'bwg'); ?>");
          jQuery(".ffwd_fullscreen").attr("class", "ffwd_ctrl_btn ffwd_fullscreen ");
          jQuery(".ffwd_fullscreen").attr("title", "<?php echo __('Fullscreen', 'bwg'); ?>");
        }

        var comment_container_width = 0;
        if (jQuery(".ffwd_object_info_container").hasClass("ffwd_open")) {
          comment_container_width = <?php echo $theme_row->lightbox_obj_width; ?>;
        }
        if (comment_container_width > jQuery(window).width()) {
          comment_container_width = jQuery(window).width();
          jQuery(".ffwd_object_info_container").css({
            width: comment_container_width
          });
          jQuery(".spider_popup_close_fullscreen").hide();
        }
        else {
          jQuery(".spider_popup_close_fullscreen").show();
        }
        if (jQuery(window).height() > <?php echo $image_height; ?> && <?php echo $open_with_fullscreen; ?> != 1 ) {
          jQuery("#spider_popup_wrap").css({
            height: <?php echo $image_height; ?>,
            top: '50%',
            marginTop: -<?php echo $image_height / 2; ?>,
            zIndex: 100000
          });
          jQuery(".ffwd_image_container").css({height: (<?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>)});
          jQuery(".ffwd_popup_image").css({
            maxHeight: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
          });

          jQuery(".ffwd_video").css({
            height: <?php echo $image_height - ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
          });
          <?php if ($filmstrip_direction == 'vertical') { ?>
          jQuery(".ffwd_filmstrip_container").css({height: <?php echo $image_height; ?>});
          jQuery(".ffwd_filmstrip").css({height: (<?php echo $image_height; ?> - 40)});
          <?php } ?>
          ffwd_popup_current_height = <?php echo $image_height; ?>;
        }
        else {
          jQuery("#spider_popup_wrap").css({
            height: jQuery(window).height(),
            top: 0,
            marginTop: 0,
            zIndex: 100000
          });
          jQuery(".ffwd_image_container").css({height: (jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>)});
          jQuery(".ffwd_popup_image").css({
            maxHeight: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
          });

          jQuery(".ffwd_video").css({
            height: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
          });
          /*ffwd_resize_instagram_post();*/
          <?php if ($filmstrip_direction == 'vertical') { ?>
          jQuery(".ffwd_filmstrip_container").css({height: (jQuery(window).height())});
          jQuery(".ffwd_filmstrip").css({height: (jQuery(window).height() - 40)});
          <?php } ?>
          ffwd_popup_current_height = jQuery(window).height();
        }
        if (jQuery(window).width() >= <?php echo $image_width; ?> && <?php echo $open_with_fullscreen; ?> != 1 ) {
          jQuery("#spider_popup_wrap").css({
            width: <?php echo $image_width; ?>,
            left: '50%',
            marginLeft: -<?php echo $image_width / 2; ?>,
            zIndex: 100000
          });
          jQuery(".ffwd_image_wrap").css({width: <?php echo $image_width; ?> - comment_container_width});
          jQuery(".ffwd_image_container").css({width: (<?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width)});
          jQuery(".ffwd_popup_image").css({
            maxWidth: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width
          });

          jQuery(".ffwd_video").css({
            width: <?php echo $image_width - ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width
          });
          <?php if ($filmstrip_direction == 'horizontal') { ?>
          jQuery(".ffwd_filmstrip_container").css({width: <?php echo $image_width; ?> - comment_container_width});
          jQuery(".ffwd_filmstrip").css({width: (<?php echo $image_width; ?>  - comment_container_width- 40)});
          <?php } ?>

          /*if content type is timeline*/
          jQuery(".ffwd_sub_attachmenst").css({width: (<?php echo $image_width; ?>  - comment_container_width- 40)});

          ffwd_popup_current_width = <?php echo $image_width; ?>;
        }
        else {
          jQuery("#spider_popup_wrap").css({
            width: jQuery(window).width(),
            left: 0,
            marginLeft: 0,
            zIndex: 100000
          });
          jQuery(".ffwd_image_wrap").css({width: (jQuery(window).width() - comment_container_width)});
          jQuery(".ffwd_image_container").css({width: (jQuery(window).width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width)});
          jQuery(".ffwd_popup_image").css({
            maxWidth: jQuery(window).width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width
          });

          jQuery(".ffwd_video").css({
            width: jQuery(window).width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - comment_container_width
          });
          <?php if ($filmstrip_direction == 'horizontal') { ?>
          jQuery(".ffwd_filmstrip_container").css({width: (jQuery(window).width() - comment_container_width)});
          jQuery(".ffwd_filmstrip").css({width: (jQuery(window).width() - comment_container_width - 40)});
          <?php } ?>

          /*if content type is timeline*/
          jQuery(".ffwd_sub_attachmenst").css({width: (jQuery(window).width() - comment_container_width - 40)});

          ffwd_popup_current_width = jQuery(window).width();
        }

        if (((jQuery(window).height() > <?php echo $image_height - 2 * $theme_row->lightbox_close_btn_top; ?>) && (jQuery(window).width() >= <?php echo $image_width - 2 * $theme_row->lightbox_close_btn_right; ?>)) && (<?php echo $open_with_fullscreen; ?> != 1)) {
          jQuery(".spider_popup_close_fullscreen").attr("class", "spider_popup_close");
        }
      else {
          if ((jQuery("#spider_popup_wrap").width() < jQuery(window).width()) && (jQuery("#spider_popup_wrap").height() < jQuery(window).height())) {
            jQuery(".spider_popup_close").attr("class", "ffwd_ctrl_btn spider_popup_close_fullscreen");
          }
        }
      }
      jQuery(window).resize(function() {
        if (typeof jQuery().fullscreen !== 'undefined' && jQuery.isFunction(jQuery().fullscreen) && !jQuery.fullscreen.isFullScreen()) {
          ffwd_popup_resize();
        }
      });
      /* Popup current width/height.*/
      var ffwd_popup_current_width = <?php echo $image_width; ?>;
      var ffwd_popup_current_height = <?php echo $image_height; ?>;
      /* Open/close comments.*/
      function ffwd_comment() {
        if (jQuery(".ffwd_object_info_container").hasClass("ffwd_open")) {
          /* Close comment.*/
          var border_width = parseInt(jQuery(".ffwd_object_info_container").css('borderRightWidth'));
          if (!border_width) {
            border_width = 0;
          }
          jQuery(".ffwd_object_info_container").animate({
          <?php echo $theme_row->lightbox_obj_pos; ?>: -jQuery(".ffwd_object_info_container").width() - border_width
        }, {
            duration: 500,
                complete: function () {
              jQuery(".spider_popup_close_fullscreen").show();
            }
          }
        );
          jQuery(".ffwd_image_wrap").animate({
          <?php echo $theme_row->lightbox_obj_pos; ?>: 0,
              width: jQuery("#spider_popup_wrap").width()
        }, 500);
          jQuery(".ffwd_image_container").animate({
            width: jQuery("#spider_popup_wrap").width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>
          }, 500);
          jQuery(".ffwd_popup_image").animate({
            maxWidth: jQuery("#spider_popup_wrap").width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>
          }, {
            duration: 500,
          });

          jQuery(".ffwd_video").animate({
            width: jQuery("#spider_popup_wrap").width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>
          }, {
            duration: 500,
            complete: function () {
            }
          });
          jQuery(".ffwd_filmstrip_container").animate({<?php echo $width_or_height; ?>: jQuery(".spider_popup_wrap").<?php echo $width_or_height; ?>()}, 500);
          jQuery(".ffwd_filmstrip").animate({<?php echo $width_or_height; ?>: jQuery(".spider_popup_wrap").<?php echo $width_or_height; ?>() - 40}, 500);

          /*if content type is timeline*/
          jQuery(".ffwd_sub_attachmenst").animate({width: jQuery(".spider_popup_wrap").width() - 40}, 500);

          /* Set filmstrip initial position.*/
          ffwd_set_filmstrip_pos(jQuery(".spider_popup_wrap").width() - 40);
          jQuery(".ffwd_object_info_container").attr("class", "ffwd_object_info_container ffwd_close");
          jQuery(".ffwd_comment").attr("title", "<?php echo __('Show Comments', 'bwg'); ?>");
        }
        else {
          /* Open comment.*/
          var comment_container_width = <?php echo $theme_row->lightbox_obj_width; ?>;
          if (comment_container_width > jQuery(window).width()) {
            comment_container_width = jQuery(window).width();
            jQuery(".ffwd_object_info_container").css({
              width: comment_container_width
            });
            jQuery(".spider_popup_close_fullscreen").hide();
            if (jQuery(".ffwd_ctrl_btn").hasClass("fa-pause")) {
              var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
              jQuery(".ffwd_play_pause").trigger(isMobile ? 'touchend' : 'click');
            }
          }
          else {
            jQuery(".spider_popup_close_fullscreen").show();
          }
          jQuery(".ffwd_object_info_container").animate({<?php echo $theme_row->lightbox_obj_pos; ?>: 0}, 500);
          jQuery(".ffwd_image_wrap").animate({
          <?php echo $theme_row->lightbox_obj_pos; ?>: jQuery(".ffwd_object_info_container").width(),
              width: jQuery("#spider_popup_wrap").width() - jQuery(".ffwd_object_info_container").width()
        }, 500);
          jQuery(".ffwd_image_container").animate({
            width: jQuery("#spider_popup_wrap").width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?> - jQuery(".ffwd_object_info_container").width()}, 500);
          jQuery(".ffwd_popup_image").animate({
            maxWidth: jQuery("#spider_popup_wrap").width() - jQuery(".ffwd_object_info_container").width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>
          }, {
            duration: 500,
            complete: function () {  }
          });

          jQuery(".ffwd_video").animate({
            width: jQuery("#spider_popup_wrap").width() - jQuery(".ffwd_object_info_container").width() - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>
          }, {
            duration: 500,
            complete: function () {
            }
          });
          jQuery(".ffwd_filmstrip_container").css({<?php echo $width_or_height; ?>: jQuery("#spider_popup_wrap").<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'vertical' ? 0: 'jQuery(".ffwd_object_info_container").width()'); ?>});
          jQuery(".ffwd_filmstrip").animate({<?php echo $width_or_height; ?>: jQuery(".ffwd_filmstrip_container").<?php echo $width_or_height; ?>() - 40}, 500);

          /* If content type is timeline*/
          var sub_att_cont = jQuery("#spider_popup_wrap").width() - jQuery(".ffwd_object_info_container").width() - 40;
          jQuery(".ffwd_sub_attachmenst").animate({width: sub_att_cont}, 500);

          /* Set filmstrip initial position.*/
          ffwd_set_filmstrip_pos(jQuery(".ffwd_filmstrip_container").<?php echo $width_or_height; ?>() - 40);
          jQuery(".ffwd_object_info_container").attr("class", "ffwd_object_info_container ffwd_open");
          jQuery(".ffwd_comment").attr("title", "<?php echo __('Hide Comments', 'bwg'); ?>");
        }
      }
      jQuery(document).ready(function () {

        <?php if($ffwd_info->open_commentbox==1 && $enable_object_info==1) { ?>
        ffwd_comment()
        <?php }?>

        jQuery('<img>').attr('src','<?php echo WD_FFWD_URL ?>/images/feed/comment_lightbox.png');
        jQuery('<img>').attr('src','<?php echo WD_FFWD_URL ?>/images/feed/play.png' );
        jQuery('<img>').attr('src','<?php echo WD_FFWD_URL ?>/images/feed/pause.png' );
        jQuery('<img>').attr('src','<?php echo WD_FFWD_URL ?>/images/feed/facebook_white.png' );
        jQuery('<img>').attr('src','<?php echo WD_FFWD_URL ?>/images/feed/twitter_white.png' );
        jQuery('<img>').attr('src','<?php echo WD_FFWD_URL ?>/images/feed/google_white.png' );
        jQuery('<img>').attr('src','<?php echo WD_FFWD_URL ?>/images/feed/resize_in.png' );
        jQuery('<img>').attr('src','<?php echo WD_FFWD_URL ?>/images/feed/resize_out.png' );
        jQuery('<img>').attr('src','<?php echo WD_FFWD_URL ?>/images/feed/fullscreen.png' );

        if (typeof jQuery().swiperight !== 'undefined' && jQuery.isFunction(jQuery().swiperight)) {
          jQuery('#spider_popup_wrap').swiperight(function () {
            ffwd_change_image(parseInt(jQuery('#ffwd_current_image_key').val()), parseInt(jQuery('#ffwd_current_image_key').val()) - 1, data)
            return false;
          });
        }
        if (typeof jQuery().swipeleft !== 'undefined' && jQuery.isFunction(jQuery().swipeleft)) {
          jQuery('#spider_popup_wrap').swipeleft(function () {
            ffwd_change_image(parseInt(jQuery('#ffwd_current_image_key').val()), parseInt(jQuery('#ffwd_current_image_key').val()) + 1, data);
            return false;
          });
        }

        ffwd_reset_zoom();
        var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
        var ffwd_click = isMobile ? 'touchend' : 'click';
        jQuery("#spider_popup_left").on(ffwd_click, function () {
          ffwd_change_image(parseInt(jQuery('#ffwd_current_image_key').val()), (parseInt(jQuery('#ffwd_current_image_key').val()) + data.length - 1) % data.length, data);
          return false;
        });
        jQuery("#spider_popup_right").on(ffwd_click, function () {
          ffwd_change_image(parseInt(jQuery('#ffwd_current_image_key').val()), (parseInt(jQuery('#ffwd_current_image_key').val()) + 1) % data.length, data);
          return false;
        });
        if (navigator.appVersion.indexOf("MSIE 10") != -1 || navigator.appVersion.indexOf("MSIE 9") != -1) {
          setTimeout(function () {
            ffwd_popup_resize();
          }, 1);
        }
        else {
          ffwd_popup_resize();
        }
        /* If browser doesn't support Fullscreen API.*/
        if (typeof jQuery().fullscreen !== 'undefined' && jQuery.isFunction(jQuery().fullscreen) && !jQuery.fullscreen.isNativelySupported()) {
          jQuery(".ffwd_fullscreen").hide();
        }
        /* Set image container height.*/
        <?php if ($filmstrip_direction == 'horizontal') { ?>
        jQuery(".ffwd_image_container").height(jQuery(".ffwd_image_wrap").height() - <?php echo $image_filmstrip_height; ?>);
        jQuery(".ffwd_image_container").width(jQuery(".ffwd_image_wrap").width());
        <?php }
        else {
        ?>
        jQuery(".ffwd_image_container").height(jQuery(".ffwd_image_wrap").height());
        jQuery(".ffwd_image_container").width(jQuery(".ffwd_image_wrap").width() - <?php echo $image_filmstrip_width; ?>);
        <?php
        } ?>
        var mousewheelevt = (/Firefox/i.test(navigator.userAgent)) ? "DOMMouseScroll" : "mousewheel" /*FF doesn't recognize mousewheel as of FF3.x*/
        jQuery('.ffwd_filmstrip').on(mousewheelevt, function(e) {
          var evt = window.event || e; /* Equalize event object.*/
          evt = evt.originalEvent ? evt.originalEvent : evt; /* Convert to originalEvent if possible.*/
          var delta = evt.detail ? evt.detail*(-40) : evt.wheelDelta; /* Check for detail first, because it is used by Opera and FF.*/
          var isMobile = (/android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase()));
          if (delta > 0) {
            /* Scroll up.*/
            jQuery(".ffwd_filmstrip_left").trigger(isMobile ? 'touchend' : 'click');
          }
          else {
            /* Scroll down.*/
            jQuery(".ffwd_filmstrip_right").trigger(isMobile ? 'touchend' : 'click');
          }
        });
        jQuery(".ffwd_filmstrip_right").on(ffwd_click, function () {
          jQuery( ".ffwd_filmstrip_thumbnails" ).stop(true, false);
          if (jQuery(".ffwd_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> >= -(jQuery(".ffwd_filmstrip_thumbnails").<?php echo $width_or_height; ?>() - jQuery(".ffwd_filmstrip").<?php echo $width_or_height; ?>())) {
            jQuery(".ffwd_filmstrip_left").css({opacity: 1, filter: "Alpha(opacity=100)"});
            if (jQuery(".ffwd_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> < -(jQuery(".ffwd_filmstrip_thumbnails").<?php echo $width_or_height; ?>() - jQuery(".ffwd_filmstrip").<?php echo $width_or_height; ?>() - <?php echo $filmstrip_thumb_margin_hor + $image_filmstrip_width; ?>)) {
              jQuery(".ffwd_filmstrip_thumbnails").animate({<?php echo $left_or_top; ?>: -(jQuery(".ffwd_filmstrip_thumbnails").<?php echo $width_or_height; ?>() - jQuery(".ffwd_filmstrip").<?php echo $width_or_height; ?>())}, 500, 'linear');
            }
            else {
              jQuery(".ffwd_filmstrip_thumbnails").animate({<?php echo $left_or_top; ?>: (jQuery(".ffwd_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> - <?php echo $filmstrip_thumb_margin_hor + $image_filmstrip_width; ?>)}, 500, 'linear');
            }
          }
          /* Disable right arrow.*/
          window.setTimeout(function(){
            if (jQuery(".ffwd_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> == -(jQuery(".ffwd_filmstrip_thumbnails").<?php echo $width_or_height; ?>() - jQuery(".ffwd_filmstrip").<?php echo $width_or_height; ?>())) {
              jQuery(".ffwd_filmstrip_right").css({opacity: 0.3, filter: "Alpha(opacity=30)"});
            }
          }, 500);
        });

        jQuery(".ffwd_filmstrip_left").on(ffwd_click, function () {
          jQuery( ".ffwd_filmstrip_thumbnails" ).stop(true, false);
          if (jQuery(".ffwd_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> < 0) {
            jQuery(".ffwd_filmstrip_right").css({opacity: 1, filter: "Alpha(opacity=100)"});
            if (jQuery(".ffwd_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> > - <?php echo $filmstrip_thumb_margin_hor + $image_filmstrip_width; ?>) {
              jQuery(".ffwd_filmstrip_thumbnails").animate({<?php echo $left_or_top; ?>: 0}, 500, 'linear');
            }
            else {
              jQuery(".ffwd_filmstrip_thumbnails").animate({<?php echo $left_or_top; ?>: (jQuery(".ffwd_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> + <?php echo $image_filmstrip_width + $filmstrip_thumb_margin_hor; ?>)}, 500, 'linear');
            }
          }
          /* Disable left arrow.*/
          window.setTimeout(function(){
            if (jQuery(".ffwd_filmstrip_thumbnails").position().<?php echo $left_or_top; ?> == 0) {
              jQuery(".ffwd_filmstrip_left").css({opacity: 0.3, filter: "Alpha(opacity=30)"});
            }
          }, 500);
        });
        /* Subattachments position */
        jQuery(".ffwd_sub_attachmenst_cont_left").on(ffwd_click, function () {
          jQuery( ".ffwd_sub_attachmenst_thumbnails" ).stop(true, false);
          if (jQuery(".ffwd_sub_attachmenst_thumbnails").position().left < 0) {
            jQuery(".ffwd_sub_attachmenst_cont_right").css({opacity: 1, filter: "Alpha(opacity=100)"});
            if (jQuery(".ffwd_sub_attachmenst_thumbnails").position().left > - <?php echo /*$filmstrip_thumb_margin_hor + $image_filmstrip_width;*/ 58 + 6; ?>) {
              jQuery(".ffwd_sub_attachmenst_thumbnails").animate({left: 0}, 500, 'linear');
            }
            else {
              jQuery(".ffwd_sub_attachmenst_thumbnails").animate({left: (jQuery(".ffwd_sub_attachmenst_thumbnails").position().left + <?php echo /*$image_filmstrip_width + $filmstrip_thumb_margin_hor;*/ 58 +6; ?>)}, 500, 'linear');
            }
          }
          /* Disable left arrow.*/
          window.setTimeout(function(){
            if (jQuery(".ffwd_sub_attachmenst_thumbnails").position().left == 0) {
              jQuery(".ffwd_sub_attachmenst_cont_left").css({opacity: 0.3, filter: "Alpha(opacity=30)"});
            }
          }, 500);
        });
        jQuery(".ffwd_sub_attachmenst_cont_right").on(ffwd_click, function () {
          jQuery( ".ffwd_sub_attachmenst_thumbnails" ).stop(true, false);
          if (jQuery(".ffwd_sub_attachmenst_thumbnails").position().left >= -(jQuery(".ffwd_sub_attachmenst_thumbnails").width() - jQuery(".ffwd_sub_attachmenst").width())) {
            jQuery(".ffwd_sub_attachmenst_cont_left").css({opacity: 1, filter: "Alpha(opacity=100)"});
            if (jQuery(".ffwd_sub_attachmenst_thumbnails").position().left < -(jQuery(".ffwd_sub_attachmenst_thumbnails").width() - jQuery(".ffwd_sub_attachmenst").width() - <?php echo /*$filmstrip_thumb_margin_hor + $image_filmstrip_width;*/ 58 + 6; ?>)) {
              jQuery(".ffwd_sub_attachmenst_thumbnails").animate({left: -(jQuery(".ffwd_sub_attachmenst_thumbnails").width() - jQuery(".ffwd_sub_attachmenst").width())}, 500, 'linear');
            }
            else {
              jQuery(".ffwd_sub_attachmenst_thumbnails").animate({left: (jQuery(".ffwd_sub_attachmenst_thumbnails").position().left - <?php echo /*$filmstrip_thumb_margin_hor + $image_filmstrip_width;*/ 58 + 6; ?>)}, 500, 'linear');
            }
          }
          /* Disable right arrow.*/
          window.setTimeout(function(){
            if (jQuery(".ffwd_sub_attachmenst_thumbnails").position().left == -(jQuery(".ffwd_sub_attachmenst_thumbnails").width() - jQuery(".ffwd_sub_attachmenst").width())) {
              jQuery(".ffwd_sub_attachmenst_cont_right").css({opacity: 0.3, filter: "Alpha(opacity=30)"});
            }
          }, 500);
        });
        /* Set filmstrip initial position.*/
        ffwd_set_filmstrip_pos(jQuery(".ffwd_filmstrip").<?php echo $width_or_height; ?>());
        /* Open/close comments.*/
        jQuery(".ffwd_comment, .ffwd_comments_close_btn").on(ffwd_click, function(e) { e.stopPropagation(); ffwd_comment()});
        /* Open/close control buttons.*/
        jQuery(".ffwd_toggle_container").on(ffwd_click, function () {
          var ffwd_open_toggle_btn_class = "<?php echo ($theme_row->lightbox_ctrl_btn_pos == 'top') ? 'fa-angle-up' : 'fa-angle-down'; ?>";
          var ffwd_close_toggle_btn_class = "<?php echo ($theme_row->lightbox_ctrl_btn_pos == 'top') ? 'fa-angle-down' : 'fa-angle-up'; ?>";
          if (jQuery(".ffwd_toggle_container i").hasClass(ffwd_open_toggle_btn_class)) {
            /* Close controll buttons.*/
            jQuery(".ffwd_ctrl_btn_container").animate({<?php echo $theme_row->lightbox_ctrl_btn_pos; ?>: '-' + jQuery(".ffwd_ctrl_btn_container").height()}, 500);
            jQuery(".ffwd_toggle_container").animate({
            <?php echo $theme_row->lightbox_ctrl_btn_pos; ?>: 0
          }, {
              duration: 500,
                  complete: function () { jQuery(".ffwd_toggle_container i").attr("class", "ffwd_toggle_btn fa " + ffwd_close_toggle_btn_class) }
            });
          }
          else {
            /* Open controll buttons.*/
            jQuery(".ffwd_ctrl_btn_container").animate({<?php echo $theme_row->lightbox_ctrl_btn_pos; ?>: 0}, 500);
            jQuery(".ffwd_toggle_container").animate({
            <?php echo $theme_row->lightbox_ctrl_btn_pos; ?>: jQuery(".ffwd_ctrl_btn_container").height()
          }, {
              duration: 500,
                  complete: function () { jQuery(".ffwd_toggle_container i").attr("class", "ffwd_toggle_btn fa " + ffwd_open_toggle_btn_class) }
            });
          }
        });
        /* Maximize/minimize.*/
        jQuery(".ffwd_resize-full").on(ffwd_click, function () {
          var comment_container_width = 0;
          if (jQuery(".ffwd_object_info_container").hasClass("ffwd_open")) {
            comment_container_width = jQuery(".ffwd_object_info_container").width();
          }
          if (jQuery(".ffwd_resize-full").hasClass("ffwd_resize_in_full")) {
            if (jQuery(window).width() > <?php echo $image_width; ?>) {
              ffwd_popup_current_width = <?php echo $image_width; ?>;
            }
            if (jQuery(window).height() > <?php echo $image_height; ?>) {
              ffwd_popup_current_height = <?php echo $image_height; ?>;
            }
            /* Minimize.*/
            jQuery("#spider_popup_wrap").animate({
              width: ffwd_popup_current_width,
              height: ffwd_popup_current_height,
              left: '50%',
              top: '50%',
              marginLeft: -ffwd_popup_current_width / 2,
              marginTop: -ffwd_popup_current_height / 2,
              zIndex: 100000
            }, 500);
            jQuery(".ffwd_image_wrap").animate({width: ffwd_popup_current_width - comment_container_width}, 500);
            jQuery(".ffwd_image_container").animate({height: ffwd_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>, width: ffwd_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>}, 500);
            jQuery(".ffwd_popup_image").animate({
              maxWidth: ffwd_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
              maxHeight: ffwd_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
            }, {
              duration: 500,
              complete: function () {

                if ((jQuery("#spider_popup_wrap").width() < jQuery(window).width()) && (jQuery("#spider_popup_wrap").height() < jQuery(window).height())) {
                  jQuery(".spider_popup_close_fullscreen").attr("class", "spider_popup_close");
                }
              }
            });

            jQuery(".ffwd_video").animate({
              width: ffwd_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
              height: ffwd_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
            }, {
              duration: 500,
              complete: function () {

                if ((jQuery("#spider_popup_wrap").width() < jQuery(window).width()) && (jQuery("#spider_popup_wrap").height() < jQuery(window).height())) {
                  jQuery(".spider_popup_close_fullscreen").attr("class", "spider_popup_close");
                }
              }
            });
            jQuery(".ffwd_filmstrip_container").animate({<?php echo $width_or_height; ?>: ffwd_popup_current_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>}, 500);
            jQuery(".ffwd_filmstrip").animate({<?php echo $width_or_height; ?>: ffwd_popup_current_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40}, 500);

            /* If content type is timeline*/
            jQuery(".ffwd_sub_attachmenst").animate({width: ffwd_popup_current_width - comment_container_width - 40}, 500);

            /* Set filmstrip initial position.*/
            ffwd_set_filmstrip_pos(ffwd_popup_current_<?php echo $width_or_height; ?> - 40);
            jQuery(".ffwd_resize-full").attr("class", "ffwd_ctrl_btn ffwd_resize-full ffwd_resize_out_full");
            jQuery(".ffwd_resize-full").attr("title", "<?php echo __('Maximize', 'bwg'); ?>");
          }
          else {
            ffwd_popup_current_width = jQuery(window).width();
            ffwd_popup_current_height = jQuery(window).height();
            /* Maximize.*/
            jQuery("#spider_popup_wrap").animate({
              width: jQuery(window).width(),
              height: jQuery(window).height(),
              left: 0,
              top: 0,
              margin: 0,
              zIndex: 100000
            }, 500);
            jQuery(".ffwd_image_wrap").animate({width: (jQuery(window).width() - comment_container_width)}, 500);
            jQuery(".ffwd_image_container").animate({height: (ffwd_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>), width: ffwd_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>}, 500);
            jQuery(".ffwd_popup_image").animate({
              maxWidth: jQuery(window).width() - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
              maxHeight: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
            }, {
              duration: 500,
              complete: function () {  }
            });

            jQuery(".ffwd_video").animate({
              width: jQuery(window).width() - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
              height: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
            }, {
              duration: 500,
              complete: function () {
              }
            });
            jQuery(".ffwd_filmstrip_container").animate({<?php echo $width_or_height; ?>: jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>}, 500);
            jQuery(".ffwd_filmstrip").animate({<?php echo $width_or_height; ?>: jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40}, 500);

            /* If content type is timeline*/
            jQuery(".ffwd_sub_attachmenst").animate({width: jQuery(window).width() - comment_container_width - 40}, 500);

            /* Set filmstrip initial position.*/
            ffwd_set_filmstrip_pos(jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40);
            jQuery(".ffwd_resize-full").attr("class", "ffwd_ctrl_btn ffwd_resize-full ffwd_resize_in_full");
            jQuery(".ffwd_resize-full").attr("title", "<?php echo __('Restore', 'bwg'); ?>");
            jQuery(".spider_popup_close").attr("class", "ffwd_ctrl_btn spider_popup_close_fullscreen");
          }
        });
        /* Fullscreen.*/

        /*Toggle with mouse click*/
        jQuery(".ffwd_fullscreen").on(ffwd_click, function () {
          var comment_container_width = 0;
          if (jQuery(".ffwd_object_info_container").hasClass("ffwd_open")) {
            comment_container_width = jQuery(".ffwd_object_info_container").width();
          }
          function ffwd_exit_fullscreen() {
            if (jQuery(window).width() > <?php echo $image_width; ?>) {
              ffwd_popup_current_width = <?php echo $image_width; ?>;
            }
            if (jQuery(window).height() > <?php echo $image_height; ?>) {
              ffwd_popup_current_height = <?php echo $image_height; ?>;
            }
            <?php
            /* "Full width lightbox" sets yes.*/
            if ($open_with_fullscreen) {
            ?>
            ffwd_popup_current_width = jQuery(window).width();
            ffwd_popup_current_height = jQuery(window).height();
            <?php
            }
            ?>
            jQuery("#spider_popup_wrap").on("fscreenclose", function() {
              jQuery("#spider_popup_wrap").css({
                width: ffwd_popup_current_width,
                height: ffwd_popup_current_height,
                left: '50%',
                top: '50%',
                marginLeft: -ffwd_popup_current_width / 2,
                marginTop: -ffwd_popup_current_height / 2,
                zIndex: 100000
              });
              jQuery(".ffwd_image_wrap").css({width: ffwd_popup_current_width - comment_container_width});
              jQuery(".ffwd_image_container").css({height: ffwd_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>, width: ffwd_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>});
              jQuery(".ffwd_popup_image").css({
                maxWidth: ffwd_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
                maxHeight: ffwd_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
              });

              jQuery(".ffwd_video").css({
                width: ffwd_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
                height: ffwd_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
              });

              jQuery(".ffwd_filmstrip_container").css({<?php echo $width_or_height; ?>: ffwd_popup_current_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>});
              jQuery(".ffwd_filmstrip").css({<?php echo $width_or_height; ?>: ffwd_popup_current_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>- 40});

              /*if content type is timeline*/
              jQuery(".ffwd_sub_attachmenst").css({width: ffwd_popup_current_width - comment_container_width - 40});

              /* Set filmstrip initial position.*/
              ffwd_set_filmstrip_pos(ffwd_popup_current_<?php echo $width_or_height; ?> - 40);
              jQuery(".ffwd_resize-full").show();
              jQuery(".ffwd_resize-full").attr("class", "ffwd_ctrl_btn ffwd_resize-full  ffwd_resize_out_full");
              jQuery(".ffwd_resize-full").attr("title", "<?php echo __('Maximize', 'bwg'); ?>");
              jQuery(".ffwd_fullscreen").attr("class", "ffwd_ctrl_btn ffwd_fullscreen ");
              jQuery(".ffwd_fullscreen").attr("title", "<?php echo __('Fullscreen', 'bwg'); ?>");
              if ((jQuery("#spider_popup_wrap").width() < jQuery(window).width()) && (jQuery("#spider_popup_wrap").height() < jQuery(window).height())) {
                jQuery(".spider_popup_close_fullscreen").attr("class", "spider_popup_close");
              }
            });
          }
          if (typeof jQuery().fullscreen !== 'undefined' && jQuery.isFunction(jQuery().fullscreen)) {
            if (jQuery.fullscreen.isFullScreen()) {
              /* Exit Fullscreen.*/
              jQuery.fullscreen.exit();
              ffwd_exit_fullscreen();
            }
            else {
              /* Fullscreen.*/
              jQuery("#spider_popup_wrap").fullscreen();
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
              jQuery(".ffwd_image_wrap").css({width: screen_width - comment_container_width});
              jQuery(".ffwd_image_container").css({height: (screen_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>), width: screen_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>});
              jQuery(".ffwd_popup_image").css({
                maxWidth: (screen_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>),
                maxHeight: (screen_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>)
              });

              jQuery(".ffwd_video").css({
                width: (screen_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>),
                height: (screen_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>)
              });

              jQuery(".ffwd_filmstrip_container").css({<?php echo $width_or_height; ?>: (screen_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>)});
              jQuery(".ffwd_filmstrip").css({<?php echo $width_or_height; ?>: (screen_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40)});

              /*if content type is timeline*/
              jQuery(".ffwd_sub_attachmenst").css({width: screen_width - comment_container_width - 40});

              /* Set filmstrip initial position.*/
              ffwd_set_filmstrip_pos(screen_<?php echo $width_or_height; ?> - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40);
              jQuery(".ffwd_resize-full").hide();
              jQuery(".ffwd_fullscreen").attr("class", "ffwd_ctrl_btn ffwd_fullscreen");/* fa fa-resize-small*/
              jQuery(".ffwd_fullscreen").attr("title", "<?php echo __('Exit Fullscreen', 'bwg'); ?>");
              jQuery(".spider_popup_close").attr("class", "ffwd_ctrl_btn spider_popup_close_fullscreen");
            }
          }
          return false;
        });
        /* Play/pause.*/
        jQuery(".ffwd_play_pause, .ffwd_popup_image").on(ffwd_click, function () {
          if (jQuery(".ffwd_ctrl_btn").hasClass("ffwd_play")) {
            /* PLay.*/
            ffwd_play();
            jQuery(".ffwd_play_pause").attr("title", "<?php echo __('Pause', 'bwg'); ?>");
            jQuery(".ffwd_play_pause").attr("class", "ffwd_ctrl_btn ffwd_play_pause ffwd_pause");
          }
          else {
            /* Pause.*/
            window.clearInterval(ffwd_playInterval);
            jQuery(".ffwd_play_pause").attr("title", "<?php echo __('Play', 'bwg'); ?>");
            jQuery(".ffwd_play_pause").attr("class", "ffwd_ctrl_btn ffwd_play_pause ffwd_play");
          }
        });
        /* Open with autoplay.*/
        <?php
        if ($open_with_autoplay) {
        ?>
        ffwd_play();
        jQuery(".ffwd_play_pause").attr("title", "<?php echo __('Pause', 'bwg'); ?>");
        jQuery(".ffwd_play_pause").attr("class", "ffwd_ctrl_btn ffwd_play_pause ffwd_pause");
        <?php
        }
        ?>
        /* Open with fullscreen.*/
        <?php
        if ($open_with_fullscreen) {
        ?>
        ffwd_open_with_fullscreen();
        <?php
        }
        ?>
        // Update scrollbar.
        if (typeof jQuery().mCustomScrollbar !== 'undefined' && jQuery.isFunction(jQuery().mCustomScrollbar)) {
          jQuery(".ffwd_object_info").mCustomScrollbar({
            advanced:{
              updateOnContentResize: true,
              scrollInertia: 80
            }
          });
        }

        /*View more comments*/
        jQuery('body').on('click', '.ffwd_view_more_comments', function(e) {
          e.preventDefault();
          jQuery(this).parent().parent().parent().find('.ffwd_single_comment').css('display', 'block');
          jQuery(this).html('');
        });

        /* View replies */
        jQuery('body').on('click', '.ffwd_comment_replies_label', function(e) {
          e.preventDefault();
          jQuery(this).parent().find('.ffwd_comment_replies_content').css('display', 'block');
          jQuery(this).remove();
        });

        /* Fill comments and likes containers*/
        ffwd_fill_likes_comments(ffwd_current_key);

        /* See more see less messages*/
        jQuery('body').on('click', '.ffwd_see_more_message', function(e) {
          e.preventDefault();
          ffwd_see_less_more(jQuery(this), 'more', 'message');
        });

        jQuery('body').on('click', '.ffwd_see_less_message', function(e) {
          e.preventDefault();
          ffwd_see_less_more(jQuery(this), 'less', 'message');
        });

        /*See more see less description*/
        jQuery('body').on('click', '.ffwd_see_more_description', function(e) {
          e.preventDefault();
          ffwd_see_less_more(jQuery(this), 'more', 'description');
        });

        jQuery('body').on('click', '.ffwd_see_less_description', function(e) {
          e.preventDefault();
          ffwd_see_less_more(jQuery(this), 'less', 'description');
        });

        /*For who post and story*/
        /*ffwd_who_post(ffwd_current_key);*/
        ffwd_fill_story_author_place(ffwd_current_key);
        /*If content_type is timeline*/
        <?php
        if ($content_type == 'timeline' || $from_album || strpos($type, "video") !== false) {
        ?>
        ffwd_fill_src('<?php echo $ffwd_info->from; ?>', true, '.ffwd_popup_image_spun', '.ffwd_popup_image_spun', ffwd_current_key, '', ffwd_from_album);
        <?php
        }
        ?>

      });

      /*Function for who post*/
      function ffwd_who_post(key) {

        var ffwd_from = (typeof data[key]["from"] != "undefined" && data[key]["from"].length != 0) ? data[key]["from"] : owner_info['id'];
        url_for_who_post = popup_graph_url.replace('{FB_ID}', ffwd_from),
            graph_url_for_who_post = url_for_who_post.replace('{EDGE}', ''),
            graph_url_for_who_post = graph_url_for_who_post.replace('{FIELDS}', 'fields=picture,name,link&');
        graph_url_for_who_post = graph_url_for_who_post.replace('{OTHER}', '');
        jQuery.getJSON(graph_url_for_who_post, popup_createCallback(key));
      }

      function popup_createCallback(key){
        return function(result) {
          popup_do_something_with_data(key, result);
        };
      }

      function popup_do_something_with_data(key, result) {
        ffwd_fill_story_author_place(key, result);
      }
      /*Function for fill story, author, place*/
      function ffwd_fill_story_author_place(key,result) {

        if(data[key]['who_post']==null && !result )
          ffwd_who_post(key)

        if(data[key]["who_post"])
          result=data[key]["who_post"]




        if(result)
        {
          var who_post = result;
          var who_post_name_link = (ffwd_enable_author == "1") ? '<a class="ffwd_from_name" href="https://www.facebook.com/'+who_post['id']+'" target="_blank">'+who_post['name']+'</a>' : '',
              owner_name_link = '<a class="ffwd_from_name" href="https://www.facebook.com/'+owner_info['id']+'" target="_blank">'+owner_info['name']+'</a>',
              who_post_pic = '<img id="ffwd_user_pic" class="ffwd_user_pic" src="'+who_post['picture']['data']['url']+'" style="max-width:40px;box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);">',
              place,
              full_place = '',
              place_id = '',
              story = data[key]["story"].replace(/'/g, "&#039;");
          who_post_index = story.indexOf(who_post['name']),
              owner_index = story.indexOf(owner_info['name']),
              story_tags = data[key]['story_tags'],/*.replace(/'/g, "&#039;")*/
              place_index = -1;

          if(data[key]['place'] != null) {

            if(data[key]['place']['id'])
              var place_id  = data[key]['place']['id'].replace(/'/g, "&#039;");
            if(data[key]['place']['location'])
            {
              var street = (ffwd_options['event_street'] == "1") ? ((typeof data[key]['place']['location']['street'] != 'undefined') ? data[key]['place']['location']['street'] : '') : '',
                  city = (ffwd_options['event_city'] == "1") ? ((typeof data[key]['place']['location']['city'] != 'undefined') ? data[key]['place']['location']['city'] : '') : '',
                  country = (ffwd_options['event_country'] == "1") ? ((typeof data[key]['place']['location']['country'] != 'undefined') ? data[key]['place']['location']['country'] : '') : '',
                  state = (ffwd_options['event_zip'] == "1") ? ((typeof data[key]['place']['location']['state'] != 'undefined') ? data[key]['place']['location']['state'] : '') : '',
                  zip = (ffwd_options['event_zip'] == "1") ? ((typeof data[key]['place']['location']['zip'] != 'undefined') ? data[key]['place']['location']['zip'] : '') : '',
                  latitude = (ffwd_options['event_map'] == "1") ? ((typeof data[key]['place']['location']['latitude'] != 'undefined') ? data[key]['place']['location']['latitude'] : '') : '',
                  longitude = (ffwd_options['event_map'] == "1") ? ((typeof data[key]['place']['location']['longitude'] != 'undefined') ? data[key]['place']['location']['longitude'] : '') : '';

              full_place =  ((ffwd_options['event_street'] == "1") ? '<div class="ffwd_place_street" >'+ street +'</div> ' : '') +
                  ((ffwd_options['event_city'] == "1" || ffwd_options['event_zip'] == "1" || ffwd_options['event_country'] == "1" ) ? '<div class="ffwd_place_city_state_country" >'+city+' '+state+' ' + zip + ' ' +country +'</div>' : '') +
                  ((ffwd_options['event_map'] == "1") ? '<a class="ffwd_place_map" href="https://maps.google.com/maps?q='+latitude+',' + longitude + '" target="_blank">Map</a>' : '');
            }
          }

          if(who_post_index != -1) {
            story = story.replace(who_post['name'], who_post_name_link);
          }
          if(owner_index != -1) {
            story = story.replace(owner_info['name'], owner_name_link);
          }
          if(who_post_index == -1 && owner_index == -1) {
            story = who_post_name_link;
          }
          // With whom after was
          if(story_tags != null) {
            var type = story_tags.constructor.name;
            if(type == "Object") {
              for(var x in story_tags) {
                var story_tag_name = story_tags[x]["0"]["name"],
                    story_tag_id = story_tags[x]["0"]["id"];
                with_name_index = story.indexOf(story_tag_name);
                if(with_name_index != -1 && story_tag_name != who_post['name'] && story_tag_name != owner_info['name'] && (story_tag_id != place_id)) {
                  story_tag_link = (/*ffwd_params['blog_style_with_whom'] == "1"*/true) ? '<a class="ffwd_from_name" href="https://www.facebook.com/'+story_tag_id+'" target="_blank">'+story_tag_name+'</a>' : '',
                      story = story.replace(story_tag_name, story_tag_link);
                }
                else if(story_tag_id == place_id) {
                  // Where after was
                  place_index = 1;
                  place = (ffwd_enable_place_name == "1") ? '<a class="ffwd_place_name" href="https://www.facebook.com/'+data[key]['place']['id']+'" target="_blank">'+story_tag_name+'</a>' : '';
                  story = story.replace("\u2014", "");
                  story = story.replace(story_tag_name.replace(/"/g, "&quot;"), place);
                }
              }
            }
            else if(type == "Array") {
              for(var j=0; j<story_tags.length; j++) {
                if(typeof story_tags[j]["0"] != "undefined") {
                  var story_tag_name = story_tags[j]["0"]["name"],
                      story_tag_id = story_tags[j]["0"]["id"];
                }else {
                  var story_tag_name = story_tags[j].name,
                      story_tag_id = story_tags[j].id;
                }
                with_name_index = story.indexOf(story_tag_name);
                if(with_name_index != -1 && story_tag_name != who_post['name'] && story_tag_name != owner_info['name'] && (story_tag_id != place_id)) {
                  story_tag_link = (/*ffwd_params['blog_style_with_whom'] == "1"*/true) ? '<a class="ffwd_from_name" href="https://www.facebook.com/'+story_tag_id+'" target="_blank">'+story_tag_name+'</a>' : '',
                      story = story.replace(story_tag_name, story_tag_link);
                }
                else if(story_tag_id == place_id) {
                  // Where after was
                  place_index = 1;
                  place = (ffwd_enable_place_name == "1") ? '<a class="ffwd_place_name" href="https://www.facebook.com/'+data[key]['place']['id']+'" target="_blank">'+story_tag_name+'</a>' : '';
                  story = story.replace("\u2014", "");
                  story = story.replace(story_tag_name.replace(/"/g, "&quot;"), place);
                }
              }
            }
          }
          // Where after was
          if(ffwd_enable_place_name == "1") {
            if(data[key]['type'] == 'events')
              story += full_place;
          }
          else {
            story = story.replace(/ at| in|/gi, "");
          }
          jQuery('.ffwd_object_from_pic').html(who_post_pic).attr("href", who_post['link']);;
          jQuery('.ffwd_story').html(story);

        }
      }

      /*Function for comments and likes cont*/
      function ffwd_fill_src(ffwd_info_from, first_time, current_image_class, next_image_class, key, direction, ffwd_from_album) {
        var url_for_cur_id = popup_graph_url.replace('{FB_ID}', data[key]['object_id']),
            cur_id_for_attachments = data[key]['object_id'].replace(data[key]['from'], ffwd_info_from),
            url_for_cur_id_for_attachments = popup_graph_url.replace('{FB_ID}', cur_id_for_attachments),
            cur_height = (!first_time) ? jQuery(current_image_class).height() : jQuery('#ffwd_image_container').height(),
            cur_width = (!first_time) ? jQuery(current_image_class).width() : jQuery('#ffwd_image_container').width(),
            graph_url_for_attachments = url_for_cur_id_for_attachments.replace('{EDGE}', 'attachments'),
            graph_url_for_album_photo = url_for_cur_id.replace('{EDGE}', '');

        graph_url_for_attachments = graph_url_for_attachments.replace('{FIELDS}', '');
        graph_url_for_attachments = graph_url_for_attachments.replace('{OTHER}', '');

        graph_url_for_album_photo = graph_url_for_album_photo.replace('{FIELDS}', 'fields=images,source,width,height,link,created_time&');
        graph_url_for_album_photo = graph_url_for_album_photo.replace('{OTHER}', '');


        if(ffwd_from_album == '0' && data[key]["type"].indexOf('video') === -1) {

          result=data[key]['attachments'];
          var src = '',
              id = '',
              length = 0,
              album_id = '',
              filmstrip_thumbnails = '';
          if (result['data'][0]) {
            /*If exists subattachments*/
            if (result['data'][0]['subattachments']) {
              length = result['data'][0]['subattachments']['data'].length;
              src = result['data'][0]['subattachments']['data'][0]['media']['image']['src'];
              id = result['data'][0]['subattachments']['data'][0]['target']['id'];
              /*First time add profile picture*/
              if (result['data'][0]['type'] == 'gallery') {
                src = result['data'][0]['subattachments']['data'][length - 1]['media']['image']['src'];
              }

              /*Fill subattachments in filmstrip*/
              for (var i = 0; i < length; i++) {
                var sub_src = result['data'][0]['subattachments']['data'][i]['media']['image']['src'],
                    object_id = result['data'][0]['subattachments']['data'][i]['target']['id'];
                //////////////
                var image_thumb_width = result['data'][0]['subattachments']['data'][i]['media']['image']['width'];
                var image_thumb_height = result['data'][0]['subattachments']['data'][i]['media']['image']['height'];

                var scale = Math.max(<?php echo $image_filmstrip_width ?> / image_thumb_width, <?php echo $image_filmstrip_height ?> /
                image_thumb_height
              )
                ;
                image_thumb_width *= scale;
                image_thumb_height *= scale;
                var thumb_left = (<?php echo $image_filmstrip_width ?> -image_thumb_width) / 2;
                var thumb_top = (<?php echo $image_filmstrip_height ?> -image_thumb_height) / 2;

                //////////////////

                filmstrip_thumbnails += '<div class="ffwd_filmstrip_subattach_thumbnail" object_index="' + i + '" object_id="' + object_id + '" onclick="ffwd_change_subattachment(jQuery(this))">' +
                    '<div style="display:table-cell;vertical-align:middle;" >' +
                    '<img style="width:' + image_thumb_width + 'px; height:' + image_thumb_height + 'px; margin-left: ' + thumb_left + 'px; margin-top: ' + thumb_top + 'px;" class="ffwd_filmstrip_thumbnail_img" src="' + sub_src + '" image_id="" image_key="" alt="" />' +
                    '</div>' +
                    '</div>';
              }
              jQuery('.ffwd_sub_attachmenst_thumbnails').html(filmstrip_thumbnails).css('width', (length) * '<?php echo($image_filmstrip_width + $filmstrip_thumb_margin_hor); ?>' + 'px');
              show_hide_sub_attachments('0px');
              jQuery('.ffwd_image_container').css('top', '<?php echo $image_filmstrip_height; ?>px')

            }
            else if (result['data'][0]['media']) {
              /*
               * Check album containing this photo (compare title)
               * If not Timeline photos or Profile Pictures so get photos from that album
               */
              if (result['data'][0]['title'] != 'Timeline Photos' && result['data'][0]['title'] != 'Profile Pictures') {
                /*Get that album id*/
                album_id = result['data'][0]['url'].split("photos/");
                if (typeof album_id[1] != 'undefined') { /*alert('chucha');*/
                  album_id = album_id[1].split(".");
                  album_id = album_id[1];
                  /*Get photos added to that album*/
                  var url_for_album_subattaments = popup_graph_url.replace('{FB_ID}', ffwd_info_from + '_' + album_id);
                  url_for_album_subattaments = url_for_album_subattaments.replace('{EDGE}', 'attachments');
                  url_for_album_subattaments = url_for_album_subattaments.replace('{FIELDS}', '');
                  url_for_album_subattaments = url_for_album_subattaments.replace('{OTHER}', '');

                  jQuery.getJSON(url_for_album_subattaments, function (result) {
                    /*So fill subattachments in filmstrip*/
                    if (result['data'][0]['subattachments']) {
                      length = result['data'][0]['subattachments']['data'].length;
                      for (var i = 0; i < length; i++) {
                        var src = result['data'][0]['subattachments']['data'][i]['media']['image']['src'],
                            object_id = result['data'][0]['subattachments']['data'][i]['target']['id'];
                        var image_thumb_width = result['data'][0]['subattachments']['data'][i]['media']['image']['width'];
                        var image_thumb_height = result['data'][0]['subattachments']['data'][i]['media']['image']['height'];

                        var scale = Math.max(<?php echo $image_filmstrip_width ?> / image_thumb_width, <?php echo $image_filmstrip_height ?> /
                        image_thumb_height
                      )
                        ;
                        image_thumb_width *= scale;
                        image_thumb_height *= scale;
                        var thumb_left = (<?php echo $image_filmstrip_width ?> -image_thumb_width) / 2;
                        var thumb_top = (<?php echo $image_filmstrip_height ?> -image_thumb_height) / 2;

                        //////////////////

                        filmstrip_thumbnails += '<div class="ffwd_filmstrip_subattach_thumbnail" object_index="' + i + '" object_id="' + object_id + '" onclick="ffwd_change_subattachment(jQuery(this))">' +
                            '<div style="display:table-cell;vertical-align:middle;" >' +
                            '<img style="width:' + image_thumb_width + 'px; height:' + image_thumb_height + 'px; margin-left: ' + thumb_left + 'px; margin-top: ' + thumb_top + 'px;" class="ffwd_filmstrip_thumbnail_img" src="' + src + '" onclick="" ontouchend="" image_id="" image_key="" alt="" />' +
                            '</div>' +
                            '</div>';
                      }
                      /*2-@ box shadowi xatr*/
                      jQuery('.ffwd_sub_attachmenst_thumbnails').html(filmstrip_thumbnails).css('width', (length) * '<?php echo($image_filmstrip_width + $filmstrip_thumb_margin_hor); ?>' + 'px');

                      show_hide_sub_attachments('0px');
                      jQuery('.ffwd_image_container').css('top', '<?php echo $image_filmstrip_height; ?>px')

                    }
                    else {
                      show_hide_sub_attachments('-<?php echo $image_filmstrip_height ?>px');
                      jQuery('.ffwd_sub_attachmenst_thumbnails').html('');
                      jQuery('.ffwd_image_container').css('top', '0')

                    }
                  }).error(function () {
                    show_hide_sub_attachments('-<?php echo $image_filmstrip_height ?>px');
                    jQuery('.ffwd_sub_attachmenst_thumbnails').html('');
                    jQuery('.ffwd_image_container').css('top', '0')


                  });
                } else {
                  show_hide_sub_attachments('-<?php echo $image_filmstrip_height ?>px');
                  jQuery('.ffwd_sub_attachmenst_thumbnails').html('');
                  jQuery('.ffwd_image_container').css('top', '0')


                }
              }
              else {
                show_hide_sub_attachments('-<?php echo $image_filmstrip_height ?>px');
                jQuery('.ffwd_sub_attachmenst_thumbnails').html('');
                jQuery('.ffwd_image_container').css('top', '0')


              }
              src = result['data'][0]['media']['image']['src'];
              id = result['data'][0]['target']['id'];
            }
            var innhtml = '<span class="ffwd_popup_image_spun1" style="display: table; width: inherit; height: inherit;"><span class="ffwd_popup_image_spun2" style="display: table-cell; vertical-align: middle; text-align: center;">';
            innhtml += '<img style="max-height: ' + cur_height + 'px; max-width: ' + cur_width + 'px;" class="ffwd_popup_image" src="' + src + '" alt="' + data[key]["alt"] + '" />';
            innhtml += '</span></span>';
            jQuery(next_image_class).html(innhtml);
            /* Don't change image when first open lightbox*/
            if (first_time) {
              var view_on_facebook = (typeof data[key]['link'] != 'undefined' && data[key]['link'] != "") ? data[key]['link'] : 'https://www.facebook.com/' + data[key]['object_id'],
                  ffwd_share_url = encodeURIComponent(view_on_facebook);
              jQuery("#ffwd_facebook_a").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + ffwd_share_url);
              jQuery("#ffwd_twitter_a").attr("href", "https://twitter.com/share?url=" + ffwd_share_url);
              jQuery("#ffwd_google_a").attr("href", "https://plus.google.com/share?url=" + ffwd_share_url);
              /* Change view on facebook link */
              jQuery("#ffwd_view_on_facebook").attr("href", view_on_facebook);
              return;
            }
            var cur_img = jQuery(next_image_class).find('img');
            cur_img.one('load', function () {
              ffwd_afterload(current_image_class, next_image_class, direction, key);
            }).each(function () {
              if (this.complete) jQuery(this).load();
            });
          }

        }
        else if(data[key]["type"].indexOf('video') !== -1) {
          /*Hide subattachment*/
          show_hide_sub_attachments('-<?php echo  $image_filmstrip_height ?>px');
          var innhtml = '<span class="ffwd_popup_image_spun1" style="display: table; width: inherit; height: inherit;"><span class="ffwd_popup_image_spun2" style="display: table-cell; vertical-align: middle; text-align: center;">';
          /*Get facebook video source (because it changed for period)*/
          var url_for_video = popup_graph_url.replace('{FB_ID}', data[key]['object_id']);
          url_for_video = url_for_video.replace('{EDGE}', '');
          url_for_video = url_for_video.replace('{FIELDS}', 'fields=source&');
          url_for_video = url_for_video.replace('{OTHER}', '');
          jQuery.getJSON(url_for_video, function(result) {
            var video_src = result['source'];
            innhtml += '<span style="height: ' + cur_height + 'px; width: ' + cur_width + 'px;" class="ffwd_video">';
            if(data[key]['status_type'] == 'shared_story') {
              innhtml += '<iframe src="' + video_src + '&enablejsapi=1&wmode=transparent' + '"' +
                  ' style="'+
                  'max-width:'+'100%'+" !important; "+
                  'max-height:'+'100%'+" !important; "+
                  'width:'+'100%; '+
                  'height:'+ '100%; ' +
                  'margin:0; '+
                  'display:table-cell; vertical-align:middle;"'+
                  'frameborder="0" scrolling="no" allowtransparency="false" allowfullscreen'+
                  ' class="ffwd_popup_iframe"></iframe>';
              innhtml += "</span>";
            }
            else {
              innhtml += '<video class="ffwd_popup_image" src="'+video_src+'" controls autoplay="autoplay"></video>'
              innhtml += "</span>";
            }
            innhtml += '</span></span>';
            jQuery(next_image_class).html(innhtml);
            if(first_time) {
              var ffwd_share_url = encodeURIComponent((typeof data[key]['link'] != 'undefined' && data[key]['link'] != "") ? data[key]['link'] : 'https://www.facebook.com/'+data[key]['object_id']);
              jQuery("#ffwd_facebook_a").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + ffwd_share_url);
              jQuery("#ffwd_twitter_a").attr("href", "https://twitter.com/share?url=" + ffwd_share_url);
              jQuery("#ffwd_google_a").attr("href", "https://plus.google.com/share?url=" + ffwd_share_url);

              /* Change view on facebook link */
              view_on_facebook = 'https://www.facebook.com/'+data[key]['object_id'];
              jQuery("#ffwd_view_on_facebook").attr("href", view_on_facebook);
              return;
            }
            var cur_video = document.querySelector(next_image_class + ' video');
            if(typeof cur_video != 'undefined' && cur_video != null)
              cur_video.onloadeddata = function() {
                /*console.log('can playy');*/
                ffwd_afterload(current_image_class, next_image_class, direction, key);
              };
            else
              ffwd_afterload(current_image_class, next_image_class, direction, key);
          }).error(function () {
            console.log('Unable to get video from facebook');
          });
          return;
        }
        else {
          /*From album*/
          jQuery.getJSON(graph_url_for_album_photo, function(result) {
            var images = result['images'];
            main_src = images[0]['source'];
            var innhtml = '<span class="ffwd_popup_image_spun1" style="display: table; width: inherit; height: inherit;"><span class="ffwd_popup_image_spun2" style="display: table-cell; vertical-align: middle; text-align: center;">';
            innhtml += '<img style="max-height: ' + cur_height + 'px; max-width: ' + cur_width + 'px;" class="ffwd_popup_image" src="'+ main_src + '" alt="' + data[key]["alt"] + '" />';
            innhtml += '</span></span>';
            jQuery(next_image_class).html(innhtml);

            /*Fill create time field*/
            result.type = "";
            jQuery(".ffwd_popup_from_time_post").html(ffwd_time(result));

            /*Fill likes and comments*/
            ffwd_fill_likes_comments(key);

            /*Don't change image when first open lightbox*/
            if(first_time) {
              var view_on_facebook = (typeof result['link'] != 'undefined') ? result['link'] : '',
                  ffwd_share_url = encodeURIComponent(view_on_facebook);
              jQuery("#ffwd_facebook_a").attr("href", "https://www.facebook.com/sharer/sharer.php?u=" + ffwd_share_url);
              jQuery("#ffwd_twitter_a").attr("href", "https://twitter.com/share?url=" + ffwd_share_url);
              jQuery("#ffwd_google_a").attr("href", "https://plus.google.com/share?url=" + ffwd_share_url);

              /* Change view on facebook link */
              jQuery("#ffwd_view_on_facebook").attr("href", view_on_facebook);
              return;
            }

            var cur_img = jQuery(next_image_class).find('img');
            cur_img.one('load', function() {
              ffwd_afterload(current_image_class, next_image_class, direction, key, true, result['link']);
            }).each(function() {
              if(this.complete) jQuery(this).load();
            });
          });
        }
      }
      /* Open with fullscreen.*/
      function ffwd_open_with_fullscreen() {
        var comment_container_width = 0;
        if (jQuery(".ffwd_object_info_container").hasClass("ffwd_open")) {
          comment_container_width = jQuery(".ffwd_object_info_container").width();
        }
        ffwd_popup_current_width = jQuery(window).width();
        ffwd_popup_current_height = jQuery(window).height();
        jQuery("#spider_popup_wrap").css({
          width: jQuery(window).width(),
          height: jQuery(window).height(),
          left: 0,
          top: 0,
          margin: 0,
          zIndex: 100000
        });
        jQuery(".ffwd_image_wrap").css({width: (jQuery(window).width() - comment_container_width)});
        jQuery(".ffwd_image_container").css({height: (ffwd_popup_current_height - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>), width: ffwd_popup_current_width - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>});
        jQuery(".ffwd_popup_image").css({
          maxWidth: jQuery(window).width() - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
          maxHeight: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
        },  {
          complete: function () {  }
        });
        jQuery(".ffwd_popup_video").css({
          width: jQuery(window).width() - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
          height: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
        },  {
          complete: function () {  }
        });
        jQuery(".ffwd_video").css({
          width: jQuery(window).width() - comment_container_width - <?php echo ($filmstrip_direction == 'vertical' ? $image_filmstrip_width : 0); ?>,
          height: jQuery(window).height() - <?php echo ($filmstrip_direction == 'horizontal' ? $image_filmstrip_height : 0); ?>
        });
        jQuery(".ffwd_filmstrip_container").css({<?php echo $width_or_height; ?>: jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?>});
        jQuery(".ffwd_filmstrip").css({<?php echo $width_or_height; ?>: jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40});

        /*if content type is timeline*/
        jQuery(".ffwd_sub_attachmenst").css({width: jQuery(window).width() - comment_container_width - 40});

        /* Set filmstrip initial position.*/
        ffwd_set_filmstrip_pos(jQuery(window).<?php echo $width_or_height; ?>() - <?php echo ($filmstrip_direction == 'horizontal' ? 'comment_container_width' : 0); ?> - 40);

        jQuery(".ffwd_resize-full").attr("class", "ffwd_ctrl_btn ffwd_resize-full");
        jQuery(".ffwd_resize-full").attr("title", "<?php echo __('Restore', 'bwg'); ?>");
        jQuery(".spider_popup_close").attr("class", "ffwd_ctrl_btn spider_popup_close_fullscreen");
      }

      function ffwd_play() {
        window.clearInterval(ffwd_playInterval);
        ffwd_playInterval = setInterval(function () {
          if (!data[parseInt(jQuery('#ffwd_current_image_key').val()) + 1]) {
            /* Wrap around.*/
            ffwd_change_image(parseInt(jQuery('#ffwd_current_image_key').val()), 0, data);
            return;
          }
          ffwd_change_image(parseInt(jQuery('#ffwd_current_image_key').val()), parseInt(jQuery('#ffwd_current_image_key').val()) + 1, data)
        }, '<?php echo $slideshow_interval * 1000; ?>');
      }
      jQuery(window).focus(function() {
        ffwd_event_stack = [];
        if (!jQuery(".ffwd_play_pause").hasClass("ffwd_play")) {
          ffwd_play();
        }
      });
      jQuery(window).blur(function() {
        ffwd_event_stack = [];
        window.clearInterval(ffwd_playInterval);
      });
    </script>
    <?php
    die();
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
