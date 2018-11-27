<?php
/**
 * Post Class
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
 *  10) Setup
 *  20) Elements
 *  30) Templates
 * 100) Helpers
 */
class Modern_Post {





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

					// Post types supports

						add_post_type_support( 'page', 'excerpt' );

						add_post_type_support( 'attachment:audio', 'thumbnail' );
						add_post_type_support( 'attachment:video', 'thumbnail' );

						add_post_type_support( 'attachment', 'custom-fields' );

				// Hooks

					// Actions

						add_action( 'tha_entry_top', __CLASS__ . '::entry_content_container', 15 );

						add_action( 'tha_entry_top', __CLASS__ . '::title', 20 );
						add_action( 'tha_entry_top', __CLASS__ . '::meta_top', 30 );

						add_action( 'tha_entry_bottom', __CLASS__ . '::meta_bottom' );
						add_action( 'tha_entry_bottom', __CLASS__ . '::skip_links', 900 );
						add_action( 'tha_entry_bottom', __CLASS__ . '::entry_content_container', 910 );

						add_action( 'tha_content_bottom', __CLASS__ . '::navigation' );

						add_action( 'wp', __CLASS__ . '::template_front_display_blog' );

					// Filters

						add_filter( 'single_post_title', __CLASS__ . '::title_single', 10, 2 );

						add_filter( 'post_class', __CLASS__ . '::post_class', 98 );

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
		 * Post classes
		 *
		 * Compatible with NS Featured Posts plugin.
		 * @link  https://wordpress.org/plugins/ns-featured-posts/
		 *
		 * @since    1.3.0
		 * @version  2.0.0
		 *
		 * @param  array $classes
		 */
		public static function post_class( $classes ) {

			// Processing

				// A generic class for easy styling

					$classes[] = 'entry';

				// Sticky post

					/**
					 * On paginated posts list the sticky class is not
					 * being applied, so, we need to compensate.
					 */
					if ( is_sticky() ) {
						$classes[] = 'is-sticky';
					}


			// Output

				return $classes;

		} // /post_class





	/**
	 * 20) Elements
	 */

		/**
		 * Post/page heading (title)
		 *
		 * @uses  `wmhook_modern_title_primary_disable` global hook to disable `#primary` section H1
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  array $args Heading setup arguments
		 */
		public static function title( $args = array() ) {

			// Pre

				$disable = (bool) apply_filters( 'wmhook_modern_post_title_disable', false, $args );

				$pre = apply_filters( 'wmhook_modern_post_title_pre', $disable, $args );

				if ( false !== $pre ) {
					if ( true !== $pre ) {
						echo $pre;
					}
					return;
				}


			// Requirements check

				if ( ! ( $title = get_the_title() ) ) {
					return;
				}


			// Helper variables

				$output = '';

				$post_id     = get_the_ID();
				$is_singular = self::is_singular();

				$posts_heading_tag = ( isset( $args['helper']['atts']['heading_tag'] ) ) ? ( trim( $args['helper']['atts']['heading_tag'] ) ) : ( 'h2' );

				$args = wp_parse_args( $args, apply_filters( 'wmhook_modern_post_title_defaults', array(
					'addon'           => '',
					'class'           => 'entry-title',
					'class_container' => 'entry-header',
					'link'            => esc_url( get_permalink() ),
					'output'          => '<header class="{class_container}"><{tag} class="{class}">{title}</{tag}>{addon}</header>',
					'tag'             => ( $is_singular ) ? ( 'h1' ) : ( $posts_heading_tag ),
					'title'           => '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $title . '</a>',
				) ) );

				// Singular title (no link applied)

					if ( $is_singular ) {

						if ( $suffix = Modern_Library::get_the_paginated_suffix( 'small' ) ) {
							$args['title'] .= $suffix;
						} else {
							$args['title'] = $title;
						}

					}

				// Filter processed $args

					$args = apply_filters( 'wmhook_modern_post_title_args', $args );

				// Is this a primary title and should we display it?

					if (
						'h1' === $args['tag']
						&& apply_filters( 'wmhook_modern_title_primary_disable', false )
					) {
						return;
					}

				// Replacements

					$replacements = (array) apply_filters( 'wmhook_modern_post_title_replacements', array(
						'{addon}'           => $args['addon'],
						'{class}'           => esc_attr( $args['class'] ),
						'{class_container}' => esc_attr( $args['class_container'] ),
						'{tag}'             => tag_escape( $args['tag'] ),
						'{title}'           => do_shortcode( $args['title'] ),
					), $args );


			// Output

				echo strtr( $args['output'], $replacements );

		} // /title



		/**
		 * Single post title paged
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  string $title
		 * @param  object $post
		 */
		public static function title_single( $title, $post ) {

			// Requirements check

				if (
					doing_action( 'wp_head' )
					|| doing_action( 'tha_header_top' )
				) {
					return $title;
				}


			// Output

				return $title . Modern_Library::get_the_paginated_suffix( 'small' );

		} // /title_single



