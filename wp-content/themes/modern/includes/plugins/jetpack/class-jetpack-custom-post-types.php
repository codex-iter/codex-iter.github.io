<?php
/**
 * Jetpack: Custom Post Types Class
 *
 * @link  https://jetpack.com/support/custom-content-types/
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
 * 10) Setup
 * 20) Portfolio
 * 30) Testimonials
 */
class Modern_Jetpack_Custom_Post_Types {





	/**
	 * 0) Init
	 */

		private static $instance;



		/**
		 * Constructor
		 *
		 * @since    2.0.0
		 * @version  2.0.2
		 */
		private function __construct() {

			// Requirements check

				if ( ! Jetpack::is_active() && ! Jetpack::is_development_mode() ) {
					return;
				}


			// Processing

				// Setup

					add_theme_support( 'jetpack-portfolio' );

						add_post_type_support( 'jetpack-portfolio', array(
							'custom-fields',
							'excerpt',
							'post-formats',
						) );

					add_theme_support( 'jetpack-testimonial' );

						add_post_type_support( 'jetpack-testimonial', array(
							'custom-fields',
						) );

				// Hooks

					// Actions

						add_action( 'wmhook_modern_postslist_before', __CLASS__ . '::portfolio_taxonomy', 20 );

						add_action( 'wp', __CLASS__ . '::template_front_display_portfolio' );
						add_action( 'wp', __CLASS__ . '::template_front_display_testimonials' );

					// Filters

						add_filter( 'wmhook_modern_post_navigation_post_type',          __CLASS__ . '::add_post_types' );
						add_filter( 'wmhook_modern_summary_continue_reading_post_type', __CLASS__ . '::add_post_types' );

						add_filter( 'wmhook_modern_loop_content_type', __CLASS__ . '::content_type_testimonials' );

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
		 * Add support for Jetpack CPTs
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  array $post_types
		 */
		public static function add_post_types( $post_types ) {

			// Processing

				$post_types[] = 'jetpack-portfolio';
				$post_types[] = 'jetpack-testimonial';


			// Output

				return $post_types;

		} // /add_post_types





	/**
	 * 20) Portfolio
	 */

		/**
		 * Portfolio: Display custom taxonomy archives links
		 *
		 * @since    1.0.0
		 * @version  2.0.0
		 *
		 * @param  string $context
		 */
		public static function portfolio_taxonomy( $context = '' ) {

			// Requirements check

				if ( $context && 'loop-front-portfolio.php' !== $context ) {
					return;
				}


			// Helper variables

				$taxonomy = (string) apply_filters( 'wmhook_modern_jetpack_portfolio_taxonomy', 'jetpack-portfolio-type' );


			// Output

				if ( taxonomy_exists( $taxonomy ) ) {
					get_template_part( 'template-parts/component/list-terms', $taxonomy );
				}

		} // /portfolio_taxonomy



		/**
		 * Page template: Front page
		 */

			/**
			 * Front page section: Portfolio
			 */

				/**
				 * Front page section: Portfolio: Display setup
				 *
				 * This has to be hooked as late as onto `wp` action so it works
				 * fine with customizer options.
				 *
				 * @since    2.0.0
				 * @version  2.2.0
				 */
				public static function template_front_display_portfolio() {

					// Helper variables

						$location = explode( '|', (string) Modern_Library_Customize::get_theme_mod( 'layout_location_front_portfolio' ) );
						if ( ! isset( $location[1] ) ) {
							$location[1] = 10;
						}

						if ( 1 > intval( Modern_Library_Customize::get_theme_mod( 'layout_posts_per_page_front_portfolio' ) ) ) {
							$location[0] = false;
						}


					// Processing

						if ( $location[0] ) {
							add_action(
								$location[0],
								__CLASS__ . '::template_front_loop_portfolio',
								$location[1]
							);
							add_action( 'wmhook_modern_postslist_before', __CLASS__ . '::template_front_title_portfolio' );
							add_action( 'wmhook_modern_postslist_after', __CLASS__ . '::template_front_link_portfolio' );
						}

				} // /template_front_display_portfolio



				/**
				 * Front page section: Portfolio: Loop
				 *
				 * @since    2.0.0
				 * @version  2.0.0
				 */
				public static function template_front_loop_portfolio() {

					// Output

						get_template_part( 'template-parts/loop/loop-front', 'portfolio' );

				} // /template_front_loop_portfolio



				/**
				 * Front page section: Portfolio: Title
				 *
				 * @since    2.0.0
				 * @version  2.0.0
				 *
				 * @param  string $context
				 */
				public static function template_front_title_portfolio( $context = '' ) {

					// Output

						if ( 'loop-front-portfolio.php' === $context ) {
							get_template_part( 'template-parts/component/title-front', 'portfolio' );
						}

				} // /template_front_title_portfolio



				/**
				 * Front page section: Portfolio: Archive link
				 *
				 * @since    1.0.0
				 * @version  2.0.0
				 *
				 * @param  string $context
				 */
				public static function template_front_link_portfolio( $context = '' ) {

					// Output

						if ( 'loop-front-portfolio.php' === $context ) {
							get_template_part( 'template-parts/component/link-front', 'portfolio' );
						}

				} // /template_front_link_portfolio





	/**
	 * 30) Testimonials
	 */

		/**
		 * Testimonials: Content type to display
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  string $content_type
		 */
		public static function content_type_testimonials( $content_type = '' ) {

			// Processing

				if ( 'jetpack-testimonial' === get_post_type() ) {
					// Display "quote" post format content
					$content_type = 'quote';
				}


			// Output

				return $content_type;

		} // /content_type_testimonials



		/**
		 * Page template: Front page
		 */

			/**
			 * Front page section: Testimonials
			 */

				/**
				 * Front page section: Testimonials: Display setup
				 *
				 * This has to be hooked as late as onto `wp` action so it works
				 * fine with customizer options.
				 *
				 * @since    2.0.0
				 * @version  2.2.0
				 */
				public static function template_front_display_testimonials() {

					// Helper variables

						$location = explode( '|', (string) Modern_Library_Customize::get_theme_mod( 'layout_location_front_testimonials' ) );
						if ( ! isset( $location[1] ) ) {
							$location[1] = 10;
						}

						if ( 1 > intval( Modern_Library_Customize::get_theme_mod( 'layout_posts_per_page_front_testimonials' ) ) ) {
							$location[0] = false;
						}


					// Processing

						if ( $location[0] ) {
							add_action(
								$location[0],
								__CLASS__ . '::template_front_loop_testimonials',
								$location[1]
							);
							add_action( 'wmhook_modern_postslist_before', __CLASS__ . '::template_front_title_testimonials' );
							add_action( 'wmhook_modern_postslist_after', __CLASS__ . '::template_front_link_testimonials' );
						}

				} // /template_front_display_testimonials



				/**
				 * Front page section: Testimonials: Loop
				 *
				 * @since    2.0.0
				 * @version  2.0.0
				 */
				public static function template_front_loop_testimonials() {

					// Output

						get_template_part( 'template-parts/loop/loop-front', 'testimonials' );

				} // /template_front_loop_testimonials



				/**
				 * Front page section: Testimonials: Title
				 *
				 * @since    2.0.0
				 * @version  2.0.0
				 *
				 * @param  string $context
				 */
				public static function template_front_title_testimonials( $context = '' ) {

					// Output

						if ( 'loop-front-testimonials.php' === $context ) {
							get_template_part( 'template-parts/component/title-front', 'testimonials' );
						}

				} // /template_front_title_testimonials



				/**
				 * Front page section: Testimonials: Archive link
				 *
				 * @since    1.0.0
				 * @version  2.0.0
				 *
				 * @param  string $context
				 */
				public static function template_front_link_testimonials( $context = '' ) {

					// Output

						if ( 'loop-front-testimonials.php' === $context ) {
							get_template_part( 'template-parts/component/link-front', 'testimonials' );
						}

				} // /template_front_link_testimonials





} // /Modern_Jetpack_Custom_Post_Types

add_action( 'after_setup_theme', 'Modern_Jetpack_Custom_Post_Types::init' );
