<?php
/**
 * Author: Rob
 * Date: 6/24/13
 * Time: 10:57 AM
 */

class FilemanagerController {
  public $uploads_dir;
  public $uploads_url;

  public function __construct() {
    $this->uploads_dir = BWG()->upload_dir;
    $this->uploads_url = BWG()->upload_url;
  }

  public function execute() {
    $task = isset($_REQUEST['task']) ? stripslashes(esc_html($_REQUEST['task'])) : 'display';
    if (method_exists($this, $task)) {
      $this->$task();
    }
    else {
      $this->display();
    }
  }

  public function get_uploads_dir() {
    return $this->uploads_dir;
  }

  public function get_uploads_url() {
    return $this->uploads_url;
  }

  public function display() {
    require_once BWG()->plugin_dir . '/filemanager/model.php';
    $model = new FilemanagerModel($this);
    require_once BWG()->plugin_dir . '/filemanager/view.php';
    $view = new FilemanagerView($this, $model);
    $view->display();
  }

  private function esc_dir($dir) {
    $dir = str_replace('../', '', $dir);

    return $dir;
  }

  public function make_dir() {
    $input_dir = (isset($_REQUEST['dir']) ? str_replace('\\', '', esc_html($_REQUEST['dir'])) : '');
    $input_dir = htmlspecialchars_decode($input_dir, ENT_COMPAT | ENT_QUOTES);
    $input_dir = $this->esc_dir($input_dir);

    $cur_dir_path = $input_dir == '' ? $this->uploads_dir : $this->uploads_dir . '/' . $input_dir;

    $new_dir_path = $cur_dir_path . '/' . (isset($_REQUEST['new_dir_name']) ? stripslashes(esc_html(sanitize_file_name($_REQUEST['new_dir_name']))) : '');
    $new_dir_path = htmlspecialchars_decode($new_dir_path, ENT_COMPAT | ENT_QUOTES);
    $new_dir_path = $this->esc_dir($new_dir_path);

    if (file_exists($new_dir_path) == true) {
      $msg = __("Directory already exists.", BWG()->prefix);
    }
    else {
      $msg = '';
      mkdir($new_dir_path);
    }
    $args = array(
      'action' => 'addImages',
      'filemanager_msg' => $msg,
      'width' => '850',
      'height' => '550',
      'task' => 'display',
      'extensions' => esc_html($_REQUEST['extensions']),
      'callback' => esc_html($_REQUEST['callback']),
      'dir' => $input_dir,
      'TB_iframe' => '1',
    );
    $query_url = wp_nonce_url( admin_url('admin-ajax.php'), 'addImages', 'bwg_nonce' );
    $query_url  = add_query_arg($args, $query_url);
    header('Location: ' . $query_url);
    exit;
  }

