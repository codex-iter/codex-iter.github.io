<?php

class FFWDModelAlbum_compact extends FFWDModelMain {
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
  public function __construct() {
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function get_ffwd_data($id, $objects_per_page, $sort_by, $ffwd, $sort_direction = ' ASC ', $pagination_type) {
    global $wpdb;
    $album_id = (isset($_REQUEST['album_id_' . $ffwd]) ? esc_html($_REQUEST['album_id_' . $ffwd]) : 0);
    if (isset($_REQUEST['page_number_' . $ffwd]) && $_REQUEST['page_number_' . $ffwd]) {
			$limit = ((int) $_REQUEST['page_number_' . $ffwd] - 1) * $objects_per_page;
		}
    else {
      $limit = 0;
    }
    if ($objects_per_page) {
			// var_dump(((string) $album_id ));
			if(((string) $album_id ) == 'back' && $pagination_type != 0 && $pagination_type != 1) { 
			  $page_number = (isset($_REQUEST['page_number_' . $ffwd]) && $_REQUEST['page_number_' . $ffwd]) ? $_REQUEST['page_number_' . $ffwd] : 0;
			  $limit_str = 'LIMIT 0,' . (((int) $page_number ) * $objects_per_page);
			}
			else
        $limit_str = 'LIMIT ' . $limit . ',' . $objects_per_page;
    }
    else {
      $limit_str = '';
    }
    $results = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d" ORDER BY `created_time_number` DESC ' . $limit_str, $id)); 
		// Store ids ans object_ids
		$id_object_id_json = array();
		foreach($results as $row) {
			$object = new stdClass();
			$object->id = $row->id;
			$object->object_id = $row->object_id;
			array_push($id_object_id_json, $object);
		}
		$this->id_object_id_json = $id_object_id_json;
		// Set graph url
    $this->graph_url = str_replace ( 
      array('{ACCESS_TOKEN}', '{LIMIT}', '{OTHER}'), 
      array('access_token=' . $this->access_token . '&', '', 'summary=true&limit=100'), 
      $this->graph_url
    );
    return $results;
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