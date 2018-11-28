<?php

/**
 * Class UninstallController_bwg
 */
class UninstallController_bwg {
  /**
   * @var $model
   */
  private $model;
  /**
   * @var $view
   */
  private $view;
  /**
   * @var string $page
   */
  private $page;
 
  public function __construct() {
    if ( !BWG()->is_pro ) {
      global $bwg_options;
      if ( !class_exists("TenWebLibConfig") ) {
        $plugin_dir = apply_filters('tenweb_free_users_lib_path', array('version' => '1.1.1', 'path' => BWG()->plugin_dir));
        include_once($plugin_dir['path'] . "/wd/config.php");
      }
      $config = new TenWebLibConfig();
      $config->set_options($bwg_options);
      $deactivate_reasons = new TenWebLibDeactivate($config);
      $deactivate_reasons->submit_and_deactivate();
    }

    $this->model = new UninstallModel_bwg();
    $this->view = new UninstallView_bwg();

    $this->page = WDWLibrary::get('page');
  }

  /**
   * Execute.
   */
  public function execute() {
    $task = WDWLibrary::get('task');

    if ( method_exists($this, $task) ) {
      $this->$task();
    }
    else {
      $this->display();
    }
  }

  /**
   * Display.
   */
  public function display() {
    $params = array();
    $params['page_title'] = sprintf(__('Uninstall %s', BWG()->prefix), BWG()->nicename);
    $params['tables'] = $this->get_tables();

    $this->view->display($params);
  }

  /**
   * Return DB tables names.
   *
   * @return array
   */
  private function get_tables() {
    global $wpdb;
    $tables = array(
      $wpdb->prefix . 'bwg_album',
      $wpdb->prefix . 'bwg_album_gallery',
      $wpdb->prefix . 'bwg_gallery',
      $wpdb->prefix . 'bwg_image',
      $wpdb->prefix . 'bwg_image_comment',
      $wpdb->prefix . 'bwg_image_rate',
      $wpdb->prefix . 'bwg_image_tag',
      $wpdb->prefix . 'bwg_option',
      $wpdb->prefix . 'bwg_theme',
      $wpdb->prefix . 'bwg_shortcode',
    );

    return $tables;
  }

  /**
   * Uninstall.
   */
  public function uninstall() {
    $params = array();
    $params['tables'] = $this->get_tables();

    $this->model->delete_folder();
    $this->model->delete_db_tables($params);
    // Deactivate all addons.
    WDWLibrary::deactivate_all_addons();
    $params['page_title'] = sprintf(__('Uninstall %s', BWG()->prefix), BWG()->nicename);
    $params['deactivate'] = TRUE;

    $this->view->display($params);
  }
}
