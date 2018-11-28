<?php
/**
 * Class ThemesModel_bwg
 */
class ThemesModel_bwg {
  /**
   * Get rows data.
   *
   * @param  $params
   *
   * @return array $rows
   */
  public function get_rows_data( $params, $total = FALSE ) {
    global $wpdb;
    $order = $params['order'];
    $orderby = $params['orderby'];
    $page_per = $params['items_per_page'];
    $page_num = $params['page_num'];
    $search = $params['search'];

    if ( !$total ) {
      $query = 'SELECT *';
    }
    else {
      $query = 'SELECT COUNT(*)';
    }
    $query .= ' FROM `' . $wpdb->prefix . 'bwg_theme` AS `t`';

    if ( $search ) {
      $query .= ' WHERE `t`.`name` LIKE "%' . $search . '%"';
    }

    if ( !$total ) {
      $query .= ' ORDER BY `t`.`' . $orderby . '` ' . $order;
      $query .= ' LIMIT ' . $page_num . ',' . $page_per;
    }

    if ( !$total ) {
      $rows = $wpdb->get_results($query);
    }
    else {
      $rows = $wpdb->get_var($query);
    }
    return $rows;
  }
  
  public function get_row_data($id, $reset = FALSE) {
    global $wpdb;
    if ( $id != 0 ) {
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwg_theme WHERE id="%d"', $id));
      $themes = json_decode($row->options);
      foreach ($themes as $key => $value) {
        $row->$key = $value;
      }
      if (!isset($row->lightbox_bg_transparent)) {
        $row->lightbox_bg_transparent = 100;
      }
      if (!isset($row->image_browser_image_title_align)) {
        $row->image_browser_image_title_align = 'top';
      }
      if (!isset($row->thumb_gal_title_font_color)) {
        $row->thumb_gal_title_font_color = $row->album_compact_back_font_color;
      }
      if (!isset($row->thumb_gal_title_font_style)) {
        $row->thumb_gal_title_font_style = $row->album_compact_back_font_style;
      }
      if (!isset($row->thumb_gal_title_font_size)) {
        $row->thumb_gal_title_font_size = $row->album_compact_back_font_size;
      }
      if (!isset($row->thumb_gal_title_font_weight)) {
        $row->thumb_gal_title_font_weight = $row->album_compact_back_font_weight;
      }
      if (!isset($row->thumb_gal_title_margin)) {
        $row->thumb_gal_title_margin = '2px';
      }
      if (!isset($row->thumb_gal_title_shadow)) {
        $row->thumb_gal_title_shadow = '0px 0px 0px #888888';
      }
      if (!isset($row->thumb_gal_title_align)) {
        $row->thumb_gal_title_align = 'center';
      }
      if (!isset($row->album_compact_gal_title_font_color)) {
        $row->album_compact_gal_title_font_color = $row->album_compact_back_font_color;
      }
      if (!isset($row->album_compact_gal_title_font_style)) {
        $row->album_compact_gal_title_font_style = $row->album_compact_back_font_style;
      }
      if (!isset($row->album_compact_gal_title_font_size)) {
        $row->album_compact_gal_title_font_size = $row->album_compact_back_font_size;
      }
      if (!isset($row->album_compact_gal_title_font_weight)) {
        $row->album_compact_gal_title_font_weight = $row->album_compact_back_font_weight;
      }
      if (!isset($row->album_compact_gal_title_margin)) {
        $row->album_compact_gal_title_margin = '0 2px 2px 2px';
      }
      if (!isset($row->album_compact_gal_title_shadow)) {
        $row->album_compact_gal_title_shadow = '0px 0px 0px #888888';
      }
      if (!isset($row->album_compact_gal_title_align)) {
        $row->album_compact_gal_title_align = 'center';
      }
      if (!isset($row->album_extended_gal_title_font_color)) {
        $row->album_extended_gal_title_font_color = $row->album_extended_back_font_color;
      }
      if (!isset($row->album_extended_gal_title_font_style)) {
        $row->album_extended_gal_title_font_style = $row->album_extended_back_font_style;
      }
      if (!isset($row->album_extended_gal_title_font_size)) {
        $row->album_extended_gal_title_font_size = $row->album_extended_back_font_size;
      }
      if (!isset($row->album_extended_gal_title_font_weight)) {
        $row->album_extended_gal_title_font_weight = $row->album_extended_back_font_weight;
      }
      if (!isset($row->album_extended_gal_title_margin)) {
        $row->album_extended_gal_title_margin = '0 2px 2px 2px';
      }
      if (!isset($row->album_extended_gal_title_shadow)) {
        $row->album_extended_gal_title_shadow = '0px 0px 0px #888888';
      }
      if (!isset($row->album_extended_gal_title_align)) {
        $row->album_extended_gal_title_align = 'center';
      }
      if (!isset($row->masonry_thumb_bg_color)) {
        $row->masonry_thumb_bg_color = '000000';
      }
	    if (!isset($row->masonry_thumbs_bg_color)) {
        $row->masonry_thumbs_bg_color = 'FFFFFF';
      }
	    if (!isset($row->masonry_thumb_title_font_size)) {
        $row->masonry_thumb_title_font_size = 16;
      }
	    if (!isset($row->masonry_thumb_title_font_color)) {
        $row->masonry_thumb_title_font_color = '323A45';
      }
	    if (!isset($row->album_masonry_thumb_title_font_color_hover)) {
        $row->album_masonry_thumb_title_font_color_hover = 'FFFFFF';
      }
	    if (!isset($row->masonry_thumb_title_font_color_hover)) {
        $row->masonry_thumb_title_font_color_hover = 'FFFFFF';
      }
	    if (!isset($row->masonry_thumb_title_font_style)) {
        $row->masonry_thumb_title_font_style = 'Ubuntu';
      }
	    if (!isset($row->masonry_thumb_title_font_weight)) {
        $row->masonry_thumb_title_font_weight = 'bold';
      }
	    if (!isset($row->masonry_thumb_title_margin)) {
        $row->masonry_thumb_title_margin = '2px';
      }
	    if (!isset($row->masonry_thumb_gal_title_font_color)) {
        $row->masonry_thumb_gal_title_font_color = $row->album_compact_back_font_color;
      }
      if (!isset($row->masonry_thumb_gal_title_font_style)) {
        $row->masonry_thumb_gal_title_font_style = $row->album_compact_back_font_style;
      }
      if (!isset($row->masonry_thumb_gal_title_font_size)) {
        $row->masonry_thumb_gal_title_font_size = $row->album_compact_back_font_size;
      }
      if (!isset($row->masonry_thumb_gal_title_font_weight)) {
        $row->masonry_thumb_gal_title_font_weight = $row->album_compact_back_font_weight;
      }
      if (!isset($row->masonry_thumb_gal_title_margin)) {
        $row->masonry_thumb_gal_title_margin = '2px';
      }
      if (!isset($row->masonry_thumb_gal_title_shadow)) {
        $row->masonry_thumb_gal_title_shadow = '';
      }
      if (!isset($row->masonry_thumb_gal_title_align)) {
        $row->masonry_thumb_gal_title_align = 'center';
      }
      if (!isset($row->album_masonry_gal_title_font_color)) {
        $row->album_masonry_gal_title_font_color = $row->album_masonry_back_font_color;
      }
      if (!isset($row->album_masonry_gal_title_font_style)) {
        $row->album_masonry_gal_title_font_style = $row->album_masonry_back_font_style;
      }
      if (!isset($row->album_masonry_gal_title_font_size)) {
        $row->album_masonry_gal_title_font_size = $row->album_masonry_back_font_size;
      }
      if (!isset($row->album_masonry_gal_title_font_weight)) {
        $row->album_masonry_gal_title_font_weight = $row->album_masonry_back_font_weight;
      }
      if (!isset($row->album_masonry_gal_title_margin)) {
        $row->album_masonry_gal_title_margin = '0 2px 2px 2px';
      }
      if (!isset($row->album_masonry_gal_title_shadow)) {
        $row->album_masonry_gal_title_shadow = '0px 0px 0px #888888';
      }
      if (!isset($row->album_masonry_gal_title_align)) {
        $row->album_masonry_gal_title_align = 'center';
      }
      if (!isset($row->mosaic_thumb_bg_color)) {
        $row->mosaic_thumb_bg_color = "000000";
      }
      if (!isset($row->mosaic_thumb_gal_title_font_color)) {
        $row->mosaic_thumb_gal_title_font_color = $row->album_compact_back_font_color;
      }
      if (!isset($row->mosaic_thumb_gal_title_font_style)) {
        $row->mosaic_thumb_gal_title_font_style = $row->album_compact_back_font_style;
      }
      if (!isset($row->mosaic_thumb_gal_title_font_size)) {
        $row->mosaic_thumb_gal_title_font_size = $row->album_compact_back_font_size;
      }
      if (!isset($row->mosaic_thumb_gal_title_font_weight)) {
        $row->mosaic_thumb_gal_title_font_weight = $row->album_compact_back_font_weight;
      }
      if (!isset($row->mosaic_thumb_gal_title_margin)) {
        $row->mosaic_thumb_gal_title_margin = '2px';
      }
      if (!isset($row->mosaic_thumb_gal_title_shadow)) {
        $row->mosaic_thumb_gal_title_shadow = '';
      }
      if (!isset($row->mosaic_thumb_gal_title_align)) {
        $row->mosaic_thumb_gal_title_align = 'center';
      }
      if (!isset($row->image_browser_gal_title_font_color)) {
        $row->image_browser_gal_title_font_color = '323A45';
      }
      if (!isset($row->image_browser_gal_title_font_style)) {
        $row->image_browser_gal_title_font_style = 'Ubuntu';
      }
      if (!isset($row->image_browser_gal_title_font_size)) {
        $row->image_browser_gal_title_font_size = 16;
      }
      if (!isset($row->image_browser_gal_title_font_weight)) {
        $row->image_browser_gal_title_font_weight = 'bold';
      }
      if (!isset($row->image_browser_gal_title_margin)) {
        $row->image_browser_gal_title_margin = '2px';
      }
      if (!isset($row->image_browser_gal_title_shadow)) {
        $row->image_browser_gal_title_shadow = '0px 0px 0px #888888';
      }
      if (!isset($row->image_browser_gal_title_align)) {
        $row->image_browser_gal_title_align = 'center';
      }
      if (!isset($row->blog_style_gal_title_font_color)) {
        $row->blog_style_gal_title_font_color = '323A45';
      }
      if (!isset($row->blog_style_gal_title_font_style)) {
        $row->blog_style_gal_title_font_style = 'Ubuntu';
      }
      if (!isset($row->blog_style_gal_title_font_size)) {
        $row->blog_style_gal_title_font_size = 16;
      }
      if (!isset($row->blog_style_gal_title_font_weight)) {
        $row->blog_style_gal_title_font_weight = 'bold';
      }
      if (!isset($row->blog_style_gal_title_margin)) {
        $row->blog_style_gal_title_margin = '2px';
      }
      if (!isset($row->blog_style_gal_title_shadow)) {
        $row->blog_style_gal_title_shadow = '0px 0px 0px #888888';
      }
      if (!isset($row->blog_style_gal_title_align)) {
        $row->blog_style_gal_title_align = 'center';
      }
      if (!isset($row->album_masonry_thumb_padding)) {
        $row->album_masonry_thumb_padding = 4;
      }
      if (!isset($row->album_masonry_container_margin)) {
        $row->album_masonry_container_margin = 1;
      }
      if (!isset($row->mosaic_thumb_title_font_color_hover)) {
        $row->mosaic_thumb_title_font_color_hover = 'FFFFFF';
      }
      if (!isset($row->album_compact_title_font_color_hover)) {
        $row->album_compact_title_font_color_hover = 'FFFFFF';
      }
      if ( $reset ) {
        if ( !$row->default_theme ) {
          $row_id = $row->id;
          $row_name = $row->name;
          $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwg_theme WHERE default_theme="%d"', 1));
          $row->id = $row_id;
          $row->name = $row_name;
          $row->default_theme = FALSE;
          $themes = json_decode($row->options);
          foreach ($themes as $key => $value) {
            $row->$key = $value;
          }
          if (!isset($row->lightbox_bg_transparent)) {
            $row->lightbox_bg_transparent = 100;
          }
          if (!isset($row->image_browser_image_title_align)) {
            $row->image_browser_image_title_align = 'top';
          }
          if (!isset($row->thumb_gal_title_font_color)) {
            $row->thumb_gal_title_font_color = '323A45';
          }
          if (!isset($row->thumb_gal_title_font_style)) {
            $row->thumb_gal_title_font_style = 'Ubuntu';
          }
          if (!isset($row->thumb_gal_title_font_size)) {
            $row->thumb_gal_title_font_size = 18;
          }
          if (!isset($row->thumb_gal_title_font_weight)) {
            $row->thumb_gal_title_font_weight = 'bold';
          }
          if (!isset($row->thumb_gal_title_margin)) {
            $row->thumb_gal_title_margin = '2px';
          }
          if (!isset($row->thumb_gal_title_shadow)) {
            $row->thumb_gal_title_shadow = '0px 0px 0px #888888';
          }
          if (!isset($row->thumb_gal_title_align)) {
            $row->thumb_gal_title_align = 'center';
          }
          if (!isset($row->album_compact_gal_title_font_color)) {
            $row->album_compact_gal_title_font_color = '323A45';
          }
          if (!isset($row->album_compact_gal_title_font_style)) {
            $row->album_compact_gal_title_font_style = 'Ubuntu';
          }
          if (!isset($row->album_compact_gal_title_font_size)) {
            $row->album_compact_gal_title_font_size = 18;
          }
          if (!isset($row->album_compact_gal_title_font_weight)) {
            $row->album_compact_gal_title_font_weight = 'bold';
          }
          if (!isset($row->album_compact_gal_title_margin)) {
            $row->album_compact_gal_title_margin = '0 2px 2px 2px';
          }
          if (!isset($row->album_compact_gal_title_shadow)) {
            $row->album_compact_gal_title_shadow = '0px 0px 0px #888888';
          }
          if (!isset($row->album_compact_gal_title_align)) {
            $row->album_compact_gal_title_align = 'center';
          }
          if (!isset($row->album_extended_gal_title_font_color)) {
            $row->album_extended_gal_title_font_color = '323A45';
          }
          if (!isset($row->album_extended_gal_title_font_style)) {
            $row->album_extended_gal_title_font_style = 'Ubuntu';
          }
          if (!isset($row->album_extended_gal_title_font_size)) {
            $row->album_extended_gal_title_font_size = 18;
          }
          if (!isset($row->album_extended_gal_title_font_weight)) {
            $row->album_extended_gal_title_font_weight = 'bold';
          }
          if (!isset($row->album_extended_gal_title_margin)) {
            $row->album_extended_gal_title_margin = '0 2px 2px 2px';
          }
          if (!isset($row->album_extended_gal_title_shadow)) {
            $row->album_extended_gal_title_shadow = '0px 0px 0px #888888';
          }
          if (!isset($row->album_extended_gal_title_align)) {
            $row->album_extended_gal_title_align = 'center';
          }
          if ($row->masonry_thumb_hover_effect) {
            $row->masonry_thumb_hover_effect = 'zoom';
          }
          if ($row->masonry_thumb_hover_effect_value) {
            $row->masonry_thumb_hover_effect_value = '1.08';
          }
          if ($row->masonry_thumb_bg_color) {
            $row->masonry_thumb_bg_color = '000000';
          }
          if ($row->masonry_thumbs_bg_color) {
            $row->masonry_thumbs_bg_color = 'FFFFFF';
          }
          if ($row->masonry_thumb_title_font_size) {
            $row->masonry_thumb_title_font_size = 16;
          }
          if ($row->masonry_thumb_title_font_color) {
            $row->masonry_thumb_title_font_color = '323A45';
          }
          if ($row->masonry_thumb_title_font_color_hover) {
            $row->masonry_thumb_title_font_color_hover = 'FFFFFF';
          }
          if ($row->masonry_thumb_title_font_style) {
            $row->masonry_thumb_title_font_style = 'Ubuntu';
          }
          if ($row->masonry_thumb_title_font_weight) {
            $row->masonry_thumb_title_font_weight = 'bold';
          }
          if ($row->masonry_thumb_title_margin) {
            $row->masonry_thumb_title_margin = '2px';
          }
          if ($row->masonry_thumb_gal_title_font_color) {
            $row->masonry_thumb_gal_title_font_color = '323A45';
          }
          if ($row->masonry_thumb_gal_title_font_style) {
            $row->masonry_thumb_gal_title_font_style = 'Ubuntu';
          }
          if ($row->masonry_thumb_gal_title_font_size) {
            $row->masonry_thumb_gal_title_font_size = 18;
          }
          if ($row->masonry_thumb_gal_title_font_weight) {
            $row->masonry_thumb_gal_title_font_weight = 'bold';
          }
          if ($row->masonry_thumb_gal_title_margin) {
            $row->masonry_thumb_gal_title_margin = '2px';
          }
          if ($row->masonry_thumb_gal_title_shadow) {
            $row->masonry_thumb_gal_title_shadow = '';
          }
          if ($row->masonry_thumb_gal_title_align) {
            $row->masonry_thumb_gal_title_align = 'center';
          }
          if ($row->masonry_description_font_size) {
            $row->masonry_description_font_size = 16;
          }
          if ($row->masonry_description_color) {
            $row->masonry_description_color = '323A45';
          }
          if (!isset($row->album_masonry_gal_title_font_color)) {
            $row->album_masonry_gal_title_font_color = '323A45';
          }
          if (!isset($row->album_masonry_gal_title_font_style)) {
            $row->album_masonry_gal_title_font_style = 'Ubuntu';
          }
          if (!isset($row->album_masonry_gal_title_font_size)) {
            $row->album_masonry_gal_title_font_size = 18;
          }
          if (!isset($row->album_masonry_gal_title_font_weight)) {
            $row->album_masonry_gal_title_font_weight = 'bold';
          }
          if (!isset($row->album_masonry_gal_title_margin)) {
            $row->album_masonry_gal_title_margin = '0 2px 2px 2px';
          }
          if (!isset($row->album_masonry_gal_title_shadow)) {
            $row->album_masonry_gal_title_shadow = '0px 0px 0px #888888';
          }
          if (!isset($row->album_masonry_gal_title_align)) {
            $row->album_masonry_gal_title_align = 'center';
          }
          if (!isset($row->mosaic_thumb_gal_title_font_color)) {
            $row->mosaic_thumb_gal_title_font_color = '323A45';
          }
          if (!isset($row->mosaic_thumb_gal_title_font_style)) {
            $row->mosaic_thumb_gal_title_font_style = 'Ubuntu';
          }
          if (!isset($row->mosaic_thumb_gal_title_font_size)) {
            $row->mosaic_thumb_gal_title_font_size = '18';
          }
          if (!isset($row->mosaic_thumb_gal_title_font_weight)) {
            $row->mosaic_thumb_gal_title_font_weight = 'bold';
          }
          if (!isset($row->mosaic_thumb_gal_title_margin)) {
            $row->mosaic_thumb_gal_title_margin = '2px';
          }
          if (!isset($row->mosaic_thumb_gal_title_shadow)) {
            $row->mosaic_thumb_gal_title_shadow = '';
          }
          if (!isset($row->mosaic_thumb_gal_title_align)) {
            $row->mosaic_thumb_gal_title_align = 'center';
          }
          if (!isset($row->image_browser_gal_title_font_color)) {
            $row->image_browser_gal_title_font_color = '323A45';
          }
          if (!isset($row->image_browser_gal_title_font_style)) {
            $row->image_browser_gal_title_font_style = 'Ubuntu';
          }
          if (!isset($row->image_browser_gal_title_font_size)) {
            $row->image_browser_gal_title_font_size = 16;
          }
          if (!isset($row->image_browser_gal_title_font_weight)) {
            $row->image_browser_gal_title_font_weight = 'bold';
          }
          if (!isset($row->image_browser_gal_title_margin)) {
            $row->image_browser_gal_title_margin = '2px';
          }
          if (!isset($row->image_browser_gal_title_shadow)) {
            $row->image_browser_gal_title_shadow = '0px 0px 0px #888888';
          }
          if (!isset($row->image_browser_gal_title_align)) {
            $row->image_browser_gal_title_align = 'center';
          }
          if (!isset($row->blog_style_gal_title_font_color)) {
            $row->blog_style_gal_title_font_color = '323A45';
          }
          if (!isset($row->blog_style_gal_title_font_style)) {
            $row->blog_style_gal_title_font_style = 'Ubuntu';
          }
          if (!isset($row->blog_style_gal_title_font_size)) {
            $row->blog_style_gal_title_font_size = 16;
          }
          if (!isset($row->blog_style_gal_title_font_weight)) {
            $row->blog_style_gal_title_font_weight = 'bold';
          }
          if (!isset($row->blog_style_gal_title_margin)) {
            $row->blog_style_gal_title_margin = '2px';
          }
          if (!isset($row->blog_style_gal_title_shadow)) {
            $row->blog_style_gal_title_shadow = '0px 0px 0px #888888';
          }
          if (!isset($row->blog_style_gal_title_align)) {
            $row->blog_style_gal_title_align = 'center';
          }
          if ($row->thumb_hover_effect) {
            $row->thumb_hover_effect = 'zoom';
          }
          if ($row->thumb_hover_effect_value) {
            $row->thumb_hover_effect_value = '1.08';
          }
          if ($row->thumb_bg_color) {
            $row->thumb_bg_color = '000000';
          }
          if ($row->thumb_title_font_color) {
            $row->thumb_title_font_color = '323A45';
          }
          if ($row->thumb_title_font_color_hover) {
            $row->thumb_title_font_color_hover = 'FFFFFF';
          }
          if ($row->album_compact_title_font_color_hover) {
            $row->album_compact_title_font_color_hover = 'FFFFFF';
          }
          if ($row->thumb_title_shadow) {
            $row->thumb_title_shadow = '';
          }
          if ($row->thumb_gal_title_font_color) {
            $row->thumb_gal_title_font_color = '323A45';
          }
          if ($row->thumb_gal_title_font_style) {
            $row->thumb_gal_title_font_style = 'Ubuntu';
          }
          if ($row->thumb_gal_title_font_size) {
            $row->thumb_gal_title_font_size = '18';
          }
          if ($row->thumb_gal_title_shadow) {
            $row->thumb_gal_title_shadow = '';
          }
          /* Mosaic */
          if ($row->mosaic_thumb_bg_color) {
            $row->mosaic_thumb_bg_color = '000000';
          }
          if ($row->mosaic_thumbs_bg_color) {
            $row->mosaic_thumbs_bg_color = 'FFFFFF';
          }
          if ($row->mosaic_thumb_hover_effect) {
            $row->mosaic_thumb_hover_effect = 'zoom';
          }
          if ($row->mosaic_thumb_hover_effect_value) {
            $row->mosaic_thumb_hover_effect_value = '1.08';
          }
          if ($row->mosaic_thumb_title_font_color) {
            $row->mosaic_thumb_title_font_color = '323A45';
          }
          if ($row->mosaic_thumb_title_font_color_hover) {
            $row->mosaic_thumb_title_font_color_hover = 'FFFFFF';
          }
          if ($row->mosaic_thumb_title_font_style) {
            $row->mosaic_thumb_title_font_style = 'Ubuntu';
          }
          if ($row->mosaic_thumb_title_shadow) {
            $row->mosaic_thumb_title_shadow = '';
          }
          if ($row->mosaic_thumb_gal_title_font_size) {
            $row->mosaic_thumb_gal_title_font_size = '18';
          }
          if ($row->mosaic_thumb_gal_title_font_color) {
            $row->mosaic_thumb_gal_title_font_color = '323A45';
          }
          if ($row->mosaic_thumb_gal_title_font_style) {
            $row->mosaic_thumb_gal_title_font_style = 'Ubuntu';
          }
          if ($row->mosaic_thumb_gal_title_shadow) {
            $row->mosaic_thumb_gal_title_shadow = '';
          }
          if (!isset($row->album_masonry_container_margin)) {
            $row->album_masonry_container_margin = 1;
          }
        }
        else {
          $theme_defaults = '{"thumb_margin":"4","album_compact_title_font_color_hover":"FFFFFF","compact_container_margin":"1","container_margin":"1","thumb_padding":"0","thumb_border_radius":"0","thumb_border_width":0,"thumb_border_style":"none","thumb_border_color":"CCCCCC","thumb_bg_color":"000000","thumbs_bg_color":"FFFFFF","thumb_bg_transparent":0,"thumb_box_shadow":"","thumb_transparent":100,"thumb_align":"center","thumb_hover_effect":"zoom","thumb_hover_effect_value":"1.08","thumb_transition":1,"thumb_title_margin":"2px","thumb_title_font_style":"Ubuntu","thumb_title_pos":"bottom","thumb_title_font_color":"323A45","thumb_title_font_color_hover":"FFFFFF","thumb_title_shadow":"","thumb_title_font_size":16,"thumb_title_font_weight":"bold","thumb_gal_title_font_color":"323A45","thumb_gal_title_font_style":"Ubuntu","thumb_gal_title_font_size":18,"thumb_gal_title_font_weight":"bold","thumb_gal_title_margin":"2px","thumb_gal_title_shadow":"","thumb_gal_title_align":"center","page_nav_position":"bottom","page_nav_align":"center","page_nav_number":0,"page_nav_font_size":12,"page_nav_font_style":"Ubuntu","page_nav_font_color":"666666","page_nav_font_weight":"bold","page_nav_border_width":1,"page_nav_border_style":"solid","page_nav_border_color":"E3E3E3","page_nav_border_radius":"0","page_nav_margin":"0","page_nav_padding":"3px 6px","page_nav_button_bg_color":"FFFFFF","page_nav_button_bg_transparent":100,"page_nav_box_shadow":"0","page_nav_button_transition":1,"page_nav_button_text":0,"lightbox_ctrl_btn_pos":"bottom","lightbox_ctrl_btn_align":"center","lightbox_ctrl_btn_height":20,"lightbox_ctrl_btn_margin_top":10,"lightbox_ctrl_btn_margin_left":7,"lightbox_ctrl_btn_transparent":100,"lightbox_ctrl_btn_color":"808080","lightbox_toggle_btn_height":20,"lightbox_toggle_btn_width":100,"lightbox_ctrl_cont_bg_color":"FFFFFF","lightbox_ctrl_cont_border_radius":4,"lightbox_ctrl_cont_transparent":85,"lightbox_close_btn_bg_color":"FFFFFF","lightbox_close_btn_border_radius":"16px","lightbox_close_btn_border_width":2,"lightbox_close_btn_border_style":"none","lightbox_close_btn_border_color":"FFFFFF","lightbox_close_btn_box_shadow":"0","lightbox_close_btn_color":"808080","lightbox_close_btn_size":20,"lightbox_close_btn_width":30,"lightbox_close_btn_height":30,"lightbox_close_btn_top":"-20","lightbox_close_btn_right":"-15","lightbox_close_btn_full_color":"000000","lightbox_close_btn_transparent":60,"lightbox_rl_btn_bg_color":"FFFFFF","lightbox_rl_btn_transparent":"60","lightbox_rl_btn_border_radius":"20px","lightbox_rl_btn_border_width":0,"lightbox_rl_btn_border_style":"none","lightbox_rl_btn_border_color":"FFFFFF","lightbox_rl_btn_box_shadow":"","lightbox_rl_btn_color":"ADADAD","lightbox_rl_btn_height":35,"lightbox_rl_btn_width":35,"lightbox_rl_btn_size":25,"lightbox_close_rl_btn_hover_color":"808080","lightbox_comment_pos":"left","lightbox_comment_width":350,"lightbox_comment_bg_color":"FFFFFF","lightbox_comment_font_color":"7A7A7A","lightbox_comment_font_style":"Ubuntu","lightbox_comment_font_size":12,"lightbox_comment_button_bg_color":"2F2F2F","lightbox_comment_button_border_color":"666666","lightbox_comment_button_border_width":1,"lightbox_comment_button_border_style":"none","lightbox_comment_button_border_radius":"7px","lightbox_comment_button_padding":"10px 10px","lightbox_comment_input_bg_color":"F7F8F9","lightbox_comment_input_border_color":"EBEBEB","lightbox_comment_input_border_width":2,"lightbox_comment_input_border_style":"none","lightbox_comment_input_border_radius":"7px","lightbox_comment_input_padding":"5px","lightbox_comment_separator_width":20,"lightbox_comment_separator_style":"none","lightbox_comment_separator_color":"383838","lightbox_comment_author_font_size":14,"lightbox_comment_date_font_size":10,"lightbox_comment_body_font_size":12,"lightbox_comment_share_button_color":"808080","lightbox_filmstrip_rl_bg_color":"EBEBEB","lightbox_filmstrip_rl_btn_size":20,"lightbox_filmstrip_rl_btn_color":"808080","lightbox_filmstrip_thumb_margin":"0 1px","lightbox_filmstrip_thumb_border_width":1,"lightbox_filmstrip_thumb_border_style":"none","lightbox_filmstrip_thumb_border_color":"000000","lightbox_filmstrip_thumb_border_radius":"0","lightbox_filmstrip_thumb_deactive_transparent":80,"lightbox_filmstrip_pos":"bottom","lightbox_filmstrip_thumb_active_border_width":0,"lightbox_filmstrip_thumb_active_border_color":"FFFFFF","lightbox_overlay_bg_transparent":60,"lightbox_bg_color":"FFFFFF","lightbox_overlay_bg_color":"EEEEEE","lightbox_rl_btn_style":"fa-angle","lightbox_bg_transparent":100,"blog_style_margin":"2px","blog_style_padding":"0","blog_style_border_radius":"0","blog_style_border_width":1,"blog_style_border_style":"solid","blog_style_border_color":"F5F5F5","blog_style_bg_color":"FFFFFF","blog_style_transparent":80,"blog_style_box_shadow":"","blog_style_align":"center","blog_style_share_buttons_margin":"5px auto 10px auto","blog_style_share_buttons_border_radius":"0","blog_style_share_buttons_border_width":0,"blog_style_share_buttons_border_style":"none","blog_style_share_buttons_border_color":"000000","blog_style_share_buttons_bg_color":"FFFFFF","blog_style_share_buttons_align":"right","blog_style_img_font_size":16,"blog_style_img_font_family":"Ubuntu","blog_style_img_font_color":"000000","blog_style_share_buttons_font_size":20,"blog_style_share_buttons_color":"B3AFAF","blog_style_share_buttons_bg_transparent":0,"blog_style_gal_title_font_color":"323A45","blog_style_gal_title_font_style":"Ubuntu","blog_style_gal_title_font_size":16,"blog_style_gal_title_font_weight":"bold","blog_style_gal_title_margin":"2px","blog_style_gal_title_shadow":"0px 0px 0px #888888","blog_style_gal_title_align":"center","image_browser_margin":"2px auto","image_browser_padding":"4px","image_browser_border_radius":"0","image_browser_border_width":1,"image_browser_border_style":"none","image_browser_border_color":"F5F5F5","image_browser_bg_color":"EBEBEB","image_browser_box_shadow":"","image_browser_transparent":80,"image_browser_align":"center","image_browser_image_description_margin":"0px 5px 0px 5px","image_browser_image_description_padding":"8px 8px 8px 8px","image_browser_image_description_border_radius":"0","image_browser_image_description_border_width":1,"image_browser_image_description_border_style":"none","image_browser_image_description_border_color":"FFFFFF","image_browser_image_description_bg_color":"EBEBEB","image_browser_image_description_align":"center","image_browser_img_font_size":15,"image_browser_img_font_family":"Ubuntu","image_browser_img_font_color":"000000","image_browser_full_padding":"4px","image_browser_full_border_radius":"0","image_browser_full_border_width":2,"image_browser_full_border_style":"none","image_browser_full_border_color":"F7F7F7","image_browser_full_bg_color":"F5F5F5","image_browser_full_transparent":90,"image_browser_image_title_align":"top","image_browser_gal_title_font_color":"323A45","image_browser_gal_title_font_style":"Ubuntu","image_browser_gal_title_font_size":16,"image_browser_gal_title_font_weight":"bold","image_browser_gal_title_margin":"2px","image_browser_gal_title_shadow":"0px 0px 0px #888888","image_browser_gal_title_align":"center","album_compact_title_margin":"2px","album_compact_thumb_margin":4,"album_compact_back_padding":"0","album_compact_thumb_padding":0,"album_compact_thumb_border_radius":"0","album_compact_thumb_border_width":0,"album_compact_title_font_style":"Ubuntu","album_compact_back_font_color":"323A45","album_compact_title_font_color":"323A45","album_compact_title_shadow":"0px 0px 0px #888888","album_compact_thumb_bg_transparent":0,"album_compact_thumb_box_shadow":"0px 0px 0px #888888","album_compact_thumb_transition":1,"album_compact_thumb_border_style":"none","album_compact_thumb_border_color":"CCCCCC","album_compact_thumb_bg_color":"000000","album_compact_back_font_weight":"bold","album_compact_back_font_size":15,"album_compact_back_font_style":"Ubuntu","album_compact_thumb_title_pos":"bottom","album_compact_thumbs_bg_color":"FFFFFF","album_compact_title_font_size":16,"album_compact_title_font_weight":"bold","album_compact_thumb_align":"center","album_compact_thumb_hover_effect":"zoom","album_compact_thumb_transparent":100,"album_compact_thumb_hover_effect_value":"1.08","album_compact_gal_title_font_color":"323A45","album_compact_gal_title_font_style":"Ubuntu","album_compact_gal_title_font_size":18,"album_compact_gal_title_font_weight":"bold","album_compact_gal_title_margin":"0 2px 2px 2px","album_compact_gal_title_shadow":"0px 0px 0px #888888","album_compact_gal_title_align":"center","album_extended_thumb_margin":2,"album_extended_thumb_padding":0,"album_extended_thumb_border_radius":"0","album_extended_thumb_border_width":0,"album_extended_thumb_border_style":"none","album_extended_thumb_border_color":"CCCCCC","album_extended_thumb_bg_color":"FFFFFF","album_extended_thumbs_bg_color":"FFFFFF","album_extended_thumb_bg_transparent":0,"album_extended_thumb_box_shadow":"","album_extended_thumb_transparent":100,"album_extended_thumb_align":"left","album_extended_thumb_hover_effect":"scale","album_extended_thumb_hover_effect_value":"1.08","album_extended_thumb_transition":1,"album_extended_back_font_color":"323A45","album_extended_back_font_style":"Ubuntu","album_extended_back_font_size":15,"album_extended_back_font_weight":"bold","album_extended_back_padding":"0","album_extended_div_bg_color":"FFFFFF","album_extended_div_bg_transparent":0,"album_extended_div_border_radius":"0 0 0 0","album_extended_div_margin":"0 0 5px 0","album_extended_div_padding":10,"album_extended_div_separator_width":1,"album_extended_div_separator_style":"solid","album_extended_div_separator_color":"E0E0E0","album_extended_thumb_div_bg_color":"FFFFFF","album_extended_thumb_div_border_radius":"0","album_extended_thumb_div_border_width":1,"album_extended_thumb_div_border_style":"solid","album_extended_thumb_div_border_color":"E8E8E8","album_extended_thumb_div_padding":"5px","album_extended_text_div_bg_color":"FFFFFF","album_extended_text_div_border_radius":"0","album_extended_text_div_border_width":1,"album_extended_text_div_border_style":"solid","album_extended_text_div_border_color":"E8E8E8","album_extended_text_div_padding":"5px","album_extended_title_span_border_width":1,"album_extended_title_span_border_style":"none","album_extended_title_span_border_color":"CCCCCC","album_extended_title_font_color":"000000","album_extended_title_font_style":"Ubuntu","album_extended_title_font_size":16,"album_extended_title_font_weight":"bold","album_extended_title_margin_bottom":2,"album_extended_title_padding":"2px","album_extended_desc_span_border_width":1,"album_extended_desc_span_border_style":"none","album_extended_desc_span_border_color":"CCCCCC","album_extended_desc_font_color":"000000","album_extended_desc_font_style":"Ubuntu","album_extended_desc_font_size":14,"album_extended_desc_font_weight":"normal","album_extended_desc_padding":"2px","album_extended_desc_more_color":"F2D22E","album_extended_desc_more_size":12,"album_extended_gal_title_font_color":"323A45","album_extended_gal_title_font_style":"Ubuntu","album_extended_gal_title_font_size":18,"album_extended_gal_title_font_weight":"bold","album_extended_gal_title_margin":"0 2px 2px 2px","album_extended_gal_title_shadow":"0px 0px 0px #888888","album_extended_gal_title_align":"center","slideshow_cont_bg_color":"F2F2F2","slideshow_close_btn_transparent":100,"slideshow_rl_btn_bg_color":"FFFFFF","slideshow_rl_btn_border_radius":"20px","slideshow_rl_btn_border_width":0,"slideshow_rl_btn_border_style":"none","slideshow_rl_btn_border_color":"FFFFFF","slideshow_rl_btn_box_shadow":"","slideshow_rl_btn_color":"D6D6D6","slideshow_rl_btn_height":37,"slideshow_rl_btn_size":12,"slideshow_rl_btn_width":37,"slideshow_close_rl_btn_hover_color":"BABABA","slideshow_filmstrip_pos":"bottom","slideshow_filmstrip_thumb_border_width":0,"slideshow_filmstrip_thumb_border_style":"none","slideshow_filmstrip_thumb_border_color":"000000","slideshow_filmstrip_thumb_border_radius":"0","slideshow_filmstrip_thumb_margin":"0px 2px 0 0 ","slideshow_filmstrip_thumb_active_border_width":0,"slideshow_filmstrip_thumb_active_border_color":"FFFFFF","slideshow_filmstrip_thumb_deactive_transparent":100,"slideshow_filmstrip_rl_bg_color":"F2F2F2","slideshow_filmstrip_rl_btn_color":"BABABA","slideshow_filmstrip_rl_btn_size":20,"slideshow_title_font_size":16,"slideshow_title_font":"Ubuntu","slideshow_title_color":"FFFFFF","slideshow_title_opacity":70,"slideshow_title_border_radius":"5px","slideshow_title_background_color":"000000","slideshow_title_padding":"0 0 0 0","slideshow_description_font_size":14,"slideshow_description_font":"Ubuntu","slideshow_description_color":"FFFFFF","slideshow_description_opacity":70,"slideshow_description_border_radius":"0","slideshow_description_background_color":"000000","slideshow_description_padding":"5px 10px 5px 10px","slideshow_dots_width":12,"slideshow_dots_height":12,"slideshow_dots_border_radius":"5px","slideshow_dots_background_color":"F2D22E","slideshow_dots_margin":3,"slideshow_dots_active_background_color":"FFFFFF","slideshow_dots_active_border_width":1,"slideshow_dots_active_border_color":"000000","slideshow_play_pause_btn_size":35,"slideshow_rl_btn_style":"fa-chevron","masonry_thumb_padding":"4","masonry_container_margin":"1","masonry_thumb_border_width":"0","masonry_thumb_border_style":"none","masonry_thumb_border_color":"CCCCCC","masonry_thumb_border_radius":"0","masonry_thumb_hover_effect":"zoom","masonry_thumb_hover_effect_value":"1.08","masonry_thumb_transition":"1","masonry_thumbs_bg_color":"FFFFFF","masonry_thumb_bg_color":"000000","masonry_thumb_bg_transparent":"0","masonry_thumb_transparent":"100","masonry_thumb_align":"center","masonry_thumb_title_font_size":"16","masonry_thumb_title_font_color":"323A45","masonry_thumb_title_font_color_hover":"FFFFFF","masonry_thumb_title_font_style":"Ubuntu","masonry_thumb_title_font_weight":"bold","masonry_thumb_title_margin":"2px","masonry_description_font_size":16,"masonry_description_color":"323A45","masonry_description_font_style":"Ubuntu","masonry_thumb_gal_title_font_size":18,"masonry_thumb_gal_title_font_color":"323A45","masonry_thumb_gal_title_font_style":"Ubuntu","masonry_thumb_gal_title_font_weight":"bold","masonry_thumb_gal_title_shadow":"","masonry_thumb_gal_title_margin":"2px","masonry_thumb_gal_title_align":"center","mosaic_thumb_padding":"2","mosaic_container_margin":"1","mosaic_thumb_border_radius":"0","mosaic_thumb_border_width":"0","mosaic_thumb_border_style":"none","mosaic_thumb_border_color":"CCCCCC","mosaic_thumb_bg_color":"000000","mosaic_thumb_transparent":"100","mosaic_thumbs_bg_color":"FFFFFF","mosaic_thumb_bg_transparent":"0","mosaic_thumb_align":"center","mosaic_thumb_hover_effect":"zoom","mosaic_thumb_hover_effect_value":"1.08","mosaic_thumb_title_margin":"2px","mosaic_thumb_title_font_style":"Ubuntu","mosaic_thumb_title_font_color":"323A45","mosaic_thumb_title_font_color_hover":"FFFFFF","mosaic_thumb_title_shadow":"","mosaic_thumb_title_font_size":16,"mosaic_thumb_title_font_weight":"bold","mosaic_thumb_gal_title_font_color":"323A45","mosaic_thumb_gal_title_font_style":"Ubuntu","mosaic_thumb_gal_title_font_size":16,"mosaic_thumb_gal_title_font_weight":"bold","mosaic_thumb_gal_title_margin":"2px","mosaic_thumb_gal_title_shadow":"","mosaic_thumb_gal_title_align":"center","lightbox_info_pos":"bottom","lightbox_info_align":"left","lightbox_info_bg_color":"FFFFFF","lightbox_info_bg_transparent":"70","lightbox_info_border_width":"1","lightbox_info_border_style":"none","lightbox_info_border_color":"000000","lightbox_info_border_radius":"0px","lightbox_info_padding":"10px 7px 44px 10px","lightbox_info_margin":"10px 10px -5px 10px","lightbox_title_color":"808080","lightbox_title_font_style":"Ubuntu","lightbox_title_font_weight":"bold","lightbox_title_font_size":"16","lightbox_description_color":"B0B0B0","lightbox_description_font_style":"Ubuntu","lightbox_description_font_weight":"bold","lightbox_description_font_size":"13","lightbox_rate_pos":"top","lightbox_rate_align":"left","lightbox_rate_icon":"star","lightbox_rate_color":"F9D062","lightbox_rate_size":"20","lightbox_rate_stars_count":"5","lightbox_rate_padding":"15px","lightbox_rate_hover_color":"F7B50E","lightbox_hit_pos":"bottom","lightbox_hit_align":"left","lightbox_hit_bg_color":"000000","lightbox_hit_bg_transparent":"70","lightbox_hit_border_width":"1","lightbox_hit_border_style":"none","lightbox_hit_border_color":"000000","lightbox_hit_border_radius":"5px","lightbox_hit_padding":"5px","lightbox_hit_margin":"0 5px","lightbox_hit_color":"FFFFFF","lightbox_hit_font_style":"Ubuntu","lightbox_hit_font_weight":"normal","lightbox_hit_font_size":"14","album_masonry_back_font_color":"323A45","album_masonry_back_font_style":"Ubuntu","album_masonry_back_font_size":15,"album_masonry_back_font_weight":"bold","album_masonry_back_padding":"0","album_masonry_title_font_color":"323A45","album_masonry_thumb_title_font_color_hover":"FFFFFF","album_masonry_title_font_style":"Ubuntu","album_masonry_thumb_title_pos":"bottom","album_masonry_title_font_size":16,"album_masonry_title_font_weight":"bold","album_masonry_title_margin":"","album_masonry_title_shadow":"0px 0px 0px #888888","album_masonry_thumb_margin":0,"album_masonry_thumb_padding":4,"album_masonry_thumb_border_radius":"0","album_masonry_container_margin":1,"album_masonry_thumb_border_width":0,"album_masonry_thumb_border_style":"none","album_masonry_thumb_border_color":"CCCCCC","album_masonry_thumb_bg_color":"000000","album_masonry_thumbs_bg_color":"FFFFFF","album_masonry_thumb_bg_transparent":0,"album_masonry_thumb_box_shadow":"","album_masonry_thumb_transparent":100,"album_masonry_thumb_align":"center","album_masonry_thumb_hover_effect":"zoom","album_masonry_thumb_hover_effect_value":"1.08","album_masonry_thumb_transition":1,"album_masonry_gal_title_font_color":"323A45","album_masonry_gal_title_font_style":"Ubuntu","album_masonry_gal_title_font_size":18,"album_masonry_gal_title_font_weight":"bold","album_masonry_gal_title_margin":"0 2px 2px 2px","album_masonry_gal_title_shadow":"0px 0px 0px #888888","album_masonry_gal_title_align":"center","carousel_cont_bg_color":"000000","carousel_cont_btn_transparent":0,"carousel_close_btn_transparent":50,"carousel_rl_btn_bg_color":"FFFFFF","carousel_rl_btn_border_radius":"20px","carousel_rl_btn_border_width":0,"carousel_rl_btn_border_style":"none","carousel_rl_btn_border_color":"FFFFFF","carousel_rl_btn_color":"303030","carousel_rl_btn_height":35,"carousel_rl_btn_size":15,"carousel_play_pause_btn_size":25,"carousel_rl_btn_width":35,"carousel_close_rl_btn_hover_color":"191919","carousel_rl_btn_style":"fa-chevron","carousel_mergin_bottom":"0.5","carousel_font_family":"arial","carousel_feature_border_width":2,"carousel_feature_border_style":"none","carousel_feature_border_color":"5D204F","carousel_caption_background_color":"000000","carousel_caption_bottom":0,"carousel_caption_p_mergin":0,"carousel_caption_p_pedding":5,"carousel_caption_p_font_weight":"bold","carousel_caption_p_font_size":14,"carousel_caption_p_color":"FFFFFF","carousel_title_opacity":100,"carousel_title_border_radius":"5px","mosaic_thumb_transition":"1"}';

          $theme_defaults = json_decode($theme_defaults);
          foreach ($theme_defaults as $key => $value) {
            $row->$key = $value;
          }
        }
      }
    }
    else {
	    // TODO. unknown logic!!!
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwg_theme WHERE default_theme="%d"', 1));
      $row->id = 0;
      $row->name = '';
      $themes = json_decode($row->options);
      foreach ($themes as $key => $value) {
        $row->$key = $value;
      }
      $row->default_theme = 0;
      $themes = json_decode($row->options);
      foreach ($themes as $key => $value) {
        $row->$key = $value;
      }
      if (!isset($row->lightbox_bg_transparent)) {
        $row->lightbox_bg_transparent = 100;
      }
      if (!isset($row->image_browser_image_title_align)) {
        $row->image_browser_image_title_align = 'top';
      }
      if (!isset($row->thumb_gal_title_font_color)) {
        $row->thumb_gal_title_font_color = '323A45';
      }
      if (!isset($row->thumb_gal_title_font_style)) {
        $row->thumb_gal_title_font_style = 'Ubuntu';
      }
      if (!isset($row->thumb_gal_title_font_size)) {
        $row->thumb_gal_title_font_size = 16;
      }
      if (!isset($row->thumb_gal_title_font_weight)) {
        $row->thumb_gal_title_font_weight = 'bold';
      }
      if (!isset($row->thumb_gal_title_margin)) {
        $row->thumb_gal_title_margin = '2px';
      }
      if (!isset($row->thumb_gal_title_shadow)) {
        $row->thumb_gal_title_shadow = '';
      }
      if (!isset($row->thumb_gal_title_align)) {
        $row->thumb_gal_title_align = 'center';
      }
      if (!isset($row->album_compact_gal_title_font_color)) {
        $row->album_compact_gal_title_font_color = '323A45';
      }
      if (!isset($row->album_compact_gal_title_font_style)) {
        $row->album_compact_gal_title_font_style = 'Ubuntu';
      }
      if (!isset($row->album_compact_gal_title_font_size)) {
        $row->album_compact_gal_title_font_size = 18;
      }
      if (!isset($row->album_compact_gal_title_font_weight)) {
        $row->album_compact_gal_title_font_weight = 'bold';
      }
      if (!isset($row->album_compact_gal_title_margin)) {
        $row->album_compact_gal_title_margin = '0 2px 2px 2px';
      }
      if (!isset($row->album_compact_gal_title_shadow)) {
        $row->album_compact_gal_title_shadow = '0px 0px 0px #888888';
      }
      if (!isset($row->album_compact_gal_title_align)) {
        $row->album_compact_gal_title_align = 'center';
      }
      if (!isset($row->album_extended_gal_title_font_color)) {
        $row->album_extended_gal_title_font_color = '323A45';
      }
      if (!isset($row->album_extended_gal_title_font_style)) {
        $row->album_extended_gal_title_font_style = 'Ubuntu';
      }
      if (!isset($row->album_extended_gal_title_font_size)) {
        $row->album_extended_gal_title_font_size = 18;
      }
      if (!isset($row->album_extended_gal_title_font_weight)) {
        $row->album_extended_gal_title_font_weight = 'bold';
      }
      if (!isset($row->album_extended_gal_title_margin)) {
        $row->album_extended_gal_title_margin = '0 2px 2px 2px';
      }
      if (!isset($row->album_extended_gal_title_shadow)) {
        $row->album_extended_gal_title_shadow = '0px 0px 0px #888888';
      }
      if (!isset($row->album_extended_gal_title_align)) {
        $row->album_extended_gal_title_align = 'center';
      }
      // Masonry default value. TODO change logic in the other version.
      $row->masonry_thumb_hover_effect = 'zoom';
      $row->masonry_thumb_hover_effect_value = '1.08';
      $row->masonry_thumb_bg_color = '000000';
      $row->masonry_thumb_title_font_size = '16';
      $row->masonry_thumb_title_font_color = '323A45' ;
      $row->masonry_thumb_title_font_color_hover = 'FFFFFF';
      $row->masonry_thumb_title_font_style = 'Ubuntu';
      $row->masonry_thumb_title_font_weight = 'bold';
      $row->masonry_thumb_title_margin = '2px';
      $row->masonry_description_font_size = '16';
      $row->masonry_description_color = '323A45';
      $row->masonry_description_font_style = 'Ubuntu';
      $row->masonry_thumb_gal_title_font_size = '18';
      $row->masonry_thumb_gal_title_font_color = '323A45';
	  	$row->masonry_thumb_gal_title_font_style = 'Ubuntu';
      $row->masonry_thumb_gal_title_font_weight = 'bold';
      $row->masonry_thumb_gal_title_shadow = '';
      $row->masonry_thumb_gal_title_margin = '0 2px 2px 2px';
      $row->masonry_thumb_gal_title_align = 'center';

	    if (!isset($row->album_masonry_gal_title_font_color)) {
        $row->album_masonry_gal_title_font_color = '323A45';
      }
      if (!isset($row->album_masonry_gal_title_font_style)) {
        $row->album_masonry_gal_title_font_style = 'Ubuntu';
      }
      if (!isset($row->album_masonry_gal_title_font_size)) {
        $row->album_masonry_gal_title_font_size = 18;
      }
      if (!isset($row->album_masonry_gal_title_font_weight)) {
        $row->album_masonry_gal_title_font_weight = 'bold';
      }
      if (!isset($row->album_masonry_gal_title_margin)) {
        $row->album_masonry_gal_title_margin = '0 2px 2px 2px';
      }
      if (!isset($row->album_masonry_gal_title_shadow)) {
        $row->album_masonry_gal_title_shadow = '0px 0px 0px #888888';
      }
      if (!isset($row->album_masonry_gal_title_align)) {
        $row->album_masonry_gal_title_align = 'center';
      }
      if (!isset($row->mosaic_thumb_gal_title_font_color)) {
        $row->mosaic_thumb_gal_title_font_color = '323A45';
      }
      if (!isset($row->mosaic_thumb_gal_title_font_style)) {
        $row->mosaic_thumb_gal_title_font_style = 'Ubuntu';
      }
      if (!isset($row->mosaic_thumb_gal_title_font_size)) {
        $row->mosaic_thumb_gal_title_font_size = 16;
      }
      if (!isset($row->mosaic_thumb_gal_title_font_weight)) {
        $row->mosaic_thumb_gal_title_font_weight = 'bold';
      }
      if (!isset($row->mosaic_thumb_gal_title_margin)) {
        $row->mosaic_thumb_gal_title_margin = '2px';
      }
      if (!isset($row->mosaic_thumb_gal_title_shadow)) {
        $row->mosaic_thumb_gal_title_shadow = '';
      }
      if (!isset($row->mosaic_thumb_gal_title_align)) {
        $row->mosaic_thumb_gal_title_align = 'center';
      }
      if (!isset($row->image_browser_gal_title_font_color)) {
        $row->image_browser_gal_title_font_color = '323A45';
      }
      if (!isset($row->image_browser_gal_title_font_style)) {
        $row->image_browser_gal_title_font_style = 'Ubuntu';
      }
      if (!isset($row->image_browser_gal_title_font_size)) {
        $row->image_browser_gal_title_font_size = 16;
      }
      if (!isset($row->image_browser_gal_title_font_weight)) {
        $row->image_browser_gal_title_font_weight = 'bold';
      }
      if (!isset($row->image_browser_gal_title_margin)) {
        $row->image_browser_gal_title_margin = '2px';
      }
      if (!isset($row->image_browser_gal_title_shadow)) {
        $row->image_browser_gal_title_shadow = '0px 0px 0px #888888';
      }
      if (!isset($row->image_browser_gal_title_align)) {
        $row->image_browser_gal_title_align = 'center';
      }      
      if (!isset($row->blog_style_gal_title_font_color)) {
        $row->blog_style_gal_title_font_color = '323A45';
      }
      if (!isset($row->blog_style_gal_title_font_style)) {
        $row->blog_style_gal_title_font_style = 'Ubuntu';
      }
      if (!isset($row->blog_style_gal_title_font_size)) {
        $row->blog_style_gal_title_font_size = 16;
      }
      if (!isset($row->blog_style_gal_title_font_weight)) {
        $row->blog_style_gal_title_font_weight = 'bold';
      }
      if (!isset($row->blog_style_gal_title_margin)) {
        $row->blog_style_gal_title_margin = '2px';
      }
      if (!isset($row->blog_style_gal_title_shadow)) {
        $row->blog_style_gal_title_shadow = '0px 0px 0px #888888';
      }
      if (!isset($row->blog_style_gal_title_align)) {
        $row->blog_style_gal_title_align = 'center';
      }
      if ( $row->thumb_hover_effect ) {
        $row->thumb_hover_effect = 'zoom';
      }
      if ( $row->thumb_hover_effect_value ) {
        $row->thumb_hover_effect_value = '1.08';
      }
      if ( $row->masonry_thumbs_bg_color ) {
        $row->masonry_thumbs_bg_color = 'FFFFFF';
      }
      if ( $row->thumb_bg_color ) {
        $row->thumb_bg_color = '000000';
      }
      if ( $row->thumb_title_font_color ) {
        $row->thumb_title_font_color = '323A45';
      }
      if ( !isset($row->thumb_title_font_color_hover) ) {
        $row->thumb_title_font_color_hover = 'FFFFFF';
      }
      if ( !isset($row->album_compact_title_font_color_hover) ) {
        $row->album_compact_title_font_color_hover = 'FFFFFF';
      }
      if ( $row->thumb_title_shadow ) {
        $row->thumb_title_shadow = '';
      }
      if ( $row->thumb_gal_title_font_color ) {
        $row->thumb_gal_title_font_color = '323A45';
      }
      if ( $row->thumb_gal_title_font_style ) {
        $row->thumb_gal_title_font_style = 'Ubuntu';
      }
      if ( $row->thumb_gal_title_font_size ) {
        $row->thumb_gal_title_font_size = '18';
      }
      if ( $row->thumb_gal_title_shadow ) {
        $row->thumb_gal_title_shadow = '';
      }
      /* Mosaic */
      if ( $row->mosaic_thumb_bg_color ) {
        $row->mosaic_thumb_bg_color = '000000';
      }
      if ( $row->mosaic_thumbs_bg_color ) {
        $row->mosaic_thumbs_bg_color = 'FFFFFF';
      }
      if ( $row->mosaic_thumb_hover_effect ) {
        $row->mosaic_thumb_hover_effect = 'zoom';
      }
      if ( $row->mosaic_thumb_hover_effect_value ) {
        $row->mosaic_thumb_hover_effect_value = '1.08';
      }
      if ( $row->mosaic_thumb_title_font_size ) {
        $row->mosaic_thumb_title_font_size = '16';
      }
      if ( $row->mosaic_thumb_title_font_color ) {
        $row->mosaic_thumb_title_font_color = '323A45';
      }
      if ( !isset($row->mosaic_thumb_title_font_color_hover) ) {
        $row->mosaic_thumb_title_font_color_hover = 'FFFFFF';
      }
      if ( $row->mosaic_thumb_title_font_style ) {
        $row->mosaic_thumb_title_font_style = 'Ubuntu';
      }
      if ( $row->mosaic_thumb_title_shadow ) {
        $row->mosaic_thumb_title_shadow = '';
      }
      if ( $row->mosaic_thumb_gal_title_font_size ) {
        $row->mosaic_thumb_gal_title_font_size = '18';
      }
      if ( $row->mosaic_thumb_gal_title_font_color ) {
        $row->mosaic_thumb_gal_title_font_color = '323A45';
      }
      if ( $row->mosaic_thumb_gal_title_font_style ) {
        $row->mosaic_thumb_gal_title_font_style = 'Ubuntu';
      }
      if ( $row->mosaic_thumb_gal_title_shadow ) {
        $row->mosaic_thumb_gal_title_shadow = '';
      }
      if (!isset($row->album_masonry_container_margin)) {
        $row->album_masonry_container_margin = 1;
      }
    }
    return $row;
  }

