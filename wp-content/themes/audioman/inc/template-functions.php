<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Audioman
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Audioman 1.0
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
function audioman_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Always add a front-page class to the front page.
	if ( is_front_page() && ! is_home() ) {
		$classes[] = 'page-template-front-page';
	}

		$classes[] = 'fluid-layout';
	
		$classes[] = 'navigation-classic';

	// Adds a class with respect to layout selected.
	$layout  = audioman_get_theme_layout();
	$sidebar = audioman_get_sidebar_id();

	$layout_class = "no-sidebar content-width-layout";

	if ( 'no-sidebar-full-width' === $layout ) {
		$layout_class = 'no-sidebar full-width-layout';
	} elseif ( 'left-sidebar' === $layout ) {
		if ( '' !== $sidebar ) {
			$layout_class = 'two-columns-layout content-right';
		}
	} elseif ( 'right-sidebar' === $layout ) {
		if ( '' !== $sidebar ) {
			$layout_class = 'two-columns-layout content-left';
		}
	}

	$classes[] = $layout_class;

	$classes[] = 'excerpt-image-top';

	$classes[] = 'header-media-fluid';

	$enable_slider = audioman_check_section( get_theme_mod( 'audioman_slider_option', 'disabled' ) );


	$enable_breadcrumb = get_theme_mod( 'audioman_breadcrumb_option', 1 );

	$enable_header_media = true;
	$header_image        = audioman_featured_overall_image();

	if ( 'disable' === $header_image ) {
		$enable_header_media = false;
	} else {
		$classes[] = 'has-header-media absolute-header';
	}

	if ( ! audioman_has_header_media_text() ) {
		$classes[] = 'header-media-text-disabled';
	}

	// Add a class if there is a custom header.
	if ( has_header_image() ) {
		$classes[] = 'has-header-image';
	}

	return $classes;
}
add_filter( 'body_class', 'audioman_body_classes' );

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function audioman_pingback_header() {
	if ( is_singular() && pings_open() ) {
		echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
	}
}
add_action( 'wp_head', 'audioman_pingback_header' );

/**
 * Remove first post from blog as it is already show via recent post template
 */
function audioman_alter_home( $query ) {
	if ( $query->is_home() && $query->is_main_query() ) {
		$cats = get_theme_mod( 'audioman_front_page_category' );

		if ( is_array( $cats ) && ! in_array( '0', $cats ) ) {
			$query->query_vars['category__in'] = $cats;
		}
	}
}
add_action( 'pre_get_posts', 'audioman_alter_home' );

/**
 * Function to add Scroll Up icon
 */
function audioman_scrollup() {
	$disable_scrollup = get_theme_mod( 'audioman_disable_scrollup' );

	if ( $disable_scrollup ) {
		return;
	}

	echo '<a href="#masthead" id="scrollup" class="backtotop">' . '<span class="screen-reader-text">' . esc_html__( 'Scroll Up', 'audioman' ) . '</span></a>' ;

}
add_action( 'wp_footer', 'audioman_scrollup', 1 );

if ( ! function_exists( 'audioman_content_nav' ) ) :
	/**
	 * Display navigation/pagination when applicable
	 *
	 * @since Audioman 1.0
	 */
	function audioman_content_nav() {
		global $wp_query;

		// Don't print empty markup in archives if there's only one page.
		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) ) {
			return;
		}

		$pagination_type = get_theme_mod( 'audioman_pagination_type', 'default' );

		if ( ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'infinite-scroll' ) ) || class_exists( 'Catch_Infinite_Scroll' ) ) {
			// Support infinite scroll plugins.
			the_posts_navigation();
		} elseif ( 'numeric' === $pagination_type && function_exists( 'the_posts_pagination' ) ) {
			the_posts_pagination( array(
				'prev_text'          => '<span>' . esc_html__( 'Prev', 'audioman' ) . '</span>',
				'next_text'          => '<span>' . esc_html__( 'Next', 'audioman' ) . '</span>',
				'screen_reader_text' => '<span class="nav-subtitle screen-reader-text">' . esc_html__( 'Page', 'audioman' ) . ' </span>',
			) );
		} else {
			the_posts_navigation();
		}
	}
