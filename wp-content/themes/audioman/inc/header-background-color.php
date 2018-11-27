<?php
/**
 * Customizer functionality
 *
 * @package Audioman
 */

/**
 * Sets up the WordPress core custom header and custom background features.
 *
 * @since Audioman 1.0
 *
 * @see audioman_header_style()
 */
function audioman_custom_header_and_background() {
	$default_text_color       = '#ffffff';

	/**
	 * Filter the arguments used when adding 'custom-header' support in Audioman.
	 *
	 * @since Audioman 1.0
	 *
	 * @param array $args {
	 *     An array of custom-header support arguments.
	 *
	 *     @type string $default-text-color Default color of the header text.
	 *     @type int      $width            Width in pixels of the custom header image. Default 1200.
	 *     @type int      $height           Height in pixels of the custom header image. Default 280.
	 *     @type bool     $flex-height      Whether to allow flexible-height header images. Default true.
	 *     @type callable $wp-head-callback Callback function used to style the header image and text
	 *                                      displayed on the blog.
	 * }
	 */
	add_theme_support( 'custom-header', apply_filters( 'audioman_custom_header_args', array(
		'default-image'      	 => get_parent_theme_file_uri( '/assets/images/header-image.jpg' ),
		'default-text-color'     => $default_text_color,
		'width'                  => 1920,
		'height'                 => 1080,
		'flex-height'            => true,
		'flex-height'            => true,
		'wp-head-callback'       => 'audioman_header_style',
		'video'                  => true,
	) ) );

	register_default_headers( array(
		'default-image' => array(
			'url'           => '%s/assets/images/header-image.jpg',
			'thumbnail_url' => '%s/assets/images/header-image-275x155.jpg',
			'description'   => esc_html__( 'Default Header Image', 'audioman' ),
		),
		'second-image' => array(
			'url'           => '%s/assets/images/header-image-1.jpg',
			'thumbnail_url' => '%s/assets/images/header-image-1-275x155.jpg',
			'description'   => esc_html__( 'Alternate Header Image', 'audioman' ),
		),
	) );
}
add_action( 'after_setup_theme', 'audioman_custom_header_and_background' );

/**
 * Customize video play/pause button in the custom header.
 *
 * @param array $settings header video settings.
 */
function audioman_video_controls( $settings ) {
	$settings['l10n']['play'] = '<span class="screen-reader-text">' . esc_html__( 'Play background video', 'audioman' ) . '</span>';
	$settings['l10n']['pause'] = '<span class="screen-reader-text">' . esc_html__( 'Pause background video', 'audioman' ) . '</span>';
	return $settings;
}
add_filter( 'header_video_settings', 'audioman_video_controls' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @since Audioman 1.2
 * @see audioman_customize_register()
 *
 * @return void
 */
function audioman_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @since Audioman 1.2
 * @see audioman_customize_register()
 *
 * @return void
 */
function audioman_customize_partial_blogdescription() {
	bloginfo( 'description' );
}