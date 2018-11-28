<?php

class FFWDControllerInfo_ffwd {
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
  public function execute() {
    $task = ((isset($_REQUEST['task'])) ? esc_html(stripslashes($_REQUEST['task'])) : '');
    $id = ((isset($_REQUEST['current_id'])) ? esc_html(stripslashes($_REQUEST['current_id'])) : 0);
    if($task != ''){
      if(!WDW_FFWD_Library::verify_nonce('info_ffwd')){
        die('Sorry, your nonce did not verify.');
      }
    }

    if (method_exists($this, $task)) {
      $this->$task($id);
    }
    else {
      $this->display();
    }
  }

  public function display() {
    require_once WD_FFWD_DIR . "/admin/models/FFWDModelInfo_ffwd.php";
    $model = new FFWDModelInfo_ffwd();

    require_once WD_FFWD_DIR . "/admin/views/FFWDViewInfo_ffwd.php";
    $view = new FFWDViewInfo_ffwd($model);
    //$this->delete_unknown_images();
    $view->display();
  }

  public function add() {
    require_once WD_FFWD_DIR . "/admin/models/FFWDModelInfo_ffwd.php";
    $model = new FFWDModelInfo_ffwd();

    require_once WD_FFWD_DIR . "/admin/views/FFWDViewInfo_ffwd.php";
    $view = new FFWDViewInfo_ffwd($model);
    $view->edit(0);
  }

  public function edit() {
    require_once WD_FFWD_DIR . "/admin/models/FFWDModelInfo_ffwd.php";
    $model = new FFWDModelInfo_ffwd();

    require_once WD_FFWD_DIR . "/admin/views/FFWDViewInfo_ffwd.php";
    $view = new FFWDViewInfo_ffwd($model);
    $id = ((isset($_POST['current_id']) && esc_html(stripslashes($_POST['current_id'])) != '') ? esc_html(stripslashes($_POST['current_id'])) : 0);
    $view->edit($id);
  }

  public function save() {
    $this->display();
		echo WDW_FFWD_Library::message('Item Succesfully Saved.', 'updated');
  }

  public function bwg_get_unique_name($name, $id) {
    global $wpdb;
    if ($id != 0) {
      $query = $wpdb->prepare("SELECT name FROM " . $wpdb->prefix . "bwg_gallery WHERE name = %s AND id != %d", $name, $id);
    }
    else {
      $query = $wpdb->prepare("SELECT name FROM " . $wpdb->prefix . "bwg_gallery WHERE name = %s", $name);
    }
    if ($wpdb->get_var($query)) {
      $num = 2;
      do {
        $alt_name = $name . "-$num";
        $num++;
        $slug_check = $wpdb->get_var($wpdb->prepare("SELECT name FROM " . $wpdb->prefix . "bwg_gallery WHERE name = %s", $alt_name));
      } while ($slug_check);
      $name = $alt_name;
    }
    return $name;
  }

