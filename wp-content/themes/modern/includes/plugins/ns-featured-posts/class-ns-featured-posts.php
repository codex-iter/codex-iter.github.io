<?php
/**
 * NS Featured Posts Class
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
 * 10) Featured posts
 */
class Modern_NS_Featured_Posts {





	/**
	 * 0) Init
	 */

		private static $instance;

		private static $supported_post_types = array();



		/**
		 * Constructor
		 *
		 * @since    2.0.0
		 * @version  2.2.0
		 */
		private function __construct() {

			// Helper variables

				$plugin_options = get_option( 'nsfp_plugin_options' );

				if ( isset( $plugin_options['nsfp_posttypes'] ) ) {
					foreach ( (array) $plugin_options['nsfp_posttypes'] as $post_type => $enabled ) {
						if ( $enabled && post_type_exists( $post_type ) ) {
							self::$supported_post_types[] = $post_type;
						}
					}
				}


			// Processing

				// Hooks

					// Filters

						add_filter( 'wmhook_modern_intro_get_slides', __CLASS__ . '::get_posts', 20 ); // After Jetpack featured content to override it.

						add_filter( 'post_class', __CLASS__ . '::post_class', 100 );

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
	 * 10) Featured posts
	 */

		/**
		 * Get featured posts in array
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  array $featured_posts
		 */
		public static function get_posts( $featured_posts = array() ) {

			// Output

				if ( ! empty( self::$supported_post_types ) ) {

					return get_posts( (array) apply_filters( 'wmhook_modern_ns_featured_posts_get_posts_args', array(
						'numberposts' => 6,
						'post_type'   => (array) self::$supported_post_types,
						'meta_key'    => '_is_ns_featured_post',
						'meta_value'  => 'yes',
					) ) );

				} else {

					return $featured_posts;

				}

		} // /get_posts



		/**
		 * Post classes
		 *
		 * @since    1.3.0
		 * @version  2.0.0
		 *
		 * @param  array $classes
		 */
		public static function post_class( $classes ) {

			// Processing

				$post_id = get_the_ID();

				if (
					in_array( get_post_type( $post_id ), (array) self::$supported_post_types )
					|| get_post_meta( $post_id, '_is_ns_featured_post', true )
				) {
					$classes[] = 'is-featured';
				}


			// Output

				return $classes;

		} // /post_class





} // /Modern_NS_Featured_Posts

add_action( 'init', 'Modern_NS_Featured_Posts::init' ); // Hooking late, after CPTs are registered.
