<?php
/**
 * Quote post format content
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

		// Processing

			// Remove <blockquote> tags

				$content = preg_replace( '/<(\/?)blockquote(.*?)>/', '', get_the_content() );

			// Quote source

				// First, look for custom field

					$quote_source = trim( get_post_meta( get_the_ID(), 'quote_source', true ) );

				// Fall back to post title as quote source if no custom field set

					if ( empty( $quote_source ) ) {
						$quote_source = wp_kses( get_the_title(), array( 'span' => array( 'class' => true ) ) );
					}

				// Finally, display the quote source only if it wasn't included in the post content

					if (
						false === stristr( $content, '<cite' )
						&& $quote_source
					) {
						$content .= '<cite class="quote-source">' . $quote_source . '</cite>';
					}


		// Output

			$content = explode( '<cite', $content );

			// Quote content

				echo '<blockquote class="quote-content">' . $content[0] . '</blockquote>';

			// Quote source

				if ( isset( $content[1] ) && $content[1] ) {
					echo '<cite' . $content[1];
				}

		do_action( 'tha_entry_content_after' );

	?></div>

	<?php do_action( 'tha_entry_bottom' ); ?>

</article>

<?php

do_action( 'tha_entry_after' );
