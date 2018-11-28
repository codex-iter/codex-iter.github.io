<?php

class FFWDModelThumbnails_masonry extends FFWDModelMain {
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
  public function get_ffwd_data($id, $objects_per_page, $sort_by, $ffwd, $sort_direction = ' ASC ') {
    global $wpdb;
    $bwg_search = ((isset($_POST['bwg_search_' . $ffwd]) && esc_html($_POST['bwg_search_' . $ffwd]) != '') ? esc_html($_POST['bwg_search_' . $ffwd]) : '');
    if ($bwg_search != '') {
      $where = 'AND alt LIKE "%%' . $bwg_search . '%%"';  
    }
    else {
      $where = '';
    }
    if (isset($_REQUEST['page_number_' . $ffwd]) && $_REQUEST['page_number_' . $ffwd]) {
      $limit = ((int) $_REQUEST['page_number_' . $ffwd] - 1) * $objects_per_page;
    }
    else {
      $limit = 0;
    }
    if ($objects_per_page) {
      $limit_str = 'LIMIT ' . $limit . ',' . $objects_per_page;
    }
    else {
      $limit_str = '';
    }
    $results = $wpdb->get_results($wpdb->prepare('SELECT * FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d" ORDER BY `created_time_number` DESC ' . $limit_str, $id)); 
    //store ids ans object_ids
		$id_object_id_json = array();
		foreach($results as $row) {
			$object = new stdClass();
			$object->id = $row->id;
			$object->object_id = $row->object_id;
			array_push($id_object_id_json, $object);
		}
		$this->id_object_id_json = $id_object_id_json;
    //set graph url
    $this->graph_url = str_replace ( 
      array('{ACCESS_TOKEN}', '{FIELDS}', '{LIMIT}', '{OTHER}'), 
      array('access_token=' . $this->access_token . '&', '', '', 'summary=true'), 
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