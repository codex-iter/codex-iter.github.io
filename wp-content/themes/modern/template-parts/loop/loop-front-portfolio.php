<?php
/**
 * Front page template loop: Portfolio posts
 *
 * We are using generic, global hook names in this file, but passing
 * a file name as a hook context/scope parameter you can check for.
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.2.0
 */





// Requirements check

	$post_type = 'jetpack-portfolio';

	if (
		! is_page_template( 'page-template/_front.php' )
		|| ! post_type_exists( $post_type )
	) {
		return;
	}


// Helper variables

	$context = basename( __FILE__ );

	$query = new WP_Query( (array) apply_filters( 'wmhook_modern_loop_query', array(
		'post_type'           => $post_type,
		'posts_per_page'      => absint( Modern_Library_Customize::get_theme_mod( 'layout_posts_per_page_front_portfolio' ) ),
		'paged'               => 1,
		'no_found_rows'       => true,
		'ignore_sticky_posts' => true,
		'post_status'         => 'publish',
	), $context ) );


// Output

	include get_theme_file_path( 'template-parts/loop/loop-front--section.php' );
