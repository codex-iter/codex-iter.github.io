<?php
/**
 * Template part for displaying posts
 *
 * @link  https://codex.wordpress.org/Template_Hierarchy
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0.0
 * @version  2.0.0
 */





do_action( 'tha_entry_before' );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php do_action( 'tha_entry_top' ); ?>

	<div class="entry-content"><?php

		do_action( 'tha_entry_content_before' );

		if ( Modern_Post::is_singular() ) {

			if ( has_excerpt() && ! Modern_Post::is_paged() ) {
				the_excerpt();
			}

			the_content( apply_filters( 'wmhook_modern_summary_continue_reading', '' ) );

		} else {
			the_excerpt();
		}

		do_action( 'tha_entry_content_after' );

	?></div>

	<?php do_action( 'tha_entry_bottom' ); ?>

</article>

<?php

do_action( 'tha_entry_after' );
