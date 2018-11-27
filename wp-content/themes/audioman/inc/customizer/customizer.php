<?php
/**
 * Theme Customizer
 *
 * @package Audioman
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function audioman_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport              = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport       = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport      = 'postMessage';
	$wp_customize->get_setting( 'header_video' )->transport          = 'refresh';
	$wp_customize->get_setting( 'external_header_video' )->transport = 'refresh';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector' => '.site-title a',
			'container_inclusive' => false,
			'render_callback' => 'audioman_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector' => '.site-description',
			'container_inclusive' => false,
			'render_callback' => 'audioman_customize_partial_blogdescription',
		) );
	}

	$wp_customize->get_control( 'header_textcolor' )->priority= 1;

	// Header Text Color With Header Media.
	audioman_register_option( $wp_customize, array(
			'name'              => 'header_textcolor_with_header_media',
			'default'           => '#ffffff',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
			'custom_control'    => 'WP_Customize_Color_Control',
			'label'             => esc_html__( 'Header Text Color With Header Media', 'audioman' ),
			'section'           => 'colors',
			'priority'      	=> 2,
		)
	);

	// Reset all settings to default.
	$wp_customize->add_section( 'audioman_reset_all', array(
		'description'   => esc_html__( 'Caution: Reset all settings to default. Refresh the page after save to view full effects.', 'audioman' ),
		'title'         => esc_html__( 'Reset all settings', 'audioman' ),
		'priority'      => 998,
	) );

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_reset_all_settings',
			'sanitize_callback' => 'audioman_sanitize_checkbox',
			'label'             => esc_html__( 'Check to reset all settings to default', 'audioman' ),
			'section'           => 'audioman_reset_all',
			'transport'         => 'postMessage',
			'type'              => 'checkbox',
		)
	);
	// Reset all settings to default end.

	// Important Links.
	$wp_customize->add_section( 'audioman_important_links', array(
		'priority'      => 999,
		'title'         => esc_html__( 'Important Links', 'audioman' ),
	) );

	// Has dummy Sanitizaition function as it contains no value to be sanitized.
	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_important_links',
			'sanitize_callback' => 'sanitize_text_field',
			'custom_control'    => 'Audioman_Important_Links_Control',
			'label'             => esc_html__( 'Important Links', 'audioman' ),
			'section'           => 'audioman_important_links',
			'type'              => 'audioman_important_links',
		)
	);
	// Important Links End.
}
add_action( 'customize_register', 'audioman_customize_register', 11 );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function audioman_customize_preview_js() {
	wp_enqueue_script( 'audioman-customize-preview', trailingslashit( esc_url ( get_template_directory_uri() ) ) . 'assets/js/customize-preview.min.js', array( 'customize-preview' ), '20180103', true );
}
add_action( 'customize_preview_init', 'audioman_customize_preview_js' );

/**
 * Include Custom Controls
 */
require get_parent_theme_file_path( 'inc/customizer/custom-controls.php' );

/**
 * Include Header Media Options
 */
require get_parent_theme_file_path( 'inc/customizer/header-media.php' );

/**
 * Include Theme Options
 */
require get_parent_theme_file_path( 'inc/customizer/theme-options.php' );

/**
 * Include Hero Content
 */
require get_parent_theme_file_path( 'inc/customizer/hero-content.php' );

/**
 * Include Featured Slider
 */
require get_parent_theme_file_path( 'inc/customizer/featured-slider.php' );

/**
 * Include Featured Content
 */
require get_parent_theme_file_path( 'inc/customizer/featured-content.php' );

/**
 * Include Testimonial
 */
require get_parent_theme_file_path( 'inc/customizer/testimonial.php' );

/**
 * Include Portfolio
 */
require get_parent_theme_file_path( 'inc/customizer/portfolio.php' );

/**
 * Include Customizer Helper Functions
 */
require get_parent_theme_file_path( 'inc/customizer/helpers.php' );

/**
 * Include Sanitization functions
 */
require get_parent_theme_file_path( 'inc/customizer/sanitize-functions.php' );

/**
 * Include Playlist
 */
require get_parent_theme_file_path( 'inc/customizer/playlist.php' );

/**
 * Include Upgrade Button
 */
require get_parent_theme_file_path( 'inc/customizer/upgrade-button/class-customize.php' );