/**
 * Customize preview scripts
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0.0
 * @version  2.0.0
 *
 * Contents:
 *
 *  10) WordPress options
 * 100) Helpers
 */





/**
 * 10) WordPress options
 */
( function( $ ) {





	/**
	 * Site title and description
	 */

		wp.customize( 'blogname', function( value ) {

			// Processing

				value
					.bind( function( to ) {

						$( '.site-title' )
							.text( to );

					} );

		} ); // /blogname

		wp.customize( 'blogdescription', function( value ) {

			// Processing

				value
					.bind( function( to ) {

						$( '.site-description' )
							.text( to );

					} );

		} ); // /blogdescription

		wp.customize( 'header_textcolor', function( value ) {

			// Processing

				value
					.bind( function( to ) {

						if ( 'blank' === to ) {

							$( '.site-title, .site-description' )
								.css( {
									'clip'     : 'rect(1px, 1px, 1px, 1px)',
									'position' : 'absolute',
								} );

							$( 'body' )
								.addClass( 'site-title-hidden' );

						} else {

							$( '.site-title, .site-description' )
								.css( {
									'clip'     : 'auto',
									'position' : 'relative',
								} );

							$( 'body' )
								.removeClass( 'site-title-hidden' );

						}

					} );

		} ); // /header_textcolor



	/**
	 * Body background color
	 */

		wp.customize( 'background_color', function( value ) {

			// Processing

				value
					.bind( function( to ) {

						/**
						 * @see `assets/scss/main/custom-styles/__intro.scss`
						 *
						 * Unfortunately, jQuery can not apply styles on CSS pseudo elements,
						 * so we need to set the linear gradient background on container,
						 * and then make sure the pseudo element inherits the styles (see below).
						 */
						$( '.intro-media' )
							.css( {
								'background-image' : 'linear-gradient( transparent, ' + to + ' )',
							} )
							.addClass( 'background-changed' );

					} );

				// Make sure the pseudo element inherits the above styles

					$( '#intro-container' )
						.append( '<style> .intro-media.background-changed::after { background-image: inherit; } </style>' );

		} ); // /background_color





} )( jQuery );





/**
 * 100) Helpers
 */
( function( window ) {

	window.modern = window.modern || {};

	/**
	 * Theme customizer preview helper
	 *
	 * @since    2.0.0
	 * @version  2.0.0
	 */
	window.modern.Customize = {





		/**
		 * Convert hex color into rgb array
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		hexToRgb : function( $hex = '' ) {

			// Processing

				var
					$rgb = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec( $hex );


			// Output

				return ( $rgb ) ? ( [
					parseInt( $rgb[1], 16 ),
					parseInt( $rgb[2], 16 ),
					parseInt( $rgb[3], 16 )
				] ) : ( [] );

		}, // /hexToRgb



		/**
		 * Convert hex color into rgb array
		 *
		 * @since    2.0.0
		 * @version  2.0.0
		 */
		hexToRgbJoin : function( $hex = '' ) {

			// Output

				return this.hexToRgb( $hex ).join();

		} // /hexToRgb





	} // /Customize

} )( window );
