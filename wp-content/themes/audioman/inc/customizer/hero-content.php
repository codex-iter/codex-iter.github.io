<?php
/**
 * Hero Content Options
 *
 * @package Audioman
 */

/**
 * Add hero content options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function audioman_hero_content_options( $wp_customize ) {
	$wp_customize->add_section( 'audioman_hero_content_options', array(
			'title' => esc_html__( 'Hero Content', 'audioman' ),
			'panel' => 'audioman_theme_options',
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_hero_content_visibility',
			'default'           => 'disabled',
			'sanitize_callback' => 'audioman_sanitize_select',
			'choices'           => audioman_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'audioman' ),
			'section'           => 'audioman_hero_content_options',
			'type'              => 'select',
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_hero_content',
			'default'           => '0',
			'sanitize_callback' => 'audioman_sanitize_post',
			'active_callback'   => 'audioman_is_hero_content_active',
			'label'             => esc_html__( 'Page', 'audioman' ),
			'section'           => 'audioman_hero_content_options',
			'type'              => 'dropdown-pages',
		)
	);
}
add_action( 'customize_register', 'audioman_hero_content_options' );

/** Active Callback Functions **/
if ( ! function_exists( 'audioman_is_hero_content_active' ) ) :
	/**
	* Return true if hero content is active
	*
	* @since Audioman 1.0
	*/
	function audioman_is_hero_content_active( $control ) {
		$enable = $control->manager->get_setting( 'audioman_hero_content_visibility' )->value();

		return ( audioman_check_section( $enable ) );
	}
endif;