		/**
		 * Post meta top
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function meta_top() {

			// Output

				get_template_part( 'template-parts/meta/entry-meta', 'top' );

		} // /meta_top



		/**
		 * Post meta bottom
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function meta_bottom() {

			// Output

				get_template_part( 'template-parts/meta/entry-meta', 'bottom' );

		} // /meta_bottom



		/**
		 * Skip links: Entry bottom
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function skip_links() {

			// Requirements check

				if (
					! self::is_singular()
					|| ! get_the_content()
				) {
					return;
				}


			// Output

				echo Modern_Library::link_skip_to( 'site-navigation', esc_html__( 'Skip back to main navigation', 'modern' ), 'focus-position-static' );

		} // /skip_links



		/**
		 * Post navigation
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function navigation() {

			// Requirements check

				if (
					! ( is_single( get_the_ID() ) || is_attachment() )
					|| ! in_array( get_post_type(), (array) apply_filters( 'wmhook_modern_post_navigation_post_type', array( 'post', 'attachment' ) ) )
				) {
					return;
				}


			// Helper variables

				$post_type_labels = get_post_type_labels( get_post_type_object( get_post_type() ) );

				/**
				 * Can't really use `sprintf()` here due to translation error when
				 * translator decides not to use the `%s` in translated string.
				 */
				$args = array(

					'prev_text' => '<span class="label">' . str_replace(
						'$s',
						$post_type_labels->singular_name,
						esc_html_x( 'Previous $s', '$s: Custom post type singular label', 'modern' )
					) . '</span> <span class="title">%title</span>',

					'next_text' => '<span class="label">' . str_replace(
						'$s',
						$post_type_labels->singular_name,
						esc_html_x( 'Next $s', '$s: Custom post type singular label', 'modern' )
					) . '</span> <span class="title">%title</span>',

				);

				if ( is_attachment() ) {
					$args = array(
						'prev_text' => '<span class="label">' . esc_html__( 'Published in', 'modern' ) . '</span> <span class="title">%title</span>',
					);
				}

				$args = (array) apply_filters( 'wmhook_modern_post_navigation_args', $args );
				$args = wp_parse_args( $args, array(
					'prev_text'          => '%title',
					'next_text'          => '%title',
					'in_same_term'       => false,
					'excluded_terms'     => '',
					'taxonomy'           => 'category',
					'screen_reader_text' => esc_html__( 'Post navigation', 'modern' ),
				) );

				$styles = '';

				$posts = array(

					'previous' => get_adjacent_post(
						$args['in_same_term'],
						$args['excluded_terms'],
						true, // Previous?
						$args['taxonomy']
					),

					'next' => get_adjacent_post(
						$args['in_same_term'],
						$args['excluded_terms'],
						false, // Previous?
						$args['taxonomy']
					),

				);


			// Processing

