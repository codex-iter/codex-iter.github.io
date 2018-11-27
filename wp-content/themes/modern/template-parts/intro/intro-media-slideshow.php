<?php
/**
 * Front page slideshow loop: Media
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.0.0
 */





?>

<div id="intro-media" class="intro-media">

	<div id="intro-slideshow-media" class="intro-slideshow-media intro-slideshow">
		<?php foreach ( Modern_Intro::get_slides() as $post ) : ?>

		<div class="intro-slideshow-item">

			<div class="intro-slideshow-item-media">
				<?php

				$image_size = 'modern-intro';

				// Using old name "banner_image" for backwards compatibility.
				$custom_image = trim( get_post_meta( $post->ID, 'banner_image', true ) );

				if ( $custom_image && '-' !== $custom_image ) {

					if ( is_numeric( $custom_image ) ) {
						echo wp_get_attachment_image( absint( $custom_image ), $image_size );
					} else {
						echo '<img src="' . esc_url( $custom_image ) . '" alt="' . the_title_attribute( 'echo=0&post=' . $post->ID ) . '" />';
					}

				} elseif ( has_post_thumbnail( $post ) ) {

					echo get_the_post_thumbnail( $post, $image_size );

				} else {

					the_custom_header_markup();

				}

				?>
			</div>

		</div>

		<?php endforeach; ?>
	</div>

</div>
