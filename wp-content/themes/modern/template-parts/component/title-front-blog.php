<?php
/**
 * Front page template section title: Blog
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





// Helper variables

	$blog_page_id = absint( get_option( 'page_for_posts' ) );

	if ( $blog_url = get_permalink( $blog_page_id ) ) {
		$label = get_the_title( $blog_page_id );
		$title = '<a href="' . esc_url( $blog_url ) . '">' . $label . '</a>';
	} else {
		$label = esc_html__( 'Blog', 'modern' );
		$title = $label;
	}


?>

<h2 class="front-page-section-title">
	<?php echo (string) apply_filters( 'wmhook_modern_title', $title, basename( __FILE__ ), $label ); ?>
</h2>
