<?php
/**
 * Add Testimonial Settings in Customizer
 *
 * @package Audioman
*/

/**
 * Add testimonial options to theme options
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function audioman_testimonial_options( $wp_customize ) {
    // Add note to Jetpack Testimonial Section
    audioman_register_option( $wp_customize, array(
            'name'              => 'audioman_jetpack_testimonial_cpt_note',
            'sanitize_callback' => 'sanitize_text_field',
            'custom_control'    => 'Audioman_Note_Control',
            'label'             => sprintf( esc_html__( 'For Testimonial Options for this Theme, go %1$shere%2$s', 'audioman' ),
                '<a href="javascript:wp.customize.section( \'audioman_testimonials\' ).focus();">',
                 '</a>'
            ),
           'section'            => 'jetpack_testimonials',
            'type'              => 'description',
            'priority'          => 1,
        )
    );

    $wp_customize->add_section( 'audioman_testimonials', array(
            'panel'    => 'audioman_theme_options',
            'title'    => esc_html__( 'Testimonials', 'audioman' ),
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
            'name'              => 'audioman_testimonial_note_1',
            'sanitize_callback' => 'sanitize_text_field',
            'custom_control'    => 'Audioman_Note_Control',
            'active_callback'   => 'audioman_is_ect_testimonial_inactive',
            /* translators: 1: <a>/link tag start, 2: </a>/link tag close. */
            'label'             => sprintf( esc_html__( 'For Testimonial, install %1$sEssential Content Types%2$s Plugin with Testimonial Content Type Enabled', 'audioman' ),
                '<a target="_blank" href="' . esc_url( $install_url ) . '">',
                '</a>'

            ),
           'section'            => 'audioman_testimonials',
            'type'              => 'description',
            'priority'          => 1,
        )
    );

    audioman_register_option( $wp_customize, array(
            'name'              => 'audioman_testimonial_option',
            'default'           => 'disabled',
            'sanitize_callback' => 'audioman_sanitize_select',
            'active_callback'   => 'audioman_is_ect_testimonial_active',
            'choices'           => audioman_section_visibility_options(),
            'label'             => esc_html__( 'Enable on', 'audioman' ),
            'section'           => 'audioman_testimonials',
            'type'              => 'select',
            'priority'          => 1,
        )
    );

    audioman_register_option( $wp_customize, array(
            'name'              => 'audioman_testimonial_cpt_note',
            'sanitize_callback' => 'sanitize_text_field',
            'custom_control'    => 'Audioman_Note_Control',
            'active_callback'   => 'audioman_is_testimonial_active',
            'label'             => sprintf( esc_html__( 'For CPT heading and sub-heading, go %1$shere%2$s', 'audioman' ),
                '<a href="javascript:wp.customize.section( \'jetpack_testimonials\' ).focus();">',
                '</a>'
            ),
            'section'           => 'audioman_testimonials',
            'type'              => 'description',
        )
    );

    audioman_register_option( $wp_customize, array(
            'name'              => 'audioman_testimonial_number',
            'default'           => '3',
            'sanitize_callback' => 'audioman_sanitize_number_range',
            'active_callback'   => 'audioman_is_testimonial_active',
            'label'             => esc_html__( 'Number of items to show', 'audioman' ),
            'section'           => 'audioman_testimonials',
            'type'              => 'number',
            'input_attrs'       => array(
                'style'             => 'width: 100px;',
                'min'               => 0,
            ),
        )
    );

    $number = get_theme_mod( 'audioman_testimonial_number', 3 );

    for ( $i = 1; $i <= $number ; $i++ ) {
        //for CPT
        audioman_register_option( $wp_customize, array(
                'name'              => 'audioman_testimonial_cpt_' . $i,
                'sanitize_callback' => 'audioman_sanitize_post',
                'active_callback'   => 'audioman_is_testimonial_active',
                'label'             => esc_html__( 'Testimoial', 'audioman' ) . ' ' . $i ,
                'section'           => 'audioman_testimonials',
                'type'              => 'select',
                'choices'           => audioman_generate_post_array( 'jetpack-testimonial' ),
            )
        );
    } // End for().
}
add_action( 'customize_register', 'audioman_testimonial_options' );

/**
 * Active Callback Functions
 */
if ( ! function_exists( 'audioman_is_testimonial_active' ) ) :
    /**
    * Return true if testimonial is active
    *
    * @since Audioman 1.0
    */
    function audioman_is_testimonial_active( $control ) {
        $enable = $control->manager->get_setting( 'audioman_testimonial_option' )->value();

        return ( audioman_check_section( $enable ) );
    }
endif;

if ( ! function_exists( 'audioman_is_ect_testimonial_inactive' ) ) :
    /**
    *
    * @since Audioman 0.1
    */
    function audioman_is_ect_testimonial_inactive( $control ) {
        return ! ( class_exists( 'Essential_Content_Jetpack_Testimonial' ) || class_exists( 'Essential_Content_Pro_Jetpack_Testimonial' ) );
    }
endif;

if ( ! function_exists( 'audioman_is_ect_testimonial_active' ) ) :
    /**
    *
    * @since Audioman 0.1
    */
    function audioman_is_ect_testimonial_active( $control ) {
        return ( class_exists( 'Essential_Content_Jetpack_Testimonial' ) || class_exists( 'Essential_Content_Pro_Jetpack_Testimonial' ) );
    }
endif;