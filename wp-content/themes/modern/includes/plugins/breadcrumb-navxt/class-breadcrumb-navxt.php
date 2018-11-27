<?php
/**
 * Breadcrumb NavXT Class
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
 * 10) Frontend
 */
class Modern_Breadcrumb_NavXT {





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

						add_action( 'tha_content_top', __CLASS__ . '::breadcrumbs', 25 );

					// Filters

						add_filter( 'wmhook_modern_breadcrumb_navxt_disabled', 'is_front_page' );

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
	 * 10) Frontend
	 */

		/**
		 * Breadcrumbs
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function breadcrumbs() {

			// Output

				get_template_part( 'template-parts/component/breadcrumbs' );

		} // /breadcrumbs





} // /Modern_Breadcrumb_NavXT

add_action( 'after_setup_theme', 'Modern_Breadcrumb_NavXT::init' );
