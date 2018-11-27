<?php
/**
 * Featured Slider Options
 *
 * @package Audioman
 */

/**
 * Add hero content options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function audioman_slider_options( $wp_customize ) {
	$wp_customize->add_section( 'audioman_featured_slider', array(
			'panel' => 'audioman_theme_options',
			'title' => esc_html__( 'Featured Slider', 'audioman' ),
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_slider_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'audioman_sanitize_select',
			'choices'           => audioman_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'audioman' ),
			'section'           => 'audioman_featured_slider',
			'type'              => 'select',
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_slider_number',
			'default'           => '4',
			'sanitize_callback' => 'audioman_sanitize_number_range',

			'active_callback'   => 'audioman_is_slider_active',
			'description'       => esc_html__( 'Save and refresh the page if No. of Slides is changed (Max no of slides is 20)', 'audioman' ),
			'input_attrs'       => array(
				'style' => 'width: 45px;',
				'min'   => 0,
				'max'   => 20,
				'step'  => 1,
			),
			'label'             => esc_html__( 'No of Slides', 'audioman' ),
			'section'           => 'audioman_featured_slider',
			'type'              => 'number',
			'transport'         => 'postMessage',
		)
	);

	$slider_number = get_theme_mod( 'audioman_slider_number', 4 );

	for ( $i = 1; $i <= $slider_number ; $i++ ) {
		// Page Sliders
		audioman_register_option( $wp_customize, array(
				'name'              =>'audioman_slider_page_' . $i,
				'sanitize_callback' => 'audioman_sanitize_post',
				'active_callback'   => 'audioman_is_slider_active',
				'label'             => esc_html__( 'Page', 'audioman' ) . ' # ' . $i,
				'section'           => 'audioman_featured_slider',
				'type'              => 'dropdown-pages',
			)
		);
	} // End for().
}
add_action( 'customize_register', 'audioman_slider_options' );

/**
 * Returns an array of featured content show registered
 *
 * @since Audioman 1.0
 */
function audioman_content_show() {
	$options = array(
		'excerpt'      => esc_html__( 'Show Excerpt', 'audioman' ),
		'full-content' => esc_html__( 'Full Content', 'audioman' ),
		'hide-content' => esc_html__( 'Hide Content', 'audioman' ),
	);
	return apply_filters( 'audioman_content_show', $options );
}


/**
 * Returns an array of featured content show registered
 *
 * @since Audioman 1.0
 */
function audioman_meta_show() {
	$options = array(
		'show-meta'      => esc_html__( 'Show Meta', 'audioman' ),
		'hide-meta' => esc_html__( 'Hide Meta', 'audioman' ),
	);
	return apply_filters( 'audioman_content_show', $options );
}

/** Active Callback Functions */

if( ! function_exists( 'audioman_is_slider_active' ) ) :
	/**
	* Return true if slider is active
	*
	* @since Audioman 1.0
	*/
	function audioman_is_slider_active( $control ) {
		$enable = $control->manager->get_setting( 'audioman_slider_option' )->value();

		return ( audioman_check_section( $enable ) );
	}
endif;


