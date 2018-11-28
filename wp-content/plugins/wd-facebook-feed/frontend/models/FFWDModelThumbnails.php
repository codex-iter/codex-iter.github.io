<?php

class FFWDModelThumbnails extends FFWDModelMain {
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
    // Store ids ans object_ids
		$id_object_id_json = array();
		foreach($results as $row) {
			$object = new stdClass();
			$object->id = $row->id;
			$object->object_id = $row->object_id;
			$object->type = $row->type;
			array_push($id_object_id_json, $object);
		}
		$this->id_object_id_json = $id_object_id_json;
    // Set graph url
    $this->graph_url = str_replace ( 
      array('{ACCESS_TOKEN}', '{LIMIT}', '{OTHER}'), 
      array('access_token=' . $this->access_token . '&', '', 'summary=true'), 
      $this->graph_url
    );
    return $results;
  }

  public function see_less_more($string) {
    $string = strip_tags($string);
    $new_string = $string;
    $hide_text_paragraph = '';
    $length = strlen($string); 
		$text_length = 50;
		if ($length > $text_length) {
      // Truncate string
      $stringCut = substr($string, 0, $text_length);
      // Make sure it ends in a word so football doesn't footba ass...
      $last_whitespace_in_string_cut = strrpos($stringCut, ' ');
			$last_whitespace_in_string_cut = ($last_whitespace_in_string_cut === false) ? 0 : $last_whitespace_in_string_cut;
      // Get hide text   
      $hide_text_length = $length - $last_whitespace_in_string_cut;             
      $hide_text = substr($string, $last_whitespace_in_string_cut, $hide_text_length);
      $hide_text_paragraph = ' <span style="display:none" class="ffwd_thumbnail_name_hide" >' . $hide_text . ' </span>';                           
      $new_string = substr($stringCut, 0, $last_whitespace_in_string_cut) . $hide_text_paragraph;                             
    }
    return $new_string;
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