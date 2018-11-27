<?php
/**
 * Front page template link: Blog
 *
 * We are using generic, global hook names in this file, but passing
 * a file name as a hook context/scope parameter you can check for.
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 */





// Requirements check

	$blog_page_id = get_option( 'page_for_posts' );

	if ( empty( $blog_page_id ) ) {
		return;
	}


// Helper variables

	$post_type = 'post';

	$labels = get_post_type_labels( get_post_type_object( $post_type ) );
	$label  = sprintf(
		/* translators: 1: Blog post type plural label. */
		esc_html__( 'All %s &raquo;', 'modern' ),
		esc_html( $labels->name )
	);

	$link = '<a href="' . esc_url( get_permalink( absint( $blog_page_id ) ) ) . '" class="button">' . $label . '</a>';


?>

<div class="archive-link archive-link-<?php echo esc_attr( $post_type ); ?>">
	<?php echo (string) apply_filters( 'wmhook_modern_link', $link, basename( __FILE__ ), $label ); ?>
</div>