endif; // audioman_content_nav

/**
 * Check if a section is enabled or not based on the $value parameter
 * @param  string $value Value of the section that is to be checked
 * @return boolean return true if section is enabled otherwise false
 */
function audioman_check_section( $value ) {
	global $wp_query;

	// Get Page ID outside Loop
	$page_id = $wp_query->get_queried_object_id();

	// Front page displays in Reading Settings
	$page_for_posts = get_option('page_for_posts');

	return ( 'entire-site' == $value  || ( ( is_front_page() || ( is_home() && intval( $page_for_posts ) !== intval( $page_id ) ) ) && 'homepage' == $value ) );
}

/**
 * Return the first image in a post. Works inside a loop.
 * @param [integer] $post_id [Post or page id]
 * @param [string/array] $size Image size. Either a string keyword (thumbnail, medium, large or full) or a 2-item array representing width and height in pixels, e.g. array(32,32).
 * @param [string/array] $attr Query string or array of attributes.
 * @return [string] image html
 *
 * @since Audioman 1.0
 */
function audioman_get_first_image( $postID, $size, $attr ) {
	ob_start();

	ob_end_clean();

	$image 	= '';

	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', get_post_field('post_content', $postID ) , $matches);

	if( isset( $matches [1] [0] ) ) {
		//Get first image
		$first_img = $matches [1] [0];

		return '<img class="pngfix wp-post-image" src="'. esc_url( $first_img ) .'">';
	}

	return false;
}

function audioman_get_theme_layout() {
	$layout = '';

	if ( is_page_template( 'templates/no-sidebar.php' ) ) {
		$layout = 'no-sidebar';
	} elseif ( is_page_template( 'templates/right-sidebar.php' ) ) {
		$layout = 'right-sidebar';
	} else {
		$layout = get_theme_mod( 'audioman_default_layout', 'no-sidebar' );

		if ( is_home() || is_archive() ) {
			$layout = get_theme_mod( 'audioman_homepage_archive_layout', 'right-sidebar' );
		}
	}

	return $layout;
}

function audioman_get_sidebar_id() {
	$sidebar = '';

	$layout = audioman_get_theme_layout();

	$sidebaroptions = '';

	if ( 'no-sidebar-full-width' === $layout || 'no-sidebar' === $layout ) {
		return $sidebar;
	}

	// WooCommerce Shop Page excluding Cart and checkout.
	if ( class_exists( 'WooCommerce' ) && is_woocommerce() ) {
		$shop_id        = get_option( 'woocommerce_shop_page_id' );
		$sidebaroptions = get_post_meta( $shop_id, 'audioman-sidebar-option', true );
	} else {
		global $post, $wp_query;

		// Front page displays in Reading Settings.
		$page_on_front  = get_option( 'page_on_front' );
		$page_for_posts = get_option( 'page_for_posts' );

		// Get Page ID outside Loop.
		$page_id = $wp_query->get_queried_object_id();
		// Blog Page or Front Page setting in Reading Settings.
		if ( $page_id == $page_for_posts || $page_id == $page_on_front ) {
	        $sidebaroptions = get_post_meta( $page_id, 'audioman-sidebar-option', true );
	    } elseif ( is_singular() ) {
	    	if ( is_attachment() ) {
				$parent 		= $post->post_parent;
				$sidebaroptions = get_post_meta( $parent, 'audioman-sidebar-option', true );

			} else {
				$sidebaroptions = get_post_meta( $post->ID, 'audioman-sidebar-option', true );
			}
		}
	}

	if ( is_active_sidebar( 'sidebar-1' ) ) {
		$sidebar = 'sidebar-1'; // Primary Sidebar.
	}

	return $sidebar;
}

