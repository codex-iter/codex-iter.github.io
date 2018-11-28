<?php
class BWGViewSite {

  public function container($params = array(), $bwg = 0, $content = '') {
    if ( !WDWLibrary::elementor_is_active() && BWG()->options->use_inline_stiles_and_scripts ) {
      wp_enqueue_style(BWG()->prefix . '_frontend');
      if ( (isset($params['show_tag_box']) && $params['show_tag_box'])
        || (isset($params['show_sort_images']) && $params['show_sort_images'])
        || $params['album_view_type'] == 'album' ) {
        if ( !wp_script_is('bwg_sumoselect', 'done') ) {
          wp_print_scripts('bwg_sumoselect');
        }
      }
      if ( $params['thumb_click_action'] == 'open_lightbox' ) {
        if ( $params['popup_enable_comment'] ) {
          if ( !wp_script_is('bwg_mCustomScrollbar', 'done') ) {
            wp_print_scripts('bwg_mCustomScrollbar');
          }
        }
        if ( $params['popup_enable_fullscreen'] ) {
          if ( !wp_script_is('jquery-fullscreen', 'done') ) {
            wp_print_scripts('jquery-fullscreen');
          }
        }
        if ( !wp_script_is('bwg_gallery_box', 'done') ) {
          wp_print_scripts('bwg_gallery_box');
        }
        if ( $params['popup_enable_rate'] ) {
          if ( !wp_script_is('bwg_raty', 'done') ) {
            wp_print_scripts('bwg_raty');
          }
        }
      }
      if ( !wp_script_is('bwg_jquery_mobile', 'done') ) {
        wp_print_scripts('bwg_jquery_mobile');
      }
      if ( !wp_script_is('bwg_frontend', 'done') ) {
        wp_print_scripts('bwg_frontend');
      }
    }
	
    $params_array = $params['params_array'];
    $theme_row = $params['theme_row'];
    ?>
    <div id="bwg_container1_<?php echo $bwg; ?>"
         class="bwg_container bwg_thumbnail bwg_<?php echo $params['gallery_type']; ?>"
         data-right-click-protection="<?php echo BWG()->options->image_right_click; ?>"
         data-bwg="<?php echo $bwg; ?>"
         data-lightbox-url="<?php echo addslashes(add_query_arg($params_array, admin_url('admin-ajax.php'))); ?>"
         data-gallery-id="<?php echo $params_array['gallery_id']; ?>"
         data-popup-width="<?php echo $params["popup_width"]; ?>"
         data-popup-height="<?php echo $params["popup_height"]; ?>"
         data-buttons-position="<?php echo $theme_row->lightbox_ctrl_btn_pos; ?>"
         data-bwg="<?php echo $bwg; ?>">
      <div id="bwg_container2_<?php echo $bwg; ?>">
        <?php
        $this->loading($bwg);
        ?>
        <form id="gal_front_form_<?php echo $bwg; ?>"
              class="bwg-hidden"
              method="post"
              action="#"
              data-current="<?php echo $bwg; ?>"
              data-shortcode-id="<?php echo isset($params['id']) ? $params['id'] : 0; ?>"
              data-gallery-type="<?php echo $params['gallery_type']; ?>"
              data-gallery-id="<?php echo $params['gallery_id']; ?>"
              data-tag="<?php echo $params['tag']; ?>"
              data-album-id="<?php echo $params['album_id']; ?>"
              data-theme-id="<?php echo $params['theme_id']; ?>"
              data-ajax-url="<?php echo add_query_arg(array('action' => 'bwg_frontend_data'), admin_url('admin-ajax.php')); ?>">
          <div id="bwg_container3_<?php echo $bwg; ?>" class="bwg-background bwg-background-<?php echo $bwg; ?>">
            <?php
			$this->title_description($params, $bwg);
            if ( (!isset($params['from']) || $params['from'] !== 'widget')
              && ((isset($params['show_sort_images']) && $params['show_sort_images'])
              || (isset($params['show_tag_box']) && $params['show_tag_box'])
              || (isset($params['show_search_box']) && $params['show_search_box'])) ) {
              ?>
              <div class="search_line">
                <?php
                $this->ajax_html_frontend_sort_box($params, $bwg);

                $this->ajax_html_frontend_search_tags($params, $bwg);

                $this->ajax_html_frontend_search_box($params, $bwg);
                ?>
              </div>
              <?php
            }
            if ( isset($params['image_rows']) && !count($params['image_rows']['images']) ) {
              if ( $params['tag'] ) {
                echo WDWLibrary::message(__('There are no images.', BWG()->prefix), 'wd_error');
              }
              else {
                echo WDWLibrary::message(__('No Images found.', BWG()->prefix), 'wd_error');
              }
            }
            if ( $params['album_view_type'] == 'album' || isset($params['image_rows']) && count($params['image_rows']['images']) ) {
              $pagination_style = $params['gallery_type'] == 'image_browser' ? 'image_browser' : 'simple';
              $this->ajax_html_frontend_page_nav($theme_row, 'top', $params, $bwg, $pagination_style);
              echo $content;
              $this->ajax_html_frontend_page_nav($theme_row, 'bottom', $params, $bwg, $pagination_style);
              $this->download_button($params, $bwg);
            }
            ?>
          </div>
        </form>
        <?php
        if ( $params['thumb_click_action'] == 'open_lightbox' ) {
          ob_start();
          ?>
          #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> #spider_popup_overlay_<?php echo $bwg; ?> {
          background-color: #<?php echo $theme_row->lightbox_overlay_bg_color; ?>;
          opacity: <?php echo number_format($theme_row->lightbox_overlay_bg_transparent / 100, 2, ".", ""); ?>;
          filter: Alpha(opacity=<?php echo $theme_row->lightbox_overlay_bg_transparent; ?>);
          }
          <?php
          $inline_style = ob_get_clean();

          if ( BWG()->options->use_inline_stiles_and_scripts && !WDWLibrary::elementor_is_active() ) {
            wp_add_inline_style( 'bwg_frontend', $inline_style );
          }
          else {
            echo '<style>' . $inline_style . '</style>';
          }
          ?>
        <div id="bwg_spider_popup_loading_<?php echo $bwg; ?>" class="bwg_spider_popup_loading"></div>
        <div id="spider_popup_overlay_<?php echo $bwg; ?>" class="spider_popup_overlay" onclick="spider_destroypopup(1000)"></div>
          <?php
        }
        ?>
      </div>
    </div>
    <?php
  }

  public function ajax_content($params, $bwg, $content) {
	  if ( isset($params['breadcrumb_arr']) && count($params['breadcrumb_arr']) > 1 ) { /* If not first album.*/
      $this->back($params, $bwg);
    }

    $this->title_description($params, $bwg);
    if ( (!isset($params['from']) || $params['from'] !== 'widget')
      && ((isset($params['show_sort_images']) && $params['show_sort_images'])
      || (isset($params['show_tag_box']) && $params['show_tag_box'])
      || (isset($params['show_search_box']) && $params['show_search_box'])) ) {
      ?>
      <div class="search_line">
        <?php
        $this->ajax_html_frontend_sort_box($params, $bwg);

        $this->ajax_html_frontend_search_tags($params, $bwg);

        $this->ajax_html_frontend_search_box($params, $bwg);
        ?>
      </div>
      <?php
    }


    if ( isset($params['image_rows']) && !count($params['image_rows']['images']) ) {
      if ( $params['tag'] ) {
        echo WDWLibrary::message(__('There are no images.', BWG()->prefix), 'wd_error');
      }
      else {
        echo WDWLibrary::message(__('No Images found.', BWG()->prefix), 'wd_error');
      }
    }

    if ( $params['album_view_type'] == 'album' || isset($params['image_rows']) && count($params['image_rows']['images']) ) {
      $pagination_style = $params['gallery_type'] == 'image_browser' ? 'image_browser' : 'simple';
      $this->ajax_html_frontend_page_nav($params['theme_row'], 'top', $params, $bwg, $pagination_style);
      echo $content;
      $this->ajax_html_frontend_page_nav($params['theme_row'], 'bottom', $params, $bwg, $pagination_style);
      $this->download_button($params, $bwg);
    }
  }

  public function loading($bwg = 0) {
    ?>
    <div id="ajax_loading_<?php echo $bwg; ?>" class="bwg_loading_div_1">
      <div class="bwg_loading_div_2">
        <div class="bwg_loading_div_3">
          <div id="loading_div_<?php echo $bwg; ?>" class="bwg_spider_ajax_loading">
          </div>
        </div>
      </div>
    </div>
    <?php
  }

  public function back($params, $bwg) {
    $theme_row = $params['theme_row'];
    ob_start();
    ?>
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_back_<?php echo $bwg; ?> {
      display: inline-block;
      background-color: rgba(0, 0, 0, 0);
      margin-bottom: 20px;
      padding: <?php echo $theme_row->back_padding; ?>;
      line-height: <?php echo $theme_row->back_font_size; ?>px;
      text-align: left;
      font-family: <?php echo $theme_row->back_font_style; ?>;
      font-weight: <?php echo $theme_row->back_font_weight; ?>;
      font-size: <?php echo $theme_row->back_font_size; ?>px;
      color: #<?php echo $theme_row->back_font_color; ?>;
      cursor: pointer;
    }
    #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_back_<?php echo $bwg; ?> .dashicons-arrow-left-alt {
      width: <?php echo $theme_row->back_font_size; ?>px;
      height: <?php echo $theme_row->back_font_size; ?>px;
      font-size: <?php echo $theme_row->back_font_size; ?>px;
    }
    <?php
    $inline_style = ob_get_clean();
    if ( BWG()->options->use_inline_stiles_and_scripts && !WDWLibrary::elementor_is_active() && !$params['ajax'] ) {
      wp_add_inline_style('bwg_frontend', $inline_style);
    }
    else {
      echo '<style>' . $inline_style . '</style>';
    }
    ?>
    <div class="bwg_back bwg_back_<?php echo $bwg; ?>" onclick="bwg_ajax('gal_front_form_<?php echo $bwg; ?>', '<?php echo $bwg; ?>', '<?php echo $params['container_id']; ?>', 'back', '', 'album')"><i class="dashicons dashicons-arrow-left-alt"></i> <?php _e('Back', BWG()->prefix); ?></div>
    <?php
  }

  public function title_description($params, $bwg) {
    $row = $params['album_view_type'] == 'album' ? $params['album_row'] : $params['gallery_row'];
    if ( is_object($row) && isset($params['showthumbs_name']) && isset($params['show_gallery_description']) ) {
      if ( (($params['showthumbs_name'] && isset($row->name) && $row->name != '')
        || ($params['show_gallery_description'] && isset($row->description) && $row->description != '')) ) {
        $theme_row = $params['theme_row'];
        ob_start();
        ?>
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_gal_title_<?php echo $bwg; ?> {
          display: block;
          padding: <?php echo $theme_row->thumb_gal_title_margin; ?>;
          background-color: rgba(0, 0, 0, 0);
          line-height: 20px;
          font-family: <?php echo $theme_row->thumb_gal_title_font_style; ?>;
          font-size: <?php echo $theme_row->thumb_gal_title_font_size; ?>px;
          font-weight: <?php echo $theme_row->thumb_gal_title_font_weight; ?>;
          color: #<?php echo $theme_row->thumb_gal_title_font_color; ?>;
          text-shadow: <?php echo $theme_row->thumb_gal_title_shadow; ?>;
          text-align: <?php echo $theme_row->thumb_gal_title_align; ?>;
        }
        #bwg_container1_<?php echo $bwg; ?> #bwg_container2_<?php echo $bwg; ?> .bwg_gal_description_<?php echo $bwg; ?> {
          margin: 20px 0;
          word-break: break-word;
          text-align: justify;
          font-size: <?php echo $theme_row->thumb_gal_title_font_size - 3; ?>px;
          font-weight: unset;
        }
        <?php
        $inline_style = ob_get_clean();
        if ( BWG()->options->use_inline_stiles_and_scripts && !WDWLibrary::elementor_is_active() && !$params['ajax'] ) {
          wp_add_inline_style('bwg_frontend', $inline_style);
        }
        else {
          echo '<style>' . $inline_style . '</style>';
        }
      }
      if ( $params['showthumbs_name'] && isset($row->name) && $row->name != '' ) {
        ?>
        <div class="bwg_gal_title_<?php echo $bwg; ?>"><?php echo $row->name; ?></div>
        <?php
      }
      if ( $params['show_gallery_description'] && isset($row->description) && $row->description != '' ) {
        ?>
        <div class="bwg_gal_title_<?php echo $bwg; ?> bwg_gal_description_<?php echo $bwg; ?>"><?php echo $row->description; ?></div>
        <?php
      }
    }
  }

  /**
   * @param $params
   * @param $bwg
   */
  public function download_button($params, $bwg) {
    if ( isset($params['gallery_download'])
      && $params['gallery_download']
      && ($params['gallery_row']->id == 0 || ($params['gallery_row']->gallery_type != 'facebook' && $params['gallery_row']->gallery_type != 'instagram' && $params['gallery_row']->gallery_type != 'instagram_post')) ) {
      if ( count($params['image_rows']['images']) ) {
        $bwg_tags_input_value = WDWLibrary::get('bwg_tag_id_'.$params['container_id']);
        $query_url = addslashes(add_query_arg(array(
                                                "action" => "download_gallery",
                                                "gallery_id" => $params['gallery_id'],
                                                "bwg" => $bwg,
                                                "type" => 'gallery',
                                                "tag_input_name" => 'bwg_tag_id_'.$params['container_id'],
                                                'bwg_tag_id_'.$params['container_id'] => $bwg_tags_input_value,
                                                "tag" => $params['tag'],
                                                "bwg_search_" . $bwg => WDWLibrary::get('bwg_search_' . $bwg),
                                              ), admin_url('admin-ajax.php')));
        ?>
        <div class="bwg_download_gallery">
          <a href="<?php echo $query_url; ?>">
            <i title="<?php _e('Download gallery', BWG()->prefix); ?>" class="bwg_ctrl_btn fa fa-download"></i>
          </a>
        </div>
        <?php
      }
    }
  }

  public function ajax_html_frontend_search_box($params, $bwg) {
    if ( isset($params['show_search_box']) && $params['show_search_box'] ) {
      $form_id = 'gal_front_form_' . $bwg;
      $current_view = $bwg;
      $cur_gal_id = $params['container_id'];
      $images_count = count(($params['album_view_type'] != 'album' ? $params['image_rows']['images'] : $params['album_gallery_rows']['rows']));
      $search_box_width = $params['search_box_width'];
      $placeholder = $params['placeholder'];
      $album_gallery_id = 0;
      $bwg_search = ((isset($_POST['bwg_search_' . $current_view]) && esc_html($_POST['bwg_search_' . $current_view]) != '') ? esc_html($_POST['bwg_search_' . $current_view]) : '');
      $type = (isset($_POST['type_' . $current_view]) ? esc_html($_POST['type_' . $current_view]) : ($album_gallery_id ? 'gallery' : 'album'));
      $album_gallery_id = (isset($_POST['album_gallery_id_' . $current_view]) ? esc_html($_POST['album_gallery_id_' . $current_view]) : ($album_gallery_id ? $album_gallery_id : 0));
      ob_start();
      ?>
      #bwg_search_container_1_<?php echo $current_view; ?> {
      max-width: <?php echo $search_box_width; ?>px;
      }
      <?php
      $inline_style = ob_get_clean();
      if ( BWG()->options->use_inline_stiles_and_scripts && !WDWLibrary::elementor_is_active() && !$params['ajax'] ) {
        wp_add_inline_style('bwg_frontend', $inline_style);
      }
      else {
        echo '<style>' . $inline_style . '</style>';
      }
      ?>
      <div class="bwg_search_container_1" id="bwg_search_container_1_<?php echo $current_view; ?>">
        <div class="bwg_search_container_2" id="bwg_search_container_2_<?php echo $current_view; ?>">
        <span class="bwg_search_input_container">
          <span class="bwg_search_loupe_container1 hidden">
          <i title="<?php echo __('SEARCH...', BWG()->prefix); ?>" class="bwg_search fa fa-search" onclick="bwg_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $cur_gal_id; ?>', <?php echo $album_gallery_id; ?>, '', '<?php echo $type; ?>', 1)"></i>
        </span>
          <input id="bwg_search_input_<?php echo $current_view; ?>" class="bwg_search_input" type="text" onkeypress="bwg_key_press(this); return bwg_check_search_input_enter(this, event);" name="bwg_search_<?php echo $current_view; ?>" value="<?php echo $bwg_search; ?>" placeholder="<?php echo $placeholder; ?>" />
          <span class="bwg_search_reset_container hidden">
          <i title="<?php echo __('Reset', BWG()->prefix); ?>" class="bwg_reset fa fa-times" onclick="bwg_clear_search_input('<?php echo $current_view; ?>'); bwg_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $cur_gal_id; ?>', <?php echo $album_gallery_id; ?>, '', '<?php echo $type; ?>', 1)"></i>
        </span>
          <input id="bwg_images_count_<?php echo $current_view; ?>" class="bwg_search_input" type="hidden" name="bwg_images_count_<?php echo $current_view; ?>" value="<?php echo $images_count; ?>">
          <span class="search_placeholder_title" onclick="bwg_search_focus(this)">
                <span class="bwg_search_loupe_container">
                  <i title="<?php echo __('SEARCH...', BWG()->prefix); ?>" class="bwg_search fa fa-search"></i>
                </span>
                <span style="font-size: 12px; font-family: Ubuntu;"><?php echo $placeholder; ?></span>
          </span>
        </span>
        </div>
      </div>
      <?php
    }
  }

  public function ajax_html_frontend_sort_box($params, $bwg) {
    if ( isset($params['show_sort_images']) && $params['show_sort_images'] ) {
      $form_id = 'gal_front_form_' . $bwg;
      $current_view = $bwg;
      $cur_gal_id = $params['container_id'];
      $sort_by = $params['sort_by'];
      $search_box_width = $params['search_box_width'];
      $type = (isset($_POST['type_' . $current_view]) ? esc_html($_POST['type_' . $current_view]) : 'album');
      $album_gallery_id = (isset($_POST['album_gallery_id_' . $current_view]) ? esc_html($_POST['album_gallery_id_' . $current_view]) : 0);
      ob_start();
      ?>
      #bwg_order_<?php echo $current_view; ?> {
      width: <?php echo $search_box_width; ?>px;
      }
      <?php
      $inline_style = ob_get_clean();
      if ( BWG()->options->use_inline_stiles_and_scripts ) {
        wp_add_inline_style('bwg_frontend', $inline_style);
      }
      ?>
      <div class="bwg_order_cont">
        <select id="bwg_order_<?php echo $current_view; ?>" class="bwg_order bwg_order_<?php echo $current_view; ?>" onchange="bwg_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $cur_gal_id; ?>', <?php echo $album_gallery_id; ?>, '', '<?php echo $type; ?>', 1, '', this.value)">
          <option <?php if ( $sort_by == 'default' ) {
            echo 'selected';
          } ?> value="default"><?php echo __('Order by Default', BWG()->prefix); ?></option>
          <option <?php if ( $sort_by == 'filename' ) {
            echo 'selected';
          } ?> value="filename"><?php echo __('Filename', BWG()->prefix); ?></option>
          <option <?php if ( $sort_by == 'size' ) {
            echo 'selected';
          } ?> value="size"><?php echo __('Size', BWG()->prefix); ?></option>
          <option <?php if ( $sort_by == 'random' || $sort_by == 'RAND()' ) {
            echo 'selected';
          } ?> value="random"><?php echo __('Random', BWG()->prefix); ?></option>
        </select>
      </div>
      <?php
    }
  }

  public function ajax_html_frontend_search_tags($params, $bwg) {
    if ( isset($params['show_tag_box']) && $params['show_tag_box'] ) {
      $form_id = 'gal_front_form_' . $bwg;
      $current_view = $bwg;
      $cur_gal_id = $params['container_id'];
      $tags_rows = $params['tags_rows'];
      $type = (isset($_POST['type_' . $current_view]) ? esc_html($_POST['type_' . $current_view]) : 'album');
      $bwg_search_tags = (isset($_POST['bwg_tag_id_' . $cur_gal_id]) && $_POST['bwg_tag_id_' . $cur_gal_id] != '') ? $_POST['bwg_tag_id_' . $cur_gal_id] : array();
      $album_gallery_id = (isset($_POST['album_gallery_id_' . $current_view]) ? esc_html($_POST['album_gallery_id_' . $current_view]) : 0);
      ?>
      <div class="search_tags_container">
        <select class="search_tags" id="bwg_tag_id_<?php echo $cur_gal_id; ?>" multiple="multiple">
          <?php
          foreach ( $tags_rows as $tags_row ) {
            $selected = (in_array($tags_row->term_id ? $tags_row->term_id : '', $bwg_search_tags)) ? 'selected="selected"' : '';
            ?>
            <option value="<?php echo $tags_row->term_id ?>" <?php echo $selected; ?>><?php echo $tags_row->name ?></option>
            <?php
          }
          ?>
        </select>
        <input type="hidden" id="bwg_tag_id_<?php echo $current_view; ?>" value="" />
        <input type="hidden" class="current_view" value="<?php echo $current_view; ?>" />
        <input type="hidden" class="form_id" value="<?php echo $form_id; ?>" />
        <input type="hidden" class="cur_gal_id" value="<?php echo $cur_gal_id; ?>" />
        <input type="hidden" class="album_gallery_id" value="<?php echo $album_gallery_id; ?>" />
        <input type="hidden" class="type" value="<?php echo $type; ?>" />
      </div>
      <?php
    }
  }

  /**
   * @param $theme_row
   * @param $position
   * @param $params
   * @param $bwg
   */
  public function ajax_html_frontend_page_nav($theme_row, $position, $params, $bwg, $pagination_style = 'simple') {
    $count_items = $params['album_view_type'] == 'album' ? $params['album_gallery_rows']['page_nav']['total'] : $params['image_rows']['page_nav']['total'];
    $page_number = $params['album_view_type'] == 'album' ? $params['album_gallery_rows']['page_nav']['limit'] : $params['image_rows']['page_nav']['limit'];
    $form_id = 'gal_front_form_' . $bwg;
    $items_per_page = $params['items_per_page'];
    $current_view = $bwg;
    $id = $params['container_id'];
    $cur_alb_gal_id = $params['cur_alb_gal_id'];
    $type = $params['album_view_type'];
    $enable_seo = BWG()->options->enable_seo;
    $pagination = $params['image_enable_page'];

    if ( isset($params['image_enable_page'])
      && isset($params['images_per_page'])
      && $params['image_enable_page']
      && $params['images_per_page']
      && ($theme_row->page_nav_position == $position) ) {
      ob_start();
      $rgb_page_nav_font_color = WDWLibrary::spider_hex2rgb($theme_row->page_nav_font_color);
      ?>
      /*pagination styles*/
      #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> {
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
      #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .displaying-num_<?php echo $current_view; ?> {
      display: none;
      }
      }
      #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .displaying-num_<?php echo $current_view; ?> {
      font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
      font-family: <?php echo $theme_row->page_nav_font_style; ?>;
      font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
      color: #<?php echo $theme_row->page_nav_font_color; ?>;
      margin-right: 10px;
      vertical-align: middle;
      }
      #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .paging-input_<?php echo $current_view; ?> {
      font-size: <?php echo $theme_row->page_nav_font_size; ?>px;
      font-family: <?php echo $theme_row->page_nav_font_style; ?>;
      font-weight: <?php echo $theme_row->page_nav_font_weight; ?>;
      color: #<?php echo $theme_row->page_nav_font_color; ?>;
      vertical-align: middle;
      }
      #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> a.disabled,
      #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> a.disabled:hover,
      #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> a.disabled:focus {
      cursor: default;
      color: rgba(<?php echo $rgb_page_nav_font_color['red']; ?>, <?php echo $rgb_page_nav_font_color['green']; ?>, <?php echo $rgb_page_nav_font_color['blue']; ?>, 0.5);
      }
      #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> a {
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
      <?php echo ($theme_row->page_nav_button_transition) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      }
      <?php
      if ($pagination_style == 'image_browser') {
        $image_browser_images_conteiner = WDWLibrary::spider_hex2rgb($theme_row->image_browser_full_bg_color);
      ?>
      #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> a.next-page:hover,
      #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> a.prev-page:hover {
        color: #000000;
      }
      #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> .first-page,
      #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> .last-page {
        padding: 0% 7%;
      }
      #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> .next-page {
        margin: 0% 4% 0% 0%;
      }
      #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> .prev-page {
        margin: 0% 0% 0% 4%;
      }
      #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> a {
        font-size: 15px !important;
        padding: 0% 7% !important;
        border-style: none !important;
        border-width: <?php echo $theme_row->page_nav_border_width; ?>px;
        border-color: #<?php echo $theme_row->page_nav_border_color; ?>;
        background-color: #<?php echo $theme_row->page_nav_button_bg_color; ?>;
        opacity: <?php echo number_format($theme_row->page_nav_button_bg_transparent / 100, 2, ".", ""); ?>;
        filter: Alpha(opacity=<?php echo $theme_row->page_nav_button_bg_transparent; ?>);
        <?php echo ($theme_row->page_nav_button_transition ) ? 'transition: all 0.3s ease 0s;-webkit-transition: all 0.3s ease 0s;' : ''; ?>
      }
      #bwg_container1_<?php echo $current_view; ?> #bwg_container2_<?php echo $current_view; ?> .tablenav-pages_<?php echo $current_view; ?> {
        background-color: rgba(<?php echo $image_browser_images_conteiner['red']; ?>, <?php echo $image_browser_images_conteiner['green']; ?>, <?php echo $image_browser_images_conteiner['blue']; ?>, <?php echo number_format($theme_row->image_browser_full_transparent / 100, 2, ".", ""); ?>);
        margin-top: 0;
      }
      <?php
      }
      $inline_style = ob_get_clean();
      if ( BWG()->options->use_inline_stiles_and_scripts && !WDWLibrary::elementor_is_active() && !$params['ajax'] ) {
        wp_add_inline_style('bwg_frontend', $inline_style);
      }
      else {
        echo '<style>' . $inline_style . '</style>';
      }
      $limit = $page_number > 1 ? $items_per_page['load_more_image_count'] : $items_per_page['images_per_page'];
      $limit = $limit ? $limit : 1;
      $type = (isset($_POST['type_' . $current_view]) ? esc_html($_POST['type_' . $current_view]) : $type);
      $album_gallery_id = (isset($_POST['album_gallery_id_' . $current_view]) ? esc_html($_POST['album_gallery_id_' . $current_view]) : $cur_alb_gal_id);
      if ( $count_items ) {
        if ( $count_items % $limit ) {
          $items_county = ($count_items - $count_items % $limit) / $limit + 1;
        }
        else {
          $items_county = ($count_items - $count_items % $limit) / $limit;
        }
        if ( $pagination == 2 ) {
          $items_county++;
        }
      }
      else {
        $items_county = 1;
      }
      if ( $page_number > $items_county ) {
        return;
      }
      $first_page = "first-page-" . $current_view;
      $prev_page = "prev-page-" . $current_view;
      $next_page = "next-page-" . $current_view;
      $last_page = "last-page-" . $current_view;
      ?>
      <span class="bwg_nav_cont_<?php echo $current_view; ?>">
    <?php
    if ( $pagination == 1 ) {
      ?>
      <div class="tablenav-pages_<?php echo $current_view; ?>">
      <?php
      if ( $theme_row->page_nav_number ) {
        ?>
        <span class="displaying-num_<?php echo $current_view; ?>"><?php echo $count_items . ' ' . __(' item(s)', BWG()->prefix); ?></span>
        <?php
      }
      if ( $count_items > $limit ) {
        if ( $theme_row->page_nav_button_text ) {
          $first_button = __('First', BWG()->prefix);
          $previous_button = __('Previous', BWG()->prefix);
          $next_button = __('Next', BWG()->prefix);
          $last_button = __('Last', BWG()->prefix);
        }
        else {
          $first_button = '«';
          $previous_button = '‹';
          $next_button = '›';
          $last_button = '»';
        }
        if ( $page_number == 1 ) {
          $first_page = "first-page disabled";
          $prev_page = "prev-page disabled";
        }
        if ( $page_number >= $items_county ) {
          $next_page = "next-page disabled";
          $last_page = "last-page disabled";
        }
        ?>
        <span class="pagination-links_<?php echo $current_view; ?>">
        <a class="<?php echo $first_page; ?>" title="<?php echo __('Go to the first page', BWG()->prefix); ?>"><?php echo $first_button; ?></a>
        <a class="<?php echo $prev_page; ?>" title="<?php echo __('Go to the previous page', BWG()->prefix); ?>" <?php echo $page_number > 1 && $enable_seo ? 'href="' . esc_url(add_query_arg(array( "page_number_" . $current_view => $page_number - 1 ), $_SERVER['REQUEST_URI'])) . '"' : ""; ?>><?php echo $previous_button; ?></a>
        <span class="paging-input_<?php echo $current_view; ?>">
          <span class="total-pages_<?php echo $current_view; ?>"><?php echo $page_number; ?></span> <?php echo __('of', BWG()->prefix); ?>
          <span class="total-pages_<?php echo $current_view; ?>">
            <?php echo $items_county; ?>
          </span>
        </span>
        <a class="<?php echo $next_page ?>" title="<?php echo __('Go to the next page', BWG()->prefix); ?>" <?php echo $page_number + 1 <= $items_county && $enable_seo ? 'href="' . esc_url(add_query_arg(array( "page_number_" . $current_view => $page_number + 1 ), $_SERVER['REQUEST_URI'])) . '"' : ""; ?>><?php echo $next_button; ?></a>
        <a class="<?php echo $last_page ?>" title="<?php echo __('Go to the last page', BWG()->prefix); ?>"><?php echo $last_button; ?></a>
      </span>
        <?php
      }
      ?>
    </div>
      <?php
    }
    elseif ($pagination == 2) {
    if ($count_items > ($limit * ($page_number - 1)) + $items_per_page['images_per_page']) {
    ?>

      <div id="bwg_load_<?php echo $current_view; ?>" class="tablenav-pages_<?php echo $current_view; ?>">
				<a class="bwg_load_btn_<?php echo $current_view; ?> bwg_load_btn" href="javascript:void(0);"><?php echo __('Load More...', BWG()->prefix); ?></a>
				<input type="hidden" id="bwg_load_more_<?php echo $current_view; ?>" name="bwg_load_more_<?php echo $current_view; ?>" value="on" />
			</div>
		<?php
    }
    }
    elseif ($pagination == 3) {
      if ($count_items > $limit * $page_number) {
        ?>
		<script type="text/javascript">
      function bwg_scroll_load_action() {
        if (jQuery(document).scrollTop() + jQuery(window).height() > (jQuery('#<?php echo $form_id; ?>').offset().top + jQuery('#<?php echo $form_id; ?>').height())) {
          spider_page_<?php echo $current_view; ?>('', <?php echo $page_number; ?>, 1, true);
          return false;
        }
      }
		  jQuery(document).ready(function () {
			jQuery(window).on("scroll", bwg_scroll_load_action );
		  });
		</script>
      <?php
      }
    }
    ?>
    <input type="hidden" id="page_number_<?php echo $current_view; ?>" name="page_number_<?php echo $current_view; ?>" value="<?php echo((isset($_POST['page_number_' . $current_view])) ? (int) $_POST['page_number_' . $current_view] : 1); ?>" />
    <script type="text/javascript">
      function spider_page_<?php echo $current_view; ?>(cur, x, y, load_more) {
        if (typeof load_more == "undefined") {
          var load_more = false;
        }
        if (jQuery(cur).hasClass('disabled')) {
          return false;
        }
        var items_county_<?php echo $current_view; ?> = <?php echo $items_county; ?>;
        switch (y) {
          case 1:
            if (x >= items_county_<?php echo $current_view; ?>) {
              document.getElementById('page_number_<?php echo $current_view; ?>').value = items_county_<?php echo $current_view; ?>;
            }
            else {
              document.getElementById('page_number_<?php echo $current_view; ?>').value = x + 1;
            }
            break;
          case 2:
            document.getElementById('page_number_<?php echo $current_view; ?>').value = items_county_<?php echo $current_view; ?>;
            break;
          case -1:
            if (x == 1) {
              document.getElementById('page_number_<?php echo $current_view; ?>').value = 1;
            }
            else {
              document.getElementById('page_number_<?php echo $current_view; ?>').value = x - 1;
            }
            break;
          case -2:
            document.getElementById('page_number_<?php echo $current_view; ?>').value = 1;
            break;
          default:
            document.getElementById('page_number_<?php echo $current_view; ?>').value = 1;
        }
        bwg_ajax('<?php echo $form_id; ?>', '<?php echo $current_view; ?>', '<?php echo $id; ?>', '<?php echo $album_gallery_id; ?>', '', '<?php echo $type; ?>', 0, '', '', load_more);
      }

      jQuery('.<?php echo $first_page; ?>').on('click', function () {
        spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, -2, 'numeric');
      });
      jQuery('.<?php echo $prev_page; ?>').on('click', function () {
        spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, -1, 'numeric');
        return false;
      });
      jQuery('.<?php echo $next_page; ?>').on('click', function () {
        spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, 1, 'numeric');
        return false;
      });
      jQuery('.<?php echo $last_page; ?>').on('click', function () {
        spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, 2, 'numeric');
      });
      jQuery('.bwg_load_btn_<?php echo $current_view; ?>').on('click', function () {
        spider_page_<?php echo $current_view; ?>(this, <?php echo $page_number; ?>, 1, true);
        return false;
      });
    </script>
    </span>
      <?php
    }
  }
}