				foreach ( $posts as $key => $post ) {
					if ( isset( $post->ID ) ) {
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'large' );
						if ( isset( $image[0] ) && $image[0] ) {
							$styles .= '.post-navigation .nav-' . sanitize_html_class( $key ) . ' a { background-image: url("' . esc_url( $image[0] ) . '"); }';
						}
					}
				}

				if ( $styles ) {
					$styles = (string) apply_filters( 'wmhook_modern_post_navigation_styles', '<style id="post-navigation-css" type="text/css">' . apply_filters( 'wmhook_modern_esc_css', $styles ) . '</style>' );
				}


			// Output

				echo str_replace(
					' role="navigation"',
					'',
					get_the_post_navigation( $args )
				) . $styles;

		} // /navigation



			/**
			 * Post navigation styles
			 *
			 * @uses  `wmhook_modern_esc_css` global hook
			 *
			 * @since    1.0.0
			 * @version  2.2.0
			 */
			public static function navigation_styles() {

				// Requirements check

					if ( ! is_single( get_the_ID() ) ) {
						return;
					}


				// Helper variables

					$output = $excluded_terms = '';

					$image_size = 'large';

					$post_navigation_args = (array) apply_filters( 'wmhook_modern_post_navigation_args', $args );
					if ( ! isset( $post_navigation_args['in_same_term'] ) ) {
						$post_navigation_args['in_same_term'] = false;
					}

					$previous = ( is_attachment() ) ? ( get_post( get_post()->post_parent ) ) : ( get_adjacent_post( $post_navigation_args['in_same_term'], $excluded_terms, true ) );
					$next     = get_adjacent_post( $post_navigation_args['in_same_term'], $excluded_terms, false );


				// Processing

					if ( $previous && has_post_thumbnail( $previous->ID ) ) {
						$image = wp_get_attachment_image_src(
							get_post_thumbnail_id( $previous->ID ),
							$image_size
						);
						$output .= "\r\n\t.post-navigation .nav-previous { background-image: url('" . esc_url_raw( $image[0] ) . "'); }";
					}

					if ( $next && has_post_thumbnail( $next->ID ) ) {
						$image = wp_get_attachment_image_src(
							get_post_thumbnail_id( $next->ID ),
							$image_size
						);
						$output .= "\r\n\t.post-navigation .nav-next { background-image: url('" . esc_url_raw( $image[0] ) . "'); }";
					}


				// Output

					if ( ! empty( $output ) ) {

						wp_add_inline_style(
							'modern',
							(string) apply_filters( 'wmhook_modern_esc_css', $output, 'Modern_Post::navigation_styles' )
						);

					}

			} // /navigation_styles



		/**
		 * Entry content container
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function entry_content_container() {

			// Output

				if ( doing_action( 'tha_entry_top' ) ) {
					echo '<div class="entry-content-container">';
				} else {
					echo '</div>';
				}

		} // /entry_content_container





	/**
	 * 40) Templates
	 */

		/**
		 * Page template: Front page
		 */

			/**
			 * Front page section: Blog
			 */

				/**
				 * Front page section: Blog: Display setup
				 *
				 * This has to be hooked as late as onto `wp` action so it works
				 * fine with customizer options.
				 *
				 * @since    2.0.0
				 * @version  2.2.0
				 */
				public static function template_front_display_blog() {

					// Helper variables

						$location = explode( '|', (string) Modern_Library_Customize::get_theme_mod( 'layout_location_front_blog' ) );
						if ( ! isset( $location[1] ) ) {
							$location[1] = 10;
						}

						if ( 1 > intval( Modern_Library_Customize::get_theme_mod( 'layout_posts_per_page_front_blog' ) ) ) {
							$location[0] = false;
						}


					// Processing

						if ( $location[0] ) {
							add_action(
								$location[0],
								__CLASS__ . '::template_front_loop_blog',
								$location[1]
							);
							add_action( 'wmhook_modern_postslist_before', __CLASS__ . '::template_front_title_blog' );
							add_action( 'wmhook_modern_postslist_after', __CLASS__ . '::template_front_link_blog' );
						}

				} // /template_front_display_blog



				/**
				 * Front page section: Blog: Loop
				 *
				 * @since    2.0.0
				 * @version  2.0.0
				 */
				public static function template_front_loop_blog() {

					// Output

						get_template_part( 'template-parts/loop/loop-front', 'blog' );

				} // /template_front_loop_blog



				/**
				 * Front page section: Blog: Title
				 *
				 * @since    2.0.0
				 * @version  2.0.0
				 *
				 * @param  string $context
				 */
				public static function template_front_title_blog( $context = '' ) {

					// Output

						if ( 'loop-front-blog.php' === $context ) {
							get_template_part( 'template-parts/component/title-front', 'blog' );
						}

				} // /template_front_title_blog



				/**
				 * Front page section: Blog: Archive link
				 *
				 * @since    1.0.0
				 * @version  2.0.0
				 *
				 * @param  string $context
				 */
				public static function template_front_link_blog( $context = '' ) {

					// Output

						if ( 'loop-front-blog.php' === $context ) {
							get_template_part( 'template-parts/component/link-front', 'blog' );
						}

				} // /template_front_link_blog



			/**
			 * Front page section: Portfolio
			 * Front page section: Testimonials
			 *
			 * @see  `includes/plugins/jetpack/class-jetpack-custom-post-types.php`
			 */





	/**
	 * 100) Helpers
	 */

		/**
		 * Boolean for checking if single post or page is displayed
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 *
		 * @param  int $post_id
		 */
		public static function is_singular( $post_id = 0 ) {

			// Helper variables

				$post_id = absint( $post_id );

				if ( ! $post_id ) {
					$post_id = get_the_ID();
				}


			// Output

				return ( is_page( $post_id ) || is_single( $post_id ) );

		} // /is_singular



		/**
		 * Boolean for checking if paged or parted
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function is_paged() {

			// Helper variables

				global $page, $paged;

				$paginated = max( absint( $page ), absint( $paged ) );


			// Output

				return 1 < $paginated;

		} // /is_paged



		/**
		 * Using some page builder?
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		public static function is_page_builder_ready() {

			// Requirements check

				if ( ! self::is_singular() ) {
					return false;
				}


			// Helper variables

				$content_layout = (string) get_post_meta( get_the_ID(), 'content_layout', true );


			// Output

				return in_array( $content_layout, array( 'stretched', 'no-padding' ) );

		} // /is_page_builder_ready



			/**
			 * Using some page builder?
			 *
			 * Return empty string if we do.
			 * Useful for `pre` filter hooks.
			 *
			 * @since    2.0.0
			 * @version  2.0.0
			 *
			 * @param  mixed $pre
			 */
			public static function is_page_builder_ready_maybe_return_empty_string( $pre ) {

				// Processing

					if ( self::is_page_builder_ready() ) {
						return '';
					}


				// Output

					return $pre;

			} // /is_page_builder_ready_maybe_return_empty_string





} // /Modern_Post

add_action( 'after_setup_theme', 'Modern_Post::init' );