if ( ! function_exists( 'audioman_truncate_phrase' ) ) :
	/**
	 * Return a phrase shortened in length to a maximum number of characters.
	 *
	 * Result will be truncated at the last white space in the original string. In this function the word separator is a
	 * single space. Other white space characters (like newlines and tabs) are ignored.
	 *
	 * If the first `$max_characters` of the string does not contain a space character, an empty string will be returned.
	 *
	 * @since Audioman 1.0
	 *
	 * @param string $text            A string to be shortened.
	 * @param integer $max_characters The maximum number of characters to return.
	 *
	 * @return string Truncated string
	 */
	function audioman_truncate_phrase( $text, $max_characters ) {

		$text = trim( $text );

		if ( mb_strlen( $text ) > $max_characters ) {
			//* Truncate $text to $max_characters + 1
			$text = mb_substr( $text, 0, $max_characters + 1 );

			//* Truncate to the last space in the truncated string
			$text = trim( mb_substr( $text, 0, mb_strrpos( $text, ' ' ) ) );
		}

		return $text;
	}
endif; //audioman_truncate_phrase

if ( ! function_exists( 'audioman_get_the_content_limit' ) ) :
	/**
	 * Return content stripped down and limited content.
	 *
	 * Strips out tags and shortcodes, limits the output to `$max_char` characters, and appends an ellipsis and more link to the end.
	 *
	 * @since Audioman 1.0
	 *
	 * @param integer $max_characters The maximum number of characters to return.
	 * @param string  $more_link_text Optional. Text of the more link. Default is "(more...)".
	 * @param bool    $stripteaser    Optional. Strip teaser content before the more text. Default is false.
	 *
	 * @return string Limited content.
	 */
	function audioman_get_the_content_limit( $max_characters, $more_link_text = '(more...)', $stripteaser = false ) {

		$content = get_the_content( '', $stripteaser );

		// Strip tags and shortcodes so the content truncation count is done correctly.
		$content = strip_tags( strip_shortcodes( $content ), apply_filters( 'get_the_content_limit_allowedtags', '<script>,<style>' ) );

		// Remove inline styles / .
		$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

		// Truncate $content to $max_char
		$content = audioman_truncate_phrase( $content, $max_characters );

		// More link?
		if ( $more_link_text ) {
			$link   = apply_filters( 'get_the_content_more_link', sprintf( '<span class="readmore"><a href="%s" class="more-link">%s</a></span>', esc_url( get_permalink() ), $more_link_text ), $more_link_text );
			$output = sprintf( '<p>%s %s</p>', $content, $link );
		} else {
			$output = sprintf( '<p>%s</p>', $content );
			$link = '';
		}

		return apply_filters( 'audioman_get_the_content_limit', $output, $content, $link, $max_characters );

	}
endif; //audioman_get_the_content_limit

if ( ! function_exists( 'audioman_content_image' ) ) :
	/**
	 * Template for Featured Image in Archive Content
	 *
	 * To override this in a child theme
	 * simply fabulous-fluid your own audioman_content_image(), and that function will be used instead.
	 *
	 * @since Audioman 1.0
	 */
	function audioman_content_image() {
		if ( has_post_thumbnail() && audioman_jetpack_featured_image_display() && is_singular() ) {
			global $post, $wp_query;

			// Get Page ID outside Loop.
			$page_id = $wp_query->get_queried_object_id();

			if ( $post ) {
		 		if ( is_attachment() ) {
					$parent = $post->post_parent;

					$individual_featured_image = get_post_meta( $parent, 'audioman-featured-image', true );
				} else {
					$individual_featured_image = get_post_meta( $page_id, 'audioman-featured-image', true );
				}
			}

			if ( empty( $individual_featured_image ) ) {
				$individual_featured_image = 'default';
			}

			if ( 'disable' === $individual_featured_image ) {
				echo '<!-- Page/Post Single Image Disabled or No Image set in Post Thumbnail -->';
				return false;
			} else {
				$class = array();

				$image_size = 'post-thumbnail';

				if ( 'default' !== $individual_featured_image ) {
					$image_size = $individual_featured_image;
					$class[]    = 'from-metabox';
				} else {
					$layout = audioman_get_theme_layout();

					if ( 'no-sidebar-full-width' === $layout ) {
						$image_size = 'audioman-slider';
					}
				}

				$class[] = $individual_featured_image;
				?>
				<div class="post-thumbnail <?php echo esc_attr( implode( ' ', $class ) ); ?>">
					<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( $image_size ); ?>
					</a>
				</div>
		   	<?php
			}
		} // End if().
	}
