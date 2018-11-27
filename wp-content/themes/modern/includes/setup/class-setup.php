<?php
/**
 * Theme Setup Class
 *
 * Theme options with `__` prefix (`get_theme_mod( '__option_id' )`) are theme
 * setup related options and can not be edited via customizer.
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 *
 * Contents:
 *
 *  0) Init
 * 10) Installation
 * 20) Setup
 * 30) Globals
 * 40) Images
 * 50) Typography
 * 60) Visual editor
 * 70) Others
 */
class Modern_Setup {





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

				// Setup

					self::content_width();

				// Hooks

					// Actions

						add_action( 'load-themes.php', __CLASS__ . '::admin_notice_welcome_activation' );

						add_action( 'after_setup_theme', __CLASS__ . '::setup' );

						add_action( 'after_setup_theme', __CLASS__ . '::visual_editor' );

						add_action( 'init', __CLASS__ . '::register_meta' );

						add_action( 'admin_init', __CLASS__ . '::image_sizes_notice' );

						add_action( 'admin_notices', __CLASS__ . '::admin_notice_upgrade', 100 );

						add_action( 'wmhook_modern_library_theme_upgrade', __CLASS__ . '::upgrade_child_theme', 10, 2 );

					// Filters

						add_filter( 'wmhook_modern_setup_image_sizes', __CLASS__ . '::image_sizes' );

						add_filter( 'wmhook_modern_assets_google_fonts_url_fonts_setup', __CLASS__ . '::google_fonts' );

