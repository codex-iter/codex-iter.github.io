<?php
/**
 * Template Name: With sidebar
 * Template Post Type: page, post, jetpack-portfolio
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0.0
 * @version  2.0.0
 */

/* translators: Custom page template name. */
__( 'With sidebar', 'modern' );





if ( is_page( get_the_ID() ) ) {
	get_template_part( 'page' );
} else {
	get_template_part( 'single', get_post_type() );
}