endif; // audioman_content_image.

if ( ! function_exists( 'audioman_get_featured_posts' ) ) :
	/**
	 * Featured content Posts
	 */
	function audioman_get_featured_posts() { 
		$type = 'featured-content';

		$number = get_theme_mod( 'audioman_featured_content_number', 3 );

		$post_list    = array();

		$args = array(
			'posts_per_page'      => $number,
			'post_type'           => 'post',
			'ignore_sticky_posts' => 1, // ignore sticky posts.
		);

		// Get valid number of posts.
		$args['post_type'] = 'featured-content';

		for ( $i = 1; $i <= $number; $i++ ) {
			$post_id = '';
			
			$post_id = get_theme_mod( 'audioman_featured_content_cpt_' . $i );

			if ( $post_id && '' !== $post_id ) {
				$post_list = array_merge( $post_list, array( $post_id ) );
			}
		}

		$args['post__in'] = $post_list;
		$args['orderby']  = 'post__in';

		$featured_posts = get_posts( $args );

		return $featured_posts;
	}
endif; // audioman_get_featured_posts.


if ( ! function_exists( 'audioman_get_featured_videos' ) ) :
	/**
	 * Featured content Posts
	 */
	function audioman_get_featured_videos() { 
		$type = get_theme_mod( 'audioman_featured_video_type', 'category' );

		$number = get_theme_mod( 'audioman_featured_video_number', 1 );

		$post_list    = array();

		$args = array(
			'posts_per_page'      => $number,
			'post_type'           => 'post',
			'ignore_sticky_posts' => 1, // ignore sticky posts.
		);

		// Get valid number of posts.
		if ( 'post' === $type || 'page' === $type ) {
			$args['post_type'] = $type;

			for ( $i = 1; $i <= $number; $i++ ) {
				$post_id = '';

				if ( 'post' === $type ) {
					$post_id = get_theme_mod( 'audioman_featured_video_post_' . $i );
				} elseif ( 'page' === $type ) {
					$post_id = get_theme_mod( 'audioman_featured_video_page_' . $i );
				}

				if ( $post_id && '' !== $post_id ) {
					$post_list = array_merge( $post_list, array( $post_id ) );
				}
			}

			$args['post__in'] = $post_list;
			$args['orderby']  = 'post__in';
		} elseif ( 'category' === $type && $cat = get_theme_mod( 'audioman_featured_video_select_category' ) ) {
			$args['category__in'] = $cat;
		}

		$featured_posts = get_posts( $args );

		return $featured_posts;
	}
endif; // audioman_get_featured_videos.

/**
 * Get Featured Posts
 */