  /**
   * Return total count of themes.
   *
   * @param $params
   *
   * @return array|null|object|string
   */
  public function total($params) {
    return $this->get_rows_data($params, TRUE);
  }

  /**
   * Delete row(s) from db.
   *
   * @param array $params
   * params = [selection, table, where, order_by, limit]
   *
   * @return false|int
   */
  public function delete_rows( $params ) {
    global $wpdb;
    $query = 'DELETE FROM ' . $wpdb->prefix . $params['table'];
    if ( isset($params['where']) ) {
      $where = $params['where'];
      $query .= ' WHERE ' . $where;
    }
    if ( isset($params['order_by']) ) {
      $query .= ' ' . $params['order_by'];
    }
    if ( isset($params['limit']) ) {
      $query .= ' ' . $params['limit'];
    }

    return $wpdb->query($query);
  }

  /**
   * Get row(s) from db.
   *
   * @param string $get_type
   * @param array  $params
   * params = [selection, table, where, order_by, limit]
   *
   * @return array
   */
  public function select_rows( $get_type, $params ) {
    global $wpdb;
    $query = "SELECT " . $params['selection'] . " FROM " . $wpdb->prefix . $params['table'];
    if ( isset($params['where']) ) {
      $query .= " WHERE " . $params['where'];
    }
    if ( isset($params['order_by']) ) {
      $query .= " " . $params['order_by'];
    }
    if ( isset($params['limit']) ) {
      $query .= " " . $params['limit'];
    }
    if ( $get_type == "get_col" ) {
      return $wpdb->get_col($query);
    }
    elseif ( $get_type == "get_var" ) {
      return $wpdb->get_var($query);
    }

    return $wpdb->get_row($query);
  }

  /**
   * Get request value.
   *
   * @param $table
   * @param $data
   *
   * @return false|int
   */
  public function insert_data_to_db( $table, $data ) {
    global $wpdb;
    $query = $wpdb->insert($wpdb->prefix . $table, $data);
    $wpdb->show_errors();

    return $query;
  }

  /**
   * Check if theme is default.
   *
   * @params int $id
   *
   * @return string
   */
  public function get_default( $id ) {
    global $wpdb;

    return $wpdb->get_var($wpdb->prepare('SELECT `default_theme` FROM `' . $wpdb->prefix . 'bwg_theme` WHERE id="%d"', $id));
  }

  /**
   * Update DB.
   *
   * @params array $params
   * @params array $where
   *
   * @return bool
   */
  public function update( $params, $where ) {
    global $wpdb;

    return $wpdb->update($wpdb->prefix . 'bwg_theme', $params, $where);
  }
}