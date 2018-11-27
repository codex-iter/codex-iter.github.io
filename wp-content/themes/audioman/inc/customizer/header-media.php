<?php
/**
 * Header Media Options
 *
 * @package Audioman
 */

/**
 * Add Header Media options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function audioman_header_media_options( $wp_customize ) {
	$wp_customize->get_section( 'header_image' )->description = esc_html__( 'If you add video, it will only show up on Homepage/FrontPage. Other Pages will use Header/Post/Page Image depending on your selection of option. Header Image will be used as a fallback while the video loads ', 'audioman' );

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_header_media_option',
			'default'           => 'entire-site-page-post',
			'sanitize_callback' => 'audioman_sanitize_select',
			'choices'           => array(
				'homepage'               => esc_html__( 'Homepage / Frontpage', 'audioman' ),
				'exclude-home'           => esc_html__( 'Excluding Homepage', 'audioman' ),
				'exclude-home-page-post' => esc_html__( 'Excluding Homepage, Page/Post Featured Image', 'audioman' ),
				'entire-site'            => esc_html__( 'Entire Site', 'audioman' ),
				'entire-site-page-post'  => esc_html__( 'Entire Site, Page/Post Featured Image', 'audioman' ),
				'pages-posts'            => esc_html__( 'Pages and Posts', 'audioman' ),
				'disable'                => esc_html__( 'Disabled', 'audioman' ),
			),
			'label'             => esc_html__( 'Enable on', 'audioman' ),
			'section'           => 'header_image',
			'type'              => 'select',
			'priority'          => 1,
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_header_media_content_alignment',
			'default'           => 'content-align-center',
			'sanitize_callback' => 'audioman_sanitize_select',
			'choices'           => array(
				'content-align-center' => esc_html__( 'Center', 'audioman' ),
				'content-align-right'  => esc_html__( 'Right', 'audioman' ),
				'content-align-left'   => esc_html__( 'Left', 'audioman' ),
			),
			'label'             => esc_html__( 'Content Alignment', 'audioman' ),
			'section'           => 'header_image',
			'type'              => 'radio',
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_header_media_logo',
			'default'           => trailingslashit( esc_url( get_template_directory_uri() ) ) . 'assets/images/header-media-logo.png',
			'sanitize_callback' => 'esc_url_raw',
			'custom_control'    => 'WP_Customize_Image_Control',
			'label'             => esc_html__( 'Header Media Logo', 'audioman' ),
			'section'           => 'header_image',
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_header_media_logo_option',
			'default'           => 'homepage',
			'sanitize_callback' => 'audioman_sanitize_select',
			'active_callback'   => 'audioman_is_header_media_logo_active',
			'choices'           => array(
				'homepage'               => esc_html__( 'Homepage / Frontpage', 'audioman' ),
				'entire-site'            => esc_html__( 'Entire Site', 'audioman' ) ),
			'label'             => esc_html__( 'Enable Header Media logo on', 'audioman' ),
			'section'           => 'header_image',
			'type'              => 'select',
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_header_media_title',
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Header Media Title', 'audioman' ),
			'section'           => 'header_image',
			'type'              => 'text',
		)
	);

    audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_header_media_text',
			'sanitize_callback' => 'wp_kses_post',
			'default'           => esc_html__( 'Go to Theme Customizer', 'audioman' ),
			'label'             => esc_html__( 'Site Header Text', 'audioman' ),
			'section'           => 'header_image',
			'type'              => 'textarea',
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_header_media_url',
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
			'label'             => esc_html__( 'Header Media Url', 'audioman' ),
			'section'           => 'header_image',
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_header_media_url_text',
			'default'           => esc_html__( 'More', 'audioman' ),
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Header Media Url Text', 'audioman' ),
			'section'           => 'header_image',
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_header_url_target',
			'sanitize_callback' => 'audioman_sanitize_checkbox',
			'label'             => esc_html__( 'Check to Open Link in New Window/Tab', 'audioman' ),
			'section'           => 'header_image',
			'type'              => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'audioman_header_media_options' );

/** Active Callback Functions */

if( ! function_exists( 'audioman_is_header_media_logo_active' ) ) :
	/**
	* Return true if header logo is active
	*
	* @since Audioman 1.0
	*/
	function audioman_is_header_media_logo_active( $control ) {
		$logo = $control->manager->get_setting( 'audioman_header_media_logo' )->value();
		if( '' != $logo ) {
			return true;
		} else {
			return false;
		}
	}
endif;