  public function rename_item() {
    $input_dir = (isset($_REQUEST['dir']) ? str_replace('\\', '', esc_html($_REQUEST['dir'])) : '');
    $input_dir = htmlspecialchars_decode($input_dir, ENT_COMPAT | ENT_QUOTES);
    $input_dir = $this->esc_dir($input_dir);

    $cur_dir_path = $input_dir == '' ? $this->uploads_dir : $this->uploads_dir . '/' . $input_dir;

    $file_names = explode('**#**', (isset($_REQUEST['file_names']) ? stripslashes(esc_html($_REQUEST['file_names'])) : ''));
    $file_name = $file_names[0];
    $file_name = htmlspecialchars_decode($file_name, ENT_COMPAT | ENT_QUOTES);
    $file_name = str_replace('../', '', $file_name);

    $file_new_name = (isset($_REQUEST['file_new_name']) ? stripslashes(esc_html($_REQUEST['file_new_name'])) : '');
    $file_new_name = htmlspecialchars_decode($file_new_name, ENT_COMPAT | ENT_QUOTES);
    $file_new_name = $this->esc_dir($file_new_name);

    $file_path = $cur_dir_path . '/' . $file_name;
    $thumb_file_path = $cur_dir_path . '/thumb/' . $file_name;
    $original_file_path = $cur_dir_path . '/.original/' . $file_name;

    $msg = '';
    global $wpdb;

    if (file_exists($file_path) == false) {
      $msg = __("File doesn't exist.", BWG()->prefix);
    }
    elseif (is_dir($file_path) == true) {
      if (rename($file_path, $cur_dir_path . '/' . sanitize_file_name($file_new_name)) == false) {
        $msg = __("Can't rename the file.", BWG()->prefix);
      }
      else {
        $wpdb->query('UPDATE ' . $wpdb->prefix . 'bwg_image SET
          image_url = INSERT(image_url, LOCATE("' . $input_dir . '/' . $file_name . '", image_url), CHAR_LENGTH("' . $input_dir . '/' . $file_name . '"), "' . $input_dir . '/' . $file_new_name . '"),
          thumb_url = INSERT(thumb_url, LOCATE("' . $input_dir . '/' . $file_name . '", thumb_url), CHAR_LENGTH("' . $input_dir . '/' . $file_name . '"), "' . $input_dir . '/' . $file_new_name . '")');
        $wpdb->query('UPDATE ' . $wpdb->prefix . 'bwg_gallery SET
          preview_image = INSERT(preview_image, LOCATE("' . $input_dir . '/' . $file_name . '", preview_image), CHAR_LENGTH("' . $input_dir . '/' . $file_name . '"), "' . $input_dir . '/' . $file_new_name . '")');
      }
    }
    elseif ((strrpos($file_name, '.') !== false)) {
      $file_extension = substr($file_name, strrpos($file_name, '.') + 1);
      if (rename($file_path, $cur_dir_path . '/' . $file_new_name . '.' . $file_extension) == false) {
        $msg = __("Can't rename the file.", BWG()->prefix);
      }
      else {
        $wpdb->update($wpdb->prefix . 'bwg_image', array(
          'filename' => $file_new_name,
          'image_url' => $input_dir . '/' . $file_new_name . '.' . $file_extension,
          'thumb_url' => $input_dir . '/thumb/' . $file_new_name . '.' . $file_extension,
          ), array(
            'thumb_url' =>  $input_dir . '/thumb/' . $file_name,
        ));
        $wpdb->update($wpdb->prefix . 'bwg_gallery', array(
          'preview_image' => $input_dir . '/thumb/' . $file_new_name . '.' . $file_extension,
          ), array(
            'preview_image' =>  $input_dir . '/thumb/' . $file_name));

        rename($thumb_file_path, $cur_dir_path . '/thumb/' . $file_new_name . '.' . $file_extension);
        rename($original_file_path, $cur_dir_path . '/.original/' . $file_new_name . '.' . $file_extension);
      }
    }
    else {
      $msg = __("Can't rename the file.", BWG()->prefix);
    }
    $_REQUEST['file_names'] = '';
    $args = array(
      'action' => 'addImages',
      'filemanager_msg' => $msg,
      'width' => '850',
      'height' => '550',
      'task' => 'display',
      'extensions' => esc_html($_REQUEST['extensions']),
      'callback' => esc_html($_REQUEST['callback']),
      'dir' => $input_dir,
      'TB_iframe' => '1',
    );
    $query_url = wp_nonce_url( admin_url('admin-ajax.php'), 'addImages', 'bwg_nonce' );
    $query_url = add_query_arg($args, $query_url);
    header('Location: ' . $query_url);
    exit;
  }

  public function remove_items() {
    $input_dir = (isset($_REQUEST['dir']) ? str_replace('\\', '', ($_REQUEST['dir'])) : '');
    $input_dir = htmlspecialchars_decode($input_dir, ENT_COMPAT | ENT_QUOTES);
    $input_dir = $this->esc_dir($input_dir);

    $cur_dir_path = $input_dir == '' ? $this->uploads_dir : $this->uploads_dir . '/' . $input_dir;

    $file_names = explode('**#**', (isset($_REQUEST['file_names']) ? stripslashes(esc_html($_REQUEST['file_names'])) : ''));

    $msg = '';
    foreach ($file_names as $file_name) {
      $file_name = htmlspecialchars_decode($file_name, ENT_COMPAT | ENT_QUOTES);
      $file_name = str_replace('../', '', $file_name);
      $file_path = $cur_dir_path . '/' . $file_name;
      $thumb_file_path = $cur_dir_path . '/thumb/' . $file_name;
      $original_file_path = $cur_dir_path . '/.original/' . $file_name;
      if (file_exists($file_path) == false) {
        $msg = __("Some of the files couldn't be removed.", BWG()->prefix);
      }
      else {
        $this->remove_file_dir($file_path, $input_dir, $file_name);
        if (file_exists($thumb_file_path)) {
          $this->remove_file_dir($thumb_file_path);
        }
        if (file_exists($original_file_path)) {
          $this->remove_file_dir($original_file_path);
        }
      }
    }
    $_REQUEST['file_names'] = '';
    $args = array(
      'action' => 'addImages',
      'filemanager_msg' => $msg,
      'width' => '850',
      'height' => '550',
      'task' => 'show_file_manager',
      'extensions' => esc_html($_REQUEST['extensions']),
      'callback' => esc_html($_REQUEST['callback']),
      'dir' => $input_dir,
      'TB_iframe' => '1',
    );
    $query_url = wp_nonce_url( admin_url('admin-ajax.php'), 'addImages', 'bwg_nonce' );
    $query_url = add_query_arg($args, $query_url);
    header('Location: ' . $query_url);
    exit;
  }

  public function paste_items() {
    $input_dir = (isset($_REQUEST['dir']) ? str_replace('\\', '', ($_REQUEST['dir'])) : '');
    $input_dir = htmlspecialchars_decode($input_dir, ENT_COMPAT | ENT_QUOTES);
    $input_dir = $this->esc_dir($input_dir);

    $msg = '';
    $flag = TRUE;

    $file_names = explode('**#**', (isset($_REQUEST['clipboard_files']) ? stripslashes($_REQUEST['clipboard_files']) : ''));
    $src_dir = (isset($_REQUEST['clipboard_src']) ? stripslashes($_REQUEST['clipboard_src']) : '');
    $relative_source_dir = $src_dir;
    $src_dir = $src_dir == '' ? $this->uploads_dir : $this->uploads_dir . '/' . $src_dir;
    $src_dir = htmlspecialchars_decode($src_dir, ENT_COMPAT | ENT_QUOTES);
    $src_dir = $this->esc_dir($src_dir);

    $dest_dir = (isset($_REQUEST['clipboard_dest']) ? stripslashes($_REQUEST['clipboard_dest']) : '');
    $dest_dir = $dest_dir == '' ? $this->uploads_dir : $this->uploads_dir . '/' . $dest_dir;
    $dest_dir = htmlspecialchars_decode($dest_dir, ENT_COMPAT | ENT_QUOTES);
    $dest_dir = $this->esc_dir($dest_dir);

    switch ((isset($_REQUEST['clipboard_task']) ? stripslashes($_REQUEST['clipboard_task']) : '')) {
      case 'copy':
        foreach ($file_names as $file_name) {
          $file_name = htmlspecialchars_decode($file_name, ENT_COMPAT | ENT_QUOTES);
          $file_name = str_replace('../', '', $file_name);
          $src = $src_dir . '/' . $file_name;
          if (file_exists($src) == false) {
            $msg = "Failed to copy some of the files.";
            $msg = $file_name;
            continue;
          }
          $dest = $dest_dir . '/' . $file_name;
          if (!is_dir($src_dir . '/' . $file_name)) {
            if (!is_dir($dest_dir . '/thumb')) {
              mkdir($dest_dir . '/thumb', 0755);
            }
            $thumb_src = $src_dir . '/thumb/' . $file_name;
            $thumb_dest = $dest_dir . '/thumb/' . $file_name;
            if (!is_dir($dest_dir . '/.original')) {
              mkdir($dest_dir . '/.original', 0755);
            }
            $original_src = $src_dir . '/.original/' . $file_name;
            $original_dest = $dest_dir . '/.original/' . $file_name;
          }
          $i = 0;
          if (file_exists($dest) == true) {
            $path_parts = pathinfo($dest);
            while (file_exists($path_parts['dirname'] . '/' . $path_parts['filename'] . '(' . ++$i . ')' . '.' . $path_parts['extension'])) {
            }
            $dest = $path_parts['dirname'] . '/' . $path_parts['filename'] . '(' . $i . ')' . '.' . $path_parts['extension'];
            if (!is_dir($src_dir . '/' . $file_name)) {
              $thumb_dest = $path_parts['dirname'] . '/thumb/' . $path_parts['filename'] . '(' . $i . ')' . '.' . $path_parts['extension'];
              $original_dest = $path_parts['dirname'] . '/.original/' . $path_parts['filename'] . '(' . $i . ')' . '.' . $path_parts['extension'];
            }
          }

          if (!$this->copy_file_dir($src, $dest)) {
            $msg = __("Failed to copy some of the files.", BWG()->prefix);
          }
          if (!is_dir($src_dir . '/' . $file_name)) {
            $this->copy_file_dir($thumb_src, $thumb_dest);
            $this->copy_file_dir($original_src, $original_dest);
          }
        }
        break;
      case 'cut':
        if ( $src_dir != $dest_dir ) {
          foreach ( $file_names as $file_name ) {
            $file_name = htmlspecialchars_decode($file_name, ENT_COMPAT | ENT_QUOTES);
            $file_name = str_replace('../', '', $file_name);
            $src = $src_dir . '/' . $file_name;
            $dest = $dest_dir . '/' . $file_name;
            if ( (file_exists($src) == FALSE) || (file_exists($dest) == TRUE) ) {
              $flag = FALSE;
            }
            else {
              $flag = rename($src, $dest);
            }
            if ( !$flag ) {
              $msg = __("Failed to move some of the files.", BWG()->prefix);
            }
            else {
              global $wpdb;
              if ( is_dir($dest_dir . '/' . $file_name) ) {
                $wpdb->query('UPDATE ' . $wpdb->prefix . 'bwg_image SET
                  image_url = INSERT(image_url, LOCATE("' . str_replace($this->uploads_dir . '/', '', $src) . '", image_url), CHAR_LENGTH("' . str_replace($this->uploads_dir . '/', '', $src) . '"), "' . str_replace(str_replace($input_dir, '', $dest_dir), '', $dest) . '"),
                  thumb_url = INSERT(thumb_url, LOCATE("' . str_replace($this->uploads_dir . '/', '', $src) . '", thumb_url), CHAR_LENGTH("' . str_replace($this->uploads_dir . '/', '', $src) . '"), "' . str_replace(str_replace($input_dir, '', $dest_dir), '', $dest) . '")');
                $wpdb->query('UPDATE ' . $wpdb->prefix . 'bwg_gallery SET
                  preview_image = INSERT(preview_image, LOCATE("' . str_replace($this->uploads_dir . '/', '', $src) . '", preview_image), CHAR_LENGTH("' . str_replace($this->uploads_dir . '/', '', $src) . '"), "' . str_replace(str_replace($input_dir, '', $dest_dir), '', $dest) . '")');
              }
              else {
                $thumb_src = $src_dir . '/thumb/' . $file_name;
                $thumb_dest = $dest_dir . '/thumb/' . $file_name;
                if ( !is_dir($dest_dir . '/thumb') ) {
                  mkdir($dest_dir . '/thumb', 0755);
                }
                $original_src = $src_dir . '/.original/' . $file_name;
                $original_dest = $dest_dir . '/.original/' . $file_name;
                if ( !is_dir($dest_dir . '/.original') ) {
                  mkdir($dest_dir . '/.original', 0755);
                }
                rename($thumb_src, $thumb_dest);
                rename($original_src, $original_dest);
                $wpdb->update($wpdb->prefix . 'bwg_image', array(
                  'filename' => $file_name,
                  'image_url' => str_replace(str_replace($input_dir, '', $dest_dir), '', $dest),
                  'thumb_url' => $input_dir . '/thumb/' . $file_name,
                ), array(
                  'thumb_url' => $relative_source_dir . '/thumb/' . $file_name,
                ));
                $wpdb->update($wpdb->prefix . 'bwg_gallery', array(
                  'preview_image' => $input_dir . '/thumb/' . $file_name,
                ), array(
                  'preview_image' => $relative_source_dir . '/thumb/' . $file_name,
                ));
              }
            }
          }
        }
        break;
    }

    $args = array(
      'action' => 'addImages',
      'filemanager_msg' => $msg,
      'width' => '850',
      'height' => '550',
      'task' => 'show_file_manager',
      'extensions' => esc_html($_REQUEST['extensions']),
      'callback' => esc_html($_REQUEST['callback']),
      'dir' => $input_dir,
      'TB_iframe' => '1',
    );
    $query_url = wp_nonce_url( admin_url('admin-ajax.php'), 'addImages', 'bwg_nonce' );
    $query_url = add_query_arg($args, $query_url);
    header('Location: ' . $query_url);
    exit;
  }

  public function import_items() {
    $args = array(
      'action' => 'bwg_UploadHandler',
      'importer_thumb_width' => esc_html($_REQUEST['importer_thumb_width']),
      'importer_thumb_height' => esc_html($_REQUEST['importer_thumb_height']),
      'callback' => esc_html($_REQUEST['callback']),
      'file_namesML' => esc_html($_REQUEST['file_namesML']),
      'importer_img_width' => esc_html($_REQUEST['importer_img_width']),
      'importer_img_height' => esc_html($_REQUEST['importer_img_height']),
      'import' => 'true',
      'redir' => esc_html($_REQUEST['dir']),
      'dir' => esc_html($_REQUEST['dir']) . '/',
    );
    $query_url = wp_nonce_url( admin_url('admin-ajax.php'), 'bwg_UploadHandler', 'bwg_nonce' );
    $query_url = add_query_arg($args, $query_url);
    header('Location: ' . $query_url);
    exit;
  }

  private function remove_file_dir($del_file_dir, $input_dir = FALSE, $file_name = FALSE) {
    $del_file_dir = $this->esc_dir($del_file_dir);
    if (is_dir($del_file_dir) == true) {
      $files_to_remove = scandir($del_file_dir);
      foreach ($files_to_remove as $file) {
        if ($file != '.' and $file != '..') {
          $this->remove_file_dir($del_file_dir . '/' . $file, $input_dir . '/' . $file_name, $file);
        }
      }
      rmdir($del_file_dir);
    }
    else {
      unlink($del_file_dir);
      if ( $input_dir !== FALSE && $file_name !== FALSE ) {
        global $wpdb;
        $deleted_image_dir = $input_dir . '/thumb/' . $file_name;
        // delete image by preview_image.
        $wpdb->delete($wpdb->prefix . 'bwg_image', array( 'thumb_url' => $deleted_image_dir ));
        // Get gallery by preview_image or random_preview_image.
        $galleries = $wpdb->get_results('SELECT `id` FROM `' . $wpdb->prefix . 'bwg_gallery` WHERE `preview_image` = "' . $deleted_image_dir . '" OR `random_preview_image` = "' . $deleted_image_dir . '"');
        // Update random preview image on bwg_gallery.
        if ( !empty($galleries) ) {
          $gallerIds = array();
          foreach ( $galleries as $item ) {
            $gallerIds[$item->id] = $item->id;
          }
          // Get thumb images by gallery id.
          $thumbIds = array();
          $thumbs = $wpdb->get_results('SELECT `gallery_id`, `thumb_url` FROM `' . $wpdb->prefix . 'bwg_image` WHERE `gallery_id` IN (' . implode(',', $gallerIds) . ')');
          if ( !empty($thumbs) ) {
            foreach ( $thumbs as $item ) {
              $thumbIds[$item->gallery_id][] = $item->thumb_url;
            }
          }
          foreach ( $gallerIds as $gid ) {
            $random_preview_image = '';
            if ( !empty($thumbIds[$gid]) ) {
              $rand_keys = array_rand($thumbIds[$gid], 1);
              $random_preview_image = $thumbIds[$gid][$rand_keys];
              if ( !preg_match('/^(http|https):\\/\\/[a-z0-9_]+([\\-\\.]{1}[a-z_0-9]+)*\\.[_a-z]{2,5}' . '((:[0-9]{1,5})?\\/.*)?$/i', $random_preview_image) ) {
                $random_preview_image = wp_normalize_path($thumbIds[$gid][$rand_keys]);
              }
            }
            $wpdb->update($wpdb->prefix . 'bwg_gallery', array(
              'preview_image' => '',
              'random_preview_image' => $random_preview_image,
            ), array( 'id' => $gid ));
          }
        }
      }
    }
  }

  private function copy_file_dir($src, $dest) {
    $src = $this->esc_dir($src);
    $dest = $this->esc_dir($dest);

    if (is_dir($src) == true) {
      $dir = opendir($src);
      @mkdir($dest);
      while (false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
          if (is_dir($src . '/' . $file)) {
            $this->copy_file_dir($src . '/' . $file, $dest . '/' . $file);
          }
          else {
            copy($src . '/' . $file, $dest . '/' . $file);
          }
        }
      }
      closedir($dir);
      return true;
    }
    else {
      return copy($src, $dest);
    }
  }
}
