<?php
/**
 * Jetpack: Customize Class
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
 * 10) Options
 */
class Modern_Jetpack_Customize {





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

						add_action( 'customize_register', __CLASS__ . '::setup' );

					// Filters

						add_filter( 'wmhook_modern_theme_options', __CLASS__ . '::options' );

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
	 * 10) Options
	 */

		/**
		 * Modify native WordPress options and setup partial refresh
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  object $wp_customize  WP customizer object.
		 */
		public static function setup( $wp_customize ) {

			// Processing

				// Partial refresh

					// Option pointers only

						// "Front page" page template sections options

							$wp_customize->selective_refresh->add_partial( 'layout_location_front_portfolio', array(
								'selector' => '.front-page-section-type-jetpack-portfolio .front-page-section-inner',
							) );

							$wp_customize->selective_refresh->add_partial( 'layout_location_front_testimonials', array(
								'selector' => '.front-page-section-type-jetpack-testimonial .front-page-section-inner',
							) );

		} // /setup



		/**
		 * Theme options addons and modifications
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  array $options
		 */
		public static function options( $options = array() ) {

			// Processing

				if ( is_callable( 'Modern_Customize::front_page_section_locations' ) ) {

					if ( post_type_exists( 'jetpack-portfolio' ) ) {
						$options = array_merge( $options, array(

							/**
							 * Layout / Front page template portfolio section
							 */
							300 . 'layout' . 300 => array(
								'type'    => 'html',
								'content' => '<h3><small>' . esc_html__( 'Front page:', 'modern' ) . '</small> ' . esc_html__( 'Portfolio section', 'modern' ) . '</h3><p class="description">' . esc_html__( 'Options for setting up portfolio section on "Front page" template.', 'modern' ) . '</p>',
							),

								300 . 'layout' . 310 => array(
									'type'    => 'select',
									'id'      => 'layout_location_front_portfolio',
									'label'   => esc_html__( 'Display location', 'modern' ),
									'choices' => Modern_Customize::front_page_section_locations(),
									'default' => 'tha_content_before|10',
								),

								300 . 'layout' . 320 => array(
									'type'    => 'range',
									'id'      => 'layout_posts_per_page_front_portfolio',
									'label'   => esc_html__( 'Posts count', 'modern' ),
									'default' => 6,
									'min'     => 0,
									'max'     => 12,
									'step'    => 1,
								),

						) );
					}

					if ( post_type_exists( 'jetpack-testimonial' ) ) {
						$options = array_merge( $options, array(

							/**
							 * Layout / Front page template testimonials section
							 */
							300 . 'layout' . 400 => array(
								'type'    => 'html',
								'content' => '<h3><small>' . esc_html__( 'Front page:', 'modern' ) . '</small> ' . esc_html__( 'Testimonials section', 'modern' ) . '</h3><p class="description">' . esc_html__( 'Options for setting up testimonials section on "Front page" template.', 'modern' ) . '</p>',
							),

								300 . 'layout' . 410 => array(
									'type'    => 'select',
									'id'      => 'layout_location_front_testimonials',
									'label'   => esc_html__( 'Display location', 'modern' ),
									'choices' => Modern_Customize::front_page_section_locations(),
									'default' => 'tha_content_after|10',
								),

								300 . 'layout' . 420 => array(
									'type'    => 'range',
									'id'      => 'layout_posts_per_page_front_testimonials',
									'label'   => esc_html__( 'Posts count', 'modern' ),
									'default' => 3,
									'min'     => 0,
									'max'     => 12,
									'step'    => 1,
								),

						) );
					}

				}


			// Output

				return $options;

		} // /options





} // /Modern_Jetpack_Customize

add_action( 'after_setup_theme', 'Modern_Jetpack_Customize::init' );
