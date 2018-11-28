<?php
/**
 * Plugin Name: Photo Gallery
 * Plugin URI: https://10web.io/plugins/wordpress-photo-gallery/
 * Description: This plugin is a fully responsive gallery plugin with advanced functionality.  It allows having different image galleries for your posts and pages. You can create unlimited number of galleries, combine them into albums, and provide descriptions and tags.
 * Version: 1.5.10
 * Author: Photo Gallery Team
 * Author URI: https://10web.io/pricing/
 * License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

defined('ABSPATH') || die('Access Denied');

$bwg = 0;
final class BWG {
  /**
   * The single instance of the class.
   */
  protected static $_instance = null;
  /**
   * Plugin directory path.
   */
  public $plugin_dir = '';
  /**
   * Plugin directory url.
   */
  public $plugin_url = '';
  /**
   * Plugin main file.
   */
  public $main_file = '';
  /**
   * Plugin version.
   */
  public $plugin_version = '';
  /**
   * Plugin database version.
   */
  public $db_version = '';
  /**
   * Plugin prefix.
   */
  public $prefix = '';
  public $nicename = '';
  public $nonce = 'bwg_nonce';
  public $is_pro = FALSE;
  public $is_demo = FALSE;
  public $options = array();
  public $upload_dir = '';
  public $upload_url = '';
  public $free_msg = '';

  /**
   * Main BWG Instance.
   *
   * Ensures only one instance is loaded or can be loaded.
   *
   * @static
   * @return BWG - Main instance.
   */
  public static function instance() {
    if ( is_null( self::$_instance ) ) {
      self::$_instance = new self();
    }
    return self::$_instance;
  }

  /**
   * BWG Constructor.
   */
  public function __construct() {
    $this->define_constants();
    require_once($this->plugin_dir . '/framework/WDWLibrary.php');
    $this->add_actions();
  }

  /**
   * Define Constants.
   */
  private function define_constants() {
    $this->plugin_dir = WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__));
    $this->plugin_url = plugins_url(plugin_basename(dirname(__FILE__)));
    $this->main_file = plugin_basename(__FILE__);
    $this->plugin_version = '1.5.10';
    $this->db_version = '1.5.10';
    $this->prefix = 'bwg';
    $this->nicename = __('Photo Gallery', $this->prefix);

    $this->use_home_url();

    require_once($this->plugin_dir . '/framework/BWGOptions.php');
    $this->options = new WD_BWG_Options();

    $this->upload_dir = $this->options->upload_dir;
    $this->upload_url = $this->options->upload_url;

    $this->free_msg = __('This option is disabled in free version.', $this->prefix);
    $this->is_demo = get_site_option('tenweb_admin_demo');
  }

  /**
   * Add actions.
   */
  private function add_actions() {
    add_action('init', array($this, 'init_free_users_lib'), 8);
    add_action('init', array($this, 'init'), 9);
    add_action('admin_menu', array( $this, 'admin_menu' ) );

    // Frontend AJAX actions.
    add_action('wp_ajax_bwg_frontend_data', array($this, 'frontend_data'));
    add_action('wp_ajax_nopriv_bwg_frontend_data', array($this, 'frontend_data'));
    add_action('wp_ajax_GalleryBox', array($this, 'frontend_ajax'));
    add_action('wp_ajax_nopriv_GalleryBox', array($this, 'frontend_ajax'));
    add_action('wp_ajax_bwg_captcha', array($this, 'bwg_captcha'));
    add_action('wp_ajax_nopriv_bwg_captcha', array($this, 'bwg_captcha'));
    if ( $this->is_pro ) {
      add_action('wp_ajax_Share', array( $this, 'frontend_ajax' ));
      add_action('wp_ajax_nopriv_Share', array( $this, 'frontend_ajax' ));
      add_action('wp_ajax_view_facebook_post', array($this, 'bwg_add_embed_ajax'));
      add_action('wp_ajax_nopriv_view_facebook_post', array($this, 'bwg_add_embed_ajax'));
      add_action('wp_ajax_nopriv_download_gallery', array($this, 'frontend_ajax'));
      add_action('wp_ajax_download_gallery', array($this, 'frontend_ajax'));
    }

    // Admin AJAX actions.
    add_action('wp_ajax_galleries_' . $this->prefix , array($this, 'admin_ajax'));
    add_action('wp_ajax_albumsgalleries_' . $this->prefix , array($this, 'admin_ajax'));
    add_action('wp_ajax_bwg_UploadHandler', array($this, 'bwg_UploadHandler'));
    add_action('wp_ajax_addImages', array($this, 'bwg_filemanager_ajax'));
    add_action('wp_ajax_addMusic', array($this, 'bwg_filemanager_ajax'));
    add_action('wp_ajax_addEmbed', array($this, 'bwg_add_embed_ajax'));
    add_action('wp_ajax_editimage_' . $this->prefix, array($this, 'admin_ajax'));
    add_action('wp_ajax_addTags_' . $this->prefix, array($this, 'admin_ajax'));
    add_action('wp_ajax_options_' . $this->prefix, array($this, 'admin_ajax'));
    if( $this->is_pro ) {
      add_action('wp_ajax_addInstagramGallery', array( $this, 'bwg_add_embed_ajax' ));
      add_action('wp_ajax_addFacebookGallery', array( $this, 'bwg_add_embed_ajax' ));
    }

    if ( !is_admin() ) {
      add_shortcode('Best_Wordpress_Gallery', array($this, 'shortcode'));
    }
    // Editor message dismiss.
    add_action('wp_ajax_bwg_editor_missing_dismissed', array($this, 'dismiss_notice'));
    add_action('wp_ajax_bwg_recreate_dismissed', array($this, 'dismiss_notice'));

    // Add media button to WP editor.
    add_action('wp_ajax_shortcode_' . $this->prefix, array($this, 'admin_ajax'));
    add_filter('media_buttons_context', array($this, 'media_button'));
    add_filter('mce_external_plugins', array($this, 'bwg_register'));
    add_filter('mce_buttons', array($this, 'media_internal_button'), 0);

    // Add script to header.
    add_action('admin_head', array($this, 'global_script'));

    // Photo Gallery Widget.
    if ( class_exists('WP_Widget') ) {
      add_action('widgets_init', array($this, 'register_widgets'));
    }

    // Plugin activation.
    register_activation_hook(__FILE__, array($this, 'global_activate'));
    add_action('wpmu_new_blog', array($this, 'new_blog_added'), 10, 6);

    // Plugin update.
    if ( !isset($_GET['action']) || $_GET['action'] != 'deactivate' ) {
      add_action('admin_init', array($this, 'global_update'));
    }

    // Plugin deactivate.
    register_deactivation_hook( __FILE__, array($this, 'global_deactivate'));

    // Register scripts/styles.
    add_action('wp_enqueue_scripts', array($this, 'register_frontend_scripts'));
    add_action('admin_enqueue_scripts', array($this, 'register_admin_scripts'));

    add_filter('set-screen-option', array($this, 'set_option_galleries'), 10, 3);
    add_filter('set-screen-option', array($this, 'set_option_albums'), 10, 3);
    add_filter('set-screen-option', array($this, 'set_option_themes'), 10, 3);
    add_filter('set-screen-option', array($this, 'set_option_comments'), 10, 3);
    add_filter('set-screen-option', array($this, 'set_option_rates'), 10, 3);

    if ( $this->is_pro ) {
      add_filter('single_template', array( $this, 'share_template' ));
    }

    add_filter('widget_tag_cloud_args', array($this, 'tag_cloud_widget_args'));

    if ( $this->is_pro ) {
      add_filter('cron_schedules', array( $this, 'autoupdate_interval' ));
      add_action('bwg_schedule_event_hook', array( $this, 'social_galleries' ));
    }

	  // Check add-ons versions.
    if ( $this->is_pro ) {
      add_action('admin_notices', array($this, 'check_addons_compatibility'));
    }
  	add_action('plugins_loaded', array($this, 'plugins_loaded'));
    // There is no instagram provider for https.
    wp_oembed_add_provider('#https://instagr(\.am|am\.com)/p/.*#i', 'https://api.instagram.com/oembed', TRUE);
    if ( !$this->is_pro ) {
      add_filter("plugin_row_meta", array($this, 'add_plugin_meta_links'), 10, 2);
    }

    // Enqueue block editor assets for Gutenberg.
    add_filter('tw_get_block_editor_assets', array($this, 'register_block_editor_assets'));
    add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));

    add_action('admin_notices', array($this, 'admin_notices'));

	  // Privacy policy.
    add_action( 'admin_init', array($this, 'add_privacy_policy_content') );
    // Prevent adding shortcode conflict with some builders.

	  $this->before_shortcode_add_builder_editor();

	  // Register widget for Elementor builder.
    add_action('elementor/widgets/widgets_registered', array($this, 'register_elementor_widgets'));
    // Register 10Web category for Elementor widget if 10Web builder doesn't installed.
    add_action('elementor/elements/categories_registered', array($this, 'register_widget_category'), 1, 1);
  }

  /**
   * Register widget for Elementor builder.
   */
  public function register_elementor_widgets() {
    if ( defined('ELEMENTOR_PATH') && class_exists('Elementor\Widget_Base') ) {
      require_once ($this->plugin_dir . '/admin/controllers/elementorWidget.php');
    }
  }

  /**
   * Register 10Web category for Elementor widget if 10Web builder doesn't installed.
   *
   * @param $elements_manager
   */
  public function register_widget_category( $elements_manager ) {
    $elements_manager->add_category('tenweb-widgets', array(
      'title' => __('10WEB', 'tenweb-builder'),
      'icon' => 'fa fa-plug',
    ));
  }

  public function register_block_editor_assets($assets) {
    $version = '2.0.0';
    $js_path = $this->plugin_url . '/js/tw-gb/block.js';
    $css_path = $this->plugin_url . '/css/tw-gb/block.css';
    if (!isset($assets['version']) || version_compare($assets['version'], $version) === -1) {
      $assets['version'] = $version;
      $assets['js_path'] = $js_path;
      $assets['css_path'] = $css_path;
    }
    return $assets;
  }

  function add_privacy_policy_content() {
    if ( !function_exists('wp_add_privacy_policy_content') ) {
      return;
    }
    $content = __('When you leave a comment on this site, we send your name, email
        address, IP address and comment text to example.com. Example.com does
        not retain your personal data.', BWG()->prefix);
    wp_add_privacy_policy_content(BWG()->nicename, wp_kses_post(wpautop($content, FALSE)));
  }

  public function enqueue_block_editor_assets() {
    $key = 'tw/' . $this->prefix;
    $plugin_name = $this->nicename;
    $icon_url = $this->plugin_url . '/images/tw-gb/photo-gallery.svg';
    $icon_svg = $this->plugin_url . '/images/tw-gb/icon.svg';
    $url = add_query_arg(array('action' => 'shortcode_bwg'), admin_url('admin-ajax.php'));
    ?>
    <script>
      if ( !window['tw_gb'] ) {
        window['tw_gb'] = {};
      }
      if ( !window['tw_gb']['<?php echo $key; ?>'] ) {
        window['tw_gb']['<?php echo $key; ?>'] = {
          title: '<?php echo $plugin_name; ?>',
          titleSelect: '<?php echo sprintf(__('Select %s', $this->prefix), $plugin_name); ?>',
          iconUrl: '<?php echo $icon_url; ?>',
          iconSvg: {
            width: '20',
            height: '20',
            src: '<?php echo $icon_svg; ?>'
          },
          isPopup: true,
          data: {
            shortcodeUrl: '<?php echo $url; ?>'
          }
        }
      }
    </script>
    <?php
    // Remove previously registered or enqueued versions
    $wp_scripts = wp_scripts();
    foreach ($wp_scripts->registered as $key => $value) {
      // Check for an older versions with prefix.
      if (strpos($key, 'tw-gb-block') > 0) {
        wp_deregister_script( $key );
        wp_deregister_style( $key );
      }
    }
    // Get the last version from all 10Web plugins.
    $assets = apply_filters('tw_get_block_editor_assets', array());
    // Not performing unregister or unenqueue as in old versions all are with prefixes.
    wp_enqueue_script('tw-gb-block', $assets['js_path'], array( 'wp-blocks', 'wp-element' ), $assets['version']);
    wp_localize_script('tw-gb-block', 'tw_obj', array(
      'nothing_selected' => __('Nothing selected.', $this->prefix),
      'empty_item' => __('- Select -', $this->prefix),
    ));
    wp_enqueue_style('tw-gb-block', $assets['css_path'], array( 'wp-edit-blocks' ), $assets['version']);
  }

  /**
   * Wordpress init actions.
   */
  public function init() {
    ob_start();
    $this->overview();
	  add_action('init', array($this, 'language_load'));
    add_action('init', array($this, 'create_post_types'));
  }

  /**
   * Wordpress admin notice actions.
   */
  public function admin_notices() {
    // Show this notice only on Photo Gallery pages.
    if ( isset( $_GET[ 'page' ] ) && strpos( esc_html( $_GET[ 'page' ] ), '_bwg' ) !== FALSE ) {
      /**
       * possible values are 'editor_missing', 'editor_missing_dismissed', 'recreate_dismissed', false
       */
      $wp_editor_state = get_option( 'bwg_wp_editor_state' );
      // Check if host is ready to edit images.
      $this->wp_editor_exists = wp_image_editor_supports( array( 'methods' => array( 'resize' ) ) );
      $wp_editor_message = false;
      $wp_editor_new_state = false;
      if ( !$this->wp_editor_exists ) {
        // Editor missing and error notification is not dismissed.
        if ( false === $wp_editor_state || 'editor_missing' === $wp_editor_state ) {
          $wp_editor_message_action = 'bwg_editor_missing_dismissed';
          $wp_editor_message = '<p>' . sprintf(__('Image edit functionality is not supported by your web host. We highly recommend you to contact your hosting provider and ask them to enable %s library.', $this->prefix), '<b>' . __("PHP GD", $this->prefix) . '</b>') . '</p>';
          $wp_editor_message .= '<p>' . sprintf(__('Without image editing functions, image thumbnails will not be created, thus causing load time issues on published galleries. Furthermore, some of Photo Gallery\'s features, e.g. %s, %s, and %s, will not be available.', $this->prefix), '<b>' . __("crop", $this->prefix) . '</b>', '<b>' . __("edit", $this->prefix) . '</b>', '<b>' . __("rotate", $this->prefix) . '</b>') . '</p>';
          $wp_editor_new_state = 'editor_missing';
        }
      }
      else {
        // Editor exists, some error state was detected before and recreate thumbnails message is not dismissed.
        if ( false !== $wp_editor_state && 'recreate_dismissed' != $wp_editor_state ) {
          $options_url = admin_url('admin.php?page=options_bwg');
          $wp_editor_message_action = 'bwg_recreate_dismissed';
          $wp_editor_message = '<p>' . sprintf(__('Image edit functionality was just activated on your web host. Please go to %s, navigate to %s tab and press %s button.', $this->prefix), '<b><a href="' . $options_url . '" title="' . __("Options", $this->prefix) . '">' .  __("Options page", $this->prefix) . '</a></b>', '<b>' . __("General", $this->prefix) . '</b>', '<b>' . __("Recreate", $this->prefix) . '</b>') . '</p>';
          $wp_editor_new_state = 'recreate';
        }
      }
      if ( $wp_editor_new_state ) {
        update_option( 'bwg_wp_editor_state', $wp_editor_new_state );
      }
      if ( $wp_editor_message ) {
        ?>
        <div id="bwg_image_editor_notice" class="notice notice-warning is-dismissible" data-action="<?php echo $wp_editor_message_action; ?>">
          <?php echo $wp_editor_message; ?>
        </div>
        <?php
      }
    }
  }

  /**
   * Dismiss Image editor messages.
   */
  public function dismiss_notice() {
    $action = WDWLibrary::get('action');
    $allowed_pages = array(
      'bwg_editor_missing_dismissed',
      'bwg_recreate_dismissed',
    );
    if ( !empty($action) && in_array($action, $allowed_pages) ) {
      $action = str_replace(BWG()->prefix . '_', '', $action);
      update_option( 'bwg_wp_editor_state', $action );
    }
    die();
  }

  private function use_home_url() {
    $home_url = str_replace(array("http://", "https://"), "", home_url());
    $pos = strpos($home_url, "/");
    if ( $pos ) {
      $home_url = substr($home_url, 0, $pos);
    }

    $site_url = str_replace("http://", "", $this->plugin_url);
    $site_url = str_replace("https://", "", $site_url);
    $pos = strpos($site_url, "/");
    if ( $pos ) {
      $site_url = substr($site_url, 0, $pos);
    }

    if ( $site_url != $home_url ) {
      $this->front_url = home_url("wp-content/plugins/" . plugin_basename(dirname(__FILE__)));
    }
    else {
      $this->front_url = $this->plugin_url;
    }
  }

  /**
   * Plugin menu.
   */
  public function admin_menu() {
    $permissions = $this->is_pro ? $this->options->permissions : 'manage_options';
    $parent_slug = $this->is_pro ? 'galleries_' . $this->prefix : null;
    if( $this->is_pro || get_option( "bwg_subscribe_done" ) == 1 ) {
      add_menu_page($this->nicename, $this->nicename, $permissions, 'galleries_' . $this->prefix, array($this , 'admin_pages'), $this->plugin_url . '/images/icons/icon.png');
      $parent_slug = 'galleries_' . $this->prefix;
    }

    $galleries_page = add_submenu_page($parent_slug, __('Add Galleries/Images', $this->prefix), __('Add Galleries/Images', $this->prefix), $permissions, 'galleries_' . $this->prefix, array($this , 'admin_pages'));
    add_action('load-' . $galleries_page, array($this, 'galleries_per_page_option'));

    $albums_page = add_submenu_page($parent_slug, __('Gallery Groups', $this->prefix), __('Gallery Groups', $this->prefix), $permissions, 'albums_' . $this->prefix, array($this , 'admin_pages'));
    add_action('load-' . $albums_page, array($this, 'albums_per_page_option'));

    add_submenu_page($parent_slug, __('Tags', $this->prefix), __('Tags', $this->prefix), $permissions, 'edit-tags.php?taxonomy=bwg_tag');

    add_submenu_page($parent_slug, __('Options', $this->prefix), __('Options', $this->prefix), 'manage_options', 'options_' . $this->prefix, array($this , 'admin_pages'));

    $themes_page = add_submenu_page($parent_slug, __('Themes', $this->prefix), __('Themes', $this->prefix), 'manage_options', 'themes_' . $this->prefix, array($this , 'admin_pages'));
    add_action('load-' . $themes_page, array($this, 'themes_per_page_option'));

    if( $this->is_pro ) {
      $comments_page = add_submenu_page($parent_slug, __('Comments', $this->prefix), __('Comments', $this->prefix), 'manage_options', 'comments_' . $this->prefix, array($this , 'admin_pages'));
      add_action('load-' . $comments_page, array($this, 'comments_per_page_option'));

      $rates_page = add_submenu_page($parent_slug, __('Ratings', $this->prefix), __('Ratings', $this->prefix), 'manage_options', 'ratings_' . $this->prefix, array($this , 'admin_pages'));
      add_action('load-' . $rates_page, array($this, 'rates_per_page_option'));
    }
    else {
      // Temporary deactivated.
      // add_submenu_page($parent_slug, __('Premium Version', $this->prefix), __('Premium Version', $this->prefix), 'manage_options', 'licensing_' . $this->prefix, array($this , 'admin_pages'));
    }

    do_action('bwg_add_submenu_item', $parent_slug);
    
    add_submenu_page($parent_slug, __('Add-ons',$this->prefix), __('Add-ons', $this->prefix), 'manage_options', 'addons_' . $this->prefix, array($this , 'addons'));

    add_submenu_page(NULL, __('Uninstall', $this->prefix), __('Uninstall', $this->prefix), 'manage_options', 'uninstall_' . $this->prefix, array($this , 'admin_pages'));
    add_submenu_page(NULL, __('Generate Shortcode', $this->prefix), __('Generate Shortcode', $this->prefix), $permissions, 'shortcode_' . $this->prefix, array($this , 'admin_pages'));
  }

  /**
   * Admin pages.
   */
  public function admin_pages() {
    $allowed_pages = array(
      'galleries_' . $this->prefix,
      'albums_' . $this->prefix,
      'options_' . $this->prefix,
      'themes_' . $this->prefix,
      'comments_' . $this->prefix,
      'ratings_' . $this->prefix,
      'uninstall_' . $this->prefix,
      'shortcode_' . $this->prefix,
      'licensing_' . $this->prefix,
    );
    $page = WDWLibrary::get('page');
    if ( !empty($page) && in_array($page, $allowed_pages) ) {
      $page = WDWLibrary::clean_page_prefix($page);
      $controller_page = $this->plugin_dir . '/admin/controllers/' . $page . '.php';
      $model_page = $this->plugin_dir . '/admin/models/' . $page . '.php';
      $view_page = $this->plugin_dir . '/admin/views/' . $page . '.php';
      if ( !is_file($controller_page) ) {
        echo wp_sprintf(__('The controller %s file not exist.', $this->prefix), '"<b>' . $page . '</b>"');

        return FALSE;
      }
      if ( !is_file($view_page) ) {
        echo wp_sprintf(__('The view %s file not exist.', $this->prefix), '"<b>' . $page . '</b>"');

        return FALSE;
      }
      // Load page file.
      require_once($this->plugin_dir . '/admin/views/AdminView.php');
      require_once($controller_page);
      if ( is_file($model_page) ) {
        require_once($model_page);
      }
      require_once($view_page);
      $controller_class = $page . 'Controller_' . $this->prefix;
      $model_class = $page . 'Model_' . $this->prefix;
      $view_class = $page . 'View_' . $this->prefix;
      // Checking page class.
      if ( !class_exists($controller_class) ) {
        echo wp_sprintf(__('The %s class not exist.', $this->prefix), '"<b>' . $controller_class . '</b>"');

        return FALSE;
      }
      if ( !class_exists($view_class) ) {
        echo wp_sprintf(__('The %s class not exist.', $this->prefix), '"<b>' . $view_class . '</b>"');

        return FALSE;
      }
      $controller = new $controller_class();
      $controller->execute();
    }
  }

  /**
   * Add-ons page.
   */
  public function addons() {
    if (function_exists('current_user_can')) {
      if (!current_user_can('manage_options')) {
        die('Access Denied');
      }
    }
    else {
      die('Access Denied');
    }
    require_once($this->plugin_dir . '/addons/addons.php');
    bwg_addons_display();
  }

  /**
   * Register admin pages scripts/styles.
   */
  public function register_admin_scripts() {
    $required_scripts = array( 'jquery' );
    $required_styles = array(
      // 'admin-bar',
      // 'dashicons',
      'wp-admin', // admin styles
      'buttons', // buttons styles
      'media-views', // media uploader styles
      'wp-auth-check', // check all
    );

    wp_register_style($this->prefix . '_tables', $this->plugin_url . '/css/bwg_tables.css', $required_styles, $this->plugin_version);

    wp_register_script($this->prefix . '_admin', $this->plugin_url . '/js/bwg.js', $required_scripts, $this->plugin_version);
    wp_localize_script($this->prefix . '_admin', 'bwg', array(
      'delete_confirmation' => __('Do you want to delete selected items?', $this->prefix),
      'select_at_least_one_item' => __('You must select at least one item.', $this->prefix),
      'remove_pricelist_confirmation' => __('Do you want to remove pricelist from selected items?', $this->prefix),
      'google_fonts' => WDWLibrary::get_google_fonts(),
    ));

    wp_register_script($this->prefix . '_embed', $this->plugin_url . '/js/bwg_embed.js', array('jquery'), $this->plugin_version);

    wp_localize_script($this->prefix . '_admin', 'bwg_objectL10B', array(
      'bwg_field_required'  => __('field is required.', $this->prefix),
      'bwg_select_image'  => __('You must select an image file.', $this->prefix),
      'bwg_select_audio'  => __('You must select an audio file.', $this->prefix),
      'bwg_access_token'  => __('You do not have Instagram access token. Sign in with Instagram in Options -> Advanced tab -> Social. ', $this->prefix),
      'bwg_client_id' => __('You do not have Instagram CLIENT_ID. Input its value in Options->Embed options.', $this->prefix),
      'bwg_post_number'  => __('Instagram recent post number must be between 1 and 33.', $this->prefix),
      'bwg_not_empty'  => __('Gallery type cannot be changed, since it is not empty. In case you would like to have Instagram or Facebook gallery, please create a new one.', $this->prefix),
      'bwg_enter_url'  => __('Please enter url to embed.', $this->prefix),
      'bwg_cannot_response'  => __('Error: cannot get response from the server.', $this->prefix),
      'bwg_something_wrong'  => __('Error: something wrong happened at the server.', $this->prefix),
      'bwg_error'  => __('Error', $this->prefix),
      'bwg_show_order'  => __('Show order column', $this->prefix),
      'bwg_hide_order'  => __('Hide order column', $this->prefix),
      'selected_item'  =>  __('Selected %d item.', $this->prefix),
      'selected_items'  =>  __('Selected %d items.', $this->prefix),
      'saved'  => __('Items Succesfully Saved.', $this->prefix),
      'recovered'  => __('Item Succesfully Recovered.', $this->prefix),
      'published'  => __('Item Succesfully Published.', $this->prefix),
      'unpublished'  => __('Item Succesfully Unpublished.', $this->prefix),
      'deleted'  => __('Item Succesfully Deleted.', $this->prefix),
      'one_item'  => __('You must select at least one item.', $this->prefix),
      'resized'  => __('Items Succesfully resized.', $this->prefix),
      'watermark_set'  => __('Watermarks Succesfully Set.', $this->prefix),
      'reset'  => __('Items Succesfully Reset.', $this->prefix),
      'save_tag' => __('Save Tag', $this->prefix),
      'delete_alert' => __('Do you want to delete selected items?', $this->prefix),
      'default_warning' => __('This action will reset gallery type to mixed and will save that choice. You cannot undo it.', $this->prefix),
      'change_warning' => __('After pressing save/apply buttons, you cannot change gallery type back to Instagram!', $this->prefix),
      'other_warning' => __('This action will reset gallery type to mixed and will save that choice. You cannot undo it.', $this->prefix),
      'insert' => __('Insert', $this->prefix),
      'import_failed' => __('Failed to import images from media library', $this->prefix),
      'only_the_following_types_are_allowed' => __('Sorry, only jpg, jpeg, gif, png types are allowed.', $this->prefix),
      'wp_upload_dir' => wp_upload_dir(),
      'ajax_url' => wp_nonce_url( admin_url('admin-ajax.php'), 'bwg_UploadHandler', 'bwg_nonce' ),
      'uploads_url' => BWG()->options->upload_url,
      'recreate_success' => __('Thumbnails successfully recreated.', $this->prefix),
      'watermark_option_reset' => __('All images are successfully reset.', $this->prefix),
    ));
    wp_localize_script($this->prefix . '_admin', 'bwg_objectGGF', WDWLibrary::get_google_fonts());
    wp_enqueue_script('jquery-ui-sortable');
    wp_register_script($this->prefix . '_jscolor', $this->plugin_url . '/js/jscolor/jscolor.js', array('jquery'), '1.3.9');

    wp_register_style($this->prefix . '_addons', $this->plugin_url . '/addons/style.css');

    wp_register_style($this->prefix . '_shortcode', $this->plugin_url . '/css/bwg_shortcode.css', $required_styles, $this->plugin_version);
    wp_register_script($this->prefix . '_shortcode', $this->plugin_url . '/js/bwg_shortcode.js', $required_scripts, $this->plugin_version);
    wp_localize_script($this->prefix . '_shortcode', 'bwg_objectGGF', WDWLibrary::get_google_fonts());

    if ( !$this->is_pro ) {
      wp_register_style($this->prefix . '_licensing', $this->plugin_url . '/css/bwg_licensing.css', $required_styles, $this->plugin_version);
    }

    // Roboto font for top bar.
    wp_register_style($this->prefix . '-roboto', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700');
    wp_register_style($this->prefix . '-pricing', $this->plugin_url . '/css/pricing.css', array(), $this->plugin_version);

    // For drag and drop on mobiles.
    wp_register_script($this->prefix . '_jquery.ui.touch-punch.min', $this->plugin_url . '/js/jquery.ui.touch-punch.min.js', array(), '0.2.3');
  }

  /**
   * Frontend AJAX actions.
   */
  public function frontend_data() {
    $params = array();
    $params['id'] = WDWLibrary::get('shortcode_id', 0);

    // Get values for elementor widget.
    $params['gallery_type'] = WDWLibrary::get('gallery_type', 'thumbnails');
    $params['gallery_id'] = WDWLibrary::get('gallery_id', 0);
    $params['tag'] = WDWLibrary::get('tag', 0);
    $params['album_id'] = WDWLibrary::get('album_id', 0);
    $params['theme_id'] = WDWLibrary::get('theme_id', 0);
    $params['ajax'] = TRUE;

    echo $this->shortcode($params);

    die();
  }

  /**
   * Frontend AJAX actions.
   */
  public function frontend_ajax() {
    if (function_exists('switch_to_locale') && function_exists('get_locale')) {
      switch_to_locale(get_locale());
    }

    $allowed_pages = array(
      'GalleryBox',
      'Share',
      'download_gallery',
    );
    $page = WDWLibrary::get('action');
    if ( !empty($page) && in_array($page, $allowed_pages) ) {
      require_once($this->plugin_dir . '/frontend/controllers/BWGController' . ucfirst($page) . '.php');
      $controller_class = 'BWGController' . ucfirst($page);
      $controller = new $controller_class();
      $controller->execute();
    }
  }

  public function shortcode( $params = array() ) {
    if ( is_admin() && defined('DOING_AJAX') && !DOING_AJAX) {
      return;
    }
    if ( isset($params['id']) && $params['id'] ) {
      global $wpdb;
      $shortcode = $wpdb->get_var($wpdb->prepare("SELECT tagtext FROM " . $wpdb->prefix . "bwg_shortcode WHERE id='%d'", $params['id']));
      if ($shortcode) {
        $shortcode_params = explode('" ', $shortcode);
        foreach ($shortcode_params as $shortcode_param) {
          $shortcode_param = str_replace('"', '', $shortcode_param);
          $shortcode_elem = explode('=', $shortcode_param);
          $params[str_replace(' ', '', $shortcode_elem[0])] = $shortcode_elem[1];
        }
      }
      else {
        return;
      }
    }

    // 'gallery_type' is the only parameter not being checked.
    // Checking for incomplete shortcodes.
    if ( isset($params['gallery_type']) ) {
      $pairs = WDWLibrary::get_shortcode_option_params( $params );
      if ( isset($params['ajax']) ) {
        $pairs['ajax'] = $params['ajax'];
      }
      ob_start();
      $this->front_end( $pairs );
      return str_replace( array( "\r\n", "\n", "\r" ), '', ob_get_clean() );
//      return ob_get_clean();
    }
  }

  /**
   * Frontend.
   *
   * @param $params
   */
  public function front_end($params) {
    require_once(BWG()->plugin_dir . '/framework/WDWLibraryEmbed.php');
    require_once(BWG()->plugin_dir . '/frontend/controllers/controller.php');
    $controller = new BWGControllerSite( ucfirst( $params[ 'gallery_type' ] ) );
    if ( WDWLibrary::get('shortcode_id', 0) || isset($params['ajax']) ) {
      $controller->execute($params, 1, WDWLibrary::get('bwg', 0));
    }
    else {
      $bwg = WDWLibrary::unique_number();
      $controller->execute($params, 1, $bwg);
    }

    return;
  }

  // TODO: move
  public function bwg_captcha() {
    if (isset($_GET['action']) && esc_html($_GET['action']) == 'bwg_captcha') {
      $i = (isset($_GET["i"]) ? esc_html($_GET["i"]) : '');
      $r2 = (isset($_GET["r2"]) ? (int) $_GET["r2"] : 0);
      $rrr = (isset($_GET["rrr"]) ? (int) $_GET["rrr"] : 0);
      $randNum = 0 + $r2 + $rrr;
      $digit = (isset($_GET["digit"]) ? (int) $_GET["digit"] : 0);
      $cap_width = $digit * 10 + 15;
      $cap_height = 26;
      $cap_length_min = $digit;
      $cap_length_max = $digit;
      $cap_digital = 1;
      $cap_latin_char = 1;
      function code_generic($_length, $_digital = 1, $_latin_char = 1) {
        $dig = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
        $lat = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
        $main = array();
        if ($_digital) {
          $main = array_merge($main, $dig);
        }
        if ($_latin_char) {
          $main = array_merge($main, $lat);
        }
        shuffle($main);
        $pass = substr(implode('', $main), 0, $_length);
        return $pass;
      }
      $l = rand($cap_length_min, $cap_length_max);
      $code = code_generic($l, $cap_digital, $cap_latin_char);
      WDWLibrary::bwg_session_start();
      $_SESSION['bwg_captcha_code'] = $code;
      if (function_exists('imagecreatetruecolor')) {
        $canvas = imagecreatetruecolor( $cap_width, $cap_height );
        $c = imagecolorallocate( $canvas, rand( 150, 255 ), rand( 150, 255 ), rand( 150, 255 ) );
        imagefilledrectangle( $canvas, 0, 0, $cap_width, $cap_height, $c );
        $count = strlen( $code );
        $color_text = imagecolorallocate( $canvas, 0, 0, 0 );
        for ( $it = 0; $it < $count; $it++ ) {
          $letter = $code[ $it ];
          imagestring( $canvas, 6, (10 * $it + 10), $cap_height / 4, $letter, $color_text );
        }
        for ( $c = 0; $c < 150; $c++ ) {
          $x = rand( 0, $cap_width - 1 );
          $y = rand( 0, 29 );
          $col = '0x' . rand( 0, 9 ) . '0' . rand( 0, 9 ) . '0' . rand( 0, 9 ) . '0';
          imagesetpixel( $canvas, $x, $y, $col );
        }
        header( 'Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
        header( 'Cache-Control: no-store, no-cache, must-revalidate' );
        header( 'Cache-Control: post-check=0, pre-check=0', FALSE );
        header( 'Pragma: no-cache' );
        header( 'Content-Type: image/jpeg' );
        imagejpeg( $canvas, NULL, BWG()->options->jpeg_quality );
      }
      die('');
    }
  }
  // TODO: move
  public function bwg_add_embed_ajax() {
    $permissions = $this->is_pro ? BWG()->options->permissions : 'manage_options';
    if (function_exists('current_user_can')) {
      if (!current_user_can($permissions)) {
        die('Access Denied');
      }
    }
    else {
      die('Access Denied');
    }
    require_once(BWG()->plugin_dir . '/framework/WDWLibrary.php');
    if (!WDWLibrary::verify_nonce('')) {
      die(WDWLibrary::delimit_wd_output(json_encode(array("error", "Sorry, your nonce did not verify."))));
    }

    require_once(BWG()->plugin_dir . '/framework/WDWLibraryEmbed.php');
    $embed_action = WDWLibrary::get('action');
    switch ( $embed_action ) {
      case 'addEmbed':
        $url_to_embed = WDWLibrary::get('URL_to_embed');
        $data = WDWLibraryEmbed::add_embed($url_to_embed);
        echo WDWLibrary::delimit_wd_output($data);
        wp_die();
        break;
      case 'addInstagramGallery':
        $instagram_access_token = WDWLibrary::get('instagram_access_token');
        $autogallery_image_number = WDWLibrary::get('autogallery_image_number');
        $whole_post = WDWLibrary::get('whole_post');
        $data = WDWLibraryEmbed::add_instagram_gallery($instagram_access_token, $whole_post, $autogallery_image_number);
        if ( !$data ) {
          echo WDWLibrary::delimit_wd_output(json_encode(array( "error", "Cannot get instagram data" )));
        }
        if ( $data ) {
          $images_new = json_decode($data, TRUE);
          if ( empty($images_new) ) {
            echo WDWLibrary::delimit_wd_output(json_encode(array( "error", "Cannot get instagram data" )));
          }
          else {
            echo WDWLibrary::delimit_wd_output($data);
          }
        }
        wp_die();
        break;
      case 'addFacebookGallery':
        $arg = array(
          'app_id' => WDWLibrary::get('app_id'),
          'app_secret' => WDWLibrary::get('app_secret'),
          'album_url' => WDWLibrary::get('album_url'),
          'album_limit' => WDWLibrary::get('album_limit'),
          'update_flag' => WDWLibrary::get('update_flag'),
          'content_type' => WDWLibrary::get('content_type'),
        );
        if ( has_filter('init_facebook_album_data_bwg') ) {
          $data = apply_filters('init_facebook_album_data_bwg', array(), $arg);
          echo json_encode($data);
        }
        wp_die();
        break;
      default:
        die('Nothing to add');
        break;
    }
  }

  public function admin_ajax() {
    $page = WDWLibrary::get('action');
    $allowed_pages = array(
      'galleries_' . $this->prefix,
      'albumsgalleries_' . $this->prefix,
      'addTags_' . $this->prefix,
      'shortcode_' . $this->prefix,
      'editimage_' . $this->prefix,
      'options_' . $this->prefix,
    );
    if ( !empty($page) && in_array($page, $allowed_pages) ) {
      $page = WDWLibrary::clean_page_prefix($page);
      $controller_page = $this->plugin_dir . '/admin/controllers/' . $page . '.php';
      $model_page = $this->plugin_dir . '/admin/models/' . $page . '.php';
      $view_page = $this->plugin_dir . '/admin/views/' . $page . '.php';
      // Load page file.
      require_once($this->plugin_dir . '/admin/views/AdminView.php');
      require_once($controller_page);
      require_once($model_page);
      require_once($view_page);
      $controller_class = $page . 'Controller_' . $this->prefix;
      $controller = new $controller_class();
      $controller->execute();
    }
  }

  // TODO:
  public function bwg_UploadHandler() {
    require_once(BWG()->plugin_dir . '/framework/WDWLibrary.php');
    if(!WDWLibrary::verify_nonce('bwg_UploadHandler')){
      die('Sorry, your nonce did not verify.');
    }
    require_once(BWG()->plugin_dir . '/filemanager/UploadHandler.php');
  }
  // TODO:
  public function bwg_filemanager_ajax() {
    $permissions = $this->is_pro ? BWG()->options->permissions : 'manage_options';
    if (function_exists('current_user_can')) {
      if (!current_user_can($permissions)) {
        die('Access Denied');
      }
    }
    else {
      die('Access Denied');
    }
    require_once(BWG()->plugin_dir . '/framework/WDWLibrary.php');
    $page = WDWLibrary::get('action');
    if (($page != '') && (($page == 'addImages') || ($page == 'addMusic'))) {
      if (!WDWLibrary::verify_nonce($page)) {
        die('Sorry, your nonce did not verify.');
      }
      require_once(BWG()->plugin_dir . '/filemanager/controller.php');
      $controller_class = 'FilemanagerController';
      $controller = new $controller_class();
      $controller->execute();
    }
  }

  /**
    * Register Photo Gallery button.
    *
    * @param $plugin_array
    *
    * @return mixed
    */
  public function bwg_register($plugin_array) {
    $url = BWG()->plugin_url . '/js/bwg_editor_button.js';
    $plugin_array["bwg_mce"] = $url;

    return $plugin_array;
  }

  /**
   * Add media button to Wp editor.
   *
   * @param $context
   *
   * @return string
   */
  function media_button($context) {
    ob_start();
    $url = add_query_arg(array('action' => 'shortcode_bwg', 'TB_iframe' => '1'), admin_url('admin-ajax.php'));
	  ?>
    <a onclick="if ( typeof tb_click == 'function' && (jQuery(this).parent().attr('id').indexOf('elementor') !== -1 || typeof bwg_check_ready == 'function') ) {
            tb_click.call(this);
            bwg_create_loading_block();
            bwg_set_shortcode_popup_dimensions(); } return false;" href="<?php echo $url; ?>" class="bwg-shortcode-btn button" title="<?php _e('Insert Photo Gallery', $this->prefix); ?>">
      <span class="wp-media-buttons-icon" style="background: url(<?php echo $this->plugin_url; ?>/images/icons/bwg_edit_but.png) no-repeat scroll left top rgba(0, 0, 0, 0);"></span>
      <?php _e('Add Photo Gallery', $this->prefix); ?>
    </a>
    <?php
    $context .= ob_get_clean();

    return $context;
  }

  /**
   * Add media button to visual editor.
   *
   * @param $buttons
   *
   * @return mixed
   */
  function media_internal_button($buttons) {
    array_push($buttons, "bwg_mce");

    return $buttons;
  }

  /**
   * Add script to header.
   */
  public function global_script() {
	  ?>
    <script>
      var bwg_admin_ajax = '<?php echo add_query_arg(array('action' => 'shortcode_' . $this->prefix), admin_url('admin-ajax.php')); ?>';
      var bwg_ajax_url = '<?php echo add_query_arg(array('action' => ''), admin_url('admin-ajax.php')); ?>';
      var bwg_plugin_url = '<?php echo BWG()->plugin_url; ?>';
      jQuery(document).ready(function () {
        bwg_check_ready = function () {}
        jQuery(document).keyup(function(e) {
          if ( e.keyCode == 27 ) {
            bwg_remove_loading_block();
          }
        });
      });
      // Set shortcode popup dimensions.
      function bwg_set_shortcode_popup_dimensions() {
        var H = jQuery(window).height(), W = jQuery(window).width();
        jQuery("#TB_title").hide().first().show();
        // New
        var tbWindow = jQuery('#TB_window');
        if (tbWindow.size()) {
          tbWindow.width(W).height(H);
          jQuery('#TB_iframeContent').width(W).height(H);
          tbWindow.attr('style',
            'top:'+ '0px !important;' +
            'left:' + '0px !important;' +
            'margin-left:' + '0;' +
            'z-index:' + '1000500;' +
            'max-width:' + 'none;' +
            'max-height:' + 'none;' +
            '-moz-transform:' + 'none;' +
            '-webkit-transform:' + 'none'
          );
        }
        // Edit
        var tbWindow = jQuery('.mce-window[aria-label="Photo Gallery"]');
        if (tbWindow.length) {
          // To prevent wp centering window with old sizes.
          setTimeout(function() {
            tbWindow.width(W).height(H);
            tbWindow.css({'top': 0, 'left': 0, 'margin-left': '0', 'z-index': '1000500'});
            tbWindow.find('.mce-window-body').width(W).height(H);
          }, 10);
        }
      }
      // Create loading block.
      function bwg_create_loading_block() {
        jQuery('body').append('<div class="loading_div" style="display:block; width: 100%; height: 100%; opacity: 0.6; position: fixed; background-color: #000000; background-image: url('+ bwg_plugin_url +'/images/spinner.gif); background-position: center; background-repeat: no-repeat; background-size: 50px; z-index: 1001000; top: 0; left: 0;"></div>');
      }
      // Remove loading block.
      function bwg_remove_loading_block() {
        jQuery(".loading_div", window.parent.document).remove();
        jQuery('.loading_div').remove();
      }
	  </script>
    <?php
  }

  /**
   * Register widget.
   */
  public function register_widgets() {
    require_once(BWG()->plugin_dir . '/framework/WDWLibraryEmbed.php');
    require_once(BWG()->plugin_dir . '/admin/controllers/Widget.php');
    register_widget("WidgetController_bwg");
    require_once(BWG()->plugin_dir . '/admin/controllers/WidgetSlideshow.php');
    register_widget("WidgetSlideshowController_bwg");
    if ( $this->is_pro ) {
      require_once(BWG()->plugin_dir . '/admin/controllers/WidgetTags.php');
      register_widget("WidgetTagsController_bwg");
    }
    // Allow to work old widgets registered with this name of class added with SiteOrigin builder.
    register_widget("BWGControllerWidget");
    register_widget("BWGControllerWidgetSlideshow");
    if ( $this->is_pro ) {
      register_widget("BWGControllerWidgetTags");
    }
  }

  /**
   * Global activate.
   *
   * @param $networkwide
   */
  public function global_activate($networkwide) {
    if ( function_exists('is_multisite') && is_multisite() ) {
      // Check if it is a network activation - if so, run the activation function for each blog id.
      if ( $networkwide ) {
        global $wpdb;
        // Get all blog ids.
        $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
        foreach ( $blogids as $blog_id ) {
          switch_to_blog($blog_id);
          $this->activate();
          restore_current_blog();
        }

        return;
      }
    }
    $this->activate();
  }

  /**
   * Activate.
   */
  public function activate() {
    if ( $this->is_pro ) {
      delete_transient('bwg_update_check');
      wp_schedule_event(time(), 'bwg_autoupdate_interval', 'bwg_schedule_event_hook');
    }
    $version = get_option('wd_bwg_version');
    $new_version = $this->db_version;
    if ($version && version_compare($version, $new_version, '<')) {
      require_once BWG()->plugin_dir . "/update.php";
      BWGUpdate::tables($version);
      update_option("wd_bwg_version", $new_version);
      delete_user_meta(get_current_user_id(), 'bwg_photo_gallery');
    }
    elseif (!$version) {
      require_once $this->plugin_dir . "/insert.php";
      BWGInsert::tables();
      update_user_meta(get_current_user_id(),'bwg_photo_gallery', '1');
      add_option("wd_bwg_version", $new_version, '', 'no');
      if ( !$this->is_pro ) {
        add_option("wd_bwg_theme_version", '1.0.0', '', 'no');
      }
    }
    else {
      require_once $this->plugin_dir . "/insert.php";
      BWGInsert::tables();
      add_option("wd_bwg_version", $new_version, '', 'no');
      if ( !$this->is_pro ) {
        add_option("wd_bwg_theme_version", '1.0.0', '', 'no');
      }
    }
    $this->create_post_types();
    flush_rewrite_rules();
  }

  /**
   * Global deactivate.
   *
   * @param $networkwide
   */
  public function global_deactivate($networkwide) {
    if ( function_exists('is_multisite') && is_multisite() ) {
      if ( $networkwide ) {
        global $wpdb;
        // Check if it is a network activation - if so, run the activation function for each blog id.
        // Get all blog ids.
        $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
        foreach ( $blogids as $blog_id ) {
          switch_to_blog($blog_id);
          $this->deactivate();
          restore_current_blog();
        }

        return;
      }
    }
    $this->deactivate();
  }

  /**
   * Deactivate.
   */
  public function deactivate() {
    wp_clear_scheduled_hook( 'bwg_schedule_event_hook' );
    flush_rewrite_rules();
  }

  public function new_blog_added( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
    if ( is_plugin_active_for_network('photo-gallery/photo-gallery.php') ) {
      switch_to_blog($blog_id);
      $this->activate();
      restore_current_blog();
    }
  }

  /**
   * Global update.
   */
  public function global_update() {
    if (function_exists('is_multisite') && is_multisite()) {
      global $wpdb;
      // Get all blog ids.
      $blogids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
      foreach ($blogids as $blog_id) {
        switch_to_blog($blog_id);
        $this->update_hook();
        restore_current_blog();
      }
      return;
    }
    $this->update_hook();
  }

  /**
   * Update.
   */
  public function update_hook() {
    $version = get_option('wd_bwg_version');
    $new_version = $this->db_version;
    if ( $version && version_compare($version, $new_version, '<') ) {
      require_once BWG()->plugin_dir . "/update.php";
      BWGUpdate::tables($version);
      update_option("wd_bwg_version", $new_version);
    }
  }

  /**
   * Add pagination to gallery admin pages.
   */
  public function galleries_per_page_option() {
    $option = 'per_page';
    $args_galleries = array(
      'default' => 20,
      'option' => 'bwg_galleries_per_page',
    );
    add_screen_option($option, $args_galleries);
  }
  public function albums_per_page_option() {
    $option = 'per_page';
    $args_albums = array(
      'default' => 20,
      'option' => 'bwg_albums_per_page'
    );
    add_screen_option( $option, $args_albums );
  }
  public function themes_per_page_option() {
    $option = 'per_page';
    $args_themes = array(
      'default' => 20,
      'option' => 'bwg_themes_per_page',
    );
    add_screen_option($option, $args_themes);
  }
  public function comments_per_page_option() {
    $option = 'per_page';
    $args_comments = array(
      'default' => 20,
      'option' => 'bwg_comments_per_page',
    );
    add_screen_option($option, $args_comments);
  }
  public function rates_per_page_option() {
    $option = 'per_page';
    $args_rates = array(
      'default' => 20,
      'option' => 'bwg_rates_per_page',
    );
    add_screen_option($option, $args_rates);
  }
  public function set_option_galleries( $status, $option, $value ) {
    if ( 'bwg_galleries_per_page' == $option ) {
      return $value;
    }

    return $status;
  }
  public function set_option_albums( $status, $option, $value ) {
    if ( 'bwg_albums_per_page' == $option ) {
      return $value;
    }

    return $status;
  }
  public function set_option_themes( $status, $option, $value ) {
    if ( 'bwg_themes_per_page' == $option ) {
      return $value;
    }

    return $status;
  }
  public function set_option_comments( $status, $option, $value ) {
    if ( 'bwg_comments_per_page' == $option ) {
      return $value;
    }

    return $status;
  }
  public function set_option_rates( $status, $option, $value ) {
    if ( 'bwg_rates_per_page' == $option ) {
      return $value;
    }

    return $status;
  }

  /**
   * Register frontend scripts and styles.
   */
  public function register_frontend_scripts() {
    $version = BWG()->plugin_version;
    $required_styles = array(
      $this->prefix . '_sumoselect',
      $this->prefix . '_font-awesome',
      $this->prefix . '_mCustomScrollbar',
    );
	  $required_scripts = array(
      'jquery',
    );
	  // Google fonts.
    if (BWG()->options->enable_google_fonts) {
      require_once(BWG()->plugin_dir . '/framework/WDWLibrary.php');
      $google_fonts_link = WDWLibrary::get_all_used_google_fonts();
      if (!empty($google_fonts_link)) {
        wp_register_style($this->prefix . '_googlefonts', $google_fonts_link, null, null);
        array_push($required_styles, $this->prefix . '_googlefonts');
      }
    }

    wp_register_script($this->prefix . '_sumoselect', BWG()->front_url . '/js/jquery.sumoselect.min.js', $required_scripts, '3.0.3', true);
    wp_register_style($this->prefix . '_sumoselect', BWG()->front_url . '/css/sumoselect.min.css', array(), '3.0.3');

    // Styles/Scripts for popup.
    wp_register_style($this->prefix . '_font-awesome', BWG()->front_url . '/css/font-awesome/font-awesome.min.css', array(), '4.6.3');
    wp_register_script($this->prefix . '_jquery_mobile', BWG()->front_url . '/js/jquery.mobile.min.js', $required_scripts, $version, true);
    wp_register_script($this->prefix . '_mCustomScrollbar', BWG()->front_url . '/js/jquery.mCustomScrollbar.concat.min.js', $required_scripts, $version, true);
    wp_register_style($this->prefix . '_mCustomScrollbar', BWG()->front_url . '/css/jquery.mCustomScrollbar.min.css', array(), $version);

    wp_register_script($this->prefix . '_jquery-fullscreen', BWG()->front_url . '/js/jquery.fullscreen-0.4.1.min.js', $required_scripts, '0.4.1', true);
    wp_register_script($this->prefix . '_gallery_box', BWG()->front_url . '/js/bwg_gallery_box.js', $required_scripts, $version, true);
    wp_register_script($this->prefix . '_embed', BWG()->front_url . '/js/bwg_embed.js', $required_scripts, $version, true);
    array_push($required_scripts,
        $this->prefix . '_sumoselect',
        $this->prefix . '_jquery_mobile',
        $this->prefix . '_mCustomScrollbar',
        $this->prefix . '_jquery-fullscreen',
        $this->prefix . '_gallery_box',
        $this->prefix . '_embed'
		);

	  if ( $this->is_pro ) {
      wp_register_script($this->prefix . '_raty', BWG()->front_url . '/js/jquery.raty.min.js', $required_scripts, '2.5.2', true);
      wp_register_script($this->prefix . '_featureCarousel', BWG()->plugin_url . '/js/jquery.featureCarousel.min.js', $required_scripts, $version, true);
      // 3D Tag Cloud.
      wp_register_script($this->prefix . '_3DEngine', BWG()->front_url . '/js/3DEngine/3DEngine.min.js', $required_scripts, '1.0.0', true);
	  
	    array_push($required_scripts,
        $this->prefix . '_raty',
        $this->prefix . '_featureCarousel',
        $this->prefix . '_3DEngine');
    }

    wp_register_style($this->prefix . '_frontend', BWG()->front_url . '/css/bwg_frontend.css', $required_styles, $version);
    wp_register_script($this->prefix . '_frontend', BWG()->front_url . '/js/bwg_frontend.js', $required_scripts, $version, true);
	
    if ( !BWG()->options->use_inline_stiles_and_scripts || WDWLibrary::elementor_is_active() ) {
      wp_enqueue_style($this->prefix . '_frontend');
      wp_enqueue_script($this->prefix . '_frontend');
    }

    wp_localize_script($this->prefix . '_gallery_box', 'bwg_objectL10n', array(
      'bwg_field_required'  => __('field is required.', $this->prefix),
      'bwg_mail_validation' => __('This is not a valid email address.', $this->prefix),
      'bwg_search_result' => __('There are no images matching your search.', $this->prefix),
      'is_pro' => $this->is_pro,
    ));

    wp_localize_script($this->prefix . '_frontend', 'bwg_objectsL10n', array(
      'bwg_select_tag'  => __('Select Tag', $this->prefix),
      'bwg_order_by'  => __('Order By', $this->prefix),
      'bwg_search' => __('Search', $this->prefix),
      'bwg_show_ecommerce' =>  __('Show Ecommerce', $this->prefix),
      'bwg_hide_ecommerce' =>  __('Hide Ecommerce', $this->prefix),
      'bwg_show_comments' =>  __('Show Comments', $this->prefix),
      'bwg_hide_comments' =>  __('Hide Comments', $this->prefix),
      'bwg_how_comments' =>  __('how Comments', $this->prefix),
      'bwg_restore' =>  __('Restore', $this->prefix),
      'bwg_maximize' =>  __('Maximize', $this->prefix),
      'bwg_fullscreen' =>  __('Fullscreen', $this->prefix),
      'bwg_search_tag' =>  __('SEARCH...', $this->prefix),
      'bwg_tag_no_match' => __('No tags found', $this->prefix),
      'bwg_all_tags_selected' => __('All tags selected', $this->prefix),
      'bwg_tags_selected' => __('tags selected', $this->prefix),
      'play' => __('Play', $this->prefix),
      'pause' => __('Pause', $this->prefix),
      'is_pro' => $this->is_pro,
    ));
  }

  /**
   * Languages localization.
   */
  public function language_load() {
    load_plugin_textdomain($this->prefix, FALSE, basename(dirname(__FILE__)) . '/languages');
  }

  public function init_free_users_lib() {
    add_filter('tenweb_free_users_lib_path', array($this, 'tenweb_lib_path'));
  }

  public function tenweb_lib_path($path) {
    // The version of WD Lib
    $version = '1.1.1';
    if (!isset($path['version']) || version_compare($path['version'], $version) === -1) {
      $path['version'] = $version;
      $path['path'] = $this->plugin_dir;
    }
    return $path;
  }

  /**
   * Overview.
   */
  public function overview() {
    if (is_admin() && !isset($_REQUEST['ajax'])) {
      if (!class_exists("TenWebLib")) {
        $plugin_dir = apply_filters('tenweb_free_users_lib_path', array('version' => '1.1.1', 'path' => $this->plugin_dir));
        require_once($plugin_dir['path'] . '/wd/start.php');
      }
      global $bwg_options;
      $bwg_options = array(
        "prefix" => "bwg",
        "wd_plugin_id" => 55,
        "plugin_id" => 101,
        "plugin_title" => "Photo Gallery",
        "plugin_wordpress_slug" => "photo-gallery",
        "plugin_dir" => BWG()->plugin_dir,
        "plugin_main_file" => __FILE__,
        "description" => __('Photo Gallery is a fully responsive gallery plugin with advanced functionality. It allows having different image galleries for your posts and pages. You can create unlimited number of galleries, combine them into gallery groups, and provide descriptions and tags.', $this->prefix),
        // from web-dorado.com
        "plugin_features" => array(
          0 => array(
            "title" => __("Easy Set-up and Management", $this->prefix),
            "description" => __("Create stunning, 100% responsive, SEO-friendly photo galleries in minutes. Use the File Manager with single-step and easy-to-manage functionality to rename, upload, copy, add and remove images and image directories. Otherwise use WordPress built in media uploader.", $this->prefix),
          ),
          1 => array(
            "title" => __("Unlimited Photos and Albums", $this->prefix),
            "description" => __("The plugin allows creating unlimited number of galleries or gallery groups and upload images in each gallery as many as you wish. Add single/ multiple galleries into your pages and posts with the help of functional shortcode; visual shortcodes for an easier management.", $this->prefix),
          ),
          2 => array(
            "title" => __("Customizable", $this->prefix),
            "description" => __("The gallery plugin is easily customizable. You can edit themes changing sizes and colors for different features. Specify the number of images to display in a single row in an gallery group. Additionally, you can customize thumbnail images by cropping, flipping and rotating them.", $this->prefix),
          ),
          3 => array(
            "title" => __("10 View Options", $this->prefix),
            "description" => __("Photo Gallery plugin allows displaying galleries and gallery groups in 10 elegant and beautiful views:, Thumbnails, Masonry, Mosaic, Slideshow, Image Browser, Masonry Album, Compact Album, Extended Album, Blog Style Gallery, Ecommerce.", $this->prefix),
          ),
          4 => array(
            "title" => __("Audio and Video Support", $this->prefix),
            "description" => __("You can include both videos and images within a single gallery. WordPress Photo Gallery Plugin supports YouTube and Vimeo videos within Galleries. It's also possible to add audio tracks for the image slideshow.", $this->prefix),
          )
        ),
        // user guide from web-dorado.com
        "user_guide" => array(
          0 => array(
            "main_title" => __("Installing", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-gallery/installing.html",
            "titles" => array()
          ),
          1 => array(
            "main_title" => __("Creating/Editing Galleries", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-gallery/creating-editing-galleries.html",
            "titles" => array(
              array(
                "title" => __("Instagram Gallery", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/creating-editing-galleries/instagram-gallery.html",
              ),
            )
          ),
          2 => array(
            "main_title" => __("Creating/Editing Tags", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-gallery/creating-editing-tag.html",
            "titles" => array()
          ),
          3 => array(
            "main_title" => __("Creating/Editing Albums", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-gallery/creating-editing-albums.html",
            "titles" => array()
          ),
          4 => array(
            "main_title" => __("Editing Options", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-gallery/editing-options.html",
            "titles" => array(
              array(
                "title" => __("Global Options", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-options/global-options.html",
              ),
              array(
                "title" => __("Watermark", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-options/watermark.html",
              ),
              array(
                "title" => __("Advertisement", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-options/advertisement.html",
              ),
              array(
                "title" => __("Lightbox", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-options/lightbox.html",
              ),
              array(
                "title" => __("Album Options", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-options/album-options.html",
              ),
              array(
                "title" => __("Slideshow", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-options/slideshow.html",
              ),
              array(
                "title" => __("Thumbnail Options", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-options/thumbnail-options.html",
              ),
              array(
                "title" => __("Image Options", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-options/image-options.html",
              ),
              array(
                "title" => __("Social Options", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-options/social-options.html",
              ),
              array(
                "title" => __("Carousel Options", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-options/carousel-options.html",
              ),
            )
          ),
          5 => array(
            "main_title" => __("Creating/Editing Themes", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-gallery/editing-themes.html",
            "titles" => array(
              array(
                "title" => __("Thumbnails", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-themes/thumbnails.html",
              ),
              array(
                "title" => __("Masonry", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-themes/masonry.html",
              ),
              array(
                "title" => __("Mosaic", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-themes/mosaic.html",
              ),
              array(
                "title" => __("Slideshow", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-themes/slideshow.html",
              ),
              array(
                "title" => __("Image Browser", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-themes/image-browser.html",
              ),
              array(
                "title" => __("Compact Album", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-themes/compact-album.html",
              ),
              array(
                "title" => __("Masonry Album", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-themes/masonry-album.html",
              ),
              array(
                "title" => __("Extended Album", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-themes/extended-album.html",
              ),
              array(
                "title" => __("Blog Style", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-themes/blog-style.html",
              ),
              array(
                "title" => __("Lightbox", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-themes/lightbox.html",
              ),
              array(
                "title" => __("Page Navigation", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-themes/page-navigation.html",
              ),
              array(
                "title" => __("Carousel", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/editing-themes/carousel.html",
              ),
            )
          ),
          6 => array(
            "main_title" => __("Generating Shortcode", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-gallery/shortcode-generating.html",
            "titles" => array()
          ),
          7 => array(
            "main_title" => __("Editing Comments", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-gallery/comments-editing.html",
            "titles" => array()
          ),
          8 => array(
            "main_title" => __("Editing Ratings", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-gallery/ratings-editing.html",
            "titles" => array()
          ),
          9 => array(
            "main_title" => __("Publishing the Created Photo Gallery", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-gallery/publishing-gallery.html",
            "titles" => array(
              array(
                "title" => __("General Parameters", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/publishing-gallery/general-parameters.html",
              ),
              array(
                "title" => __("Lightbox Parameters", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/publishing-gallery/lightbox-parameters.html",
              ),
              array(
                "title" => __("Advertisement", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/publishing-gallery/advertisement.html",
              ),
            )
          ),
          10 => array(
            "main_title" => __("Publishing Photo Gallery Widgets", $this->prefix),
            "url" => "https://web-dorado.com/wordpress-gallery/publishing-gallery-widgets.html",
            "titles" => array(
              array(
                "title" => __("Tag Cloud", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/publishing-gallery-widgets/tag-cloud.html",
              ),
              array(
                "title" => __("Photo Gallery Tags Cloud", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/publishing-gallery-widgets/gallery-tags-cloud.html",
              ),
              array(
                "title" => __("Photo Gallery Slideshow", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/publishing-gallery-widgets/gallery-slideshow.html",
              ),
              array(
                "title" => __("Photo Gallery Widget", $this->prefix),
                "url" => "https://web-dorado.com/wordpress-gallery/publishing-gallery-widgets/gallery-widget.html",
              ),
            )
          ),
        ),
        "video_youtube_id" => "4Mxg0FsFZZE",  // e.g. https://www.youtube.com/watch?v=acaexefeP7o youtube id is the acaexefeP7o
        "plugin_wd_url" => "https://10web.io/plugins/wordpress-photo-gallery/",
        "plugin_wd_demo_link" => "https://demo.10web.io/photo-gallery/",
        "plugin_wd_addons_link" => "https://10web.io/plugins/wordpress-photo-gallery/",
        "plugin_wd_docs_link" => "https://help.10web.io/hc/en-us/sections/360002159111-Photo-Gallery/",
        "after_subscribe" => admin_url('admin.php?page=galleries_bwg'), // this can be plagin overview page or set up page
        "plugin_wizard_link" => '',
        "plugin_menu_title" => $this->nicename,
        "plugin_menu_icon" => BWG()->plugin_url . '/images/icons/icon.png',
        "deactivate" => !$this->is_pro,
        "subscribe" => !$this->is_pro,
        "custom_post" => '',
        "menu_position" => null,
        "display_overview" => false,
      );

      ten_web_lib_init($bwg_options);
    }
  }

  /**
   * Create custom post types.
   */
  public function create_post_types() {
    if (!isset(BWG()->options)) {
      BWG()->options = new WD_BWG_Options();
    }

    if (BWG()->options->show_hide_post_meta == 1) {
      $show_hide_post_meta = array('editor', 'comments', 'thumbnail', 'title');
    }
    else {
      $show_hide_post_meta = array('editor', 'thumbnail', 'title');
    }
    if (BWG()->options->show_hide_custom_post == 0) {
      $show_hide_custom_post = false;
    }
    else {
      $show_hide_custom_post = true;
    }
    $args = array(
      'public' => TRUE,
      'exclude_from_search' => TRUE,
      'publicly_queryable' => TRUE,
      'show_ui' => $show_hide_custom_post,
      'show_in_menu' => TRUE,
      'show_in_nav_menus' => FALSE,
      'permalink_epmask' => TRUE,
      'rewrite' => TRUE,
      'label'  => __('Galleries', $this->prefix),
      'supports' => $show_hide_post_meta,
    );
    register_post_type( 'bwg_gallery', $args );

    $args = array(
      'public' => TRUE,
      'exclude_from_search' => TRUE,
      'publicly_queryable' => TRUE,
      'show_ui' => $show_hide_custom_post,
      'show_in_menu' => TRUE,
      'show_in_nav_menus' => FALSE,
      'permalink_epmask' => TRUE,
      'rewrite' => TRUE,
      'label'  => __('Albums', $this->prefix),
      'supports' => $show_hide_post_meta
    );
    register_post_type( 'bwg_album', $args );

    $args = array(
      'public' => TRUE,
      'exclude_from_search' => TRUE,
      'publicly_queryable' => TRUE,
      'show_ui' => $show_hide_custom_post,
      'show_in_menu' => TRUE,
      'show_in_nav_menus' => FALSE,
      'permalink_epmask' => TRUE,
      'rewrite' => TRUE,
      'label'  => __('Gallery tags', $this->prefix),
      'supports' => $show_hide_post_meta
    );
    register_post_type( 'bwg_tag', $args );

    if ( $this->is_pro ) {
      $args = array(
        'public' => FALSE,
        'publicly_queryable' => TRUE,
        'exclude_from_search' => TRUE,
        /*'query_var'          => 'share',
        'rewrite'            => array('slug' => 'share'),*/
      );
      register_post_type('bwg_share', $args);
    }

    WDWLibrary::register_custom_taxonomies();
  }

  /**
   * Change Share template.
   *
   * @param $single_template
   *
   * @return string
   */
  public function share_template( $single_template ) {
    global $post;
    if ( isset($post) && isset($post->post_type) && $post->post_type == 'bwg_share' ) {
      $single_template = BWG()->plugin_dir . '/framework/WDWShare.php';
    }

    return $single_template;
  }

  public function tag_cloud_widget_args($args) {
    if ($args['taxonomy'] == 'bwg_tag') {
      require_once BWG()->plugin_dir . "/frontend/models/BWGModelWidget.php";
      $model = new BWGModelWidgetFrontEnd();
      $model->get_tags_data(0);
    }
    return $args;
  }

  public function autoupdate_interval( $schedules ) {
    require_once(BWG()->plugin_dir . '/framework/WDWLibraryEmbed.php');
    $schedules['bwg_autoupdate_interval'] = array(
      'interval' => 60 * BWG()->options->autoupdate_interval,
      'display' => __('Photo gallery plugin autoupdate interval.', $this->prefix),
    );
    return $schedules;
  }

  public function social_galleries() {
    if ( BWG()->options->instagram_access_token != '' ) {
      $this->instagram_galleries();
      wp_die();
    }
  }

  public function instagram_galleries() {
    /* Check if instagram galleries exist and refresh them every hour.*/
    require_once(BWG()->plugin_dir . '/framework/WDWLibraryEmbed.php');
    /* Array of IDs of instagram galleries.*/
    $response = array();
    $instagram_galleries = WDWLibraryEmbed::check_instagram_galleries();
    if ( !empty($instagram_galleries[0]) ) {
      foreach ( $instagram_galleries as $gallery ) {
        array_push($response, WDWLibraryEmbed::refresh_social_gallery($gallery));
      }
    }
  }

	/**
	* Plugins loaded actions.
	*/
	public function plugins_loaded() {
		// Initialize add-ons.
		if ( $this->is_pro ) {
			do_action('bwg_init_addons');
		}
	}
	
	/**
	* Incompatibility message.
	*
	* @param $add_ons_notice
	*/
	function addons_compatibility_notice($add_ons_notice) {
		$addon_names = implode($add_ons_notice, ', ');
		$count = count($add_ons_notice);
		$single = __('Please update the %s add-on to start using.', $this->prefix);
		$plural = __('Please update the %s add-ons to start using.', $this->prefix);
		echo '<div class="error"><p>' . sprintf( _n($single, $plural, $count, $this->prefix), $addon_names ) .'</p></div>';
	}

   /**
   * Check add-ones version compatibility with Photo Gallery.
   */
	function check_addons_compatibility() {
		$add_ons = array(
		  'photo-gallery-facebook' => array( 'version' => '1.1.0', 'file' => 'photo-gallery-facebook.php' ),
		  'photo-gallery-export' => array( 'version' => '1.0.3', 'file' => 'photo-gallery-export.php' ),
		  'photo-gallery-ecommerce' => array( 'version' => '1.0.17', 'file' => 'photo-gallery-ecommerce.php' ),
		);
		$add_ons_notice = array();
		include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		foreach ( $add_ons as $add_on_key => $add_on_value ) {
		  $addon_path = plugin_dir_path(dirname(__FILE__)) . $add_on_key . '/' . $add_on_value['file'];
		  if ( is_plugin_active($add_on_key . '/' . $add_on_value['file']) ) {
			$addon = get_plugin_data($addon_path);
			if ( version_compare($addon['Version'], $add_on_value['version'], '<=') ) {
			  deactivate_plugins($addon_path);
			  array_push($add_ons_notice, $addon['Name']);
			}
		  }
		}
		if ( !empty($add_ons_notice) ) {
		  $this->addons_compatibility_notice($add_ons_notice);
		}
	}

  /**
   * Add star rating to plugin meta links.
   *
   * @param $meta_fields
   * @param $file
   *
   * @return array
   */
  function add_plugin_meta_links($meta_fields, $file) {
    if ( plugin_basename(__FILE__) == $file ) {
      $plugin_url = "https://wordpress.org/support/plugin/photo-gallery";
      $prefix = $this->prefix;
      $meta_fields[] = "<a href='" . $plugin_url . "' target='_blank'>" . __('Support Forum', $prefix) . "</a>";
      $meta_fields[] = "<a href='" . $plugin_url . "/reviews#new-post' target='_blank' title='" . __('Rate', $prefix) . "'>
            <i class='wdi-rate-stars'>"
        . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
        . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
        . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
        . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
        . "<svg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-star'><polygon points='12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2'/></svg>"
        . "</i></a>";

      $stars_color = "#ffb900";

      echo "<style>"
        . ".wdi-rate-stars{display:inline-block;color:" . $stars_color . ";position:relative;top:3px;}"
        . ".wdi-rate-stars svg{fill:" . $stars_color . ";}"
        . ".wdi-rate-stars svg:hover{fill:" . $stars_color . "}"
        . ".wdi-rate-stars svg:hover ~ svg{fill:none;}"
        . "</style>";
    }

    return $meta_fields;
  }

  /**
   * Allowed upload mime_types.
   *
   * @param array $mimes
   *
   * @return array $mimes
   */
  function allowed_upload_mime_types( $mimes ) {
    // Optional. allowed a mime type.
    $allowed = array( 'jpg|jpeg|jpe', 'gif', 'png' );
    foreach ( $mimes as $key => $val ) {
      if ( !in_array( $key, $allowed ) ) {
        unset( $mimes[ $key ] );
      }
    }
    return $mimes;
  }

  /**
   * Prevent adding shortcode conflict with some builders.
   */
  private function before_shortcode_add_builder_editor() {
    if ( defined('ELEMENTOR_VERSION') ) {
      add_action('elementor/editor/before_enqueue_scripts', array( $this, 'global_script' ));
    }
    if ( class_exists('FLBuilder') ) {
      add_action('wp_enqueue_scripts', array( $this, 'global_script' ));
    }
  }
}

/**
 * Main instance of BWG.
 *
 * @return BWG The main instance to prevent the need to use globals.
 */
function BWG() {
  return BWG::instance();
}

BWG();

/**
 * Display gallery with function.
 *
 * @param $id Shortcode id.
 */
function photo_gallery( $id ) {
  echo BWG()->shortcode(array('id' => $id));
}

/**
 * Show notice to install 10web manager plugin
 */
function wdpg_tenweb_install_notice() {
  // Show notice only on plugin pages.
  if ( ( !isset($_GET['page']) || strpos(esc_html($_GET['page']), '_bwg') === FALSE ) || ( isset($_GET['task']) && !strpos(esc_html($_GET['task']), 'edit') === TRUE ) ) {
    return '';
  }
  wp_enqueue_script('thickbox');
  wp_enqueue_script('bwg_admin', BWG()->plugin_url . '/js/bwg.js', array(), BWG()->plugin_version);

  // Remove old notice.
  if ( get_option('tenweb_notice_status') !== FALSE ) {
    update_option('tenweb_notice_status', '1', 'no');
  }

  $meta_value = get_option('tenweb_notice_status');
  if ( $meta_value === '' || $meta_value === FALSE ) {
    ob_start();
    $prefix = BWG()->prefix;
    $nicename = BWG()->nicename;
    $url = BWG()->plugin_url;
    $dismiss_url = add_query_arg(array( 'action' => 'wd_tenweb_dismiss' ), admin_url('admin-ajax.php'));

    $slug = '10web-manager';
    $install_url = esc_url(wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . $slug), 'install-plugin_' . $slug));
    $activation_url = na_action_link($slug.'/10web-manager.php', 'activate');
    $tenweb_url = admin_url( 'admin.php?page=tenweb_menu' );
    $verify_url = add_query_arg( array ('action' => 'tenweb_status'), admin_url('admin-ajax.php'));
    ?>
    <div class="notice" id="wd_tenweb_notice_cont" style="display: none;">
      <div class="tenweb_logo notice_col"><img id="wd_tenweb_logo_notice" src="<?php echo $url . '/images/tenweb/10web-logo.svg'; ?>" /></div>
      <div class="tenweb_description notice_col">
        <div>
          <h1><?php _e("Hey There...", $prefix); ?></h1>
          <p><?php _e("Connect this website to 10Web dashboard to enable all the services for your magical photo website management experience:", $prefix); ?></p>
        </div>
      </div>
      <div class="tenweb_plugins_icons notice_col">
        <div id="tenweb_plugins_icons_cont">
          <span class="tenweb_plugins_icons_item"><img src="<?php echo $url . '/images/tenweb/Group 53914.svg'; ?>" /><span>Image optimizer</span></span>
          <span class="tenweb_plugins_icons_item"><img src="<?php echo $url . '/images/tenweb/Group 53915.svg'; ?>" /><span>SEO</span></span>
          <span class="tenweb_plugins_icons_item"><img src="<?php echo $url . '/images/tenweb/Group 53916.svg'; ?>" /><span>Automatic backup</span></span>
          <span class="tenweb_plugins_icons_item"><img src="<?php echo $url . '/images/tenweb/Group 53917.svg'; ?>" /><span>Performance Check</span></span>
          <span class="tenweb_plugins_icons_item"><img src="<?php echo $url . '/images/tenweb/Group 58983.svg'; ?>" /><span>Security</span></span>
        </div>
      </div>
      <div class="tenweb_action notice_col">
        <div>
          <p><?php _e("Just install this plugin, and signup for free.", $prefix); ?></p>
          <?php
          $plugin_dir = ABSPATH . 'wp-content/plugins/10web-manager/';
          if ( is_dir($plugin_dir) && !is_plugin_active( '10web-manager/manager.php' ) ) {
            ?>
            <a class="button tenweb_activaion" id="activate_now" data-tenweb-url="<?php echo $tenweb_url; ?>" data-install-url="<?php echo $install_url; ?>" data-activate-url="<?php echo $activation_url; ?>"><?php _e("Activate", $prefix); ?></a>
            <span class="spinner" id="loading"></span>
            <span class="error_activate hide"><?php _e("Activation failed, please try again.", $prefix); ?></span>
            <?php
          } else if( ! is_dir($plugin_dir) ) {
            ?>
            <a class="button tenweb_activaion" id="install_now" data-install-url="<?php echo $install_url; ?>" data-activate-url="<?php echo $activation_url; ?>"><?php _e("Install", $prefix); ?></a>
            <a class="button tenweb_activaion hide" id="activate_now" data-tenweb-url="<?php echo $tenweb_url; ?>" data-install-url="<?php echo $install_url; ?>" data-activate-url="<?php echo $activation_url; ?>"><?php _e("Activation", $prefix); ?></a>
            <span class="spinner" id="loading"></span>
            <span class="error_install hide tenweb_active"><?php _e("Installation failed, please try again.", $prefix); ?></span>
            <?php
          }
          ?>

        </div>
      </div>
      <button type="button" class="wd_tenweb_notice_dissmiss notice-dismiss" onclick="jQuery('#wd_tenweb_notice_cont').attr('style', 'display: none !important;'); jQuery.post('<?php echo $dismiss_url; ?>');"><span class="screen-reader-text"></span></button>
      <div id="verifyUrl" data-url="<?php echo $verify_url ?>"></div>

    </div>
    <script>
      var url = jQuery(".tenweb_activaion").attr("data-install-url");
      var activate_url = jQuery(".tenweb_activaion").attr("data-activate-url");

      function install_tenweb_plugin() {
        jQuery("#loading").addClass('is-active');
        jQuery(this).prop('disable',true);
        var io_plugin_url = '<?php echo plugins_url('10web-manager/10web-manager.php');?>'; // Getting 10web manager plugin url

        jQuery.ajax({
          method: "POST",
          url: url,
        }).done(function() {
          jQuery.ajax({ // Check if plugin installed
            type: 'POST',
            url: jQuery("#verifyUrl").attr('data-url'),
            error: function()
            {
              jQuery("#loading").removeClass('is-active');
              jQuery(".error_install").show();
            },
            success: function(response)
            {
              var plStatus = JSON.parse(response);
              if(plStatus.status_install != 1) {
                jQuery('#install_now').addClass('hide');
                jQuery('#activate_now').removeClass('hide');
                activate_tenweb_plugin();
              }
              else {
                jQuery("#loading").removeClass('is-active');
                jQuery(".error_install").removeClass('hide');
              }
            }
          });
        })
              .fail(function() {
                //window.location = window.location.href;
                jQuery("#loading").removeClass('is-active');
                jQuery(".error_install").removeClass('hide');
              });

      }

      function activate_tenweb_plugin() {
        jQuery("#loading").addClass('is-active');
        jQuery.ajax({
          method: "POST",
          url: activate_url,
        }).done(function() {
          jQuery("#loading").removeClass('is-active');
          var data_tenweb_url = '';
          jQuery.ajax({ // Check if plugin installed
            type: 'POST',
            url: jQuery("#verifyUrl").attr('data-url'),
            error: function()
            {
              jQuery("#loading").removeClass('is-active');
              jQuery(".error_activate").removeClass('hide');
            },
            success: function(response)
            {
              var plStatus = JSON.parse(response);
              if(plStatus.status_active == 1) {
                jQuery('#install_now').addClass('hide');
                data_tenweb_url = jQuery('#activate_now').attr('data-tenweb-url');
                jQuery.post('<?php echo $dismiss_url; ?>');
              }
              else {
                jQuery("#loading").removeClass('is-active');
                jQuery(".error_activate").removeClass('hide');
              }
            },
            complete : function() {
              if ( data_tenweb_url != '' ) {
                window.location.href = data_tenweb_url;
              }
            }
          });

        })
              .fail(function() {
                //window.location = window.location.href;
                jQuery("#loading").removeClass('is-active');
              });
      }

      jQuery("#install_now").on("click",function(){
        install_tenweb_plugin();
      })
      jQuery("#activate_now").on("click",function(){
        activate_tenweb_plugin()
      })
    </script>
    <style>
      #wd_tenweb_notice_cont {
        height: 137px;
        border-radius: 10px;
        position: relative;
        background-image: url("<?php echo $url?>/images/tenweb/notice_bg.png");
        background-position: center center;
        display: inline-block!important;
      }

      #wd_tenweb_notice_cont .spinner {
        background: url("<?php echo $url?>/images/spinner.gif") no-repeat;
        background-size: 15px 15px;
        margin: 0px;
        position:absolute;
        width: 15px;
        height: 15px;
        bottom: 7px;
        right: 10px;
      }

      #wd_tenweb_notice_cont p {
        color: #333B46;
      }

      .tenweb_action div {
        position: relative;
      }

      .notice_col {
        display: flex;
        float: left;
        height: 140px;
        flex-direction: column;
        justify-content: center;
        align-items: center;
      }

      #wd_tenweb_notice_cont .notice-dismiss {
        padding: 0px;
        margin: 5px;
      }

      .tenweb_description {
        position: relative;
      }

      .tenweb_description div {
        display: flex;
        height: 140px;
        flex-direction: column;
        justify-content: center;
        align-items: center;
      }

      .tenweb_description h1 {
        font-size: 24px;
        font-weight: bold;
        width: 100%;
      }
      .tenweb_description p {
        font-size: 13px;
      }


      .tenweb_plugins_icons{
        position: relative;
      }

      .tenweb_plugins_icons #tenweb_plugins_icons_cont {
        display: flex;
        height: 110px;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;

      }

      .tenweb_plugins_icons_item {
        float: left;
        line-height: 25px;
        margin-right: 25px;
        margin-bottom: 15px;
      }

      .tenweb_plugins_icons_item:last-child {
        margin-right: 0;
      }

      .tenweb_plugins_icons_item img {
        width: 25px;
        float: left;
        margin-right: 5px;
      }

      .tenweb_plugins_icons_item span {
        line-height: 25px;
        font-size: 14px;
        font-weight: bold;
      }

      .tenweb_action .tenweb_activaion {
        background-color: #323A45;
        width: 100%;
        text-align: center;
        color: #fff;
        border: 1px solid #323A45;
        border-radius: 15px;
        height: 30px;
        line-height: 30px;
        margin: 0px;
        font-weight: bold;
      }

      .tenweb_action .tenweb_activaion:hover {
        background-color: #323A45;
        color:#ffffff
      }

      .tenweb_logo{
        width: calc(12% - 10px);
        padding: 0 10px 0 0;
        display: flex;
        height: 140px;
        flex-direction: column;
        justify-content: center;
        align-items: center;


      }
      .tenweb_logo img {
      }

      .tenweb_description {
        width: calc(30% - 20px);
        padding: 0 0 0 2%;
      }

      .tenweb_plugins_icons {
        width: calc(41% - 20px);
        padding: 0 10px;
      }

      .tenweb_action {
        width: calc(15% - 10px);
        margin-right: 1%;
        position: relative;
      }

      .tenweb_action p {
        text-align: center;
        font-size: 14px;
        padding: 0px;
      }

      @media only screen and (max-width: 1100px) {

        #wd_tenweb_notice_cont #wd_tenweb_logo_notice {
          height: 25px;
        }

        .tenweb_action p {
          padding: 0px;
          font-size: 12px;
        }

        .tenweb_plugins_icons_item span {
          font-size: 12px;
        }

        .tenweb_plugins_icons #tenweb_plugins_icons_cont {
          display: flex;
          height: 120px;
          justify-content: center;
          align-items: center;
          flex-wrap: wrap;
        }

        .tenweb_plugins_icons_item {
          margin-bottom: 0px;
        }

        #wd_tenweb_notice_cont .spinner {
          right: 0px;
        }
      }

      @media only screen and (max-width: 840px) {
        #wd_tenweb_notice_cont {
          width: calc(100% - 20px);
          height: auto;
          display: inline-block;
        }
        .notice_col {
          width:100%;
          min-height:50px;
          height: auto;
          margin: auto;
          text-align: center;
          padding: 0px;
        }

        .tenweb_plugins_icons_item span {
          font-size: 13px;
        }

        .tenweb_logo img {
          position: relative;
          float: inherit;
          text-align: center;
        }

        #wd_tenweb_notice_cont #wd_tenweb_logo_notice {
          float: none;
        }

        .tenweb_action {
          margin-bottom: 20px;
        }

        .tenweb_action a.tenweb_activaion {
          width: 150px;
          height: 30px;
          padding: 0;

        }

        .tenweb_description div {
          height: auto;
        }

        .tenweb_description h1 {
          font-size: 18px;
          font-weight: bold;
          padding-top: 0;
          margin-left: 10px;
        }

        .tenweb_action p {
          font-size: 13px;
        }

        .tenweb_description p {
          font-size: 15px;
        }

        .tenweb_plugins_icons #tenweb_plugins_icons_cont {
          display: flex;
          height: 80px;
          justify-content: center;
          align-items: center;
          flex-wrap: wrap;
        }

        #wd_tenweb_notice_cont .spinner {
          right: 70px;
        }


      }



      @media only screen and (max-width: 500px) {
        body #wd_backup_logo {
          max-width: 100%;
        }

        .tenweb_plugins_icons #tenweb_plugins_icons_cont {
          display: flex;
          height: 110px;
          justify-content: center;
          align-items: center;
          flex-wrap: wrap;
          margin: 15px 0;
        }

        body #wd_tenweb_notice_cont p {
          padding-right: 0;
        }

        .tenweb_plugins_icons_item {
          margin-right: 20px;
          margin-bottom: 0px;
        }

        .tenweb_action .tenweb_activaion {
          width: 100%!important;
        }

      }


      .hide {
        display: none!important;
      }
      #verifyUrl{
        display: none
      }

      #loading {
        vertical-align: middle;
        float: none!important;
        margin: 0 !important;
      }
      #wd_tenweb_logo_notice {
        height: 32px;
        float: left;
        margin-right: 10px;
      }
      .error_install, .error_activate {
        color:red;
        font-size: 10px;
      }


      #wd_tenweb_notice_cont a {
        margin: 0 5px;
      }
      #wd_tenweb_notice_cont .dashicons-dismiss:before {
        content: "\f153";
        background: 0 0;
        color: #72777c;
        display: block;
        font: 400 16px/20px dashicons;
        speak: none;
        height: 20px;
        text-align: center;
        width: 20px;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
      }
      .wd_tenweb_notice_dissmiss {
        margin-top: 5px;
      }
    </style>
    <?php
    echo ob_get_clean();
  }
}

if( !function_exists('is_plugin_active') ) {
  include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if( !is_plugin_active( '10web-manager/10web-manager.php' ) ) {
  add_action('admin_notices', 'wdpg_tenweb_install_notice');
}

if ( !function_exists('wd_tenwebps_install_notice_status') ) {
  // Add usermeta to db.
  function wd_tenwebps_install_notice_status() {
    update_option('tenweb_notice_status', '1', 'no');
  }
  add_action('wp_ajax_wd_tenweb_dismiss', 'wd_tenwebps_install_notice_status');
}


//Check status 10web manager install
function check_tenweb_status(){
  $status_install = 0;
  $status_active = 0;
  $plugin_dir = ABSPATH . 'wp-content/plugins/10web-manager/';
  if ( !is_dir($plugin_dir)){
    $status_install = 1;
  }else if(is_plugin_active( '10web-manager/10web-manager.php' )) {
    $status_active = 1;
  }
  $jsondata = array('status_install' => $status_install, 'status_active' => $status_active);
  echo json_encode($jsondata); exit;
}
add_action('wp_ajax_tenweb_status', 'check_tenweb_status');

/**
 * Get activation or deactivation link of a plugin
 *
 * @author Nazmul Ahsan <mail@nazmulahsan.me>
 * @param string $plugin plugin file name
 * @param string $action action to perform. activate or deactivate
 * @return string $url action url
 */
function na_action_link( $plugin, $action = 'activate' ) {
  if ( strpos( $plugin, '/' ) ) {
    $plugin = str_replace( '\/', '%2F', $plugin );
  }
  $url = sprintf( admin_url( 'plugins.php?action=' . $action . '&plugin=%s&plugin_status=all&paged=1&s' ), $plugin );
  $_REQUEST['plugin'] = $plugin;
  $url = wp_nonce_url( $url, $action . '-plugin_' . $plugin );
  return $url;
}
