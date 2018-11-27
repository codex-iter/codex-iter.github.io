<?php
/**
 * Theme Starter Content Class
 *
 * @link  https://make.wordpress.org/core/2016/11/30/starter-content-for-themes-in-4-7/
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
 * 10) Content
 * 20) Partials
 */
class Modern_Starter_Content {





	/**
	 * 0) Init
	 */

		private static $content;



		/**
		 * Constructor
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		private function __construct() {}





	/**
	 * 10) Content
	 */

		/**
		 * Theme starter content
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function content() {

			// Processing

				// Loading

					self::posts();

					self::attachments();

					self::options();

					self::nav_menus();

					self::widgets();

				// Setup

					if ( ! empty( self::$content ) ) {
						add_theme_support( 'starter-content', self::$content );
					}

		} // /content





	/**
	 * 20) Partials
	 */

		/**
		 * Pages
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function posts() {

			// Output

				self::$content['posts'] = array(

					'home' => array(
						'post_title' => esc_html_x( 'Welcome to Modern WordPress theme!', 'Theme starter content', 'modern' ),
						'template'   => 'page-template/_front.php',
						'thumbnail'  => '{{image-arch}}',
					),

					'about' => array(
						'template'  => 'page-template/_sidebar.php',
						'thumbnail' => '{{image-leaves}}',
					),

					'contact' => array(
						'thumbnail' => '{{image-road}}',
					),

					'blog',

				);

		} // /posts



		/**
		 * Attachments
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function attachments() {

			// Helper variables

				$post_title = esc_html_x( 'Please see Settings &rarr; Media for the best image sizes.', 'Theme starter content', 'modern' );


			// Output

				self::$content['attachments'] = array(

					'image-arch' => array(
						'post_title' => $post_title,
						'file'       => 'assets/images/header/unsplash.colin-carter-75587.jpg',
					),

					'image-leaves' => array(
						'post_title' => $post_title,
						'file'       => 'assets/images/header/pixabay.leaves-1345836.jpg',
					),

					'image-road' => array(
						'post_title' => $post_title,
						'file'       => 'assets/images/header/pixabay.winter-622126.jpg',
					),

				);

		} // /attachments



		/**
		 * WordPress options
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function options() {

			// Output

				self::$content['options'] = array(

					// Reading

						'show_on_front'  => 'page',
						'page_on_front'  => '{{home}}',
						'page_for_posts' => '{{blog}}',
						'posts_per_page' => 6,

					// Media

						'thumbnail_size_w' => 420,
						'thumbnail_size_h' => 0,
						'thumbnail_crop'   => 0,

						'medium_size_w' => 744,
						'medium_size_h' => 0,

						'large_size_w' => 1200,
						'large_size_h' => 0,

					// Permalinks

						'permalink_structure' => '/%postname%/',

				);

		} // /options



		/**
		 * Navigational menus
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function nav_menus() {

			// Output

				self::$content['nav_menus'] = array(

					'primary' => array(
						'name'  => esc_html_x( 'Primary Menu', 'Theme starter content', 'modern' ),
						'items' => array(

							'page_home' => array(
								'title' => esc_html_x( 'Home', 'Short for "documentation". Theme starter content.', 'modern' ),
							),

							'page_about',

							'link_documentation_portfolio' => array(
								'title' => esc_html_x( 'Portfolio', 'Short for "documentation". Theme starter content.', 'modern' ),
								'url'   => 'https://webmandesign.github.io/docs/modern/#portfolio',
							),

							'link_documentation_testimonials' => array(
								'title' => esc_html_x( 'Testimonials', 'Short for "documentation". Theme starter content.', 'modern' ),
								'url'   => 'https://webmandesign.github.io/docs/modern/#testimonials',
							),

							'page_blog',
							'page_contact',

							'link_documentation' => array(
								'title' => esc_html_x( 'Documentation', 'Short for "documentation". Theme starter content.', 'modern' ),
								'url'   => 'https://webmandesign.github.io/docs/modern',
							),

						),
					),

					'social' => array(
						'name'  => esc_html_x( 'Social Links Menu', 'Theme starter content', 'modern' ),
						'items' => array(

							'link_facebook' => array(
								'title' => esc_html_x( 'Facebook', 'Theme starter content', 'modern' ),
								'url'   => 'https://www.facebook.com/',
							),

							'link_twitter' => array(
								'title' => esc_html_x( 'Twitter', 'Theme starter content', 'modern' ),
								'url'   => 'https://twitter.com/',
							),

							'link_email',

						),
					),

				);

		} // /nav_menus



		/**
		 * Widgets
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function widgets() {

			// Output

				self::$content['widgets'] = array(

					'sidebar' => array(

						'text_sidebar' => array(
							'text',
							array(
								'title' => esc_html_x( 'Remove sidebar', 'Theme starter content', 'modern' ),
								'text'  => esc_html_x( 'To remove sidebar and use fullwidth layout for posts, pages and archive pages, you can just remove all the widgets from this sidebar.', 'Theme starter content', 'modern' ),
							),
						),

					),

					'footer' => array(

						'text_business_info',

						'text_empty' => array(
							'text',
							array(
								'title' => '',
								'text'  => '',
							),
						),

						'text_about',

					),

				);

		} // /widgets





} // /Modern_Starter_Content

add_action( 'after_setup_theme', 'Modern_Starter_Content::content' );
