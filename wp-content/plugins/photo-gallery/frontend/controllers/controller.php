<?php
class BWGControllerSite {

  private $model;
  private $view;

  public $thumb_urls;

  public function __construct( $view = 'Thumbnails' ) {
    require_once BWG()->plugin_dir . "/frontend/models/model.php";
    $this->model = new BWGModelSite();
    require_once BWG()->plugin_dir . "/frontend/views/view.php";
    require_once BWG()->plugin_dir . '/frontend/views/BWGView' . $view . '.php';
    $view_class = 'BWGView' . $view;
    $this->view = new $view_class();
  }

  public function execute( $params = array(), $from_shortcode = 0, $bwg = 0 ) {
    $theme_id = $params['theme_id'];
    $theme_row = $this->model->get_theme_row_data($theme_id);
    if ( !$theme_row ) {
      echo WDWLibrary::message(__('There is no theme selected or the theme was deleted.', BWG()->prefix), 'wd_error');
      return;
    }
    else {
      /* Thumbnails */
      {
        if ( !isset( $theme_row->thumb_gal_title_font_color ) ) {
          $theme_row->thumb_gal_title_font_color = '323A45';
        }
        if ( !isset( $theme_row->thumb_gal_title_font_style ) ) {
          $theme_row->thumb_gal_title_font_style = 'Ubuntu';
        }
        if ( !isset( $theme_row->thumb_gal_title_font_size ) ) {
          $theme_row->thumb_gal_title_font_size = 16;
        }
        if ( !isset( $theme_row->thumb_gal_title_font_weight ) ) {
          $theme_row->thumb_gal_title_font_weight = 'bold';
        }
        if ( !isset( $theme_row->thumb_gal_title_margin ) ) {
          $theme_row->thumb_gal_title_margin = '2px';
        }
        if ( !isset( $theme_row->thumb_gal_title_shadow ) ) {
          $theme_row->thumb_gal_title_shadow = '0px 0px 0px #888888';
        }
        if ( !isset( $theme_row->thumb_gal_title_align ) ) {
          $theme_row->thumb_gal_title_align = 'center';
        }
        if ( !isset( $theme_row->container_margin ) ) {
          $theme_row->container_margin = 1;
        }
        if ( !isset( $theme_row->thumb_title_font_color_hover ) ) {
          $theme_row->thumb_title_font_color_hover = 'FFFFFF';
        }
      }
      {
        /* Masonry*/
        if ( !isset( $theme_row->masonry_thumb_gal_title_font_color ) ) {
          $theme_row->masonry_thumb_gal_title_font_color = '323A45';
        }
          if ( !isset( $theme_row->masonry_thumb_gal_title_font_color_hover ) ) {
              $theme_row->masonry_thumb_gal_title_font_color_hover = 'FFFFFF';
          }
        if ( !isset( $theme_row->masonry_thumb_gal_title_font_style ) ) {
          $theme_row->masonry_thumb_gal_title_font_style = 'Ubuntu';
        }
        if ( !isset( $theme_row->masonry_thumb_gal_title_font_size ) ) {
          $theme_row->masonry_thumb_gal_title_font_size = 16;
        }
        if ( !isset( $theme_row->masonry_thumb_gal_title_font_weight ) ) {
          $theme_row->masonry_thumb_gal_title_font_weight = 'bold';
        }
        if ( !isset( $theme_row->masonry_thumb_gal_title_margin ) ) {
          $theme_row->masonry_thumb_gal_title_margin = '2px';
        }
        if ( !isset( $theme_row->masonry_thumb_gal_title_shadow ) ) {
          $theme_row->masonry_thumb_gal_title_shadow = '0px 0px 0px #888888';
        }
        if ( !isset( $theme_row->masonry_thumb_gal_title_align ) ) {
          $theme_row->masonry_thumb_gal_title_align = 'center';
        }
        if ( !isset( $theme_row->masonry_container_margin ) ) {
          $theme_row->masonry_container_margin = 1;
        }
        if ( !isset($theme_row->masonry_thumb_title_margin) ) {
          $theme_row->masonry_thumb_title_margin = '2px';
        }
      }
      /* Mosaic */
      {
        if ( !isset( $theme_row->mosaic_thumb_gal_title_font_color ) ) {
          $theme_row->mosaic_thumb_gal_title_font_color = '323A45';
        }
        if ( !isset( $theme_row->mosaic_thumb_gal_title_font_style ) ) {
          $theme_row->mosaic_thumb_gal_title_font_style = 'Ubuntu';
        }
        if ( !isset( $theme_row->mosaic_thumb_gal_title_font_size ) ) {
          $theme_row->mosaic_thumb_gal_title_font_size = 16;
        }
        if ( !isset( $theme_row->mosaic_thumb_gal_title_font_weight ) ) {
          $theme_row->mosaic_thumb_gal_title_font_weight = 'bold';
        }
        if ( !isset( $theme_row->mosaic_thumb_gal_title_margin ) ) {
          $theme_row->mosaic_thumb_gal_title_margin = '2px';
        }
        if ( !isset( $theme_row->mosaic_thumb_gal_title_shadow ) ) {
          $theme_row->mosaic_thumb_gal_title_shadow = '0px 0px 0px #888888';
        }
        if ( !isset( $theme_row->mosaic_thumb_gal_title_align ) ) {
          $theme_row->mosaic_thumb_gal_title_align = 'center';
        }
        if ( !isset( $theme_row->mosaic_container_margin ) ) {
          $theme_row->mosaic_container_margin = 1;
        }
        if ( !isset($theme_row->mosaic_thumb_title_font_color_hover) ) {
          $theme_row->mosaic_thumb_title_font_color_hover = 'FFFFFF';
        }
      }
      /* Image browser */
      {
        if ( !isset( $theme_row->image_browser_gal_title_font_color ) ) {
          $theme_row->image_browser_gal_title_font_color = '323A45';
        }
        if ( !isset( $theme_row->image_browser_gal_title_font_style ) ) {
          $theme_row->image_browser_gal_title_font_style = 'Ubuntu';
        }
        if ( !isset( $theme_row->image_browser_gal_title_font_size ) ) {
          $theme_row->image_browser_gal_title_font_size = 16;
        }
        if ( !isset( $theme_row->image_browser_gal_title_font_weight ) ) {
          $theme_row->image_browser_gal_title_font_weight = 'bold';
        }
        if ( !isset( $theme_row->image_browser_gal_title_margin ) ) {
          $theme_row->image_browser_gal_title_margin = '2px';
        }
        if ( !isset( $theme_row->image_browser_gal_title_shadow ) ) {
          $theme_row->image_browser_gal_title_shadow = '0px 0px 0px #888888';
        }
        if ( !isset( $theme_row->image_browser_gal_title_align ) ) {
          $theme_row->image_browser_gal_title_align = 'center';
        }
      }
      /* Blog style. */
      {
        if (!isset($theme_row->blog_style_gal_title_font_color)) {
          $theme_row->blog_style_gal_title_font_color = '323A45';
        }
        if (!isset($theme_row->blog_style_gal_title_font_style)) {
          $theme_row->blog_style_gal_title_font_style = 'Ubuntu';
        }
        if (!isset($theme_row->blog_style_gal_title_font_size)) {
          $theme_row->blog_style_gal_title_font_size = 16;
        }
        if (!isset($theme_row->blog_style_gal_title_font_weight)) {
          $theme_row->blog_style_gal_title_font_weight = 'bold';
        }
        if (!isset($theme_row->blog_style_gal_title_margin)) {
          $theme_row->blog_style_gal_title_margin = '2px';
        }
        if (!isset($theme_row->blog_style_gal_title_shadow)) {
          $theme_row->blog_style_gal_title_shadow = '0px 0px 0px #888888';
        }
        if (!isset($theme_row->blog_style_gal_title_align)) {
          $theme_row->blog_style_gal_title_align = 'center';
        }
      }
      /* Compact album. */
      {
        if ( !isset( $theme_row->compact_container_margin ) ) {
          $theme_row->compact_container_margin = 1;
        }
        if (!isset($theme_row->album_compact_gal_title_font_color)) {
          $theme_row->album_compact_gal_title_font_color = '323A45';
        }
        if (!isset($theme_row->album_compact_gal_title_font_style)) {
          $theme_row->album_compact_gal_title_font_style = 'Ubuntu';
        }
        if (!isset($theme_row->album_compact_gal_title_font_size)) {
          $theme_row->album_compact_gal_title_font_size = 18;
        }
        if (!isset($theme_row->album_compact_gal_title_font_weight)) {
          $theme_row->album_compact_gal_title_font_weight = 'bold';
        }
        if (!isset($theme_row->album_compact_gal_title_margin)) {
          $theme_row->album_compact_gal_title_margin = '0 2px 2px 2px';
        }
        if (!isset($theme_row->album_compact_gal_title_shadow)) {
          $theme_row->album_compact_gal_title_shadow = '0px 0px 0px #888888';
        }
        if (!isset($theme_row->album_compact_gal_title_align)) {
          $theme_row->album_compact_gal_title_align = 'center';
        }
        if ( !isset( $theme_row->album_compact_title_font_color_hover ) ) {
          $theme_row->album_compact_title_font_color_hover = 'FFFFFF';
        }
        if ( !isset( $theme_row->compact_container_margin ) ) {
          $theme_row->compact_container_margin = 1;
        }
      }
      /* Masonry album */
      {
        if (!isset($theme_row->album_masonry_gal_title_font_color)) {
          $theme_row->album_masonry_gal_title_font_color = '323A45';
        }
        if (!isset($theme_row->album_masonry_thumb_title_font_color_hover)) {
          $theme_row->album_masonry_thumb_title_font_color_hover = 'FFFFFF';
        }
        if (!isset($theme_row->album_masonry_gal_title_font_style)) {
          $theme_row->album_masonry_gal_title_font_style = 'Ubuntu';
        }
        if (!isset($theme_row->album_masonry_gal_title_font_size)) {
          $theme_row->album_masonry_gal_title_font_size = 18;
        }
        if (!isset($theme_row->album_masonry_gal_title_font_weight)) {
          $theme_row->album_masonry_gal_title_font_weight = 'bold';
        }
        if (!isset($theme_row->album_masonry_gal_title_margin)) {
          $theme_row->album_masonry_gal_title_margin = '0 2px 2px 2px';
        }
        if (!isset($theme_row->album_masonry_gal_title_shadow)) {
          $theme_row->album_masonry_gal_title_shadow = '0px 0px 0px #888888';
        }
        if (!isset($theme_row->album_masonry_gal_title_align)) {
          $theme_row->album_masonry_gal_title_align = 'center';
        }
        if (!isset($theme_row->album_masonry_container_margin)) {
          $theme_row->album_masonry_container_margin = 1;
        }
      }
      /* Extended album.*/
      {
        if ( !isset($theme_row->album_extended_gal_title_font_color) ) {
          $theme_row->album_extended_gal_title_font_color = 'CCCCCC';
        }
        if ( !isset($theme_row->album_extended_gal_title_font_style) ) {
          $theme_row->album_extended_gal_title_font_style = 'segoe ui';
        }
        if ( !isset($theme_row->album_extended_gal_title_font_size) ) {
          $theme_row->album_extended_gal_title_font_size = 18;
        }
        if ( !isset($theme_row->album_extended_gal_title_font_weight) ) {
          $theme_row->album_extended_gal_title_font_weight = 'bold';
        }
        if ( !isset($theme_row->album_extended_gal_title_margin) ) {
          $theme_row->album_extended_gal_title_margin = '0 2px 2px 2px';
        }
        if ( !isset($theme_row->album_extended_gal_title_shadow) ) {
          $theme_row->album_extended_gal_title_shadow = '0px 0px 0px #888888';
        }
        if ( !isset($theme_row->album_extended_gal_title_align) ) {
          $theme_row->album_extended_gal_title_align = 'center';
        }
      }
    }

    if ( !isset($params['type']) ) {
      $params['type'] = '';
    }

    if ( isset($_POST['sortImagesByValue_' . $bwg]) ) {
      $sort_by = esc_html($_POST['sortImagesByValue_' . $bwg]);
      if ( $sort_by == 'random' ) {
        $params['sort_by'] = 'RAND()';
      }
      else {
        if ( in_array($sort_by, array('default', 'filename', 'size')) ) {
          $params['sort_by'] = $sort_by;
        }
      }
    }

    if ( strpos($params['gallery_type'], 'album') !== FALSE ) { //Album views (compact/masonry/extended).
      // View type.
      $params['view_type'] = 'album';

      // Type in album view (album or gallery).
      $params['album_view_type'] = (isset($_REQUEST['type_' . $bwg]) ? esc_html($_REQUEST['type_' . $bwg]) : (isset($params['type']) && $params['type'] ? $params['type'] : 'album')); // Album or gallery in album.

      // Album or gallery id.
      $params['album_gallery_id'] = (isset($_REQUEST['album_gallery_id_' . $bwg]) ? esc_html($_REQUEST['album_gallery_id_' . $bwg]) : $params['album_id']);
      $params['cur_alb_gal_id'] = $params['album_gallery_id'];

      if ( isset($params['compuct_album_image_thumb_width']) ) { // Compact album view.
        // Gallery type in album (thumbnail/masonry/mosaic).
        $params['gallery_view_type'] = $params['compuct_album_view_type'];
        $params['image_enable_page'] = $params['compuct_album_enable_page'];
        $params['container_id'] = 'bwg_album_compact_' . $bwg;
        /* Set theme parameters for back button.*/
        $theme_row->back_padding = $theme_row->album_compact_back_padding;
        $theme_row->back_font_size = $theme_row->album_compact_back_font_size;
        $theme_row->back_font_style = $theme_row->album_compact_back_font_style;
        $theme_row->back_font_weight = $theme_row->album_compact_back_font_weight;
        $theme_row->back_font_color = $theme_row->album_compact_back_font_color;
      }
      elseif ( isset($params['extended_album_image_thumb_width']) ) { // Extended album view.
        // Gallery type in album (thumbnail/masonry/mosaic).
        $params['gallery_view_type'] = $params['extended_album_view_type'];
        $params['image_enable_page'] = $params['extended_album_enable_page'];
        $params['container_id'] = 'bwg_album_extended_' . $bwg;
        /* Set theme parameters for back button.*/
        $theme_row->back_padding = $theme_row->album_extended_back_padding;
        $theme_row->back_font_size = $theme_row->album_extended_back_font_size;
        $theme_row->back_font_style = $theme_row->album_extended_back_font_style;
        $theme_row->back_font_weight = $theme_row->album_extended_back_font_weight;
        $theme_row->back_font_color = $theme_row->album_extended_back_font_color;
      }
      elseif ( isset($params['masonry_album_thumb_width']) ) {
        $params['gallery_view_type'] = 'masonry';
        $params['image_enable_page'] = $params['masonry_album_enable_page'];
        $params['container_id'] = 'bwg_album_masonry_' . $bwg;
        /* Set theme parameters for back button.*/
        $theme_row->back_padding = $theme_row->album_masonry_back_padding;
        $theme_row->back_font_size = $theme_row->album_masonry_back_font_size;
        $theme_row->back_font_style = $theme_row->album_masonry_back_font_style;
        $theme_row->back_font_weight = $theme_row->album_masonry_back_font_weight;
        $theme_row->back_font_color = $theme_row->album_masonry_back_font_color;
      }

      $params['showthumbs_name'] = $params['show_album_name'];

      if ( $params['album_view_type'] == 'album' ) { // Album in album.
        $from = (isset($params['from']) ? esc_html($params['from']) : 0);
        $album_row = $this->model->get_album_row_data($params['album_gallery_id'], $from === "widget");
        $params['album_row'] = $album_row;
        if ( isset($album_row->published) && $album_row->published == 0 ) {
          return;
        }
        if ( !$params['album_row'] ) {
          echo WDWLibrary::message(__('There is no album selected or the gallery was deleted.', BWG()->prefix), 'wd_error');

          return;
        }

        // Disable features for album.
        $params['gallery_download'] = FALSE;
        $params['show_search_box'] = FALSE;
        $params['show_sort_images'] = FALSE;
        $params['show_tag_box'] = FALSE;
        $params['gallery_id'] = 0;

        if ( isset($params['compuct_album_image_thumb_width']) ) { // Compact album view.
          $params['image_enable_page'] = $params['compuct_album_enable_page'];
          $params['images_per_page'] = $params['compuct_albums_per_page'];
          $params['items_col_num'] = $params['compuct_album_column_number'];
        }
        elseif ( isset($params['extended_album_image_thumb_width']) ) { // Extended album view.
          $params['image_enable_page'] = $params['extended_album_enable_page'];
          $params['images_per_page'] = $params['extended_albums_per_page'];
          $params['items_col_num'] = $params['extended_album_image_column_number'];
          $params['image_column_number'] = $params['extended_album_image_column_number'];
        }
        elseif ( isset($params['masonry_album_thumb_width']) ) {
          $params['image_enable_page'] = $params['masonry_album_enable_page'];
          $params['images_per_page'] = $params['masonry_albums_per_page'];
          $params['items_col_num'] = $params['masonry_album_column_number'];
          $params['image_column_number'] = $params['masonry_album_image_column_number'];
        }
        else {
          $params['image_enable_page'] = $params['compuct_album_enable_page'];
          $params['images_per_page'] = $params['compuct_albums_per_page'];
          $params['items_col_num'] = $params['compuct_album_column_number'];
        }

        $params['album_gallery_div_class'] = 'bwg_album_thumbnails_' . $bwg;
        $params['load_more_image_count'] = $params['images_per_page'];
        $params['items_per_page'] = array('images_per_page' => $params['images_per_page'], 'load_more_image_count' => $params['load_more_image_count']);

        $album_gallery_rows = $this->model->get_alb_gals_row($bwg, $params['album_gallery_id'], $params['images_per_page'], $params['sort_by'], $params['image_enable_page'], $from);
        $params['album_gallery_rows'] = $album_gallery_rows;
      }
      else { // Gallery views (thumbnail/masonry/mosaic).
        if ( $params['gallery_view_type'] == 'masonry' ) {
          $params['gallery_type'] = 'thumbnails_masonry';
        }
        elseif ( $params['gallery_view_type'] == 'mosaic' ) {
          $params['gallery_type'] = 'thumbnails_mosaic';
        }
        else {
          $params['gallery_type'] = 'thumbnails';
        }

        $params['gallery_id'] = $params['album_gallery_id'];
        $params['container_id'] = 'bwg_' . $params['gallery_type'] . '_' . $bwg;

        /* Set parameters for gallery view from album shortcode.*/
        if ( isset($params['compuct_album_image_thumb_width']) ) { // Compact album view.
          $params['thumb_width'] = $params['compuct_album_image_thumb_width'];
          $params['thumb_height'] = $params['compuct_album_image_thumb_height'];
          $params['image_title'] = $params['compuct_album_image_title'];

          $params['image_column_number'] = $params['compuct_album_image_column_number'];
          $params['images_per_page'] = $params['compuct_album_images_per_page'];

          $params['mosaic_hor_ver'] = $params['compuct_album_mosaic_hor_ver'];
          $params['resizable_mosaic'] = $params['compuct_album_resizable_mosaic'];
          $params['mosaic_total_width'] = $params['compuct_album_mosaic_total_width'];
        }
        elseif ( isset($params['extended_album_image_thumb_width']) ) { // Extended album view.
          $params['thumb_width'] = $params['extended_album_image_thumb_width'];
          $params['thumb_height'] = $params['extended_album_image_thumb_height'];
          $params['image_title'] = $params['extended_album_image_title'];

          $params['image_column_number'] = $params['extended_album_image_column_number'];
          $params['images_per_page'] = $params['extended_album_images_per_page'];

          $params['mosaic_hor_ver'] = $params['extended_album_mosaic_hor_ver'];
          $params['resizable_mosaic'] = $params['extended_album_resizable_mosaic'];
          $params['mosaic_total_width'] = $params['extended_album_mosaic_total_width'];
        }
        elseif ( isset($params['masonry_album_thumb_width']) ) {
          $params['thumb_width'] = $params['masonry_album_image_thumb_width'];
          $params['image_column_number'] = $params['masonry_album_image_column_number'];
          $params['images_per_page'] = $params['masonry_album_images_per_page'];
          $params['play_icon'] = BWG()->options->masonry_play_icon;
        }

        $params['masonry_hor_ver'] = BWG()->options->masonry;
        $params['show_masonry_thumb_description'] = BWG()->options->show_masonry_thumb_description;

        $gallery_row = $this->model->get_gallery_row_data($params['gallery_id']);

        if ( empty($gallery_row) && $params['type'] == '' && $params["tag"] == 0 ) {
          echo WDWLibrary::message(__('There is no gallery selected or the gallery was deleted.', BWG()->prefix), 'wd_error');
          return;
        }
        else {
          $params['gallery_row'] = $gallery_row;
        }

        $params['load_more_image_count'] = $params['images_per_page'];
        $params['items_per_page'] = array('images_per_page' => $params['images_per_page'], 'load_more_image_count' => $params['load_more_image_count']);

        $params['image_rows'] = $this->model->get_image_rows_data($params['gallery_id'], $bwg, $params['type'], 'bwg_tag_id_bwg_'.$params['gallery_type'].'_' . $bwg, $params['tag'], $params['images_per_page'], $params['load_more_image_count'], $params['sort_by'], $params['order_by']);

        // Disable Jetpack Photon module for gallery images.
        $this->thumb_urls = $params['image_rows']['thumb_urls'];
        if ( class_exists('Jetpack') && Jetpack::is_module_active('photon') ) {
          add_filter( 'jetpack_photon_skip_image', array($this, 'disable_jetpack'), 11, 3 );
        }

        $params['tags_rows'] = $this->model->get_tags_rows_data($params['gallery_id']);
      }
    }
    else { // View type gallery.
      $params['view_type'] = 'gallery';
      $params['album_view_type'] = '';
      $params['container_id'] = 'bwg_' . $params['gallery_type'] . '_' . $bwg;
      $params['cur_alb_gal_id'] = 0;
      $gallery_row = $this->model->get_gallery_row_data($params['gallery_id']);
      if( !empty($gallery_row) && isset($gallery_row->published) && $gallery_row->published == 0 ) {
        return;
      }
      if ( empty($gallery_row) && $params['type'] == '' && $params["tag"] == 0 ) {
        echo WDWLibrary::message(__('There is no gallery selected or the gallery was deleted.', BWG()->prefix), 'wd_error');
        return;
      }
      else {
        $params['gallery_row'] = $gallery_row;
      }

      $params['load_more_image_count'] = (isset($params['load_more_image_count']) && ($params['image_enable_page'] == 2)) ? $params['load_more_image_count'] : $params['images_per_page'];
      $params['items_per_page'] = array('images_per_page' => $params['images_per_page'], 'load_more_image_count' => $params['load_more_image_count']);
      if ( $params['gallery_type'] == 'image_browser' ) {
        $params['image_enable_page'] = 1;
        $params['images_per_page'] = 1;
        $params['load_more_image_count'] = 1;
      }
      if ( $params['gallery_type'] == 'blog_style' ) {
        $params['image_enable_page'] = $params['blog_style_enable_page'];
        $params['images_per_page'] =  $params['blog_style_images_per_page'];
        $params['load_more_image_count'] = (isset($params['blog_style_load_more_image_count']) && ($params['image_enable_page'] == 2)) ? $params['blog_style_load_more_image_count'] : $params['images_per_page'];
        $params['items_per_page'] = array('images_per_page' => $params['images_per_page'], 'load_more_image_count' => $params['load_more_image_count']);
      }
      $params['image_rows'] = $this->model->get_image_rows_data($params['gallery_id'], $bwg, $params['type'], 'bwg_tag_id_bwg_'.$params['gallery_type'].'_' . $bwg, $params['tag'], $params['images_per_page'], $params['load_more_image_count'], $params['sort_by'], $params['order_by']);

      // Disable Jetpack Photon module for gallery images.
      $this->thumb_urls = $params['image_rows']['thumb_urls'];
      if ( class_exists('Jetpack') && Jetpack::is_module_active('photon') ) {
        add_filter( 'jetpack_photon_skip_image', array($this, 'disable_jetpack'), 11, 3 );
      }

      $params['tags_rows'] = $this->model->get_tags_rows_data($params['gallery_id']);
    }

    $params['current_url'] = (is_ssl() ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $params_array = array(
      'action' => 'GalleryBox',
      'tags' => (isset($params['tag']) ? $params['tag'] : 0),
      'current_view' => $bwg,
      'gallery_id' => $params['gallery_id'],
      'theme_id' => $params['theme_id'],
      'thumb_width' => $params['thumb_width'],
      'thumb_height' => $params['thumb_height'],
      'open_with_fullscreen' => $params['popup_fullscreen'],
      'open_with_autoplay' => $params['popup_autoplay'],
      'image_width' => $params['popup_width'],
      'image_height' => $params['popup_height'],
      'image_effect' => $params['popup_effect'],
      'wd_sor' => ($params['sort_by'] == 'RAND()') ? 'order' : $params['sort_by'],
      'wd_ord' => $params['order_by'],
      'enable_image_filmstrip' => $params['popup_enable_filmstrip'],
      'image_filmstrip_height' => $params['popup_filmstrip_height'],
      'enable_image_ctrl_btn' => $params['popup_enable_ctrl_btn'],
      'enable_image_fullscreen' => $params['popup_enable_fullscreen'],
      'popup_enable_info' => $params['popup_enable_info'],
      'popup_info_always_show' => $params['popup_info_always_show'],
      'popup_info_full_width' => $params['popup_info_full_width'],
      'popup_hit_counter' => $params['popup_hit_counter'],
      'popup_enable_rate' => $params['popup_enable_rate'],
      'slideshow_interval' => $params['popup_interval'],
      'enable_comment_social' => $params['popup_enable_comment'],
      'enable_image_facebook' => $params['popup_enable_facebook'],
      'enable_image_twitter' => $params['popup_enable_twitter'],
      'enable_image_google' => $params['popup_enable_google'],
      'enable_image_ecommerce' => $params['popup_enable_ecommerce'],
      'enable_image_pinterest' => $params['popup_enable_pinterest'],
      'enable_image_tumblr' => $params['popup_enable_tumblr'],
      'watermark_type' => $params['watermark_type'],
      'slideshow_effect_duration' => isset($params['popup_effect_duration']) ? $params['popup_effect_duration'] : 1,
      'current_url' => urlencode($params['current_url']),
      'popup_enable_email' => $params['popup_enable_email'],
      'popup_enable_captcha' => $params['popup_enable_captcha'],
      'comment_moderation' => $params['comment_moderation'],
      'autohide_lightbox_navigation' => $params['autohide_lightbox_navigation'],
      'popup_enable_fullsize_image' => $params['popup_enable_fullsize_image'],
      'popup_enable_download' => $params['popup_enable_download'],
      'show_image_counts' => $params['show_image_counts'],
      'enable_loop' => $params['enable_loop'],
      'enable_addthis' => $params['enable_addthis'],
      'addthis_profile_id' => $params['addthis_profile_id'],
    );
    if ($params['watermark_type'] != 'none') {
      $params_array['watermark_link'] = $params['watermark_link'];
      $params_array['watermark_opacity'] = $params['watermark_opacity'];
      $params_array['watermark_position'] = $params['watermark_position'];
    }
    if ($params['watermark_type'] == 'text') {
      $params_array['watermark_text'] = $params['watermark_text'];
      $params_array['watermark_font_size'] = $params['watermark_font_size'];
      $params_array['watermark_font'] = $params['watermark_font'];
      $params_array['watermark_color'] = $params['watermark_color'];
    }
    elseif ($params['watermark_type'] == 'image') {
      $params_array['watermark_url'] = $params['watermark_url'];
      $params_array['watermark_width'] = $params['watermark_width'];
      $params_array['watermark_height'] = $params['watermark_height'];
    }
    $params['params_array'] = $params_array;

    $params[ 'theme_row' ] = $theme_row;

  	$this->display($params, $from_shortcode, $bwg);
  }

  public function display($params = array(), $from_shortcode = 0, $bwg = 0) {
    $params['ajax'] = isset($params['ajax']) ? TRUE : FALSE;
    $this->view->display($params, $bwg, $params['ajax']);
    if ($from_shortcode) {
      return;
    }
    else {
      die();
    }
  }

  /**
   * Disable Jetpack Photon module for gallery images.
   *
   * @param $val
   * @param $src
   * @param $tag
   *
   * @return bool
   */
  public function disable_jetpack( $val, $src, $tag ) {
    if ( in_array($src, $this->thumb_urls) ) {
      return TRUE;
    }

    return $val;
  }
}
