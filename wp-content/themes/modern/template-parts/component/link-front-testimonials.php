<?php
/**
 * Front page template link: Testimonials
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 */





// Helper variables

	$post_type = 'jetpack-testimonial';

	$labels = get_post_type_labels( get_post_type_object( $post_type ) );
	$label  = sprintf(
		/* translators: 1: Testimonial post type plural label. */
		esc_html__( 'All %s &raquo;', 'modern' ),
		esc_html( $labels->name )
	);

	$link = '<a href="' . esc_url( get_post_type_archive_link( $post_type ) ) . '" class="button">' . $label . '</a>';


?>

<div class="archive-link archive-link-<?php echo esc_attr( $post_type ); ?>">
	<?php echo (string) apply_filters( 'wmhook_modern_link', $link, basename( __FILE__ ), $label ); ?>
</div>
