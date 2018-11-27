<?php
/**
 * Status post format content
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0.0
 * @version  2.0.0
 */





// Helper variables

	$hover_title = sprintf(
		esc_html_x( 'Status: %1$s on %2$s', 'Status post format text on mouse hover (1: author name, 2: status publish date).', 'modern' ),
		esc_html( get_the_author() ),
		esc_html( get_the_date() . ' | ' . get_the_time() )
	);


do_action( 'tha_entry_before' );

?>

<article id="post-<?php the_ID(); ?>" title="<?php echo esc_attr( $hover_title ); ?>" <?php post_class(); ?>>

	<?php do_action( 'tha_entry_top' ); ?>

	<div class="entry-content"><?php

		do_action( 'tha_entry_content_before' );

		the_content();

		do_action( 'tha_entry_content_after' );

	?></div>

	<?php do_action( 'tha_entry_bottom' ); ?>

</article>

<?php

do_action( 'tha_entry_after' );
