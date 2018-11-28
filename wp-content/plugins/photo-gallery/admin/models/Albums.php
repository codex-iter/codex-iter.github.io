<?php

/**
 * Class AlbumsModel_bwg
 */
class AlbumsModel_bwg {
  /**
   * Get rows data or total count.
   *
   * @param      $params
   * @param bool $total
   *
   * @return array|null|object|string
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

    $query .= ' FROM `' . $wpdb->prefix . 'bwg_album`';
    if ( !current_user_can('manage_options') && BWG()->options->album_role ) {
      $query .= " WHERE author=" . get_current_user_id();
    }
    else {
      $query .= " WHERE author>=0";
    }

    if ( $search ) {
      $query .= ' AND `name` LIKE "%' . $search . '%"';
    }
    if ( !$total ) {
      $query .= ' ORDER BY `' . $orderby . '` ' . $order;
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

  /**
   * Return total count.
   *
   * @param $params
   *
   * @return array|null|object|string
   */
  public function total($params) {
    return $this->get_rows_data($params, TRUE);
  }

  /**
   * Delete.
   *
   * @param      $id
   * @param bool $all
   *
   * @return int
   */
  public function delete( $id, $all = FALSE ) {
    global $wpdb;
    $where = ($all ? '' : ' WHERE id=' . $id);
    $alb_gal_where = ($all ? '' : ' AND alb_gal_id=' . $id);

    // Remove custom post.
    if ( $all ) {
      $wpdb->query('DELETE FROM `' . $wpdb->prefix . 'posts` WHERE `post_type`="bwg_album"');
    }
    else {
      $row = $wpdb->get_row( $wpdb->prepare('SELECT `slug` FROM `' . $wpdb->prefix . 'bwg_album` WHERE id="%d"', $id) );
      if ( !empty($row) ) {
        WDWLibrary::bwg_remove_custom_post( array( 'slug' => $row->slug, 'post_type' => 'bwg_album') );
      }
    }

    $delete = $wpdb->query('DELETE FROM `' . $wpdb->prefix . 'bwg_album`' . $where);
    $wpdb->query('DELETE FROM `' . $wpdb->prefix . 'bwg_album_gallery` WHERE is_album="1"' . $alb_gal_where);

    if ( $delete ) {
      if ( $all ) {
        $message = 5;
      }
      else {
        $message = 3;
      }
    }
    else {
      $message = 2;
    }

    return $message;
  }

