<?php

/**
 * Class AlbumsgalleriesModel_bwg
 */
class AlbumsgalleriesModel_bwg {
  /**
   * Get rows data or total count.
   *
   * @param      $params
   * @param bool $total
   *
   * @return array|null|object|string
   */
  public function get_rows_data($params, $total = FALSE) {
    global $wpdb;
    $where = $params['search'] ? '`name` LIKE "%' . $params['search'] . '%"' : '';
    $order_by = $total ? '' : ' ORDER BY `' . $params['orderby'] . '` ' . $params['order'];
    $limit = $total ? '' : ' LIMIT ' . $params['page_num'] . ',' . $params['items_per_page'];
    $query = '(SELECT id, name, preview_image, random_preview_image, published, 1 as is_album FROM ' . $wpdb->prefix . 'bwg_album WHERE id <> ' . $params['album_id'] . ' ' . (($where) ? 'AND '. $where : '' ) . ')
                UNION ALL
              (SELECT id, name, preview_image, random_preview_image, published, 0 as is_album FROM ' . $wpdb->prefix . 'bwg_gallery ' . (($where) ? 'WHERE '. $where : '' ) . $order_by . $limit . ')';

    if ($total) {
      $query = 'SELECT COUNT(*) FROM (' . $query . ') as temp';
      return $wpdb->get_var($query);
    }
    $rows = $wpdb->get_results($query);

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
}