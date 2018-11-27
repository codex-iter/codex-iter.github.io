<?php
/**
 * Theme Options
 *
 * @package Audioman
 */

/**
 * Add theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function audioman_theme_options( $wp_customize ) {
	$wp_customize->add_panel( 'audioman_theme_options', array(
		'title'    => esc_html__( 'Theme Options', 'audioman' ),
		'priority' => 130,
	) );

	// Breadcrumb Option.
	$wp_customize->add_section( 'audioman_breadcrumb_options', array(
			'description' => esc_html__( 'Breadcrumbs are a great way of letting your visitors find out where they are on your site with just a glance.', 'audioman' ),
			'panel'       => 'audioman_theme_options',
			'title'       => esc_html__( 'Breadcrumb', 'audioman' ),
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_breadcrumb_option',
			'default'           => 1,
			'sanitize_callback' => 'audioman_sanitize_checkbox',
			'label'             => esc_html__( 'Check to enable Breadcrumb', 'audioman' ),
			'section'           => 'audioman_breadcrumb_options',
			'type'              => 'checkbox',
		)
	);
	
	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_latest_posts_title',
			'default'           => esc_html__( 'News', 'audioman' ),
			'sanitize_callback' => 'wp_kses_post',
			'label'             => esc_html__( 'Latest Posts Title', 'audioman' ),
			'section'           => 'audioman_theme_options',
		)
	);

	// Layout Options
	$wp_customize->add_section( 'audioman_layout_options', array(
		'title' => esc_html__( 'Layout Options', 'audioman' ),
		'panel' => 'audioman_theme_options',
		)
	);

	/* Default Layout */
	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_default_layout',
			'default'           => 'no-sidebar',
			'sanitize_callback' => 'audioman_sanitize_select',
			'label'             => esc_html__( 'Default Layout', 'audioman' ),
			'section'           => 'audioman_layout_options',
			'type'              => 'radio',
			'choices'           => array(
				'right-sidebar'         => esc_html__( 'Right Sidebar ( Content, Primary Sidebar )', 'audioman' ),
				'no-sidebar'            => esc_html__( 'No Sidebar', 'audioman' ),
			),
		)
	);

	/* Homepage/Archive Layout */
	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_homepage_archive_layout',
			'default'           => 'right-sidebar',
			'sanitize_callback' => 'audioman_sanitize_select',
			'label'             => esc_html__( 'Homepage/Archive Layout', 'audioman' ),
			'section'           => 'audioman_layout_options',
			'type'              => 'radio',
			'choices'           => array(
				'right-sidebar'         => esc_html__( 'Right Sidebar ( Content, Primary Sidebar )', 'audioman' ),
				'no-sidebar'            => esc_html__( 'No Sidebar', 'audioman' ),
			),
		)
	);

	// Excerpt Options.
	$wp_customize->add_section( 'audioman_excerpt_options', array(
		'panel'     => 'audioman_theme_options',
		'title'     => esc_html__( 'Excerpt Options', 'audioman' ),
	) );

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_excerpt_length',
			'default'           => '20',
			'sanitize_callback' => 'absint',
			'input_attrs' => array(
				'min'   => 10,
				'max'   => 200,
				'step'  => 5,
				'style' => 'width: 60px;',
			),
			'label'    => esc_html__( 'Excerpt Length (words)', 'audioman' ),
			'section'  => 'audioman_excerpt_options',
			'type'     => 'number',
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_excerpt_more_text',
			'default'           => esc_html__( 'Continue reading...', 'audioman' ),
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Read More Text', 'audioman' ),
			'section'           => 'audioman_excerpt_options',
			'type'              => 'text',
		)
	);

	// Excerpt Options.
	$wp_customize->add_section( 'audioman_search_options', array(
		'panel'     => 'audioman_theme_options',
		'title'     => esc_html__( 'Search Options', 'audioman' ),
	) );

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_search_text',
			'default'           => esc_html__( 'Search', 'audioman' ),
			'sanitize_callback' => 'sanitize_text_field',
			'label'             => esc_html__( 'Search Text', 'audioman' ),
			'section'           => 'audioman_search_options',
			'type'              => 'text',
		)
	);
	
	// Homepage / Frontpage Options.
	$wp_customize->add_section( 'audioman_homepage_options', array(
		'description' => esc_html__( 'Only posts that belong to the categories selected here will be displayed on the front page', 'audioman' ),
		'panel'       => 'audioman_theme_options',
		'title'       => esc_html__( 'Homepage / Frontpage Options', 'audioman' ),
	) );

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_front_page_category',
			'sanitize_callback' => 'audioman_sanitize_category_list',
			'custom_control'    => 'Audioman_Multi_Categories_Control',
			'label'             => esc_html__( 'Categories', 'audioman' ),
			'section'           => 'audioman_homepage_options',
			'type'              => 'dropdown-categories',
		)
	);

	// Pagination Options.
	$pagination_type = get_theme_mod( 'audioman_pagination_type', 'default' );

	$nav_desc = '';

	/**
	* Check if navigation type is Jetpack Infinite Scroll and if it is enabled
	*/
	$nav_desc = sprintf(
		wp_kses(
			__( 'For infinite scrolling, use %1$sCatch Infinite Scroll Plugin%2$s with Infinite Scroll module Enabled.', 'audioman' ),
			array(
				'a' => array(
					'href' => array(),
					'target' => array(),
				),
				'br'=> array()
			)
		),
		'<a target="_blank" href="https://wordpress.org/plugins/catch-infinite-scroll/">',
		'</a>'
	);

	$wp_customize->add_section( 'audioman_pagination_options', array(
		'description'     => $nav_desc,
		'panel'           => 'audioman_theme_options',
		'title'           => esc_html__( 'Pagination Options', 'audioman' ),
		'active_callback' => 'audioman_scroll_plugins_inactive'
	) );

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_pagination_type',
			'default'           => 'default',
			'sanitize_callback' => 'audioman_sanitize_select',
			'choices'           => audioman_get_pagination_types(),
			'label'             => esc_html__( 'Pagination type', 'audioman' ),
			'section'           => 'audioman_pagination_options',
			'type'              => 'select',
		)
	);

	/* Scrollup Options */
	$wp_customize->add_section( 'audioman_scrollup', array(
		'panel'    => 'audioman_theme_options',
		'title'    => esc_html__( 'Scrollup Options', 'audioman' ),
	) );

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_disable_scrollup',
			'sanitize_callback' => 'audioman_sanitize_checkbox',
			'label'             => esc_html__( 'Disable Scroll Up', 'audioman' ),
			'section'           => 'audioman_scrollup',
			'type'              => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'audioman_theme_options' );

/** Active Callback Functions */
if( ! function_exists( 'audioman_scroll_plugins_inactive' ) ) :
	/**
	* Return true if infinite scroll functionality exists
	*
	* @since Audioman 1.0
	*/
	function audioman_scroll_plugins_inactive( $control ) {
		if ( ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'infinite-scroll' ) ) || class_exists( 'Catch_Infinite_Scroll' ) ) {
			// Support infinite scroll plugins.
			return false;
		}

		return true;
	}
endif;