  public function save_order($flag = TRUE) {
    global $wpdb;
    $gallery_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'wd_fb_info');
    if ($gallery_ids_col) {
      foreach ($gallery_ids_col as $gallery_id) {
        if (isset($_POST['order_input_' . $gallery_id])) {
          $order_values[$gallery_id] = (int) $_POST['order_input_' . $gallery_id];
        }
        else {
          $order_values[$gallery_id] = (int) $wpdb->get_var($wpdb->prepare('SELECT `order` FROM ' . $wpdb->prefix . 'wd_fb_info WHERE `id`="%d"', $gallery_id));
        }
      }
      asort($order_values);
      $i = 1;
      foreach ($order_values as $key => $order_value) {
        $wpdb->update($wpdb->prefix . 'wd_fb_info', array('order' => $i), array('id' => $key));
        $i++;
      }
      if ($flag) {
        echo WDW_FFWD_Library::message('Ordering Succesfully Saved.', 'updated');
      }
    }
    $this->display();
  }

  public function delete($id) {
    global $wpdb;
    $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wd_fb_info WHERE id="%d"', $id);
    $fb_data = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d"', $id);
    if ($wpdb->query($query)) {
      $wpdb->query($fb_data);
      echo WDW_FFWD_Library::message('Item Succesfully Deleted.', 'updated');
    }
    else {
      echo WDW_FFWD_Library::message('Error. Please install plugin again.', 'error');
    }
    $this->display();
  }
  
  public function delete_all() {
    global $wpdb;
    $flag = FALSE;
    $fb_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'wd_fb_info');
    foreach ($fb_ids_col as $fb_id) {
      if (isset($_POST['check_' . $fb_id]) || isset($_POST['check_all_items'])) {
        $flag = TRUE;
        $query = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wd_fb_info WHERE id="%d"', $fb_id);
        $wpdb->query($query);
        $query_image = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'wd_fb_data WHERE fb_id="%d"', $fb_id);
        $wpdb->query($query_image);
        // $query_album_gallery = $wpdb->prepare('DELETE FROM ' . $wpdb->prefix . 'bwg_album_gallery WHERE alb_gal_id="%d" AND is_album="%d"', $fb_id, 0);
        // $wpdb->query($query_album_gallery);
      }
    }
    if ($flag) {
      echo WDW_FFWD_Library::message('Items Succesfully Deleted.', 'updated');
    }
    else {
      echo WDW_FFWD_Library::message('You must select at least one item.', 'error');
    }
    $this->display();
  }

  public function publish($id) {
    global $wpdb;
    $save = $wpdb->update($wpdb->prefix . 'wd_fb_info', array('published' => 1), array('id' => $id));
    if ($save !== FALSE) {
      echo WDW_FFWD_Library::message('Item Succesfully Published.', 'updated');
    }
    else {
      echo WDW_FFWD_Library::message('Error. Please install plugin again.', 'error');
    }
    $this->display();
  }
  
  public function publish_all() {
    global $wpdb;
    $flag = FALSE;
    if (isset($_POST['check_all_items'])) {
      $wpdb->query('UPDATE ' .  $wpdb->prefix . 'wd_fb_info SET published=1');
      $flag = TRUE;
    }
    else {
      $gal_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'wd_fb_info');
      foreach ($gal_ids_col as $gal_id) {
        if (isset($_POST['check_' . $gal_id])) {
          $flag = TRUE;
          $wpdb->update($wpdb->prefix . 'wd_fb_info', array('published' => 1), array('id' => $gal_id));
        }
      }
    }
    if ($flag) {
      echo WDW_FFWD_Library::message('Items Succesfully Published.', 'updated');
    }
    else {
      echo WDW_FFWD_Library::message('You must select at least one item.', 'error');
    }
    $this->display();
  }

  public function unpublish($id) {
    global $wpdb;
    $save = $wpdb->update($wpdb->prefix . 'wd_fb_info', array('published' => 0), array('id' => $id));
    if ($save !== FALSE) {
      echo WDW_FFWD_Library::message('Item Succesfully Unpublished.', 'updated');
    }
    else {
      echo WDW_FFWD_Library::message('Error. Please install plugin again.', 'error');
    }
    $this->display();
  }
  
  public function unpublish_all() {
    global $wpdb;
    $flag = FALSE;
    if (isset($_POST['check_all_items'])) {
      $wpdb->query('UPDATE ' .  $wpdb->prefix . 'wd_fb_info SET published=0');
      $flag = TRUE;
    }
    else {
      $gal_ids_col = $wpdb->get_col('SELECT id FROM ' . $wpdb->prefix . 'wd_fb_info');
      foreach ($gal_ids_col as $gal_id) {
        if (isset($_POST['check_' . $gal_id])) {
          $flag = TRUE;
          $wpdb->update($wpdb->prefix . 'wd_fb_info', array('published' => 0), array('id' => $gal_id));
        }
      }
    }
    if ($flag) {
      echo WDW_FFWD_Library::message('Items Succesfully Unpublished.', 'updated');
    }
    else {
      echo WDW_FFWD_Library::message('You must select at least one item.', 'error');
    }
    $this->display();
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