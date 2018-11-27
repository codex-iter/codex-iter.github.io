/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */
( function( $ ) {
    // Site title and description.
    wp.customize( 'blogname', function( value ) {
        value.bind( function( to ) {
            $( '.site-title a' ).text( to );
        } );
    } );
    wp.customize( 'blogdescription', function( value ) {
        value.bind( function( to ) {
            $( '.site-description' ).text( to );
        } );
    } );
    // Header text color without header media background.
    wp.customize( 'header_textcolor', function( value ) {
        value.bind( function( to ) {
            if ( 'blank' === to ) {
                $( '.site-title a, .site-description' ).css( {
                    'clip': 'rect(1px, 1px, 1px, 1px)',
                    'position': 'absolute'
                } );
            } else {
                $( '.site-title a, .site-description' ).css( {
                    'clip': 'auto',
                    'position': 'relative'
                } );
                
                $( 'body:not(.absolute-header) .site-title a, body:not(.absolute-header) .site-title a:hover, body:not(.absolute-header) .site-title a:focus,    body:not(.absolute-header) .site-description, body:not(.absolute-header) .main-navigation a, body:not(.absolute-header) .menu-toggle, body:not(.absolute-header) .dropdown-toggle, body:not(.absolute-header) .site-header-cart .cart-contents, body:not(.absolute-header) .site-header-menu .social-navigation a' ).css( {
                    'color': to
                } );
            }
        } );
    } );
    // Header text color with header media background.
    wp.customize( 'header_textcolor_with_header_media', function( value ) {
        value.bind( function( to ) {
            $( '.absolute-header .site-title a, .absolute-header .site-title a:hover, .absolute-header .site-title a:focus, .absolute-header .site-description, .absolute-header .main-navigation a, .absolute-header .menu-toggle, .absolute-header .dropdown-toggle, .absolute-header .site-header-cart .cart-contents, .absolute-header .site-header-menu .social-navigation a' ).css( {
                'color': to
            } );
        } );
    } );
} )( jQuery );