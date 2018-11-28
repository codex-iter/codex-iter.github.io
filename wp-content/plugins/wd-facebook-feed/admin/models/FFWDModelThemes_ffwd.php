<?php

class FFWDModelThemes_ffwd {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $per_page = 20;
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
    $user = get_current_user_id();
    $screen = get_current_screen();
    $option = $screen->get_option('per_page', 'option');

    $this->per_page = get_user_meta($user, $option, true);

    if ( empty ( $this->per_page) || $this->per_page < 1 ) {
      $this->per_page = $screen->get_option( 'per_page', 'default' );
    }
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function get_rows_data() {
    global $wpdb;
    $where = ((isset($_POST['search_value']) && (esc_html($_POST['search_value']) != '')) ? 'WHERE name LIKE "%' . esc_html($_POST['search_value']) . '%"' : '');
    $asc_or_desc = ((isset($_POST['asc_or_desc'])) ? esc_html($_POST['asc_or_desc']) : 'asc');
    $asc_or_desc = ($asc_or_desc != 'asc') ? 'desc' : 'asc';
    $order_by = ' ORDER BY ' . ((isset($_POST['order_by']) && esc_html($_POST['order_by']) != '') ? esc_html($_POST['order_by']) : 'id') . ' ' . $asc_or_desc;
    if (isset($_POST['page_number']) && $_POST['page_number']) {
      $limit = ((int) $_POST['page_number'] - 1) * $this->per_page;
    }
    else {
      $limit = 0;
    }
    $query = "SELECT * FROM " . $wpdb->prefix . "wd_fb_theme " . $where . $order_by . " LIMIT " . $limit . ",".$this->per_page;
    $rows = $wpdb->get_results($query);
    return $rows;
  }

