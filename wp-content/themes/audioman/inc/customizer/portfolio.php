<?php
/**
 * Add Portfolio Settings in Customizer
 *
 * @package Audioman
 */

/**
 * Add portfolio options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function audioman_portfolio_options( $wp_customize ) {
	// Add note to Jetpack Portfolio Section
	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_jetpack_portfolio_cpt_note',
			'sanitize_callback' => 'sanitize_text_field',
			'custom_control'    => 'Audioman_Note_Control',
			'label'             => sprintf( esc_html__( 'For Portfolio Options for this Theme, go %1$shere%2$s', 'audioman' ),
				 '<a href="javascript:wp.customize.section( \'audioman_portfolio\' ).focus();">',
				 '</a>'
			),
			'section'           => 'jetpack_portfolio',
			'type'              => 'description',
			'priority'          => 1,
		)
	);

	$wp_customize->add_section( 'audioman_portfolio', array(
			'panel'    => 'audioman_theme_options',
			'title'    => esc_html__( 'Portfolio', 'audioman' ),
		)
	);

	$action = 'install-plugin';
	$slug   = 'essential-content-types';

	$install_url = wp_nonce_url(
	    add_query_arg(
	        array(
	            'action' => $action,
	            'plugin' => $slug
	        ),
	        admin_url( 'update.php' )
	    ),
	    $action . '_' . $slug
	);

	// Add note to ECT Featured Content Section
    audioman_register_option( $wp_customize, array(
            'name'              => 'audioman_portfolio_jetpack_note',
            'sanitize_callback' => 'sanitize_text_field',
            'custom_control'    => 'Audioman_Note_Control',
            'active_callback'   => 'audioman_is_ect_portfolio_inactive',
            /* translators: 1: <a>/link tag start, 2: </a>/link tag close. */
            'label'             => sprintf( esc_html__( 'For Portfolio, install %1$sEssential Content Types%2$s Plugin with Portfolio Type Enabled', 'audioman' ),
                '<a target="_blank" href="' . esc_url( $install_url ) . '">',
                '</a>'

            ),
           'section'            => 'audioman_portfolio',
            'type'              => 'description',
            'priority'          => 1,
        )
    );

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_portfolio_option',
			'default'           => 'disabled',
			'sanitize_callback' => 'audioman_sanitize_select',
			'active_callback'	=> 'audioman_is_ect_portfolio_active',
			'choices'           => audioman_section_visibility_options(),
			'label'             => esc_html__( 'Enable on', 'audioman' ),
			'section'           => 'audioman_portfolio',
			'type'              => 'select',
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_portfolio_cpt_note',
			'sanitize_callback' => 'sanitize_text_field',
			'custom_control'    => 'Audioman_Note_Control',
			'active_callback'   => 'audioman_is_portfolio_active',
			'label'             => sprintf( esc_html__( 'For CPT heading and sub-heading, go %1$shere%2$s', 'audioman' ),
				 '<a href="javascript:wp.customize.control( \'jetpack_portfolio_title\' ).focus();">',
				 '</a>'
			),
			'section'           => 'audioman_portfolio',
			'type'              => 'description',
		)
	);

	audioman_register_option( $wp_customize, array(
			'name'              => 'audioman_portfolio_number',
			'default'           => 5,
			'sanitize_callback' => 'audioman_sanitize_number_range',
			'active_callback'   => 'audioman_is_portfolio_active',
			'label'             => esc_html__( 'Number of items to show', 'audioman' ),
			'section'           => 'audioman_portfolio',
			'type'              => 'number',
			'input_attrs'       => array(
				'style'             => 'width: 100px;',
				'min'               => 0,
			),
		)
	);

	$number = get_theme_mod( 'audioman_portfolio_number', 5 );

	for ( $i = 1; $i <= $number ; $i++ ) {
		//for CPT
		audioman_register_option( $wp_customize, array(
				'name'              => 'audioman_portfolio_cpt_' . $i,
				'sanitize_callback' => 'audioman_sanitize_post',
				'active_callback'   => 'audioman_is_portfolio_active',
				'label'             => esc_html__( 'Portfolio', 'audioman' ) . ' ' . $i ,
				'section'           => 'audioman_portfolio',
				'type'              => 'select',
				'choices'           => audioman_generate_post_array( 'jetpack-portfolio' ),
			)
		);
	} // End for().
}
add_action( 'customize_register', 'audioman_portfolio_options' );

/**
 * Active Callback Functions
 */
if ( ! function_exists( 'audioman_is_portfolio_active' ) ) :
	/**
	* Return true if portfolio is active
	*
	* @since Audioman 1.0
	*/
	function audioman_is_portfolio_active( $control ) {
		$enable = $control->manager->get_setting( 'audioman_portfolio_option' )->value();

		//return true only if previwed page on customizer matches the type of content option selected
		return ( audioman_check_section( $enable ) );
	}
endif;

if ( ! function_exists( 'audioman_is_ect_portfolio_inactive' ) ) :
    /**
    *
    * @since Adonis 0.1
    */
    function audioman_is_ect_portfolio_inactive( $control ) {
        return ! ( class_exists( 'Essential_Content_Jetpack_Portfolio' ) || class_exists( 'Essential_Content_Pro_Jetpack_Portfolio' ) );
    }
endif;

if ( ! function_exists( 'audioman_is_ect_portfolio_active' ) ) :
    /**
    *
    * @since Adonis 0.1
    */
    function audioman_is_ect_portfolio_active( $control ) {
        return ( class_exists( 'Essential_Content_Jetpack_Portfolio' ) || class_exists( 'Essential_Content_Pro_Jetpack_Portfolio' ) );
    }
endif;