<?php
/**
 * Jetpack: Content Options Class
 *
 * @link  https://jetpack.com/support/content-options/
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
 * 10) Author bio
 * 20) Featured images
 */
class Modern_Jetpack_Content_Options {





	/**
	 * 0) Init
	 */

		private static $instance;



		/**
		 * Constructor
		 *
		 * @since    2.0.0
		 * @version  2.2.0
		 */
		private function __construct() {

			// Requirements check

				if ( ! Jetpack::is_active() && ! Jetpack::is_development_mode() ) {
					return;
				}


			// Processing

				// Setup

					$content_options = array(
						'author-bio'   => true,
						'post-details' => array(
							'stylesheet' => 'modern',
							'categories' => '.cat-links',
							'comment'    => '.comments-link',
							'date'       => '.posted-on',
							'tags'       => '.tags-links',
						),
						'featured-images' => array(
							'archive'   => true,
							'post'      => true,
							'page'      => true,
							// No need to check for `jetpack-portfolio` CPT as this is done in Jetpack already.
							'portfolio' => true,
						),
					);

					if ( is_multi_author() ) {
						$content_options['post-details']['author'] = '.byline';
					}

					add_theme_support( 'jetpack-content-options', (array) apply_filters( 'wmhook_modern_jetpack_setup_content_options', $content_options ) );

				// Hooks

					// Actions

						add_action( 'tha_entry_bottom', __CLASS__ . '::author_bio' );

					// Filters

						add_filter( 'jetpack_author_bio_avatar_size', __CLASS__ . '::author_bio_avatar_size' );

						add_filter( 'body_class', __CLASS__ . '::featured_images_body_class' );

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
	 * 10) Author bio
	 */

		/**
		 * Display author bio
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function author_bio() {

			// Requirements check

				if (
					! function_exists( 'jetpack_author_bio' )
					|| ! Modern_Post::is_singular()
					|| post_password_required()
					|| ! in_array( get_post_type(), (array) apply_filters( 'wmhook_modern_jetpack_author_bio_post_type', array( 'post' ) ) )
				) {
					return;
				}


			// Output

				echo self::get_author_bio();

		} // /author_bio



		/**
		 * Get author bio HTML
		 *
		 * @since    2.0.0
		 * @version  2.2.0
		 *
		 * @param  boolean $remove_default_paragraph
		 */
		public static function get_author_bio( $remove_default_paragraph = true ) {

			// Requirements check

				if ( ! function_exists( 'jetpack_author_bio' ) ) {
					return;
				}


			// Processing

				ob_start();
				jetpack_author_bio();
				$output = ob_get_clean();

				if ( $remove_default_paragraph ) {
					$output = str_replace(
						array(
							'<p class="author-bio">',
							'</p><!-- .author-bio -->',
						),
						array(
							'<div class="author-bio">',
							'</div><!-- .author-bio -->',
						),
						$output
					);
				}


			// Output

				return $output;

		} // /get_author_bio



		/**
		 * Author bio avatar size
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function author_bio_avatar_size() {

			// Output

				return 240;

		} // /author_bio_avatar_size





	/**
	 * 20) Featured images
	 */

		/**
		 * Featured images display body classes
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  array $classes
		 */
		public static function featured_images_body_class( $classes = array() ) {

			// Requirements check

				if ( ! function_exists( 'jetpack_featured_images_get_settings' ) ) {
					return $classes;
				}


			// Helper variables

				$classes = (array) $classes; // Just in case...

				$supported_options = get_theme_support( 'jetpack-content-options' );
				$supported_options = ( ! empty( $supported_options[0]['featured-images'] ) ) ? ( $supported_options[0]['featured-images'] ) : ( array() );
				ksort( $supported_options );

				$featured_images_options = (array) jetpack_featured_images_get_settings();

			// Processing

				foreach ( $supported_options as $option => $enabled ) {
					if ( isset( $featured_images_options[ $option . '-option' ] ) ) {
						$status    = ( $featured_images_options[ $option . '-option' ] ) ? ( 'enabled' ) : ( 'disabled' );
						$classes[] = sanitize_html_class( 'jetpack-featured-images-' . $option . '-' . $status );
					}
				}


			// Output

				return $classes;

		} // /featured_images_body_class





} // /Modern_Jetpack_Content_Options

add_action( 'after_setup_theme', 'Modern_Jetpack_Content_Options::init' );
