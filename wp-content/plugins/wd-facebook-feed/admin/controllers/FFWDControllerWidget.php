<?php

class FFWDControllerWidget extends WP_Widget {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  private $view;
  private $model;
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  public function __construct() {
    $widget_ops = array(
        'classname' => 'ffwd_info',
        'description' => 'Add Facebook feed to Your widget area.'
    );
    // Widget Control Settings.
    $control_ops = array('id_base' => 'ffwd_info');
    // Create the widget.
    parent::__construct('ffwd_info', 'Facebook Feed WD', $widget_ops, $control_ops);
    require_once WD_FFWD_DIR . "/admin/models/FFWDModelWidget.php";
    $this->model = new FFWDModelWidget();
    require_once WD_FFWD_DIR . "/admin/views/FFWDViewWidget.php";
    $this->view = new FFWDViewWidget($this->model);
  }
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////

  public function widget($args, $instance) {
    $this->view->widget($args, $instance);
  }

  public function form( $instance ) {
    $this->view->form($instance, parent::get_field_id('title'), parent::get_field_name('title'), parent::get_field_id('id'), parent::get_field_name('id'), parent::get_field_id('count'), parent::get_field_name('count'), parent::get_field_id('width'), parent::get_field_name('width'), parent::get_field_id('height'), parent::get_field_name('height'), parent::get_field_id('theme_id'), parent::get_field_name('theme_id'), parent::get_field_id('view_type'), parent::get_field_name('view_type'));
  }

  // Update Settings.
  public function update($new_instance, $old_instance) {
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['id'] = $new_instance['id'];
    $instance['count'] = $new_instance['count'];
    $instance['width'] = $new_instance['width'];
    $instance['height'] = $new_instance['height'];
    //$instance['theme_id'] = $new_instance['theme_id'];
    //$instance['view_type'] = $new_instance['view_type'];
    return $instance;
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