<?php
/**
 * Created by PhpStorm.
 * User: mher
 * Date: 10/19/18
 * Time: 4:41 PM
 */

class FFWDElementor {

  protected static $instance = null;

  private function __construct(){
    // Register widget for Elementor builder.
    add_action('elementor/widgets/widgets_registered', array($this, 'register_elementor_widgets'));

    if(!defined('TWBB_VERSION')) {
      //fires after elementor editor styles and scripts are enqueued.
      add_action('elementor/editor/after_enqueue_styles', array($this, 'enqueue_editor_styles'), 1);

      // Register 10Web category for Elementor widget if 10Web builder doesn't installed.
      add_action('elementor/elements/categories_registered', array($this, 'register_widget_category'), 1, 1);
    }

  }

  public function register_elementor_widgets(){
    if(defined('ELEMENTOR_PATH') && class_exists('Elementor\Widget_Base')) {
      $file_path = plugin_dir_path(__FILE__) . '/widget.php';
      if(is_file($file_path)) {
        include_once $file_path;
      }
    }
  }

  public function enqueue_editor_styles(){
    wp_enqueue_style('twbb-editor-styles', plugin_dir_url(__FILE__) . 'styles/editor.css', array(), '1.0.0');
  }

  public function register_widget_category($elements_manager){
    $elements_manager->add_category('tenweb-widgets', array(
      'title' => __('10WEB', 'tenweb-builder'),
      'icon' => 'fa fa-plug',
    ));
  }

  public static function get_instance(){
    if(self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }

}