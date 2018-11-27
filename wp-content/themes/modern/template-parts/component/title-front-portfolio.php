<?php
/**
 * Front page template section title: Portfolio
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

	$post_type = 'jetpack-portfolio';
	$labels    = get_post_type_labels( get_post_type_object( $post_type ) );
	$title     = '<a href="' . esc_url( get_post_type_archive_link( $post_type ) ) . '">' . esc_html( $labels->name ) . '</a>';


?>

<h2 class="front-page-section-title">
	<?php echo (string) apply_filters( 'wmhook_modern_title', $title, basename( __FILE__ ), esc_html( $labels->name ) ); ?>
</h2>
