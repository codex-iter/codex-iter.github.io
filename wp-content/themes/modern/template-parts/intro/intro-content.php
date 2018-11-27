<?php
/**
 * Page intro content
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.2.0
 */





// Requirements check

	if (
		! is_front_page()
		|| Modern_Post::is_paged()
	) {
		return;
	}


// Helper variables

	$post_id = get_the_ID();

	$class_title  = ( is_single() ) ? ( 'entry-title' ) : ( 'page-title' );
	$class_title .= ' h1 intro-title';


// Processing

	if ( is_home() ) {

		// Customizer option text...
		$title = trim(
			// This is already sanitized in database.
			Modern_Library_Customize::get_theme_mod( 'texts_intro' )
		);

		// ...or site tagline
		if ( empty( $title ) ) {
			$title = get_bloginfo( 'description', 'display' );
		}

	} elseif ( $custom_field_title = get_post_meta( $post_id, 'banner_text', true ) ) {
	// Using old name "banner_text" for backwards compatibility.

		$title = trim( wp_strip_all_tags( $custom_field_title ) );

	} else {

		$title = get_the_title( $post_id );

	}

	$title_tag = (string) apply_filters( 'wmhook_modern_title_tag', 'h2', $post_id );
	$title     = (string) apply_filters( 'wmhook_modern_intro_title', $title, $post_id );


// Output

	if ( $title ) {
		echo '<' . tag_escape( $title_tag ) . ' class="' . esc_attr( $class_title ) . '">' . $title . '</' . tag_escape( $title_tag ) . '>';
	}
