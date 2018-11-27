<?php
/**
 * Header Class
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.2.0
 *
 * Contents:
 *
 *  0) Init
 * 10) HTML head
 * 20) Body start
 * 30) Site header
 * 40) Setup
 */
class Modern_Header {





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

						add_action( 'tha_html_before', __CLASS__ . '::doctype' );

						add_action( 'wp_head', __CLASS__ . '::head', 1 );
						add_action( 'wp_head', __CLASS__ . '::head_pingback', 1 );
						add_action( 'wp_head', __CLASS__ . '::head_chrome_color', 1 );

						add_action( 'tha_body_top', __CLASS__ . '::oldie', 5 );
						add_action( 'tha_body_top', __CLASS__ . '::site_open' );
						add_action( 'tha_body_top', __CLASS__ . '::skip_links' );

						add_action( 'tha_header_top', __CLASS__ . '::open', 1 );
						add_action( 'tha_header_top', __CLASS__ . '::open_inner', 5 );
						add_action( 'tha_header_top', __CLASS__ . '::site_branding' );

						add_action( 'tha_header_bottom', __CLASS__ . '::close_inner', 1 );
						add_action( 'tha_header_bottom', __CLASS__ . '::close', 101 );

					// Filters

						add_filter( 'body_class', __CLASS__ . '::body_class', 98 );

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
	 * 10) HTML head
	 */

		/**
		 * HTML DOCTYPE
		 *
		 * @since    1.0.0
		 * @version  2.0.0
		 */
		public static function doctype() {

			// Output

				echo '<!doctype html>';

		} // /doctype



		/**
		 * HTML HEAD
		 *
		 * @since    1.0.0
		 * @version  2.0.0
		 */
		public static function head() {

			// Processing

				get_template_part( 'template-parts/header/head' );

		} // /head



		/**
		 * Add a pingback url auto-discovery header for singularly identifiable articles
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function head_pingback() {

			// Output

				if ( is_singular() && pings_open() ) {
					echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
				}

		} // /head_pingback



		/**
		 * Chrome theme color with support for Chrome Theme Color Changer plugin
		 *
		 * @see  https://wordpress.org/plugins/chrome-theme-color-changer
		 *
		 * @since    2.0.0
		 * @version  2.2.0
		 */
		public static function head_chrome_color() {

			// Output

				if ( ! class_exists( 'Chrome_Theme_Color_Changer' ) ) {
					echo '<meta name="theme-color" content="' . esc_attr( Modern_Library_Customize::get_theme_mod( 'color_header_background' ) ) . '">';
				}

		} // /head_chrome_color





	/**
	 * 20) Body start
	 */

		/**
		 * IE upgrade message
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function oldie() {

			// Requirements check

				if ( ! isset( $GLOBALS['is_IE'] ) || ! $GLOBALS['is_IE'] ) {
					return;
				}


			// Processing

				get_template_part( 'template-parts/component', 'oldie' );

		} // /oldie



		/**
		 * Skip links: Body top
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function skip_links() {

			// Output

				echo '<ul class="skip-link-list">'
				     . '<li class="skip-link-list-item">'
				     . Modern_Library::link_skip_to( 'site-navigation', __( 'Skip to main navigation', 'modern' ) )
				     . '</li>'
				     . '<li class="skip-link-list-item">'
				     . Modern_Library::link_skip_to( 'content' )
				     . '</li>'
				     . '<li class="skip-link-list-item">'
				     . Modern_Library::link_skip_to( 'colophon', __( 'Skip to footer', 'modern' ) )
				     . '</li>'
				     . '</ul>';

		} // /skip_links



		/**
		 * Site container: Open
		 *
		 * @since    1.0.0
		 * @version  2.0.0
		 */
		public static function site_open() {

			// Output

				echo '<div id="page" class="site">' . "\r\n";

		} // /site_open





	/**
	 * 30) Site header
	 *
	 * Header menu:
	 * @see  includes/frontend/class-menu.php
	 */

		/**
		 * Header: Open
		 *
		 * @since    1.0.0
		 * @version  2.0.0
		 */
		public static function open() {

			// Output

				echo "\r\n\r\n" . '<header id="masthead" class="site-header">' . "\r\n\r\n";

		} // /open



		/**
		 * Header: Close
		 *
		 * @since    1.0.0
		 * @version  2.0.0
		 */
		public static function close() {

			// Output

				echo "\r\n\r\n" . '</header>' . "\r\n\r\n";

		} // /close



		/**
		 * Header inner container: Open
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function open_inner() {

			// Output

				echo "\r\n\r\n" . '<div class="site-header-content"><div class="site-header-inner">' . "\r\n\r\n";

		} // /open_inner



		/**
		 * Header inner container: Close
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function close_inner() {

			// Output

				echo "\r\n\r\n" . '</div></div>' . "\r\n\r\n";

		} // /close_inner



		/**
		 * Logo, site branding
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function site_branding() {

			// Output

				get_template_part( 'template-parts/header/site', 'branding' );

		} // /site_branding





	/**
	 * 40) Setup
	 */

		/**
		 * HTML Body classes
		 *
		 * @since    1.0.0
		 * @version  2.2.0
		 *
		 * @param  array $classes
		 */
		public static function body_class( $classes = array() ) {

			// Helper variables

				$classes = (array) $classes; // Just in case...


			// Processing

				// JS fallback

					$classes[] = 'no-js';

				// Footer layout

					// Enable footer masonry layout?

						if ( is_customize_preview() ) {
							$classes[] = 'has-masonry-footer';
						} else {
							$footer_widgets = wp_get_sidebars_widgets();
							if (
								isset( $footer_widgets['footer'] )
								&& 3 < count( (array) $footer_widgets['footer'] )
							) {
								$classes[] = 'has-masonry-footer';
							}
						}

				// Is mobile navigation enabled?

					if ( Modern_Library_Customize::get_theme_mod( 'navigation_mobile' ) ) {
						$classes[] = 'has-navigation-mobile';
					}

				// Is site branding text displayed?

					if ( 'blank' === get_header_textcolor() ) {
						$classes[] = 'site-title-hidden';
					}

				// Singular?

					if ( is_singular() ) {
						$classes[] = 'is-singular';

						$post_id = get_the_ID();

						// Privacy Policy page

							if ( (int) get_option( 'wp_page_for_privacy_policy' ) === $post_id ) {
								$classes[] = 'page-privacy-policy';
							}

						// Has featured image?

							if ( has_post_thumbnail() ) {
								$classes[] = 'has-post-thumbnail';
							}

						// Has custom intro image?

							// Using old name "banner_image" for backwards compatibility.
							if ( get_post_meta( $post_id, 'banner_image', true ) ) {
								$classes[] = 'has-custom-banner-image';
							}

						// Any page builder layout

							$content_layout = (string) get_post_meta( $post_id, 'content_layout', true );

							if ( 'stretched' === $content_layout ) {
								$classes[] = 'content-layout-no-paddings';
								$classes[] = 'content-layout-stretched';
							} elseif ( 'no-paddings' === $content_layout ) {
								$classes[] = 'content-layout-no-paddings';
							}

					} else {

						// Add a class of hfeed to non-singular pages

							$classes[] = 'hfeed';

					}

				// Has more than 1 published author?

					if ( is_multi_author() ) {
						$classes[] = 'group-blog';
					}

				// Intro displayed?

					if ( ! (bool) apply_filters( 'wmhook_modern_intro_disable', false ) ) {
						$classes[] = 'has-intro';
					} else {
						$classes[] = 'no-intro';
					}

				// Widget areas

					foreach ( (array) apply_filters( 'wmhook_modern_header_body_classes_sidebars', array() ) as $sidebar ) {
						if ( ! is_active_sidebar( $sidebar ) ) {
							$classes[] = 'no-widgets-' . $sidebar;
						} else {
							$classes[] = 'has-widgets-' . $sidebar;
						}
					}

				// Posts layout

					$classes[] = 'posts-layout-columns-' . absint( Modern_Library_Customize::get_theme_mod( 'layout_posts_columns' ) );

					$classes[] = sanitize_title( 'has-posts-layout-' . Modern_Library_Customize::get_theme_mod( 'layout_posts' ) );

				// Enable intro slideshow?

					if (
						is_front_page()
						&& is_callable( 'Modern_Intro::get_slides_count' )
						&& 1 < Modern_Intro::get_slides_count()
					) {
						$classes[] = 'has-intro-slideshow';
					}


			// Output

				asort( $classes );

				return array_unique( $classes );

		} // /body_class





} // /Modern_Header

add_action( 'after_setup_theme', 'Modern_Header::init' );