  public function get_row_data($id, $reset) {
    global $wpdb;
    if ($id != 0) {
      $row = $wpdb->get_row($wpdb->prepare('SELECT id,name,default_theme FROM ' . $wpdb->prefix . 'wd_fb_theme WHERE id="%d"', $id));
      $params = $wpdb->get_var($wpdb->prepare('SELECT params FROM ' . $wpdb->prefix . 'wd_fb_theme WHERE id="%d"', $id));


      $row = (object) array_merge((array)$row, (array)json_decode($params));

      if ($reset) {
        if (!$row->default_theme) {
          $row_id = $row->id;
          $row_name = $row->name;
          $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_theme WHERE default_theme="%d"', 1));

			$row = (object) array_merge((array)$row, (array)json_decode(  $row->params));
			unset($row->params);

          $row->id = $row_id;
          $row->name = $row_name;
          $row->default_theme = FALSE;
        }
        else {




			$row->thumb_margin='10';
			$row->thumb_padding='2';
			$row->thumb_border_radius='2px';
			$row->thumb_border_width='1';
			$row->thumb_border_style='none';
			$row->thumb_border_color='000000';
			$row->thumb_bg_color='FFFFFF';
			$row->thumbs_bg_color='FFFFFF';
			$row->thumb_bg_transparent='100';
			$row->thumb_box_shadow='0px 0px 1px #000000';
			$row->thumb_transparent='100';
			$row->thumb_align='center';
			$row->thumb_hover_effect='rotate';
			$row->thumb_hover_effect_value='2deg';
			$row->thumb_transition='1';
			$row->thumb_title_font_color='797979';
			$row->thumb_title_font_style='inherit';
			$row->thumb_title_pos='bottom';
			$row->thumb_title_font_size='11';
			$row->thumb_title_font_weight='normal';
			$row->thumb_title_margin='10';
			$row->thumb_title_shadow='';
			$row->thumb_like_comm_pos='bottom';
			$row->thumb_like_comm_font_size='11';
			$row->thumb_like_comm_font_color='FFFFFF';
			$row->thumb_like_comm_font_style='inherit';
			$row->thumb_like_comm_font_weight='normal';
			$row->thumb_like_comm_shadow='0px 0px 1px #000000';
			$row->masonry_thumb_padding='4';
			$row->masonry_thumb_border_radius='2px';
			$row->masonry_thumb_border_width='1';
			$row->masonry_thumb_border_style='solid';
			$row->masonry_thumb_border_color='FFFFFF';
			$row->masonry_thumbs_bg_color='FFFFFF';
			$row->masonry_thumb_bg_transparent='100';
			$row->masonry_thumb_transparent='100';
			$row->masonry_thumb_align='center';
			$row->masonry_thumb_hover_effect='scale';
			$row->masonry_thumb_hover_effect_value='1.1';
			$row->masonry_thumb_transition='1';
			$row->masonry_description_font_size='11';
			$row->masonry_description_color='A3A3A3';
			$row->masonry_description_font_style='inherit';
			$row->masonry_like_comm_pos='bottom';
			$row->masonry_like_comm_font_size='11';
			$row->masonry_like_comm_font_color='FFFFFF';
			$row->masonry_like_comm_font_style='inherit';
			$row->masonry_like_comm_font_weight='normal';
			$row->masonry_like_comm_shadow='0px 0px 1px #000000';
			$row->blog_style_align='left';
			$row->blog_style_bg_color='FFFFFF';
			$row->blog_style_fd_name_bg_color='000000';
			$row->blog_style_fd_name_align='center';
			$row->blog_style_fd_name_padding='10';
			$row->blog_style_fd_name_color='FFFFFF';
			$row->blog_style_fd_name_size='15';
			$row->blog_style_fd_name_font_weight='normal';
			$row->blog_style_fd_icon='';
			$row->blog_style_fd_icon_color='';
			$row->blog_style_fd_icon_size='';
			$row->blog_style_transparent='100';
			$row->blog_style_obj_img_align='center';
			$row->blog_style_margin='10';
			$row->blog_style_box_shadow='';
			$row->blog_style_border_width='1';
			$row->blog_style_border_style='solid';
			$row->blog_style_border_color='C9C9C9';
			$row->blog_style_border_type='top';
			$row->blog_style_border_radius='';
			$row->blog_style_obj_icons_color='gray';
			$row->blog_style_obj_date_pos='after';
			$row->blog_style_obj_font_family='inherit';
			$row->blog_style_obj_info_bg_color='FFFFFF';
			$row->blog_style_page_name_color='000000';
			$row->blog_style_obj_page_name_size='13';
			$row->blog_style_obj_page_name_font_weight='bold';
			$row->blog_style_obj_story_color='000000';
			$row->blog_style_obj_story_size='11';
			$row->blog_style_obj_story_font_weight='normal';
			$row->blog_style_obj_place_color='000000';
			$row->blog_style_obj_place_size='13';
			$row->blog_style_obj_place_font_weight='normal';
			$row->blog_style_obj_name_color='000000';
			$row->blog_style_obj_name_size='13';
			$row->blog_style_obj_name_font_weight='bold';
			$row->blog_style_obj_message_color='000000';
			$row->blog_style_obj_message_size='11';
			$row->blog_style_obj_message_font_weight='normal';
			$row->blog_style_obj_hashtags_color='000000';
			$row->blog_style_obj_hashtags_size='12';
			$row->blog_style_obj_hashtags_font_weight='normal';
			$row->blog_style_obj_likes_social_bg_color='EAEAEA';
			$row->blog_style_obj_likes_social_color='656565';
			$row->blog_style_obj_likes_social_size='11';
			$row->blog_style_obj_likes_social_font_weight='normal';
			$row->blog_style_obj_comments_bg_color='FFFFFF';
			$row->blog_style_obj_comments_color='000000';
			$row->blog_style_obj_comments_font_family='inherit';
			$row->blog_style_obj_comments_font_size='11';
			$row->blog_style_obj_users_font_color='000000';
			$row->blog_style_obj_comments_social_font_weight='normal';
			$row->blog_style_obj_comment_border_width='1';
			$row->blog_style_obj_comment_border_style='solid';
			$row->blog_style_obj_comment_border_color='C9C9C9';
			$row->blog_style_obj_comment_border_type='top';
			$row->blog_style_evt_str_color='000000';
			$row->blog_style_evt_str_size='11';
			$row->blog_style_evt_str_font_weight='normal';
			$row->blog_style_evt_ctzpcn_color='000000';
			$row->blog_style_evt_ctzpcn_size='11';
			$row->blog_style_evt_ctzpcn_font_weight='normal';
			$row->blog_style_evt_map_color='000000';
			$row->blog_style_evt_map_size='11';
			$row->blog_style_evt_map_font_weight='normal';
			$row->blog_style_evt_date_color='000000';
			$row->blog_style_evt_date_size='11';
			$row->blog_style_evt_date_font_weight='normal';
			$row->blog_style_evt_info_font_family='inherit';
			$row->album_compact_back_font_color='000000';
			$row->album_compact_back_font_style='inherit';
			$row->album_compact_back_font_size='16';
			$row->album_compact_back_font_weight='bold';
			$row->album_compact_back_padding='0';
			$row->album_compact_title_font_color='797979';
			$row->album_compact_title_font_style='inherit';
			$row->album_compact_thumb_title_pos='bottom';
			$row->album_compact_title_font_size='13';
			$row->album_compact_title_font_weight='normal';
			$row->album_compact_title_margin='2px';
			$row->album_compact_title_shadow='0px 0px 0px #888888';
			$row->album_compact_thumb_margin='4';
			$row->album_compact_thumb_padding='0';
			$row->album_compact_thumb_border_radius='0';
			$row->album_compact_thumb_border_width='0';
			$row->album_compact_thumb_border_style='none';
			$row->album_compact_thumb_border_color='CCCCCC';
			$row->album_compact_thumb_bg_color='FFFFFF';
			$row->album_compact_thumbs_bg_color='FFFFFF';
			$row->album_compact_thumb_bg_transparent='0';
			$row->album_compact_thumb_box_shadow='0px 0px 0px #888888';
			$row->album_compact_thumb_transparent='100';
			$row->album_compact_thumb_align='center';
			$row->album_compact_thumb_hover_effect='scale';
			$row->album_compact_thumb_hover_effect_value='1.1';
			$row->album_compact_thumb_transition='0';
			$row->lightbox_overlay_bg_color='000000';
			$row->lightbox_overlay_bg_transparent='70';
			$row->lightbox_bg_color='000000';
			$row->lightbox_ctrl_btn_pos='bottom';
			$row->lightbox_ctrl_btn_align='center';
			$row->lightbox_ctrl_btn_height='20';
			$row->lightbox_ctrl_btn_margin_top='10';
			$row->lightbox_ctrl_btn_margin_left='7';
			$row->lightbox_ctrl_btn_transparent='100';
			$row->lightbox_ctrl_btn_color='';
			$row->lightbox_toggle_btn_height='14';
			$row->lightbox_toggle_btn_width='100';
			$row->lightbox_ctrl_cont_bg_color='000000';
			$row->lightbox_ctrl_cont_transparent='65';
			$row->lightbox_ctrl_cont_border_radius='4';
			$row->lightbox_close_btn_transparent='100';
			$row->lightbox_close_btn_bg_color='000000';
			$row->lightbox_close_btn_border_width='2';
			$row->lightbox_close_btn_border_radius='16px';
			$row->lightbox_close_btn_border_style='none';
			$row->lightbox_close_btn_border_color='FFFFFF';
			$row->lightbox_close_btn_box_shadow='0';
			$row->lightbox_close_btn_color='';
			$row->lightbox_close_btn_size='10';
			$row->lightbox_close_btn_width='20';
			$row->lightbox_close_btn_height='20';
			$row->lightbox_close_btn_top='-10';
			$row->lightbox_close_btn_right='-10';
			$row->lightbox_close_btn_full_color='';
			$row->lightbox_rl_btn_bg_color='000000';
			$row->lightbox_rl_btn_transparent='80';
			$row->lightbox_rl_btn_border_radius='20px';
			$row->lightbox_rl_btn_border_width='0';
			$row->lightbox_rl_btn_border_style='none';
			$row->lightbox_rl_btn_border_color='FFFFFF';
			$row->lightbox_rl_btn_box_shadow='';
			$row->lightbox_rl_btn_color='';
			$row->lightbox_rl_btn_height='40';
			$row->lightbox_rl_btn_width='40';
			$row->lightbox_rl_btn_size='20';
			$row->lightbox_close_rl_btn_hover_color='';
			$row->lightbox_obj_pos='left';
			$row->lightbox_obj_width='350';
			$row->lightbox_obj_icons_color='gray';
			$row->lightbox_obj_date_pos='after';
			$row->lightbox_obj_font_family='inherit';
			$row->lightbox_obj_info_bg_color='E2E2E2';
			$row->lightbox_page_name_color='4B4B4B';
			$row->lightbox_obj_page_name_size='14';
			$row->lightbox_obj_page_name_font_weight='bold';
			$row->lightbox_obj_story_color='4B4B4B';
			$row->lightbox_obj_story_size='11';
			$row->lightbox_obj_story_font_weight='normal';
			$row->lightbox_obj_place_color='000000';
			$row->lightbox_obj_place_size='13';
			$row->lightbox_obj_place_font_weight='normal';
			$row->lightbox_obj_name_color='4B4B4B';
			$row->lightbox_obj_name_size='14';
			$row->lightbox_obj_name_font_weight='bold';
			$row->lightbox_obj_message_color='000000';
			$row->lightbox_obj_message_size='11';
			$row->lightbox_obj_message_font_weight='normal';
			$row->lightbox_obj_hashtags_color='000000';
			$row->lightbox_obj_hashtags_size='12';
			$row->lightbox_obj_hashtags_font_weight='normal';
			$row->lightbox_obj_likes_social_bg_color='878787';
			$row->lightbox_obj_likes_social_color='FFFFFF';
			$row->lightbox_obj_likes_social_size='11';
			$row->lightbox_obj_likes_social_font_weight='normal';
			$row->lightbox_obj_comments_bg_color='EAEAEA';
			$row->lightbox_obj_comments_color='4A4A4A';
			$row->lightbox_obj_comments_font_family='inherit';
			$row->lightbox_obj_comments_font_size='11';
			$row->lightbox_obj_users_font_color='4B4B4B';
			$row->lightbox_obj_comments_social_font_weight='normal';
			$row->lightbox_obj_comment_border_width='1';
			$row->lightbox_obj_comment_border_style='solid';
			$row->lightbox_obj_comment_border_color='C9C9C9';
			$row->lightbox_obj_comment_border_type='top';
			$row->lightbox_filmstrip_pos='top';
			$row->lightbox_filmstrip_rl_bg_color='3B3B3B';
			$row->lightbox_filmstrip_rl_btn_size='20';
			$row->lightbox_filmstrip_rl_btn_color='';
			$row->lightbox_filmstrip_thumb_margin='0 1px';
			$row->lightbox_filmstrip_thumb_border_width='1';
			$row->lightbox_filmstrip_thumb_border_style='solid';
			$row->lightbox_filmstrip_thumb_border_color='000000';
			$row->lightbox_filmstrip_thumb_border_radius='0';
			$row->lightbox_filmstrip_thumb_deactive_transparent='80';
			$row->lightbox_filmstrip_thumb_active_border_width='0';
			$row->lightbox_filmstrip_thumb_active_border_color='FFFFFF';
			$row->lightbox_rl_btn_style='';
			$row->lightbox_evt_str_color='000000';
			$row->lightbox_evt_str_size='11';
			$row->lightbox_evt_str_font_weight='normal';
			$row->lightbox_evt_ctzpcn_color='000000';
			$row->lightbox_evt_ctzpcn_size='11';
			$row->lightbox_evt_ctzpcn_font_weight='normal';
			$row->lightbox_evt_map_color='000000';
			$row->lightbox_evt_map_size='11';
			$row->lightbox_evt_map_font_weight='normal';
			$row->lightbox_evt_date_color='000000';
			$row->lightbox_evt_date_size='11';
			$row->lightbox_evt_date_font_weight='normal';
			$row->lightbox_evt_info_font_family='inherit';
			$row->page_nav_position='bottom';
			$row->page_nav_align='center';
			$row->page_nav_number='0';
			$row->page_nav_font_size='12';
			$row->page_nav_font_style='inherit';
			$row->page_nav_font_color='666666';
			$row->page_nav_font_weight='bold';
			$row->page_nav_border_width='1';
			$row->page_nav_border_style='solid';
			$row->page_nav_border_color='E3E3E3';
			$row->page_nav_border_radius='0';
			$row->page_nav_margin='0';
			$row->page_nav_padding='3px 6px';
			$row->page_nav_button_bg_color='FFFFFF';
			$row->page_nav_button_bg_transparent='100';
			$row->page_nav_box_shadow='0';
			$row->page_nav_button_transition='1';
			$row->page_nav_button_text='0';
			$row->lightbox_obj_icons_color_likes_comments_count='white';
        }
      }
    }
    else {

      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_theme WHERE default_theme="%d"', 1));
      $row = (object) array_merge((array)$row, (array)json_decode(  $row->params));
      unset($row->params);

      $row->id = 0;
      $row->name = '';
      $row->default_theme = 0;
    }



    return $row;
  }

  public function page_nav() {
    global $wpdb;
    $where = ((isset($_POST['search_value']) && (esc_html($_POST['search_value']) != '')) ? 'WHERE name LIKE "%' . esc_html($_POST['search_value']) . '%"'  : '');
    $query = "SELECT COUNT(*) FROM " . $wpdb->prefix . "wd_fb_theme " . $where;
    $total = $wpdb->get_var($query);
    $page_nav['total'] = $total;
    if (isset($_POST['page_number']) && $_POST['page_number']) {
      $limit = ((int) $_POST['page_number'] - 1) * $this->per_page;
    }
    else {
      $limit = 0;
    }
    $page_nav['limit'] = (int) ($limit / $this->per_page + 1);
    return $page_nav;
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function per_page(){
    return $this->per_page;

  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}
