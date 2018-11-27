<?php
/**
 * Custom Header / Intro Class
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.2
 *
 * Contents:
 *
 *  0) Init
 * 10) Setup
 * 20) Output
 * 30) Conditions
 * 40) Assets
 * 50) Slideshow
 */
class Modern_Intro {





	/**
	 * 0) Init
	 */

		private static $instance;



		/**
		 * Constructor
		 *
		 * @uses  `wmhook_modern_title_primary_disable` global hook to disable `#primary` section H1
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		private function __construct() {

			// Processing

				// Setup

					self::setup();

				// Hooks

					// Actions

						add_action( 'tha_header_after', __CLASS__ . '::container' );

						add_action( 'wmhook_modern_intro', __CLASS__ . '::content' );

						add_action( 'wmhook_modern_intro_after', __CLASS__ . '::media', -10 );

						add_action( 'wp', __CLASS__ . '::set_slideshow_display' );

					// Filters

						add_filter( 'wmhook_modern_intro_disable', __CLASS__ . '::disable', 5 );

						add_filter( 'theme_mod_' . 'header_image', __CLASS__ . '::image', 15 ); // Has to be priority 15 for correct customizer previews.

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
		 * Setup custom header
		 *
		 * @link  https://codex.wordpress.org/Function_Reference/add_theme_support#Custom_Header
		 * @link  https://make.wordpress.org/core/2016/11/26/video-headers-in-4-7/
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function setup() {

			// Helper variables

				$image_sizes = array_filter( apply_filters( 'wmhook_modern_setup_image_sizes', array() ) );


			// Processing

				add_theme_support( 'custom-header', apply_filters( 'wmhook_modern_custom_header_args', array(
					'default-text-color' => 'ffffff',
					'width'              => ( isset( $image_sizes['modern-intro'] ) ) ? ( $image_sizes['modern-intro'][0] ) : ( 1920 ),
					'height'             => ( isset( $image_sizes['modern-intro'] ) ) ? ( $image_sizes['modern-intro'][1] ) : ( 1080 ),
					'flex-width'         => true,
					'flex-height'        => true,
					'random-default'     => true,
				) ) );

				// Default custom headers packed with the theme

					$header_images = array(
						'pixabay.canada-407701.jpg',
						'pixabay.leaves-1345836.jpg',
						'pixabay.trees-2601040.jpg',
						'pixabay.trees-925682.jpg',
						'pixabay.winter-622126.jpg',
						'StockSnap_ZKBPKSFZ7I.jpg',
						'unsplash.colin-carter-75587.jpg',
					);

					$i = 0;
					foreach ( $header_images as $key => $image ) {
						$header_images[ $image ] = array(
							'url'           => '%s/assets/images/header/' . $image,
							'thumbnail_url' => '%s/assets/images/header/thumbnail/' . $image,
							'description'   => sprintf(
								/* translators: Custom header image description, %d: image sequential number. */
								esc_html__( 'Header image %d', 'modern' ),
								++$i
							),
						);
						unset( $header_images[ $key ] );
					}

					register_default_headers( $header_images );

		} // /setup





	/**
	 * 20) Output
	 */

		/**
		 * Container
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function container() {

			// Requirements check

				if ( self::is_disabled() ) {
					return;
				}


			// Processing

				get_template_part( 'template-parts/intro/intro', 'container' );

		} // /container



		/**
		 * Content
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function content() {

			// Helper variables

				$post_type = ( is_singular() ) ? ( get_post_type() ) : ( '' );


			// Processing

				get_template_part( 'template-parts/intro/intro-content', $post_type );

		} // /content



		/**
		 * Media
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function media() {

			// Helper variables

				$post_type = ( is_singular() ) ? ( get_post_type() ) : ( '' );


			// Processing

				get_template_part( 'template-parts/intro/intro-media', $post_type );

		} // /media





	/**
	 * 30) Conditions
	 */

		/**
		 * Is Intro disabled?
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function is_disabled() {

			// Output

				return (bool) apply_filters( 'wmhook_modern_intro_disable', false );

		} // /is_disabled



		/**
		 * Is Intro enabled?
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function is_enabled() {

			// Output

				return (bool) ! self::is_disabled();

		} // /is_enabled



		/**
		 * Disabling conditions
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  boolean $disable
		 */
		public static function disable( $disable = false ) {

			// Helper variables

				// Check if is_singular() to prevent issues in archives

					$meta_no_intro = ( is_singular() ) ? ( get_post_meta( get_the_ID(), 'no_intro', true ) ) : ( '' );


			// Output

				return ! empty( $meta_no_intro );

		} // /disable





	/**
	 * 40) Assets
	 */

		/**
		 * Header image URL
		 *
		 * @since    2.0.0
		 * @version  2.0.2
		 *
		 * @param  string $url  Image URL or other custom header value.
		 */
		public static function image( $url ) {

			// Requirements check

				$is_dedicated_blog_page = is_home() && ! is_front_page();

				if (
					! is_singular()
					&& ! $is_dedicated_blog_page
				) {
					return $url;
				}


			// Helper variables

				$image_size = 'modern-intro';
				$post_id    = ( $is_dedicated_blog_page ) ? ( get_option( 'page_for_posts' ) ) : ( null );

				if ( empty( $post_id ) ) {
					$post_id = get_the_ID();
				}

				// Using old name "banner_image" for backwards compatibility.
				$intro_image = trim( get_post_meta( $post_id, 'banner_image', true ) );


			// Processing

				if ( $intro_image ) {

					if ( is_numeric( $intro_image ) ) {
						$url = wp_get_attachment_image_src( absint( $intro_image ), $image_size );
						$url = $url[0];
					} else {
						$url = (string) $intro_image;
					}

				} elseif ( has_post_thumbnail( $post_id ) ) {

					$url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), $image_size );
					$url = $url[0];

				}


			// Output

				return $url;

		} // /image





	/**
	 * 50) Slideshow
	 */

		/**
		 * Set intro slideshow display
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function set_slideshow_display() {

			// Requirements check

				if (
					! is_front_page()
					|| ! self::get_slides_count()
				) {
					return;
				}


			// Processing

				remove_action( 'wmhook_modern_intro', __CLASS__ . '::content' );
				remove_action( 'wmhook_modern_intro_after', __CLASS__ . '::media', -10 );

				add_action( 'wmhook_modern_intro', __CLASS__ . '::content_slideshow' );
				add_action( 'wmhook_modern_intro_after', __CLASS__ . '::media_slideshow', -10 );

		} // /set_slideshow_display



		/**
		 * Display slideshow content
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function content_slideshow() {

			// Processing

				get_template_part( 'template-parts/intro/intro-content', 'slideshow' );

		} // /content_slideshow



		/**
		 * Display slideshow media
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function media_slideshow() {

			// Processing

				get_template_part( 'template-parts/intro/intro-media', 'slideshow' );

		} // /media_slideshow



		/**
		 * Get slides for slideshow
		 *
		 * Retrieves featured posts to populate slideshow.
		 * Compatible with Jetpack `featured-content` where we pass the
		 * "wmhook_modern_intro_get_slides" filter name into settings.
		 *
		 * @see  Modern_Jetpack_Setup::__construct()
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function get_slides() {

			// Output

				return (array) apply_filters( 'wmhook_modern_intro_get_slides', array() );

		} // /get_slides



		/**
		 * Get slides count
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function get_slides_count() {

			// Output

				return count( (array) self::get_slides() );

		} // /get_slides_count





} // /Modern_Intro

add_action( 'after_setup_theme', 'Modern_Intro::init' );
