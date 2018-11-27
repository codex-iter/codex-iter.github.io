<?php
/**
 * Playlist Options
 *
 * @package Audioman
 */

/**
 * Add playlist options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function audioman_playlist( $wp_customize ) {
	$wp_customize->add_section( 'audioman_playlist', array(
			'title' => esc_html__( 'Playlist', 'audioman' ),
			'panel' => 'audioman_theme_options',
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_playlist_visibility',
			'default'           => 'disabled',
			'sanitize_callback' => 'audioman_sanitize_select',
			'choices'           => audioman_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'audioman' ),
			'section'           => 'audioman_playlist',
			'type'              => 'select',
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_playlist_section_title',
			'default'           => esc_html__( 'New Releases', 'audioman' ),
			'sanitize_callback' => 'wp_kses_post',
			'active_callback'   => 'audioman_is_playlist_active',
			'label'             => esc_html__( 'Section Title', 'audioman' ),
			'section'           => 'audioman_playlist',
			'type'              => 'text',
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_playlist',
			'default'           => '0',
			'sanitize_callback' => 'audioman_sanitize_post',
			'active_callback'   => 'audioman_is_playlist_active',
			'label'             => esc_html__( 'Page', 'audioman' ),
			'section'           => 'audioman_playlist',
			'type'              => 'dropdown-pages',
		)
	);
}
add_action( 'customize_register', 'audioman_playlist', 12 );

/** Active Callback Functions **/
if ( ! function_exists( 'audioman_is_playlist_active' ) ) :
	/**
	* Return true if playlist is active
	*
	* @since Audioman 1.0
	*/
	function audioman_is_playlist_active( $control ) {
		global $wp_query;

		$page_id = $wp_query->get_queried_object_id();

		// Front page display in Reading Settings
		$page_for_posts = get_option( 'page_for_posts' );

		$enable = $control->manager->get_setting( 'audioman_playlist_visibility' )->value();

		//return true only if previewed page on customizer matches the type of content option selected
		return ( 'entire-site' == $enable  || ( ( is_front_page() || ( is_home() && $page_for_posts != $page_id ) ) &&	 'homepage' == $enable )
			);
	}
endif;