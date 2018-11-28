<?php

class FFWDControllerThemes_ffwd
{
    ////////////////////////////////////////////////////////////////////////////////////////
    // Events                                                                             //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Constants                                                                          //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Variables                                                                          //
    ////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////
    // Constructor & Destructor                                                           //
    ////////////////////////////////////////////////////////////////////////////////////////
    public function __construct()
    {
    }
    ////////////////////////////////////////////////////////////////////////////////////////
    // Public Methods                                                                     //
    ////////////////////////////////////////////////////////////////////////////////////////
    public function execute()
    {
        $task = WDW_FFWD_Library::get('task');
        $id = WDW_FFWD_Library::get('current_id', 0);
        $message = WDW_FFWD_Library::get('message');
        if ($task != '') {
            if (!WDW_FFWD_Library::verify_nonce('themes_ffwd')) {
                die('Sorry, your nonce did not verify.');
            }
        }
        echo WDW_FFWD_Library::message_id($message);
        if (method_exists($this, $task)) {
            $this->$task($id);
        } else {
            $this->display();
        }
    }

    public function display()
    {
        require_once WD_FFWD_DIR . "/admin/models/FFWDModelThemes_ffwd.php";
        $model = new FFWDModelThemes_ffwd();

        require_once WD_FFWD_DIR . "/admin/views/FFWDViewThemes_ffwd.php";
        $view = new FFWDViewThemes_ffwd($model);
        $view->display();
    }

    public function add()
    {
        require_once WD_FFWD_DIR . "/admin/models/FFWDModelThemes_ffwd.php";
        $model = new FFWDModelThemes_ffwd();

        require_once WD_FFWD_DIR . "/admin/views/FFWDViewThemes_ffwd.php";
        $view = new FFWDViewThemes_ffwd($model);
        $view->edit(0, false);
    }

    public function edit()
    {
        require_once WD_FFWD_DIR . "/admin/models/FFWDModelThemes_ffwd.php";
        $model = new FFWDModelThemes_ffwd();

        require_once WD_FFWD_DIR . "/admin/views/FFWDViewThemes_ffwd.php";
        $view = new FFWDViewThemes_ffwd($model);
        $id = WDW_FFWD_Library::get('current_id', 0);
        $view->edit($id, false);
    }

    public function reset()
    {
        require_once WD_FFWD_DIR . "/admin/models/FFWDModelThemes_ffwd.php";
        $model = new FFWDModelThemes_ffwd();

        require_once WD_FFWD_DIR . "/admin/views/FFWDViewThemes_ffwd.php";
        $view = new FFWDViewThemes_ffwd($model);
        $id = WDW_FFWD_Library::get('current_id', 0);
        echo WDW_FFWD_Library::message('Changes must be saved.', 'error');
        $view->edit($id, TRUE);
    }

    public function save()
    {
        $message = $this->save_db();
        $page = WDW_FFWD_Library::get('page');
        $query_url = wp_nonce_url(admin_url('admin.php'), 'themes_ffwd', 'ffwd_nonce');
        $query_url = add_query_arg(array('page' => $page, 'task' => 'display', 'message' => $message), $query_url);
        WDW_FFWD_Library::spider_redirect($query_url);
    }

    public function apply()
    {
        $message = $this->save_db();
        global $wpdb;
        $id = (int)$wpdb->get_var('SELECT MAX(`id`) FROM ' . $wpdb->prefix . 'wd_fb_theme');
        $current_id = WDW_FFWD_Library::get('current_id', $id);
        $page = WDW_FFWD_Library::get('page');
        $current_type = WDW_FFWD_Library::get('current_type', 'Thumbnail');
        $query_url = wp_nonce_url(admin_url('admin.php'), 'themes_ffwd', 'ffwd_nonce');
        $query_url = add_query_arg(array('page' => $page, 'task' => 'edit', 'current_id' => $current_id, 'message' => $message, 'current_type' => $current_type), $query_url);
        WDW_FFWD_Library::spider_redirect($query_url);
    }

