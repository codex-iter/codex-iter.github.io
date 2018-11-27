<?php
/**
 * Loop Class
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.2.0
 *
 * Contents:
 *
 *   0) Init
 *  10) Pagination
 *  20) Search
 *  30) Archives
 * 100) Others
 */
class Modern_Loop {





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

			// Processing

				// Hooks

					// Actions

						add_action( 'wmhook_modern_postslist_after', __CLASS__ . '::pagination' );

						add_action( 'wmhook_modern_postslist_before', __CLASS__ . '::search_form' );

						// add_action( 'pre_get_posts', __CLASS__ . '::ignore_sticky_posts' );

					// Filters

						add_filter( 'get_the_archive_description', __CLASS__ . '::archive_author_description' );

						add_filter( 'navigation_markup_template', __CLASS__ . '::pagination_comments', 10, 2 );

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
	 * 10) Pagination
	 */

		/**
		 * Pagination
		 *
		 * @since    1.0.0
		 * @version  2.0.0
		 */
		public static function pagination() {

			// Requirements check

				// Don't display pagination if Jetpack Infinite Scroll in use

					if ( class_exists( 'The_Neverending_Home_Page' ) ) {
						return;
					}


			// Helper variables

				$output = '';

				$args = (array) apply_filters( 'wmhook_modern_pagination_args', array(
					'prev_text' => esc_html_x( '&laquo;', 'Pagination text (visible): previous.', 'modern' ) . '<span class="screen-reader-text"> '
					               . esc_html_x( 'Previous page', 'Pagination text (hidden): previous.', 'modern' ) . '</span>',
					'next_text' => '<span class="screen-reader-text">' . esc_html_x( 'Next page', 'Pagination text (hidden): next.', 'modern' )
					               . ' </span>' . esc_html_x( '&raquo;', 'Pagination text (visible): next.', 'modern' ),
				), 'loop' );


			// Processing

				if ( $output = paginate_links( $args ) ) {
					global $wp_query;

					$total   = ( isset( $wp_query->max_num_pages ) ) ? ( $wp_query->max_num_pages ) : ( 1 );
					$current = ( get_query_var( 'paged' ) ) ? ( absint( get_query_var( 'paged' ) ) ) : ( 1 );

					$output = '<nav class="pagination" aria-label="' . esc_attr( 'Posts Navigation', 'modern' ) . '" data-current="' . esc_attr( $current ) . '" data-total="' . esc_attr( $total ) . '">'
					          . $output
					          . '</nav>';
				}


			// Output

				echo $output;

		} // /pagination



		/**
		 * Parted post navigation
		 *
		 * Shim for passing the Theme Check review.
		 * Using table of contents generator instead.
		 *
		 * @see  Modern_Library::add_table_of_contents()
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function shim() {

			// Output

				wp_link_pages();

		} // /shim



		/**
		 * Comments pagination
		 *
		 * From simple next/previous links to full pagination.
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  string $template  The default template.
		 * @param  string $class     The class passed by the calling function.
		 */
		public static function pagination_comments( $template, $class ) {

			// Requirements check

				if ( 'comment-navigation' !== $class ) {
					return $template;
				}


			// Helper variables

				$args = (array) apply_filters( 'wmhook_modern_pagination_args', array(
					'prev_text' => esc_html_x( '&laquo;', 'Pagination text (visible): previous.', 'modern' ) . '<span class="screen-reader-text"> '
					               . esc_html_x( 'Previous page', 'Pagination text (hidden): previous.', 'modern' ) . '</span>',
					'next_text' => '<span class="screen-reader-text">' . esc_html_x( 'Next page', 'Pagination text (hidden): next.', 'modern' )
					               . ' </span>' . esc_html_x( '&raquo;', 'Pagination text (visible): next.', 'modern' ),
				), 'comments' );

				$pagination = paginate_comments_links( array_merge( $args, array( 'echo' => false ) ) );

				$total   = get_comment_pages_count();
				$current = ( get_query_var( 'cpage' ) ) ? ( absint( get_query_var( 'cpage' ) ) ) : ( 1 );


			// Processing

				// Modifying navigation wrapper classes

					$template = str_replace(
						'<nav class="navigation',
						'<nav class="navigation pagination comment-pagination',
						$template
					);

				// Adding responsive view HTML helper attributes

					$template = str_replace(
						'<nav',
						'<nav data-current="' . esc_attr( $current ) . '" data-total="' . esc_attr( $total ) . '"',
						$template
					);

				// Displaying pagination HTML in the template

					$template = str_replace(
						'<div class="nav-links">%3$s</div>',
						'<div class="nav-links">' . $pagination . '</div>',
						$template
					);


			// Output

				return $template;

		} // /pagination_comments





	/**
	 * 20) Search
	 */

		/**
		 * Output search form on top of search results page
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function search_form() {

			// Requirements check

				if ( ! is_search() ) {
					return;
				}


			// Output

				get_search_form( true );

		} // /search_form





	/**
	 * 30) Archives
	 */

		/**
		 * Author archive description
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  string $desc
		 */
		public static function archive_author_description( $desc = '' ) {

			// Requirements check

				if ( ! is_author() ) {
					return $desc;
				}


			// Output

				return apply_filters( 'the_content', get_the_author_meta( 'description' ) );

		} // /archive_author_description





	/**
	 * 100) Others
	 */

		/**
		 * Ignore sticky posts in main loop
		 *
		 * @since    1.0.0
		 * @version  2.0.0
		 *
		 * @param  obj $query
		 */
		public static function ignore_sticky_posts( $query ) {

			// Processing

				if (
					$query->is_home()
					&& $query->is_main_query()
				) {
					$query->set( 'ignore_sticky_posts', 1 );
				}

		} // /ignore_sticky_posts





} // /Modern_Loop

add_action( 'after_setup_theme', 'Modern_Loop::init' );
