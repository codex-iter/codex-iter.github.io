<?php
/**
 * One Click Demo Import Class
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.2
 *
 * Contents:
 *
 *   0) Init
 *  10) Files
 *  20) Texts
 *  30) Setup
 * 100) Helpers
 */
class Modern_One_Click_Demo_Import {





	/**
	 * 0) Init
	 */

		private static $instance;



		/**
		 * Constructor
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		private function __construct() {

			// Processing

				// Hooks

					// Actions

						add_action( 'admin_enqueue_scripts', __CLASS__ . '::styles', 99 );

						add_action( 'pt-ocdi/before_content_import', __CLASS__ . '::before' );

						add_action( 'pt-ocdi/before_widgets_import', __CLASS__ . '::before_widgets_import' );

						add_action( 'pt-ocdi/after_import', __CLASS__ . '::after' );

					// Filters

						add_filter( 'pt-ocdi/import_files', __CLASS__ . '::files' );

						add_filter( 'pt-ocdi/plugin_intro_text', __CLASS__ . '::info' );
						add_action( 'pt-ocdi/plugin_intro_text', __CLASS__ . '::jetpack_custom_posts' );

						add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );

		} // /__construct



		/**
		 * Initialization (get instance)
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function init() {

			// Processing

				if ( null === self::$instance ) {
					self::$instance = new self;
				}


			// Output

				return self::$instance;

		} // /init





	/**
	 * 10) Files
	 */

		/**
		 * Import files setup
		 *
		 * For WordPress.org repository themes the file URL needs to be local.
		 * Add the demo XML and WIE files into `includes/starter-content` folder
		 * and use `get_theme_file_uri()` for file URL.
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function files() {

			// Output

				return array(

					array(
						'import_file_name'       => esc_html__( 'Theme demo content', 'modern' ),
						'import_file_url'        => esc_url( get_theme_file_uri( 'includes/plugins/one-click-demo-import/demo-content-modern.xml' ) ),
						'import_widget_file_url' => esc_url( get_theme_file_uri( 'includes/plugins/one-click-demo-import/demo-widgets-modern.wie' ) ),
						'preview_url'            => 'https://themedemos.webmandesign.eu/modern/',
					),

				);

		} // /files





	/**
	 * 20) Texts
	 */

		/**
		 * Info texts
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  string $text  Default intro text.
		 */
		public static function info( $text = '' ) {

			// Processing

				$text .= '<div class="media-files-quality-info">';

					$text .= '<h3>';
					$text .= esc_html__( 'Media files quality', 'modern' );
					$text .= '</h3>';

					$text .= '<p>';
					$text .= esc_html__( 'Please note that imported media files (such as images, video and audio files) are of low quality to prevent copyright infringement.', 'modern' );
					$text .= ' ' . esc_html__( 'Please read "Credits" section of theme documentation for reference where the demo media files were obtained from.', 'modern' );
					$text .= ' <a href="https://webmandesign.github.io/docs/modern/#credits">' . esc_html__( 'Get media for your website &raquo;', 'modern' ) . '</a>';
					$text .= '</p>';

				$text .= '</div>';

				$text .= '<div class="ocdi__demo-import-notice">';

					$text .= '<h3>';
					$text .= esc_html__( 'Install required plugins!', 'modern' );
					$text .= '</h3>';

					$text .= '<p>';
					$text .= esc_html__( 'Please read the information about the theme demo required plugins first.', 'modern' );
					$text .= ' ' . esc_html__( 'If you do not install and activate demo required plugins, some of the content will not be imported.', 'modern' );
					$text .= ' <a href="https://github.com/webmandesign/demo-content/tree/master/modern/content#before-you-begin" title="' . esc_attr__( 'Read the information before you run the theme demo content import process.', 'modern' ) . '"><strong>';
					$text .= esc_html__( 'View the list of required plugins &raquo;', 'modern' );
					$text .= '</strong></a>';
					$text .= '</p>';

				$text .= '</div>';


			// Output

				return $text;

		} // /info





	/**
	 * 30) Setup
	 */

		/**
		 * Before import actions
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function before() {

			// Helper variables

				$image_sizes = array_filter( (array) apply_filters( 'wmhook_modern_setup_image_sizes', array() ) );


			// Processing

				// Image sizes

					foreach ( array( 'thumbnail', 'medium', 'medium_large', 'large' ) as $size ) {
						if ( isset( $image_sizes[ $size ] ) ) {
							update_option( $size . '_size_w', $image_sizes[ $size ][0] );
							update_option( $size . '_size_h', $image_sizes[ $size ][1] );
							update_option( $size . '_crop', $image_sizes[ $size ][2] );
						}
					}

				// NS Featured Posts

					self::ns_featured_posts();

		} // /before



		/**
		 * NS Featured Posts plugin options
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function ns_featured_posts() {

			// Helper variables

				$plugin_options = get_option( 'nsfp_plugin_options' );


			// Processing

				$plugin_options['nsfp_posttypes']['post'] = 1;
				$plugin_options['nsfp_posttypes']['jetpack-portfolio'] = 1;

				update_option( 'nsfp_plugin_options', $plugin_options );

		} // /ns_featured_posts



		/**
		 * After import actions
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  string $selected_import
		 */
		public static function after( $selected_import = '' ) {

			// Processing

				// Front and blog page

					self::front_and_blog_page();

				// Menu locations

					self::menu_locations();

		} // /after