    public function save_db()
    {
        global $wpdb;
        $id = (int)WDW_FFWD_Library::get('current_id', 0);
        $name = (isset($_POST['name']) ? esc_html(stripslashes($_POST['name'])) : 'exaple');
        $default_theme = (isset($_POST['default_theme']) ? esc_html(stripslashes($_POST['default_theme'])) : 0);

        $theme_params_keys = array('thumb_margin',
            'thumb_padding',
            'thumb_border_radius',
            'thumb_border_width',
            'thumb_border_style',
            'thumb_border_color',
            'thumb_bg_color',
            'thumbs_bg_color',
            'thumb_bg_transparent',
            'thumb_box_shadow',
            'thumb_transparent',
            'thumb_align',
            'thumb_hover_effect',
            'thumb_hover_effect_value',
            'thumb_transition',
            'thumb_title_font_color',
            'thumb_title_font_style',
            'thumb_title_pos',
            'thumb_title_font_size',
            'thumb_title_font_weight',
            'thumb_title_margin',
            'thumb_title_shadow',
            'thumb_like_comm_pos',
            'thumb_like_comm_font_size',
            'thumb_like_comm_font_color',
            'thumb_like_comm_font_style',
            'thumb_like_comm_font_weight',
            'thumb_like_comm_shadow',
            'masonry_thumb_padding',
            'masonry_thumb_border_radius',
            'masonry_thumb_border_width',
            'masonry_thumb_border_style',
            'masonry_thumb_border_color',
            'masonry_thumbs_bg_color',
            'masonry_thumb_bg_transparent',
            'masonry_thumb_transparent',
            'masonry_thumb_align',
            'masonry_thumb_hover_effect',
            'masonry_thumb_hover_effect_value',
            'masonry_thumb_transition',
            'masonry_description_font_size',
            'masonry_description_color',
            'masonry_description_font_style',
            'masonry_like_comm_pos',
            'masonry_like_comm_font_size',
            'masonry_like_comm_font_color',
            'masonry_like_comm_font_style',
            'masonry_like_comm_font_weight',
            'masonry_like_comm_shadow',
            'blog_style_align',
            'blog_style_bg_color',
            'blog_style_fd_name_bg_color',
            'blog_style_fd_name_align',
            'blog_style_fd_name_padding',
            'blog_style_fd_name_color',
            'blog_style_fd_name_size',
            'blog_style_fd_name_font_weight',
            'blog_style_fd_icon',
            'blog_style_fd_icon_color',
            'blog_style_fd_icon_size',
            'blog_style_transparent',
            'blog_style_obj_img_align',
            'blog_style_margin',
            'blog_style_box_shadow',
            'blog_style_border_width',
            'blog_style_border_style',
            'blog_style_border_color',
            'blog_style_border_type',
            'blog_style_border_radius',
            'blog_style_obj_icons_color',
            'blog_style_obj_date_pos',
            'blog_style_obj_font_family',
            'blog_style_obj_info_bg_color',
            'blog_style_page_name_color',
            'blog_style_obj_page_name_size',
            'blog_style_obj_page_name_font_weight',
            'blog_style_obj_story_color',
            'blog_style_obj_story_size',
            'blog_style_obj_story_font_weight',
            'blog_style_obj_place_color',
            'blog_style_obj_place_size',
            'blog_style_obj_place_font_weight',
            'blog_style_obj_name_color',
            'blog_style_obj_name_size',
            'blog_style_obj_name_font_weight',
            'blog_style_obj_message_color',
            'blog_style_obj_message_size',
            'blog_style_obj_message_font_weight',
            'blog_style_obj_hashtags_color',
            'blog_style_obj_hashtags_size',
            'blog_style_obj_hashtags_font_weight',
            'blog_style_obj_likes_social_bg_color',
            'blog_style_obj_likes_social_color',
            'blog_style_obj_likes_social_size',
            'blog_style_obj_likes_social_font_weight',
            'blog_style_obj_comments_bg_color',
            'blog_style_obj_comments_color',
            'blog_style_obj_comments_font_family',
            'blog_style_obj_comments_font_size',
            'blog_style_obj_users_font_color',
            'blog_style_obj_comments_social_font_weight',
            'blog_style_obj_comment_border_width',
            'blog_style_obj_comment_border_style',
            'blog_style_obj_comment_border_color',
            'blog_style_obj_comment_border_type',
            'blog_style_evt_str_color',
            'blog_style_evt_str_size',
            'blog_style_evt_str_font_weight',
            'blog_style_evt_ctzpcn_color',
            'blog_style_evt_ctzpcn_size',
            'blog_style_evt_ctzpcn_font_weight',
            'blog_style_evt_map_color',
            'blog_style_evt_map_size',
            'blog_style_evt_map_font_weight',
            'blog_style_evt_date_color',
            'blog_style_evt_date_size',
            'blog_style_evt_date_font_weight',
            'blog_style_evt_info_font_family',
            'album_compact_back_font_color',
            'album_compact_back_font_style',
            'album_compact_back_font_size',
            'album_compact_back_font_weight',
            'album_compact_back_padding',
            'album_compact_title_font_color',
            'album_compact_title_font_style',
            'album_compact_thumb_title_pos',
            'album_compact_title_font_size',
            'album_compact_title_font_weight',
            'album_compact_title_margin',
            'album_compact_title_shadow',
            'album_compact_thumb_margin',
            'album_compact_thumb_padding',
            'album_compact_thumb_border_radius',
            'album_compact_thumb_border_width',
            'album_compact_thumb_border_style',
            'album_compact_thumb_border_color',
            'album_compact_thumb_bg_color',
            'album_compact_thumbs_bg_color',
            'album_compact_thumb_bg_transparent',
            'album_compact_thumb_box_shadow',
            'album_compact_thumb_transparent',
            'album_compact_thumb_align',
            'album_compact_thumb_hover_effect',
            'album_compact_thumb_hover_effect_value',
            'album_compact_thumb_transition',
            'lightbox_overlay_bg_color',
            'lightbox_overlay_bg_transparent',
            'lightbox_bg_color',
            'lightbox_ctrl_btn_pos',
            'lightbox_ctrl_btn_align',
            'lightbox_ctrl_btn_height',
            'lightbox_ctrl_btn_margin_top',
            'lightbox_ctrl_btn_margin_left',
            'lightbox_ctrl_btn_transparent',
            'lightbox_ctrl_btn_color',
            'lightbox_toggle_btn_height',
            'lightbox_toggle_btn_width',
            'lightbox_ctrl_cont_bg_color',
            'lightbox_ctrl_cont_transparent',
            'lightbox_ctrl_cont_border_radius',
            'lightbox_close_btn_transparent',
            'lightbox_close_btn_bg_color',
            'lightbox_close_btn_border_width',
            'lightbox_close_btn_border_radius',
            'lightbox_close_btn_border_style',
            'lightbox_close_btn_border_color',
            'lightbox_close_btn_box_shadow',
            'lightbox_close_btn_color',
            'lightbox_close_btn_size',
            'lightbox_close_btn_width',
            'lightbox_close_btn_height',
            'lightbox_close_btn_top',
            'lightbox_close_btn_right',
            'lightbox_close_btn_full_color',
            'lightbox_rl_btn_bg_color',
            'lightbox_rl_btn_transparent',
            'lightbox_rl_btn_border_radius',
            'lightbox_rl_btn_border_width',
            'lightbox_rl_btn_border_style',
            'lightbox_rl_btn_border_color',
            'lightbox_rl_btn_box_shadow',
            'lightbox_rl_btn_color',
            'lightbox_rl_btn_height',
            'lightbox_rl_btn_width',
            'lightbox_rl_btn_size',
            'lightbox_close_rl_btn_hover_color',
            'lightbox_obj_pos',
            'lightbox_obj_width',
            'lightbox_obj_icons_color',
            'lightbox_obj_date_pos',
            'lightbox_obj_font_family',
            'lightbox_obj_info_bg_color',
            'lightbox_page_name_color',
            'lightbox_obj_page_name_size',
            'lightbox_obj_page_name_font_weight',
            'lightbox_obj_story_color',
            'lightbox_obj_story_size',
            'lightbox_obj_story_font_weight',
            'lightbox_obj_place_color',
            'lightbox_obj_place_size',
            'lightbox_obj_place_font_weight',
            'lightbox_obj_name_color',
            'lightbox_obj_name_size',
            'lightbox_obj_name_font_weight',
            'lightbox_obj_message_color',
            'lightbox_obj_message_size',
            'lightbox_obj_message_font_weight',
            'lightbox_obj_hashtags_color',
            'lightbox_obj_hashtags_size',
            'lightbox_obj_hashtags_font_weight',
            'lightbox_obj_likes_social_bg_color',
            'lightbox_obj_likes_social_color',
            'lightbox_obj_likes_social_size',
            'lightbox_obj_likes_social_font_weight',
            'lightbox_obj_comments_bg_color',
            'lightbox_obj_comments_color',
            'lightbox_obj_comments_font_family',
            'lightbox_obj_comments_font_size',
            'lightbox_obj_users_font_color',
            'lightbox_obj_comments_social_font_weight',
            'lightbox_obj_comment_border_width',
            'lightbox_obj_comment_border_style',
            'lightbox_obj_comment_border_color',
            'lightbox_obj_comment_border_type',
            'lightbox_filmstrip_pos',
            'lightbox_filmstrip_rl_bg_color',
            'lightbox_filmstrip_rl_btn_size',
            'lightbox_filmstrip_rl_btn_color',
            'lightbox_filmstrip_thumb_margin',
            'lightbox_filmstrip_thumb_border_width',
            'lightbox_filmstrip_thumb_border_style',
            'lightbox_filmstrip_thumb_border_color',
            'lightbox_filmstrip_thumb_border_radius',
            'lightbox_filmstrip_thumb_deactive_transparent',
            'lightbox_filmstrip_thumb_active_border_width',
            'lightbox_filmstrip_thumb_active_border_color',
            'lightbox_rl_btn_style',
            'lightbox_evt_str_color',
            'lightbox_evt_str_size',
            'lightbox_evt_str_font_weight',
            'lightbox_evt_ctzpcn_color',
            'lightbox_evt_ctzpcn_size',
            'lightbox_evt_ctzpcn_font_weight',
            'lightbox_evt_map_color',
            'lightbox_evt_map_size',
            'lightbox_evt_map_font_weight',
            'lightbox_evt_date_color',
            'lightbox_evt_date_size',
            'lightbox_evt_date_font_weight',
            'lightbox_evt_info_font_family',
            'page_nav_position',
            'page_nav_align',
            'page_nav_number',
            'page_nav_font_size',
            'page_nav_font_style',
            'page_nav_font_color',
            'page_nav_font_weight',
            'page_nav_border_width',
            'page_nav_border_style',
            'page_nav_border_color',
            'page_nav_border_radius',
            'page_nav_margin',
            'page_nav_padding',
            'page_nav_button_bg_color',
            'page_nav_button_bg_transparent',
            'page_nav_box_shadow',
            'page_nav_button_transition',
            'page_nav_button_text',
            'lightbox_obj_icons_color_likes_comments_count');


        $params = array();
        foreach ($theme_params_keys as $theme_param_key) {

            $params[$theme_param_key] = (isset($_POST[$theme_param_key]) ? esc_html(stripslashes($_POST[$theme_param_key])) : '');
        }


        if ($id != 0) {
            $save = $wpdb->update($wpdb->prefix . 'wd_fb_theme', array(
                'name' => $name,
                'params' => json_encode($params),
                'default_theme' => $default_theme,
            ), array('id' => $id));
        } else {
            $save = $wpdb->insert($wpdb->prefix . 'wd_fb_theme', array(
                'name' => $name,
                'params' => json_encode($params),
                'default_theme' => $default_theme,
            ), array(
                '%s',
                '%s',
                '%d',
            ));
        }
        if ($save !== FALSE) {
            return 1;
        } else {
            return 2;
        }
    }

