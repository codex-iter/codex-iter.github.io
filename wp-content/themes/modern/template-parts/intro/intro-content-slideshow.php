<?php
/**
 * Front page slideshow loop: Content
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 */





?>

<div id="intro-slideshow-content" class="intro-slideshow-content intro-slideshow">
	<?php foreach ( Modern_Intro::get_slides() as $post ) : ?>

	<div class="intro-slideshow-item">

		<p class="h1 intro-title intro-slideshow-item-title">
			<?php

			$link_url = apply_filters( 'wmhook_modern_intro_slideshow_link_url', get_permalink( $post ), $post );

			// Using old name "banner_text" for backwards compatibility.
			$custom_title = trim( get_post_meta( $post->ID, 'banner_text', true ) );

			if ( $link_url ) {
				echo '<a href="' . esc_url( get_permalink( $post ) ) . '">';
			}

			if ( $custom_title ) {
				echo esc_html( $custom_title );
			} else {
				echo get_the_title( $post );
			}

			if ( $link_url ) {
				echo '</a>';
			}

			?>
		</p>

	</div>

	<?php endforeach; ?>
</div>