function audioman_get_posts( $section ) {
	$type   = 'featured-content';
	$number = get_theme_mod( 'audioman_featured_content_number', 3 );

	if ( 'featured_content' === $section ) {
		$type     = 'featured-content';
		$number   = get_theme_mod( 'audioman_featured_content_number', 3 );
		$cpt_slug = 'featured-content';
	}elseif ( 'portfolio' === $section ) {
		$type     = 'jetpack-portfolio';
		$number   = get_theme_mod( 'audioman_portfolio_number', 5 );
		$cpt_slug = 'jetpack-portfolio';
	} elseif ( 'testimonial' === $section ) {
		$type     = 'jetpack-testimonial';
		$number   = get_theme_mod( 'audioman_testimonial_number', 3 );
		$cpt_slug = 'jetpack-testimonial';
	} elseif ( 'news' === $section ) {
		$type     = get_theme_mod( 'audioman_news_type', 'demo' );
		$number   = get_theme_mod( 'audioman_news_number', 3 );
		$cpt_slug = ''; // Event has no cpt.
	}

	$post_list  = array();
	$no_of_post = 0;

	$args = array(
		'post_type'           => 'post',
		'ignore_sticky_posts' => 1, // ignore sticky posts.
	);

	// Get valid number of posts.
	if ( 'post' === $type || 'page' === $type || $cpt_slug === $type ) {
		$args['post_type'] = $type;

		for ( $i = 1; $i <= $number; $i++ ) {
			$post_id = '';

			if ( 'post' === $type ) {
				$post_id = get_theme_mod( 'audioman_' . $section . '_post_' . $i );
			} elseif ( 'page' === $type ) {
				$post_id = get_theme_mod( 'audioman_' . $section . '_page_' . $i );
			} elseif ( $cpt_slug === $type ) {
				$post_id = get_theme_mod( 'audioman_' . $section . '_cpt_' . $i );
			}

			if ( $post_id && '' !== $post_id ) {
				$post_list = array_merge( $post_list, array( $post_id ) );

				$no_of_post++;
			}
		}

		$args['post__in'] = $post_list;
		$args['orderby']  = 'post__in';
	} elseif ( 'category' === $type ) {
		if ( $cat = get_theme_mod( 'audioman_' . $section . '_select_category' ) ) {
			$args['category__in'] = $cat;
		}


		$no_of_post = $number;
	}

	$args['posts_per_page'] = $no_of_post;

	if( ! $no_of_post ) {
		return;
	}

	$posts = get_posts( $args );

	return $posts;
}

if ( ! function_exists( 'audioman_sections' ) ) :
	/**
	 * Display Sections on header and footer with respect to the section options as below
	 */
	function audioman_sections( $selector = 'header' ) {
		
		get_template_part( 'template-parts/slider/content', 'slider' );
		get_template_part( 'template-parts/header/breadcrumb' );
		get_template_part( 'template-parts/playlist/content', 'playlist' );
		get_template_part( 'template-parts/portfolio/display', 'portfolio' );
		get_template_part( 'template-parts/featured-content/display', 'featured' );
		get_template_part( 'template-parts/testimonial/display', 'testimonial' );
		get_template_part( 'template-parts/hero-content/content', 'hero' );
	}
endif;

/**
 * Enqueues front-end CSS for Header Text with Header Media
 *
 * @since Audioman 1.0
 *
 * @see wp_add_inline_style()
 */
function audioman_header_textcolor_with_header_media_css() {
	$header_textcolor_with_header_media = get_theme_mod( 'header_textcolor_with_header_media', '#ffffff' );

	// Don't do anything if the current color is the default.
	if ( $header_textcolor_with_header_media === '#ffffff' ) {
		return;
	}

	$css = '
		.absolute-header .site-title a,
		.absolute-header .site-title a:hover,
		.absolute-header .site-title a:focus,
		.absolute-header .site-description,
		.absolute-header .main-navigation a,
		.absolute-header .menu-toggle,
		.absolute-header .dropdown-toggle,
		.absolute-header .site-header-cart .cart-contents,
		.absolute-header .site-header-menu .social-navigation a {
			color: %1$s;
		}
	';

	wp_add_inline_style( 'audioman-style', sprintf( $css, $header_textcolor_with_header_media ) );
}
add_action( 'wp_enqueue_scripts', 'audioman_header_textcolor_with_header_media_css', 11 );