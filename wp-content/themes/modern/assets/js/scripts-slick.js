/**
 * Slick slideshow scripts
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.2.0
 */

( function( $ ) {

	'use strict';

	if ( $().slick ) {





		// Helper variables

			var
				$slickLocalize = ( 'undefined' !== typeof $modernSlickLocalize ) ? ( $modernSlickLocalize ) : ( { 'prev_text' : 'Previous', 'next_text' : 'Next' } ),
				$htmlButton = {
					'prev' : '<button type="button" class="slick-prev"><span class="genericon genericon-previous" aria-hidden="true"></span><span class="screen-reader-text">' + $slickLocalize['prev_text'] + '</span></button>',
					'next' : '<button type="button" class="slick-next"><span class="genericon genericon-next" aria-hidden="true"></span><span class="screen-reader-text">' + $slickLocalize['next_text'] + '</span></button>'
				};



		/**
		 * Gallery post format slideshow
		 */
		if ( ! $( document.body ).hasClass( 'has-posts-layout-masonry' ) ) {

			var
				$slickContainerPostFormatGallery = '.format-gallery .entry-media-gallery-images',
				$slickArgsPostFormatGallery = {
					'adaptiveHeight' : true,
					'autoplay'       : true,
					'fade'           : true,
					'pauseOnHover'   : true,
					'slide'          : '.entry-media-gallery-image',
					'swipeToSlide'   : true,
					'prevArrow'      : $htmlButton['prev'],
					'nextArrow'      : $htmlButton['next'],
					'rtl'            : ( 'rtl' === $( 'html' ).attr( 'dir' ) )
				};

			function setupSlickPostFormatGallery( element, slick ) {

				// Processing

					slick
						.options
							.autoplaySpeed = ( 3500 + Math.floor( Math.random() * 4 ) * 400 );

					element
						.find( '.slick-next' )
							.before( element.find( '.slick-prev' ) );

			} // /setupSlickPostFormatGallery

			$( $slickContainerPostFormatGallery )
				.on( 'init', function( e, slick ) {
					setupSlickPostFormatGallery( $( this ), slick );
				} )
				.slick( $slickArgsPostFormatGallery );

			$( document.body )
				.on( 'post-load', function() {

					// Processing

						$( $slickContainerPostFormatGallery + ':not(.slick-initialized)' )
							.on( 'init', function( e, slick ) {
								setupSlickPostFormatGallery( $( this ), slick );
							} )
							.slick( $slickArgsPostFormatGallery );

				} );

		}



		/**
		 * Intro slideshow
		 *
		 * For featured posts slideshow we need to create 2 slideshows:
		 * - one for images,
		 * - and one for titles.
		 * We sync these slideshows then: titles slideshow controls the media one.
		 */
		if ( $( document.body ).hasClass( 'has-intro-slideshow' ) ) {

			var
				$slickArgsIntroMedia = {
					'adaptiveHeight' : true,
					'accessibility'  : false,
					'arrows'         : false,
					'draggable'      : false,
					'fade'           : true,
					'pauseOnHover'   : false,
					'swipe'          : false,
					'slide'          : '.intro-slideshow-item',
					'touchMove'      : false,
					'rtl'            : ( 'rtl' === $( 'html' ).attr( 'dir' ) )
				},
				$slickArgsIntroContent = {
					'adaptiveHeight' : true,
					'asNavFor'       : '#intro-slideshow-media',
					'autoplay'       : true,
					'autoplaySpeed'  : 8000,
					'dots'           : true,
					'fade'           : true,
					'pauseOnHover'   : true,
					'slide'          : '.intro-slideshow-item',
					'swipeToSlide'   : true,
					'prevArrow'      : $htmlButton['prev'],
					'nextArrow'      : $htmlButton['next'],
					'rtl'            : ( 'rtl' === $( 'html' ).attr( 'dir' ) )
				};

			// Initialize the actual sliders

				$( '#intro-slideshow-media' )
					.slick( $slickArgsIntroMedia );
				$( '#intro-slideshow-content' )
					.slick( $slickArgsIntroContent );

		}





	} // /slick

} )( jQuery );
