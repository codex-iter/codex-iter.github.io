<?php
class BWGModelSite {
  public function get_theme_row_data($id = 0) {
    global $wpdb;
    if ($id) {
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwg_theme WHERE id="%d"', $id));
    }
    else {
      $row = $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'bwg_theme WHERE default_theme=1');
    }
    if (isset($row->options)) {
      $row = (object) array_merge((array) $row, (array) json_decode($row->options));
    }
    return $row;
  }

  public function get_gallery_row_data($id = 0, $from = '') {
    global $wpdb;
    $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwg_gallery WHERE id="%d"', $id));
    if ( $row ) {
      $row->permalink = '';
      if ($from != '') {
        $row->permalink = self::get_custom_post_permalink( array( 'slug' => $row->slug, 'post_type' => 'gallery' ) );
      }
      if ( !empty($row->preview_image) ) {
        $row->preview_image = WDWLibrary::image_url_version($row->preview_image, $row->modified_date);
      }
      if ( !empty($row->random_preview_image) ) {
        $row->random_preview_image = WDWLibrary::image_url_version($row->random_preview_image, $row->modified_date);
      }
    }
    else if ( !$id ) { /* Select all Galleries */
      $row_count = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'bwg_gallery WHERE published=1');
      if ( !$row_count ) {
        return false;
      }
      else {
        $row = new stdClass();
        $row->id = 0;
        $row->name = '';
      }
    }

    return $row;
  }

  public function get_album_row_data( $id, $from ) {
    global $wpdb;
    if ( $id == 0 ) {
      $row = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'bwg_gallery');
    }
    else {
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'bwg_album WHERE id="%d"', $id));
    }
    if ( is_object($row) ) {
      if ( $from ) {
        $row->permalink = WDWLibrary::get_custom_post_permalink(array( 'slug' => $row->slug, 'post_type' => 'album' ));
      }
      if ( !empty($row->preview_image) ) {
        $row->preview_image = WDWLibrary::image_url_version($row->preview_image, $row->modified_date);
      }
      if ( !empty($row->random_preview_image) ) {
        $row->random_preview_image = WDWLibrary::image_url_version($row->random_preview_image, $row->modified_date);
      }
    }
    return $row;
  }

  public function get_image_rows_data( $gallery_id, $bwg, $type, $tag_input_name, $tag, $images_per_page, $load_more_image_count, $sort_by, $sort_direction = 'ASC' ) {
    if ( $images_per_page < 0 ) {
      $images_per_page = 0;
    }
    if ( $load_more_image_count < 0 ) {
      $load_more_image_count = 0;
    }
    global $wpdb;
    $gallery_id = (int) $gallery_id;
    $tag = (int) $tag;
    $bwg_search = ((isset($_POST['bwg_search_' . $bwg]) && esc_html($_POST['bwg_search_' . $bwg]) != '') ? trim(esc_html($_POST['bwg_search_' . $bwg])) : '');
    $join = '';
    $where = '';
    if ( $bwg_search !== '' ) {
      $bwg_search_keys = explode(' ', $bwg_search);
      $alt_search = '(';
      $description_search = '(';
      foreach( $bwg_search_keys as $search_key) {
        $alt_search .= '`image`.`alt` LIKE "%' . trim($search_key) . '%" AND ';
        $description_search .= '`image`.`description` LIKE "%' . trim($search_key) . '%" AND ';
      }
      $alt_search = rtrim($alt_search, 'AND ');
      $alt_search .= ')';
      $description_search = rtrim($description_search, 'AND ');
      $description_search .= ')';
      $where = 'AND (' . $alt_search . ' OR ' . $description_search . ')';
    }
    if ( $sort_by == 'size' || $sort_by == 'resolution' ) {
      $sort_by = ' CAST(image.' . $sort_by . ' AS SIGNED) ';
    }
    elseif ( $sort_by == 'random' || $sort_by == 'RAND()' ) {
      $sort_by = 'RAND()';
    }
    elseif ( ($sort_by != 'alt') && ($sort_by != 'date') && ($sort_by != 'filetype') && ($sort_by != 'RAND()') && ($sort_by != 'filename') ) {
      $sort_by = 'image.`order`';
    }
    else {
      $sort_by = 'image.' . $sort_by;
    }
    $items_in_page = $images_per_page;
    $limit = 0;
    WDWLibrary::bwg_session_start();
    if ( isset($_REQUEST['page_number_' . $bwg]) && $_REQUEST['page_number_' . $bwg] ) {
      if ( $_REQUEST['page_number_' . $bwg] > 1 ) {
        $items_in_page = $load_more_image_count;
      }
      $limit = (((int) $_REQUEST['page_number_' . $bwg] - 2) * $items_in_page) + $images_per_page;
      $bwg_random_seed = isset($_SESSION['bwg_random_seed_' . $bwg]) ? $_SESSION['bwg_random_seed_' . $bwg] : '';
    }
    else {
      $bwg_random_seed = rand();
      $_SESSION['bwg_random_seed_' . $bwg] = $bwg_random_seed;
    }
    $limit_str = '';
    if ( $images_per_page ) {
      $limit_str = 'LIMIT ' . $limit . ',' . $items_in_page;
    }
    $where .= ($gallery_id ? ' AND image.gallery_id = "' . $gallery_id . '" ' : '') . ($tag ? ' AND tag.tag_id = "' . $tag . '" ' : '');
    $join = $tag ? 'LEFT JOIN ' . $wpdb->prefix . 'bwg_image_tag as tag ON image.id=tag.image_id' : '';
    if ( isset($_REQUEST[$tag_input_name]) && $_REQUEST[$tag_input_name] ) {
      $join .= ' LEFT JOIN (SELECT GROUP_CONCAT(tag_id SEPARATOR ",") AS tags_combined, image_id FROM  ' . $wpdb->prefix . 'bwg_image_tag' . ($gallery_id ? ' WHERE gallery_id="' . $gallery_id . '"' : '') . ' GROUP BY image_id) AS tags ON image.id=tags.image_id';
      $where .= ' AND CONCAT(",", tags.tags_combined, ",") REGEXP ",(' . implode("|", $_REQUEST[$tag_input_name]) . ')," ';
    }
    $join .= ' LEFT JOIN '. $wpdb->prefix .'bwg_gallery as gallery ON gallery.id = image.gallery_id';
    $where .= ' AND gallery.published = 1 ';
	$query = 'SELECT image.* FROM ' . $wpdb->prefix . 'bwg_image as image ' . $join . ' WHERE image.published=1 ' . $where . ' ORDER BY ' . str_replace('RAND()', 'RAND(' . $bwg_random_seed . ')', $sort_by) . ' ' . $sort_direction . ' ' . $limit_str;
    $rows = $wpdb->get_results($query);
    $total = $wpdb->get_var('SELECT COUNT(*) FROM ' . $wpdb->prefix . 'bwg_image as image ' . $join . ' WHERE image.published=1 ' . $where);
    $page_nav['total'] = $total;
    $page_nav['limit'] = 1;
    if ( isset($_REQUEST['page_number_' . $bwg]) && $_REQUEST['page_number_' . $bwg] ) {
      $page_nav['limit'] = (int) $_REQUEST['page_number_' . $bwg];
    }
    $images = array();
    $thumb_urls = array();
    if ( !empty($rows) ) {
      foreach ( $rows as $row ) {
        if ( strpos($row->filetype, 'EMBED') === FALSE ) {
          $row->image_url = WDWLibrary::image_url_version($row->image_url, $row->modified_date);
          $row->thumb_url = WDWLibrary::image_url_version($row->thumb_url, $row->modified_date);
          // To disable Jetpack Photon module.
		      $thumb_urls[] = BWG()->upload_url . $row->thumb_url;
        }
        else {
          // To disable Jetpack Photon module.
          $thumb_urls[] = $row->thumb_url;
        }
        $images[] = $row;
      }
    }
    return array( 'images' => $images, 'page_nav' => $page_nav, 'thumb_urls' => $thumb_urls );
  }

  public function get_tags_rows_data($gallery_id) {
    global $wpdb;
    $row = $wpdb->get_results('Select t1.* FROM ' . $wpdb->prefix . 'terms AS t1 LEFT JOIN ' . $wpdb->prefix . 'term_taxonomy AS t2 ON t1.term_id = t2.term_id' . ($gallery_id ? ' LEFT JOIN (SELECT DISTINCT tag_id , gallery_id  FROM ' . $wpdb->prefix . 'bwg_image_tag) AS t3 ON t1.term_id=t3.tag_id' : '') . ' WHERE taxonomy="bwg_tag"' . ($gallery_id ? ' AND t3.gallery_id="' . $gallery_id . '"' : '') . ' ORDER BY t1.name  ASC');
    return $row;
  }

  public function get_alb_gals_row( $bwg, $id, $albums_per_page, $sort_by, $pagination_type = 0, $from = '' ) {
    if ( $albums_per_page < 0 ) {
      $albums_per_page = 0;
    }
    global $wpdb;
    if ( $sort_by == 'random' || $sort_by == 'RAND()' ) {
      $order_by = 'ORDER BY RAND()';
    } else {
      $order_by = 'ORDER BY `order` ASC';
    }
    $limit = 0;
    if ( isset( $_REQUEST[ 'page_number_' . $bwg ] ) && $_REQUEST[ 'page_number_' . $bwg ] ) {
      $limit = ((int) $_REQUEST[ 'page_number_' . $bwg ] - 1) * $albums_per_page;
    }
    $limit_str = '';
    if ( $albums_per_page ) {
      $limit_str = 'LIMIT ' . $limit . ',' . $albums_per_page;
    }
    if ( isset( $_REQUEST[ 'action_' . $bwg ] ) && $_REQUEST[ 'action_' . $bwg ] == 'back' && ($pagination_type == 2 || $pagination_type == 3) ) {
      if ( isset( $_REQUEST[ 'page_number_' . $bwg ] ) && $_REQUEST[ 'page_number_' . $bwg ] ) {
        $limit = $albums_per_page * $_REQUEST[ 'page_number_' . $bwg ];
        $limit_str = 'LIMIT 0,' . $limit;
      }
    }
    // Select all galleries.
    if ( $id == 0 ) {
      $query = 'SELECT * FROM `' . $wpdb->prefix . 'bwg_gallery` WHERE `published`=1';
      $limitation = ' ' . $order_by . ' ' . $limit_str;
      $rows = $wpdb->get_results( $query . $limitation );
      $total = $wpdb->get_var('SELECT count(*) FROM `' . $wpdb->prefix . 'bwg_gallery` WHERE `published`=1');
    }
    else {
      $query = 'SELECT t.*, t1.preview_image, t1.random_preview_image, t1.name, t1.description, t1.slug, t1.modified_date FROM `' . $wpdb->prefix . 'bwg_album_gallery` as t';
      $query .= ' LEFT JOIN `' . $wpdb->prefix . 'bwg_album` as t1 ON (t.is_album=1 AND t.alb_gal_id = t1.id)';
      $query .= ' WHERE t.album_id="' . $id . '"';
      $query .= ' AND t1.published=1';
      $query .= ' UNION SELECT t.*, t2.preview_image, t2.random_preview_image, t2.name, t2.description, t2.slug, t2.modified_date FROM `' . $wpdb->prefix . 'bwg_album_gallery` as t';
      $query .= ' LEFT JOIN `' . $wpdb->prefix . 'bwg_gallery` as t2 ON (t.is_album=0 AND t.alb_gal_id = t2.id)';
      $query .= ' WHERE t.album_id="' . $id . '"';
      $query .= ' AND t2.published=1';
      $limitation = ' ' . $order_by . ' ' . $limit_str;
      $rows = $wpdb->get_results($query . $limitation);
      $total = count($wpdb->get_results($query));
    }
    if ( $rows ) {
      foreach ( $rows as $row ) {
        $row->def_type = isset($row->is_album) && $row->is_album ? 'album' : 'gallery';
        if ( $from ) {
          $row->permalink = WDWLibrary::get_custom_post_permalink(array( 'slug' => $row->slug, 'post_type' => $row->def_type ));
        }
        else {
          $row->permalink = '';
        }
        if ( !empty($row->preview_image) ) {
          $row->preview_image = WDWLibrary::image_url_version($row->preview_image, $row->modified_date);
        }
        if ( !empty($row->random_preview_image) ) {
          $row->random_preview_image = WDWLibrary::image_url_version($row->random_preview_image, $row->modified_date);
        }
        if ( !$row->preview_image ) {
          $row->preview_image = $row->random_preview_image;
        }

        if ( !$row->preview_image ) {
          $row->preview_image = BWG()->plugin_url . '/images/no-image.png';
          $row->preview_path = BWG()->plugin_dir . '/images/no-image.png';
        }
        else {
          $parsed_prev_url = parse_url($row->preview_image, PHP_URL_SCHEME);
          if ( $parsed_prev_url == 'http' || $parsed_prev_url == 'https' ) {
            $row->preview_path = $row->preview_image;
            $row->preview_image = $row->preview_image;
          }
          else {
            $row->preview_path = BWG()->upload_dir . $row->preview_image;
            $row->preview_image = BWG()->upload_url . $row->preview_image;
          }
        }

        $row->description = wpautop($row->description);
      }
    }
    $page_nav[ 'total' ] = $total;
    $page_nav[ 'limit' ] = 1;
    if ( isset( $_REQUEST[ 'page_number_' . $bwg ] ) && $_REQUEST[ 'page_number_' . $bwg ] ) {
      $page_nav[ 'limit' ] = (int) $_REQUEST[ 'page_number_' . $bwg ];
    }

    return array( 'rows' => $rows, 'page_nav' => $page_nav );
  }
}