    public function delete($id)
    {
        global $wpdb;
        $isDefault = $wpdb->get_var('SELECT default_theme FROM ' . $wpdb->prefix . 'wd_fb_theme WHERE id=' . $id);
        if ($isDefault) {
            echo WDW_FFWD_Library::message("You can't delete default theme", 'error');
        } else {
            $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wd_fb_theme WHERE id="%d"', $id);
            if ($wpdb->query($query)) {
                echo WDW_FFWD_Library::message('Item Succesfully Deleted.', 'updated');
                $message = 3;
            } else {
                $message = 2;
            }
        }
        $page = WDW_FFWD_Library::get('page');
        $query_url = wp_nonce_url(admin_url('admin.php'), 'themes_ffwd', 'ffwd_nonce');
        $query_url = add_query_arg(array('page' => $page, 'task' => 'display', 'message' => $message), $query_url);
        WDW_FFWD_Library::spider_redirect($query_url);
    }

    public function delete_all()
    {
        global $wpdb;
        $flag = FALSE;
        $isDefault = FALSE;
        $tag_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'wd_fb_theme');
        foreach ($tag_ids_col as $tag_id) {
            if (isset($_POST['check_' . $tag_id])) {
                $isDefault = $wpdb->get_var('SELECT default_theme FROM ' . $wpdb->prefix . 'wd_fb_theme WHERE id=' . $tag_id);
                if ($isDefault) {
                    $message = 4;
                } else {
                    $flag = TRUE;
                    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wd_fb_theme WHERE id="%d"', $tag_id);
                    $wpdb->query($query);
                }
            }
        }
        if ($flag) {
            $message = 5;
        } else {
            $message = 6;
        }
        $page = WDW_FFWD_Library::get('page');
        $query_url = wp_nonce_url(admin_url('admin.php'), 'themes_ffwd', 'ffwd_nonce');
        $query_url = add_query_arg(array('page' => $page, 'task' => 'display', 'message' => $message), $query_url);
        WDW_FFWD_Library::spider_redirect($query_url);
    }

    public function setdefault($id)
    {
        global $wpdb;
        $save = $wpdb->update($wpdb->prefix . 'wd_fb_theme', array('default_theme' => 0), array('default_theme' => 1));
        $save = $wpdb->update($wpdb->prefix . 'wd_fb_theme', array('default_theme' => 1), array('id' => $id));
        if ($save !== FALSE) {
            $message = 7;
        } else {
            $message = 2;
        }
        $page = WDW_FFWD_Library::get('page');
        $query_url = wp_nonce_url(admin_url('admin.php'), 'themes_ffwd', 'ffwd_nonce');
        $query_url = add_query_arg(array('page' => $page, 'task' => 'display', 'message' => $message), $query_url);
        WDW_FFWD_Library::spider_redirect($query_url);
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