		/**
		 * Setup front and blog page
		 *
		 * @since    2.0.0
		 * @version  2.0.2
		 */
		public static function front_and_blog_page() {

			// Processing

				update_option( 'show_on_front', 'page' );

				// Front page

					if ( $page = get_page_by_path( 'home' ) ) {
						update_option( 'page_on_front', $page->ID );
					}

				// Blog page

					if ( $page = get_page_by_path( 'blog' ) ) {
						update_option( 'page_for_posts', $page->ID );
					}

		} // /front_and_blog_page



		/**
		 * Setup navigation menu locations
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function menu_locations() {

			// Helper variables

				$menu            = array();
				$menu['primary'] = get_term_by( 'slug', 'primary', 'nav_menu' );
				$menu['social']  = get_term_by( 'slug', 'social-links', 'nav_menu' );


			// Processing

				set_theme_mod( 'nav_menu_locations', array(
					'primary' => ( isset( $menu['primary']->term_id ) ) ? ( $menu['primary']->term_id ) : ( null ),
					'social'  => ( isset( $menu['social']->term_id ) ) ? ( $menu['social']->term_id ) : ( null ),
				) );

		} // /menu_locations



		/**
		 * Remove all widgets from sidebars first
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function before_widgets_import() {

			// Processing

				delete_option( 'sidebars_widgets' );

		} // /before_widgets_import



		/**
		 * Enable Jetpack custom post types message
		 *
		 * This will display the message only if the "Custom content types"
		 * module is not active already.
		 * On page reload we attempt to activate the module automatically.
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  string $text  Default intro text.
		 */
		public static function jetpack_custom_posts( $text = '' ) {

			// Requirements check

				if (
					is_callable( 'Jetpack::is_module_active' )
					&& Jetpack::is_module_active( 'custom-content-types' )
				) {
					return $text;
				}


			// Processing

				$text .= '<div class="jetpack-info ocdi__demo-import-notice">';

					$text .= '<h3>';
					$text .= esc_html__( 'Jetpack Custom content types', 'modern' );
					$text .= '</h3>';

					$text .= '<p>';
					$text .= esc_html__( 'Please make sure your Jetpack plugin is connected and you have activated Testimonials and Portfolios "Custom content types" in Jetpack settings (navigate to Jetpack &rarr; Settings &rarr; Writing).', 'modern' );
					$text .= ' ' . esc_html__( 'If you do not activate these, the related demo content will not be imported.', 'modern' );
					$text .= '</p>';
					$text .= '<p>';
					$text .= '<em>';
					$text .= esc_html__( 'If your Jetpack plugin is connected, you may just try to reload this page and we will attempt to activate those custom content types for you automatically.', 'modern' );
					$text .= ' ';
					$text .= esc_html__( 'If the operation is successful, this message will disappear and you should see 2 new items in your WordPress dashboard menu: "Portfolio" and "Testimonials".', 'modern' );
					$text .= '</em>';
					$text .= '</p>';
					$text .= '<a href="" class="button">' . esc_html__( 'Reload this page &raquo;', 'modern' ) . '</a>';

				$text .= '</div>';

				// This will activate the post types automatically, but page reload is required.

					if ( is_callable( 'Jetpack::activate_module' ) ) {
						/**
						 * Fires before a module is activated.
						 *
						 * @param  string $module    Module slug.
						 * @param  bool   $exit      Should we exit after the module has been activated. Default to true.
						 * @param  bool   $redirect  Should the user be redirected after module activation? Default to true.
						 */
						Jetpack::activate_module( 'custom-content-types', false, false );
					}


			// Output

				return $text;

		} // /jetpack_custom_posts





	/**
	 * 100) Helpers
	 */

		/**
		 * OCDI plugin admin page styles
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function styles() {

			// Processing

				// OCDI 2.0 styling fix

					wp_add_inline_style(
						'ocdi-main-css',
						'.ocdi.about-wrap { max-width: 66em; }'
					);

		} // /styles





} // /Modern_One_Click_Demo_Import

add_action( 'after_setup_theme', 'Modern_One_Click_Demo_Import::init', 5 ); // Hook before plugin setup (see plugin code).