  /**
   * Duplicate.
   *
   * @param      $id
   * @param bool $all
   *
   * @return int
   */
  public function duplicate( $id, $all = FALSE ) {
    global $wpdb;
    $message_id = 2;
    // Duplicate all itmes.
    if ( !$id && $all ) {
      $results = $wpdb->get_results('SELECT
												`a`.*,
												`ag`.alb_gal_id,
												`ag`.is_album,
												`ag`.`order` AS `ag_order`
											FROM
												`' . $wpdb->prefix . 'bwg_album` `a`
												LEFT JOIN `' . $wpdb->prefix . 'bwg_album_gallery` `ag`
											ON
												`a`.`id` = `ag`.`album_id`');
      if ( !empty($results) ) {
        foreach ( $results as $row ) {
          $album_row['name'] = $row->name;
          $album_row['slug'] = $row->slug;
          $album_row['description'] = $row->description;
          $album_row['preview_image'] = $row->preview_image;
          $album_row['random_preview_image'] = $row->random_preview_image;
          $album_row['order'] = $row->order;
          $album_row['author'] = $row->author;
          $album_row['published'] = $row->published;
          // Insert bwg_album.
          $album_id = $this->insert_data_to_db('bwg_album', $album_row);
          if ( $album_id ) {
            $slug = $album_row['slug'] . '-' . $album_id;
            // Update bwg_album slug.
            $updated = $wpdb->query('UPDATE `' . $wpdb->prefix . 'bwg_album` SET `slug`="' . $slug . '"  WHERE id = ' . $album_id);
            $album_gallery_row['album_id'] = $album_id;
            if ( $row->alb_gal_id ) {
              $album_gallery_row['alb_gal_id'] = $row->alb_gal_id;
            }
            if ( $row->is_album ) {
              $album_gallery_row['is_album'] = $row->is_album;
            }
            if ( $row->ag_order ) {
              $album_gallery_row['order'] = $row->ag_order;
            }
            // Insert bwg_album_gallery.
            $album_gallery_id = $this->insert_data_to_db('bwg_album_gallery', $album_gallery_row);
            if ( $album_gallery_id ) {
              // Create custom post.
              $custom_post_params = array(
                'id' => $album_id,
                'title' => $album_row['name'],
                'slug' => $slug,
                'type' => array(
                  'post_type' => 'album',
                  'mode' => '',
                ),
              );
              WDWLibrary::bwg_create_custom_post($custom_post_params);
              $message_id = 11;
            }
          }
        }
      }
    }
    // Duplicate itme by id.
    else {
      $rows = $wpdb->get_results('SELECT
							`a`.*,
							`ag`.alb_gal_id,
							`ag`.is_album,
							`ag`.`order` AS `ag_order`
						FROM
							`' . $wpdb->prefix . 'bwg_album` a
						LEFT JOIN `' . $wpdb->prefix . 'bwg_album_gallery` ag
						ON
							(`a`.`id` = `ag`.`album_id`)
						WHERE
							`a`.`id` = ' . $id);
      if ( $rows ) {
        $row = $rows[0];
        $album_row['name'] = $row->name;
        $album_row['slug'] = $row->slug;
        $album_row['description'] = $row->description;
        $album_row['preview_image'] = $row->preview_image;
        $album_row['random_preview_image'] = $row->random_preview_image;
        $album_row['order'] = $row->order;
        $album_row['author'] = $row->author;
        $album_row['published'] = $row->published;
        // Insert bwg_album.
        $album_id = $this->insert_data_to_db('bwg_album', $album_row);
        if ( $album_id ) {
          $slug = $album_row['slug'] . '-' . $album_id;
          // Update bwg_album slug.
          $updated = $wpdb->query('UPDATE `' . $wpdb->prefix . 'bwg_album` SET `slug`="' . $slug . '"  WHERE id = ' . $album_id);
          $album_gallery_row['album_id'] = $album_id;
          foreach ( $rows as $row ) {
            if ( $row->alb_gal_id ) {
              $album_gallery_row['alb_gal_id'] = $row->alb_gal_id;
            }
            if ( $row->is_album ) {
              $album_gallery_row['is_album'] = $row->is_album;
            }
            if ( $row->ag_order ) {
              $album_gallery_row['order'] = $row->ag_order;
            }
            // Insert bwg_album_gallery.
            $album_gallery_id = $this->insert_data_to_db('bwg_album_gallery', $album_gallery_row);
          }
          $message_id = 11;
          // Create custom post.
          $custom_post_params = array(
            'id' => $album_id,
            'title' => $album_row['name'],
            'slug' => $slug,
            'type' => array(
              'post_type' => 'album',
              'mode' => '',
            ),
          );
          WDWLibrary::bwg_create_custom_post($custom_post_params);
        }
      }
    }

    return $message_id;
  }

  /**
   * Get row data.
   *
   * @param int $id
   *
   * @return array|null|object|stdClass|void
   */
  public function get_row_data( $id = 0 ) {
    global $wpdb;
    if ( $id != 0 ) {
      if ( !current_user_can('manage_options') && BWG()->options->album_role ) {
        $where = " WHERE author = " . get_current_user_id();
      }
      else {
        $where = " WHERE author >= 0 ";
      }
      $row = $wpdb->get_row($wpdb->prepare('SELECT * FROM `' . $wpdb->prefix . 'bwg_album`' . $where . ' AND id="%d"', $id));
    }
    else {
      $row = new stdClass();
      $row->id = 0;
      $row->name = '';
      $row->slug = '';
      $row->description = '';
      $row->preview_image = '';
      $row->order = 0;
      $row->author = get_current_user_id();
      $row->published = 1;
      $row->modified_date = time();
    }
    $user_data = get_userdata($row->author);
    $row->author = ($user_data != FALSE ? $user_data->display_name : '');

    return $row;
  }

  /**
   * Save.
   *
   * @param $id
   *
   * @return int
   */
  public function save( $id = 0 ) {
    global $wpdb;
    $message_id = 2;
    $author = get_current_user_id();
    $name = WDWLibrary::get('name');
    $slug = WDWLibrary::get('slug');
    $slug = $this->create_unique_slug((empty($slug) ? $name : $slug), $id);
    $old_slug = WDWLibrary::get('old_slug');
	$published = WDWLibrary::get('published', 0);
    $preview_image = WDWLibrary::get('preview_image');
    $description = WDWLibrary::get('description', '', FALSE);
    $albumgallery_ids = WDWLibrary::get('albumgallery_ids');
    $modified_date = WDWLibrary::get('modified_date', time());
    $data = array(
      'name' => $name,
      'slug' => $slug,
      'description' => $description,
      'preview_image' => $preview_image,
      'published' => $published,
      'modified_date' => $modified_date
    );
    if ( $id ) {
      $save = $wpdb->update($wpdb->prefix . 'bwg_album', $data, array( 'id' => $id ));
    }
    else {
      $data['author'] = $author;
      $data['order'] = ((int) $wpdb->get_var('SELECT MAX(`order`) FROM ' . $wpdb->prefix . 'bwg_album')) + 1;
      $data['modified_date'] = time();
      $data['random_preview_image'] = '';
      $save = $wpdb->insert($wpdb->prefix . 'bwg_album', $data, array(
        '%s',
        '%s',
        '%s',
        '%s',
        '%s',
        '%d',
        '%d',
        '%d',
      ));
      $id = $wpdb->insert_id;
    }
    // Create custom post (type is album).
    $custom_post_params = array(
      'id' => $id,
      'title' => $name,
      'slug' => $slug,
      'old_slug' => $old_slug,
      'type' => array(
        'post_type' => 'album',
        'mode' => '',
      ),
    );
    WDWLibrary::bwg_create_custom_post($custom_post_params);
    $save = $this->save_album_gallery($id, $albumgallery_ids);
    // Set random image.
    $random_preview_image = (($preview_image == '') ? $this->get_image_for_album($id) : '');
    $wpdb->update($wpdb->prefix . 'bwg_album', array( 'random_preview_image' => $random_preview_image ), array( 'id' => $id ));
    if ( $save !== FALSE ) {
      $message_id = 1;
    }
	
    return array('message_id' => $message_id, 'current_id' => $id);
  }

  /**
   * Get request value.
   *
   * @param string $table
   * @param array  $data
   *
   * @return array
   */
  private function insert_data_to_db( $table, $data ) {
    global $wpdb;
    $insert = $wpdb->insert($wpdb->prefix . $table, $data);
    if ( $insert ) {
      return $wpdb->insert_id;
    }

    return FALSE;
  }

  private function create_unique_slug( $slug, $id ) {
    global $wpdb;
    $slug = sanitize_title($slug);
    if ( $id != 0 ) {
      $query = $wpdb->prepare('SELECT `slug` FROM `' . $wpdb->prefix . 'bwg_album` WHERE `slug` = %s AND `id` != %d', $slug, $id);
    }
    else {
      $query = $wpdb->prepare('SELECT `slug` FROM `' . $wpdb->prefix . 'bwg_album` WHERE `slug` = %s', $slug);
    }
    if ( $wpdb->get_var($query) ) {
      $num = 2;
      do {
        $alt_slug = $slug . "-$num";
        $num++;
        $slug_check = $wpdb->get_var($wpdb->prepare("SELECT slug FROM " . $wpdb->prefix . "bwg_album WHERE slug = %s", $alt_slug));
      }
      while ( $slug_check );
      $slug = $alt_slug;
    }

    return $slug;
  }

  // Return random image from gallery or album for album preview.
  private function get_image_for_album( $album_id ) {
    global $wpdb;
    $preview_image = '';
    $gallery_row = $wpdb->get_row($wpdb->prepare("SELECT t1.preview_image,t1.random_preview_image FROM " . $wpdb->prefix . "bwg_gallery as t1 INNER JOIN " . $wpdb->prefix . "bwg_album_gallery as t2 on t1.id=t2.alb_gal_id WHERE t2.is_album=0 AND t2.album_id='%d' AND (t1.preview_image<>'' OR t1.random_preview_image<>'') ORDER BY t2.`order`", $album_id));
    if ( $gallery_row ) {
      $preview_image = (($gallery_row->preview_image) ? $gallery_row->preview_image : $gallery_row->random_preview_image);
    }
    if ( !$preview_image ) {
      $album_row = $wpdb->get_row($wpdb->prepare("SELECT t1.preview_image,t1.random_preview_image FROM " . $wpdb->prefix . "bwg_album as t1 INNER JOIN " . $wpdb->prefix . "bwg_album_gallery as t2 on t1.id=t2.alb_gal_id WHERE t2.is_album=1 AND t2.album_id='%d' AND (t1.preview_image<>'' OR t1.random_preview_image<>'') ORDER BY t2.`order`", $album_id));
      if ( $album_row ) {
        $preview_image = (($album_row->preview_image) ? $album_row->preview_image : $album_row->random_preview_image);
      }
    }

    return $preview_image;
  }

  private function save_album_gallery( $album_id, $albumgallery_ids ) {
    global $wpdb;
    $save = 2;
    $wpdb->query($wpdb->prepare('DELETE FROM `' . $wpdb->prefix . 'bwg_album_gallery` WHERE `album_id` = "%d"', $album_id));
    if ( !empty($albumgallery_ids) ) {
      $items = explode(',', rtrim($albumgallery_ids ,','));
      if ( !empty($items) ) {
        foreach ( $items as $order => $item ) {
          list($alb_gal_id, $is_album) = explode(':', $item);
          if ($alb_gal_id) {
            $data = array(
              'album_id' => $album_id,
              'alb_gal_id' => $alb_gal_id,
              'is_album' => $is_album,
              'order' => $order + 1,
            );
            $save = $wpdb->insert($wpdb->prefix . 'bwg_album_gallery', $data, array('%d', '%d', '%d', '%d'));
          }
        }
      }
    }

    return $save;
  }

  /**
   * Get albums galleries data.
   *
   * @param  int $id
   *
   * @return array $data
   */
  public function get_albums_galleries_data( $id = 0 ) {
    global $wpdb;
    $query = '(SELECT t1.id, t2.name, t2.slug, t1.is_album, t1.alb_gal_id, t1.order, t2.preview_image, t2.random_preview_image, t2.published FROM ' . $wpdb->prefix . 'bwg_album_gallery as t1 INNER JOIN ' . $wpdb->prefix . 'bwg_album as t2 on t1.alb_gal_id = t2.id where t1.is_album="1" AND t1.album_id="' . $id . '")
                UNION
            (SELECT t1.id, t2.name, t2.slug, t1.is_album, t1.alb_gal_id, t1.order, t2.preview_image, t2.random_preview_image, t2.published FROM ' . $wpdb->prefix . 'bwg_album_gallery as t1 INNER JOIN ' . $wpdb->prefix . 'bwg_gallery as t2 on t1.alb_gal_id = t2.id where t1.is_album="0" AND t1.album_id="' . $id . '") ORDER BY `order`';
    $results = $wpdb->get_results($query);
    if ( !empty($results) ) {
      foreach ( $results as $result ) {
			$preview_image = BWG()->plugin_url . '/images/no-image.png';
			if ( !empty($result->preview_image) ) {
				$preview_image = BWG()->upload_url . $result->preview_image;
			}
			if ( !empty($result->random_preview_image) ) {
				$preview_image = BWG()->upload_url . $result->random_preview_image;
				if ( WDWLibrary::check_external_link($result->random_preview_image) ) {
				    $preview_image = $result->random_preview_image;
				}
			}
		$result->preview_image = $preview_image;
      }
    }
    return $results;
  }
}
