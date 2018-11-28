<?php

class BWGElementor extends \Elementor\Widget_Base {
  /**
   * Get widget name.
   *
   * @return string Widget name.
   */
  public function get_name() {
    return 'bwg-elementor';
  }

  /**
   * Get widget title.
   *
   * @return string Widget title.
   */
  public function get_title() {
    return __('Gallery', BWG()->prefix);
  }

  /**
   * Get widget icon.
   *
   * @return string Widget icon.
   */
  public function get_icon() {
    return 'fa twbb-photo-gallery twbb-widget-icon';
  }

  /**
   * Get widget categories.
   *
   * @return array Widget categories.
   */
  public function get_categories() {
    return [ 'tenweb-widgets' ];
  }

  /**
   * Register widget controls.
   */
  protected function _register_controls() {
    $this->start_controls_section(
      'bwg_general',
      [
        'label' => __('General', BWG()->prefix),
      ]
    );

    $this->add_control(
      'bwg_view_type_tabs',
      [
        'label' => __('Gallery/Gallery group', BWG()->prefix),
        'type' => \Elementor\Controls_Manager::CHOOSE,
        'label_block' => true,
        'toggle' => false,
        'default' => 'gallery',
        'options' => [
          'gallery' => [
            'title' => __('Gallery', BWG()->prefix),
            'icon' => 'fa fa-square',
          ],
          'gallery_group' => [
            'title' => __('Gallery group', BWG()->prefix),
            'icon' => 'fa fa-th-large',
          ],
        ],
      ]
    );

    $this->add_control(
      'bwg_gallery_view_type',
      [
        'label_block' => true,
        'description' => __('Select the gallery view type.', BWG()->prefix) . '<a target="_balnk" href="' . add_query_arg(array( 'page' => 'options_' . BWG()->prefix, 'active_tab' => 1 ), admin_url('admin.php')) . '">' . __('Edit options', BWG()->prefix) . '</a>',
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'thumbnails',
        'options' => [
          'thumbnails' => __('Thumbnails', BWG()->prefix),
          'thumbnails_masonry' => __('Masonry', BWG()->prefix),
          'thumbnails_mosaic' => __('Mosaic', BWG()->prefix),
          'slideshow' => __('Slideshow', BWG()->prefix),
          'image_browser' => __('Image browser', BWG()->prefix),
          'blog_style' => __('Blog style', BWG()->prefix),
          'carousel' => __('Carousel', BWG()->prefix),
        ],
        'condition' => [
          'bwg_view_type_tabs' => 'gallery',
        ],
      ]
    );
    $this->add_control(
      'bwg_galleries',
      [
        'label' => __('Gallery', BWG()->prefix),
        'label_block' => true,
        'description' => __('Select the gallery to display.', BWG()->prefix) . '<a target="_balnk" href="' . add_query_arg(array( 'page' => 'galleries_' . BWG()->prefix ), admin_url('admin.php')) . '">' . __('Edit gallery', BWG()->prefix) . '</a>',
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 0,
        'options' => WDWLibrary::get_galleries(),
        'condition' => [
          'bwg_view_type_tabs' => 'gallery',
        ],
      ]
    );
    $this->add_control(
      'bwg_tags',
      [
        'label' => __('Tag', BWG()->prefix),
        'label_block' => true,
        'description' => __('Filter gallery images by this tag.', BWG()->prefix) . '<a target="_balnk" href="' . add_query_arg(array( 'taxonomy' => BWG()->prefix . '_tag' ), admin_url('edit-tags.php')) . '">' . __('Edit tag', BWG()->prefix) . '</a>',
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 0,
        'options' => WDWLibrary::get_tags(),
        'condition' => [
          'bwg_view_type_tabs' => 'gallery',
        ],
      ]
    );

    $this->add_control(
      'bwg_gallery_group_view_type',
      [
        'label_block' => true,
        'description' => __('Select the gallery group type.', BWG()->prefix) . '<a target="_balnk" href="' . add_query_arg(array( 'page' => 'options_' . BWG()->prefix, 'active_tab' => 2 ), admin_url('admin.php')) . '">' . __('Edit options', BWG()->prefix) . '</a>',
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 'album_compact_preview',
        'options' => [
          'album_compact_preview' => __('Compact', BWG()->prefix),
          'album_masonry_preview' => __('Masonry', BWG()->prefix),
          'album_extended_preview' => __('Extended', BWG()->prefix),
        ],
        'condition' => [
          'bwg_view_type_tabs' => 'gallery_group',
        ],
      ]
    );
    $this->add_control(
      'bwg_gallery_group',
      [
        'label' => __('Gallery group', BWG()->prefix),
        'label_block' => true,
        'description' => __('Select the gallery group to display.', BWG()->prefix) . '<a target="_balnk" href="' . add_query_arg(array( 'page' => 'albums_' . BWG()->prefix ), admin_url('admin.php')) . '">' . __('Edit gallery group', BWG()->prefix) . '</a>',
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => 0,
        'options' => WDWLibrary::get_gallery_groups(),
        'condition' => [
          'bwg_view_type_tabs' => 'gallery_group',
        ],
      ]
    );

    $this->add_control(
      'bwg_theme',
      [
        'label' => __('Theme', BWG()->prefix),
        'label_block' => true,
        'description' => __('Choose the theme for your gallery.', BWG()->prefix) . '<a target="_balnk" href="' . add_query_arg(array( 'page' => 'themes_' . BWG()->prefix ), admin_url('admin.php')) . '">' . __('Edit theme', BWG()->prefix) . '</a>',
        'type' => \Elementor\Controls_Manager::SELECT,
        'default' => WDWLibrary::get_default_theme(),
        'options' => WDWLibrary::get_theme_rows_data(),
      ]
    );

    $this->end_controls_section();
  }

  /**
   * Render widget output on the frontend.
   */
  protected function render() {
    $settings = $this->get_settings_for_display();
    $params = array();

    if ( $settings['bwg_view_type_tabs'] === 'gallery' ) {
      $params['gallery_type'] = $settings['bwg_gallery_view_type'];
      $params['gallery_id'] = $settings['bwg_galleries'];
      $params['tag'] = $settings['bwg_tags'];
    }
    elseif ( $settings['bwg_view_type_tabs'] === 'gallery_group' ) {
      $params['gallery_type'] = $settings['bwg_gallery_group_view_type'];
      $params['album_id'] = $settings['bwg_gallery_group'];
    }
    $params['theme_id'] = $settings['bwg_theme'];

    echo BWG()->shortcode($params);
  }
}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new BWGElementor());
