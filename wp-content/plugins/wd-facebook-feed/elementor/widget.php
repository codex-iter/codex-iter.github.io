<?php
/**
 * Created by PhpStorm.
 * User: mher
 * Date: 10/19/18
 * Time: 4:00 PM
 */

class FFWDElementorWidget extends \Elementor\Widget_Base {
  private $feed_options = array();
  private $default_feed = '0';

  /**
   * Get widget name.
   *
   * @return string Widget name.
   */
  public function get_name(){
    return 'ffwd-elementor';
  }

  /**
   * Get widget title.
   *
   * @return string Widget title.
   */
  public function get_title(){
    return __('Facebook Feed', 'wd-facebook-feed');
  }

  /**
   * Get widget icon.
   *
   * @return string Widget icon.
   */
  public function get_icon(){
    return 'twbb-wd-facebook-feed twbb-widget-icon';
  }

  /**
   * Get widget categories.
   *
   * @return array Widget categories.
   */
  public function get_categories(){
    return ['tenweb-widgets'];
  }

  /**
   * Register widget controls.
   */
  protected function _register_controls(){
    $this->set_options();
    $this->start_controls_section(
      'ffwd_general',
      [
        'label' => __('General', 'wd-facebook-feed'),
      ]
    );

    $this->add_control(
      'ffwd_feeds',
      [
        'label' => __('Select Feed', 'wd-facebook-feed'),
        'label_block' => true,
        'description' => '',
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => $this->default_feed,
        'options' => $this->feed_options
      ]
    );

    $this->end_controls_section();
  }

  /**
   * Render widget output on the frontend.
   */
  protected function render(){
    $settings = $this->get_settings_for_display();

    if(!empty($settings['ffwd_feeds'])) {
      echo do_shortcode('[WD_FB id="' . $settings['ffwd_feeds'] . '"]');
    } else {
      echo '<strong>' . __("Feed Doesn't exists.", "wd-facebook-feed") . '</strong>';
    }
  }

  public function set_options(){
    require_once WD_FFWD_DIR . "/admin/models/FFWDModelFFWDShortcode.php";
    $model = new FFWDModelFFWDShortcode();
    $rows = $model->get_wd_fb_data();

    if(!empty($rows)) {
      foreach($rows as $row) {
        $this->feed_options[$row->id] = $row->name;
      }
    } else {
      $this->feed_options = array('0' => 'No Feed');
    }

    reset($this->feed_options);
    $this->default_feed = key($this->feed_options);
  }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new FFWDElementorWidget());