						add_filter( 'wmhook_modern_library_editor_style_formats', __CLASS__ . '::visual_editor_formats' );

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
	 * 10) Installation
	 */

		/**
		 * Initiate "Welcome" admin notice
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function admin_notice_welcome_activation() {

			// Processing

				global $pagenow;

				if (
					is_admin()
					&& 'themes.php' == $pagenow
					&& isset( $_GET['activated'] )
				) {

					add_action( 'admin_notices', __CLASS__ . '::admin_notice_welcome', 99 );

				}

		} // /admin_notice_welcome_activation



		/**
		 * Display "Welcome" admin notice
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function admin_notice_welcome() {

			// Processing

				get_template_part( 'template-parts/admin/notice', 'welcome' );

		} // /admin_notice_welcome



		/**
		 * Display "Upgrade" admin notice(s)
		 *
		 * Note that these notices are displayed just once!
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function admin_notice_upgrade() {

			// Helper variables

				$notices = array_unique( array_filter( (array) get_transient( Modern_Customize::$transient_upgrade ) ) );


			// Processing

				if ( ! empty( $notices ) ) {
					asort( $notices );

					foreach ( $notices as $notice ) {
						get_template_part( 'template-parts/admin/notice-upgrade', $notice );
					}

					delete_transient( Modern_Customize::$transient_upgrade );
				}

		} // /admin_notice_upgrade



		/**
		 * Make sure we display child theme upgrade notice
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  string $version_old
		 * @param  string $version_new
		 */
		public static function upgrade_child_theme( $version_old, $version_new ) {

			// Requirements check

				if (
					! is_child_theme()
					|| empty( $version_old )
					|| version_compare( $version_old, '2.0.0', '>=' )
				) {
					return;
				}


			// Helper variables

				$upgrade_notice = (array) get_transient( Modern_Customize::$transient_upgrade );


			// Processing

				$upgrade_notice[] = 'child-theme'; // What admin notice to display?

				set_transient(
					Modern_Customize::$transient_upgrade,
					$upgrade_notice,
					7 * 24 * 60 * 60
				);

		} // /upgrade_child_theme





	/**
	 * 20) Setup
	 */

		/**
		 * Theme setup
		 *
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 *
		 * @since    1.0.0
		 * @version  2.0.0
		 */
		public static function setup() {

			// Helper variables

				$image_sizes   = array_filter( (array) apply_filters( 'wmhook_modern_setup_image_sizes', array() ) );
				$editor_styles = array_filter( (array) apply_filters( 'wmhook_modern_setup_editor_stylesheets', array() ) );


			// Processing

				// Localization

					/**
					 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
					 */

					// wp-content/languages/themes/modern/en_GB.mo

						load_theme_textdomain( 'modern', trailingslashit( WP_LANG_DIR ) . 'themes/' . get_template() );

					// wp-content/themes/child-theme/languages/en_GB.mo

						load_theme_textdomain( 'modern', get_stylesheet_directory() . '/languages' );

					// wp-content/themes/modern/languages/en_GB.mo

						load_theme_textdomain( 'modern', get_template_directory() . '/languages' );

				// Declare support for child theme stylesheet automatic enqueuing

					add_theme_support( 'child-theme-stylesheet' );

				// Add editor stylesheets

					add_editor_style( $editor_styles );

				// Custom menus

					/**
					 * @see  includes/frontend/class-menu.php
					 */

				// Title tag

					/**
					 * @link  https://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
					 */
					add_theme_support( 'title-tag' );

				// Site logo

					/**
					 * @link  https://codex.wordpress.org/Theme_Logo
					 */
					add_theme_support( 'custom-logo' );

				// Feed links

					/**
					 * @link  https://codex.wordpress.org/Function_Reference/add_theme_support#Feed_Links
					 */
					add_theme_support( 'automatic-feed-links' );

				// Enable HTML5 markup

					/**
					 * @link  https://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
					 */
					add_theme_support( 'html5', array(
						'caption',
						'comment-form',
						'comment-list',
						'gallery',
						'search-form',
					) );

				// Custom header

					/**
					 * @see  includes/custom-header/class-intro.php
					 */

				// Custom background

					/**
					 * @link  https://codex.wordpress.org/Function_Reference/add_theme_support#Custom_Background
					 */
					add_theme_support( 'custom-background', apply_filters( 'wmhook_modern_setup_custom_background_args', array(
						'default-color' => '1a1c1e',
					) ) );

				// Post formats

					/**
					 * @see  includes/frontend/class-post-media.php
					 */

				// Thumbnails support

					/**
					 * @link  https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
					 */
					add_theme_support( 'post-thumbnails', array( 'attachment:audio', 'attachment:video' ) );
					add_theme_support( 'post-thumbnails' );

					// Image sizes (x, y, crop)

						if ( ! empty( $image_sizes ) ) {
							foreach ( $image_sizes as $size => $setup ) {
								if ( ! in_array( $size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {

									add_image_size(
										$size,
										$image_sizes[ $size ][0],
										$image_sizes[ $size ][1],
										$image_sizes[ $size ][2]
									);

								}
							}
						}

				// Force-regenerate styles

					if ( get_transient( 'modern_regenerate_styles' ) ) {

						if ( is_callable( 'Modern_Library_Customize_Styles::generate_main_css_all' ) ) {
							Modern_Library_Customize_Styles::generate_main_css_all();
						}

						if ( is_callable( 'Modern_Library_Customize_Styles::custom_styles_cache_flush' ) ) {
							Modern_Library_Customize_Styles::custom_styles_cache_flush();
						}

						delete_transient( 'modern_regenerate_styles' );

					}

		} // /setup





	/**
	 * 30) Globals
	 */

		/**
		 * Set the content width in pixels, based on the theme's design and stylesheet
		 *
		 * Priority -100 to make it available to lower priority callbacks.
		 *
		 * @since    1.0.0
		 * @version  2.0.0
		 *
		 * @global  int $content_width
		 */
		public static function content_width() {

			// Processing

				$GLOBALS['content_width'] = absint( apply_filters( 'wmhook_modern_content_width', 1200 ) );

		} // /content_width





	/**
	 * 40) Images
	 */

		/**
		 * Image sizes
		 *
		 * @example
		 *
		 *   $image_sizes = array(
		 *     'image_size_id' => array(
		 *       absint( width ),
		 *       absint( height ),
		 *       (bool) cropped?,
		 *       (string) optional_theme_usage_explanation_text
		 *     )
		 *   );
		 *
		 * @since    1.2.2
		 * @version  2.0.0
		 *
		 * @param  array $image_sizes
		 */
		public static function image_sizes( $image_sizes = array() ) {

			// Helper variables

				global $content_width;


			// Processing

				$image_sizes = array(

					'thumbnail' => array(
						420,
						0,
						false,
						esc_html__( 'In posts list.', 'modern' ) . ' ' .
						esc_html__( 'You may want to rise this image size if you set posts to display in 2 columns.', 'modern' ),
					),

					'medium' => array(
						/**
						 * Ideally this should be 39em/70ch - the same as max entry content width.
						 * But this actually depends on the font family used.
						 */
						absint( $content_width * .62 ),
						0,
						false,
						esc_html__( 'Not used in the theme.', 'modern' ),
					),

					'large' => array(
						absint( $content_width ),
						0,
						false,
						esc_html__( 'In single post or page.', 'modern' ),
					),

					'modern-intro' => array(
						1920,
						1080,
						true,
						esc_html__( 'In intro section.', 'modern' ),
					),

				);


			// Output

				return $image_sizes;

		} // /image_sizes



		/**
		 * Register recommended image sizes notice
		 *
		 * @since    1.2.2
		 * @version  2.0.0
		 */
		public static function image_sizes_notice() {

			// Processing

				add_settings_field(
					// $id
					'recommended-image-sizes',
					// $title
					'',
					// $callback
					__CLASS__ . '::image_sizes_notice_html',
					// $page
					'media',
					// $section
					'default',
					// $args
					array()
				);

				register_setting(
					// $option_group
					'media',
					// $option_name
					'recommended-image-sizes',
					// $sanitize_callback
					'esc_attr'
				);

		} // /image_sizes_notice



		/**
		 * Display recommended image sizes notice
		 *
		 * @since    1.2.2
		 * @version  2.0.0
		 */
		public static function image_sizes_notice_html() {

			// Processing

				get_template_part( 'template-parts/admin/media', 'image-sizes' );

		} // /image_sizes_notice_html





	/**
	 * 50) Typography
	 */

		/**
		 * Google Fonts
		 *
		 * Custom fonts setup left for plugins.
		 *
		 * @since    1.3.0
		 * @version  2.0.0
		 *
		 * @param  array $fonts_setup
		 */
		public static function google_fonts( $fonts_setup ) {

			// Helper variables

				$fonts_setup = array_unique( array_filter( array(
					get_theme_mod( 'font-family-body' ),
					get_theme_mod( 'font-family-headings' ),
					get_theme_mod( 'font-family-logo' ),
				) ) );


			// Processing

				if ( empty( $fonts_setup ) ) {
					/**
					 * translators: Do not translate into your own language!
					 * If there are characters in your language that are not
					 * supported by the font, translate this to 'off'.
					 * The font will not load then.
					 */
					if ( 'off' !== esc_html_x( 'on', 'Fira Sans font: on or off', 'modern' ) ) {
						$fonts_setup[] = 'Fira Sans:400,300';
					}
				}


			// Output

				return $fonts_setup;

		} // /google_fonts





	/**
	 * 60) Visual editor
	 */

		/**
		 * Include Visual Editor (TinyMCE) setup
		 *
		 * @since    1.2.0
		 * @version  2.0.0
		 */
		public static function visual_editor() {

			// Processing

				if (
					is_admin()
					|| isset( $_GET['fl_builder'] )
				) {

					require_once MODERN_LIBRARY . 'includes/classes/class-visual-editor.php';

				}

		} // /visual_editor



		/**
		 * TinyMCE "Formats" dropdown alteration
		 *
		 * @since    1.2.0
		 * @version  2.0.0
		 *
		 * @param  array $formats
		 */
		public static function visual_editor_formats( $formats ) {

			// Processing

				// Font weight classes

					$font_weights = array(

						// Font weight names from https://developer.mozilla.org/en/docs/Web/CSS/font-weight

						100 => esc_html__( 'Thin (hairline) text', 'modern' ),
						200 => esc_html__( 'Extra light text', 'modern' ),
						300 => esc_html__( 'Light text', 'modern' ),
						400 => esc_html__( 'Normal weight text', 'modern' ),
						500 => esc_html__( 'Medium text', 'modern' ),
						600 => esc_html__( 'Semi bold text', 'modern' ),
						700 => esc_html__( 'Bold text', 'modern' ),
						800 => esc_html__( 'Extra bold text', 'modern' ),
						900 => esc_html__( 'Heavy text', 'modern' ),

					);

					$formats[ 250 . 'text_weights' ] = array(
						'title' => esc_html__( 'Text weights', 'modern' ),
						'items' => array(),
					);

					foreach ( $font_weights as $weight => $name ) {

						$formats[ 250 . 'text_weights' ]['items'][ 250 . 'text_weights' . $weight ] = array(
							'title'    => $name . ' (' . $weight . ')',
							'selector' => 'p, h1, h2, h3, h4, h5, h6, address, blockquote',
							'classes'  => 'weight-' . $weight,
						);

					} // /foreach

				// Font classes

					$formats[ 260 . 'font' ] = array(
						'title' => esc_html__( 'Fonts', 'modern' ),
						'items' => array(

							100 . 'font' . 100 => array(
								'title'    => esc_html__( 'General font', 'modern' ),
								'selector' => 'p, h1, h2, h3, h4, h5, h6, address, blockquote',
								'classes'  => 'font-body',
							),

							100 . 'font' . 110 => array(
								'title'    => esc_html__( 'Headings font', 'modern' ),
								'selector' => 'p, h1, h2, h3, h4, h5, h6, address, blockquote',
								'classes'  => 'font-headings',
							),

							100 . 'font' . 120 => array(
								'title'    => esc_html__( 'Logo font', 'modern' ),
								'selector' => 'p, h1, h2, h3, h4, h5, h6, address, blockquote',
								'classes'  => 'font-logo',
							),

							100 . 'font' . 130 => array(
								'title'    => esc_html__( 'Inherit font', 'modern' ),
								'selector' => 'p, h1, h2, h3, h4, h5, h6, address, blockquote',
								'classes'  => 'font-inherit',
							),

						),
					);

				// Columns styles

					$formats[ 400 . 'columns' ] = array(
						'title' => esc_html__( 'Columns', 'modern' ),
						'items' => array(),
					);

					for ( $i = 2; $i <= 2; $i++ ) {

						$formats[ 400 . 'columns' ]['items'][ 400 . 'columns' . ( 100 + 10 * $i ) ] = array(
							'title'   => sprintf( esc_html( _nx( 'Text in %d column', 'Text in %d columns', $i, '%d: Number of columns.', 'modern' ) ), $i ),
							'classes' => 'text-columns-' . $i,
							'block'   => 'div',
							'wrapper' => true,
						);

					}

				// Buttons

					$formats[ 500 . 'buttons' ] = array(
						'title' => esc_html__( 'Buttons', 'modern' ),
						'items' => array(

							500 . 'buttons' . 100 => array(
								'title'    => esc_html__( 'Button from link', 'modern' ),
								'selector' => 'a',
								'classes'  => 'button',
							),

						),
					);


			// Output

				return $formats;

		} // /visual_editor_formats





	/**
	 * 70) Others
	 */

		/**
		 * Set transient to force styles regeneration
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function regenerate_styles() {

			// Processing

				set_transient( 'modern_regenerate_styles', true, 2 * 60 * 60 );

		} // /regenerate_styles



		/**
		 * Register post meta
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function register_meta() {

			// Processing

				register_meta( 'post', 'content_layout', 'esc_attr' );
				register_meta( 'post', 'quote_source', 'esc_html' );
				// Using old name "banner_image" and "banner_text" for backwards compatibility.
				register_meta( 'post', 'banner_image', 'esc_attr' );
				register_meta( 'post', 'banner_text', 'esc_html' );

		} // /register_meta





} // /Modern_Setup

add_action( 'after_setup_theme', 'Modern_Setup::init', -100 ); // Low priority for early $content_width setup
