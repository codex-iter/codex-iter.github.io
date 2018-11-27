<?php
/**
 * Jetpack: Infinite Scroll Class
 *
 * @link  https://jetpack.com/support/infinite-scroll/
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
 * 10) Setup
 */
class Modern_Jetpack_Infinite_Scroll {





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

			// Requirements check

				if ( ! Jetpack::is_active() && ! Jetpack::is_development_mode() ) {
					return;
				}


			// Processing

				// Setup

					add_theme_support( 'infinite-scroll', apply_filters( 'wmhook_modern_jetpack_setup_infinite_scroll', array(
						'container'      => 'posts',
						'footer'         => false,
						'posts_per_page' => 6,
						'render'         => __CLASS__ . '::infinite_scroll_render',
						'type'           => 'scroll',
						'wrapper'        => false,
					) ) );

				// Hooks

					// Filters

						add_filter( 'infinite_scroll_js_settings', __CLASS__ . '::infinite_scroll_js_settings' );

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
	 * 10) Setup
	 */

		/**
		 * Infinite scroll JS settings array modifier
		 *
		 * @since    1.0.0
		 * @version  2.0.0
		 *
		 * @param  array $settings
		 */
		public static function infinite_scroll_js_settings( $settings ) {

			// Helper variables

				$settings['text'] = esc_js( esc_html__( 'Load more&hellip;', 'modern' ) );


			// Output

				return $settings;

		} // /infinite_scroll_js_settings



		/**
		 * Infinite scroll posts renderer
		 *
		 * We use generic, global hook names in here, but passing
		 * a context/scope parameter you can check for.
		 *
		 * @see  __construct()
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function infinite_scroll_render() {

			// Output

				while ( have_posts() ) :

					the_post();

					/**
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 *
					 * Or, you can use the filter hook below to modify which content file to load.
					 */
					get_template_part(
						'template-parts/content/content',
						apply_filters( 'wmhook_modern_loop_content_type', get_post_format(), 'jetpack-infinite-scroll' )
					);

				endwhile;

		} // /infinite_scroll_render





} // /Modern_Jetpack_Infinite_Scroll

add_action( 'after_setup_theme', 'Modern_Jetpack_Infinite_Scroll::init' );
