<?php
/**
 * Plugin Name:			Ocean Extra
 * Plugin URI:			https://oceanwp.org/extension/ocean-extra/
 * Description:			Add extra features like widgets, metaboxes, import/export and a panel to activate the premium extensions.
 * Version:				1.4.29
 * Author:				OceanWP
 * Author URI:			https://oceanwp.org/
 * Requires at least:	4.5.0
 * Tested up to:		4.9.8
 *
 * Text Domain: ocean-extra
 * Domain Path: /languages/
 *
 * @package Ocean_Extra
 * @category Core
 * @author OceanWP
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the main instance of Ocean_Extra to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Ocean_Extra
 */
function Ocean_Extra() {
	return Ocean_Extra::instance();
} // End Ocean_Extra()

Ocean_Extra();

/**
 * Main Ocean_Extra Class
 *
 * @class Ocean_Extra
 * @version	1.0.0
 * @since 1.0.0
 * @package	Ocean_Extra
 */
final class Ocean_Extra {
	/**
	 * Ocean_Extra The single instance of Ocean_Extra.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	// Admin - Start
	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;

	/**
	 * Constructor function.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function __construct( $widget_areas = array() ) {
		$this->token 			= 'ocean-extra';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.4.29';

		define( 'OE_URL', $this->plugin_url );
		define( 'OE_PATH', $this->plugin_path );
		define( 'OE_VERSION', $this->version );
		define( 'OE_ADMIN_PANEL_HOOK_PREFIX', 'theme-panel_page_oceanwp-panel' );

		// Elementor partner ID
		if ( class_exists( 'Elementor\Plugin' )
			&& ! defined( 'ELEMENTOR_PARTNER_ID' ) ) {
			define( 'ELEMENTOR_PARTNER_ID', 2121 );
		}

		// WPForms partner ID
		add_filter( 'wpforms_upgrade_link', array( $this, 'wpforms_upgrade_link' ) );

		// WooCommerce Wishlist partner ID
		if ( class_exists( 'TInvWL_Wishlist' ) ) {
			define( 'TINVWL_PARTNER', 'oceanwporg' );
			define( 'TINVWL_CAMPAIGN', 'oceanwp_theme' );
		}

		// WooCommerce Variation Swatches partner ID
		add_filter( 'gwp_affiliate_id', array( $this, 'gwp_affiliate_id' ) );

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Setup all the things
		add_action( 'init', array( $this, 'setup' ) );

		// Menu icons
		$theme = wp_get_theme();
		if ( 'OceanWP' == $theme->name || 'oceanwp' == $theme->template ) {
			require_once( OE_PATH .'/includes/panel/theme-panel.php' );
			require_once( OE_PATH .'/includes/panel/integrations-tab.php' );
			require_once( OE_PATH .'/includes/panel/library.php' );
			require_once( OE_PATH .'/includes/panel/library-shortcode.php' );
			require_once( OE_PATH .'/includes/panel/updater.php' );
			require_once( OE_PATH .'/includes/menu-icons/menu-icons.php' );

			// Outputs custom JS to the footer
			add_action( 'wp_footer', array( $this, 'custom_js' ), 9999 );

			// Register Custom JS file
			add_action( 'init', array( $this, 'register_custom_js' ) );

			// Move the Custom CSS section into the Custom CSS/JS section
			add_action( 'customize_register', array( $this, 'customize_register' ), 11 );

			// Remove customizer unnecessary sections
			add_action( 'customize_register', array( $this, 'remove_customize_sections' ), 11 );

			// Load custom widgets
			add_action( 'widgets_init', array( $this, 'custom_widgets' ), 10 );
		}

		// Allow shortcodes in text widgets
		add_filter( 'widget_text', 'do_shortcode' );

		// Allow for the use of shortcodes in the WordPress excerpt
		add_filter( 'the_excerpt', 'shortcode_unautop' );
		add_filter( 'the_excerpt', 'do_shortcode' );
	}

	/**
	 * Main Ocean_Extra Instance
	 *
	 * Ensures only one instance of Ocean_Extra is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see Ocean_Extra()
	 * @return Main Ocean_Extra instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * WPForms partner ID
	 *
	 * @since 1.0.0
	 */
	public function wpforms_upgrade_link() {
		$url = 'https://wpforms.com/lite-upgrade/?discount=LITEUPGRADE&amp;utm_source=WordPress&amp;utm_medium=' . sanitize_key( apply_filters( 'wpforms_upgrade_link_medium', 'link' ) ) . '&amp;utm_campaign=liteplugin';

		// Build final URL
		$final_url = sprintf( 'http://www.shareasale.com/r.cfm?B=837827&U=%s&M=64312&urllink=%s', '1591020', $url );

		// Return URL.
		return esc_url( $final_url );
	}

	/**
	 * WooCommerce Variation Swatches partner ID
	 *
	 * @since 1.0.0
	 */
	public function gwp_affiliate_id() {

		// Return if the plugin is not active
		if ( ! class_exists( 'Woo_Variation_Swatches' ) ) {
			return;
		}

		return 69;
	}

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'ocean-extra', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Installation.
	 * Runs on activation. Logs the version number and assigns a notice message to a WordPress option.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function install() {
		$this->_log_version_number();
	}

	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number() {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	}

	/**
	 * All theme functions hook into the oceanwp_footer_js filter for this function.
	 *
	 * @since 1.3.8
	 */
	public static function custom_js( $output = NULL ) {

		// Add filter for adding custom js via other functions
		$output = apply_filters( 'ocean_footer_js', $output );

		// Minify and output JS in the wp_footer
		if ( ! empty( $output ) ) { ?>

			<script type="text/javascript">

				/* OceanWP JS */
				<?php echo Ocean_Extra_JSMin::minify( $output ); ?>

			</script>

		<?php
		}

	}

	/**
	 * Adds customizer options
	 *
	 * @since 1.3.8
	 */
	public function register_custom_js() {
		
		// Var
		$dir = OE_PATH .'/includes/';

		// File
		if ( Ocean_Extra_Theme_Panel::get_setting( 'oe_custom_code_panel' ) ) {
			require_once( $dir . 'custom-code.php' );
		}

	}

	/**
	 * Move the Custom CSS section into the Custom CSS/JS section
	 *
	 * @since 1.3.8
	 */
	public static function customize_register( $wp_customize ) {

		// Move custom css setting
		$wp_customize->get_control( 'custom_css' )->section = 'ocean_custom_code_panel';

	}

	/**
	 * Remove customizer unnecessary sections
	 *
	 * @since 1.0.0
	 */
	public static function remove_customize_sections( $wp_customize ) {

		// Remove core sections
		$wp_customize->remove_section( 'colors' );
		$wp_customize->remove_section( 'themes' );
		$wp_customize->remove_section( 'background_image' );

		// Remove core controls
		$wp_customize->remove_control( 'header_textcolor' );
		$wp_customize->remove_control( 'background_color' );
		$wp_customize->remove_control( 'background_image' );
		$wp_customize->remove_control( 'display_header_text' );

		// Remove default settings
		$wp_customize->remove_setting( 'background_color' );
		$wp_customize->remove_setting( 'background_image' );

	}

	/**
	 * Setup all the things.
	 * Only executes if OceanWP or a child theme using OceanWP as a parent is active and the extension specific filter returns true.
	 * @return void
	 */
	public function setup() {
		$theme = wp_get_theme();

		if ( 'OceanWP' == $theme->name || 'oceanwp' == $theme->template ) {
			require_once( OE_PATH .'/includes/metabox/butterbean/butterbean.php' );
			require_once( OE_PATH .'/includes/metabox/metabox.php' );
			require_once( OE_PATH .'/includes/metabox/shortcodes.php' );
			require_once( OE_PATH .'/includes/metabox/gallery-metabox/gallery-metabox.php' );
			require_once( OE_PATH .'/includes/shortcodes/shortcodes.php' );
			require_once( OE_PATH .'/includes/image-resizer.php' );
			require_once( OE_PATH .'/includes/jsmin.php' );
			require_once( OE_PATH .'/includes/panel/notice.php' );
			require_once( OE_PATH .'/includes/walker.php' );
			require_once( OE_PATH .'/includes/dashboard.php' );

			// If Demo Import or Pro Demos is activated
			if ( class_exists( 'Ocean_Demo_Import' )
				|| class_exists( 'Ocean_Pro_Demos' ) ) {
				require_once( OE_PATH .'/includes/panel/demos.php' );
			}
			
			add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ), 999 );
		}
	}

	/**
	 * Include flickr widget class
	 *
	 * @since   1.0.0
	 */
	public static function custom_widgets() {

		if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) ) {
			return;
		}

		// Define array of custom widgets for the theme
		$widgets = apply_filters( 'ocean_custom_widgets', array(
			'about-me',
			'contact-info',
			'custom-links',
			'custom-menu',
			'facebook',
			'flickr',
			'instagram',
			'mailchimp',
			'recent-posts',
			'social',
			'social-share',
			'tags',
			'twitter',
			'video',
			'custom-header-logo',
			'custom-header-nav',
		) );

		// Loop through widgets and load their files
		if ( $widgets && is_array( $widgets ) ) {
			foreach ( $widgets as $widget ) {
				$file = OE_PATH .'/includes/widgets/' . $widget .'.php';
				if ( file_exists ( $file ) ) {
					require_once( $file );
				}
			}
		}

	}

	/**
	 * Enqueue scripts
	 *
	 * @since   1.0.0
	 */
	public function scripts() {

		// Load main stylesheet
		wp_enqueue_style( 'oe-widgets-style', plugins_url( '/assets/css/widgets.css', __FILE__ ) );

		// If rtl
		if ( is_RTL() ) {
			wp_enqueue_style( 'oe-widgets-style-rtl', plugins_url( '/assets/css/rtl.css', __FILE__ ) );
		}

	}

} // End Class