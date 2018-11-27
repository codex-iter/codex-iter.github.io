/**
 * Masonry layouts
 *
 * @requires  jQuery
 *
 * @package    Modern
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0.0
 * @version  2.2.0
 */

( function( $ ) {

	'use strict';

	if ( $().masonry ) {





		/**
		 * Gallery
		 */

			var
				$galleryContainers = $( '.gallery' );

			$galleryContainers
				.imagesLoaded( function() {

					// Processing

						$galleryContainers
							.masonry( {
								itemSelector    : '.gallery-item',
								percentPosition : true,
								isOriginLeft    : ( 'rtl' !== $( 'html' ).attr( 'dir' ) )
							} );

				} );



		/**
		 * Footer widgets
		 */
		if ( $( document.body ).hasClass( 'has-masonry-footer' ) ) {

			var
				$footerWidgetsContainer = $( '.footer-widgets' );

			$footerWidgetsContainer
				.imagesLoaded( function() {

					// Processing

						$footerWidgetsContainer
							.masonry( {
								itemSelector    : '.widget',
								percentPosition : true,
								isOriginLeft    : ( 'rtl' !== $( 'html' ).attr( 'dir' ) )
							} );

				} );



			/**
			 * Customize preview widgets partial refresh
			 *
			 * From Twenty Thirteen WordPress theme.
			 *
			 * @see  `twentythirteen/js/functions.js`
			 */
			if ( 'undefined' !== typeof wp && wp.customize && wp.customize.selectiveRefresh ) {

				// Retain previous masonry-brick initial position.
				wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function( placement ) {
					var copyPosition = (
						placement.partial.extended( wp.customize.widgetsPreview.WidgetPartial ) &&
						placement.removedNodes instanceof jQuery &&
						placement.removedNodes.is( '.masonry-brick' ) &&
						placement.container instanceof jQuery
					);
					if ( copyPosition ) {
						placement.container.css( {
							position: placement.removedNodes.css( 'position' ),
							top: placement.removedNodes.css( 'top' ),
							left: placement.removedNodes.css( 'left' )
						} );
					}
				} );

				// Re-arrange footer widgets when sidebar is updated via selective refresh in the Customizer.
				wp.customize.selectiveRefresh.bind( 'sidebar-updated', function( sidebarPartial ) {

					// Processing

						// Make sure we affect 'footer' widgetized area only.
						if ( 'footer' === sidebarPartial.sidebarId ) {

							$footerWidgetsContainer
								.masonry( 'reloadItems' )
								.masonry( 'layout' );

						}

				} );

			}



			/**
			 * Jetpack Infinite Scroll footer widgets reload
			 */

				$( document.body )
					.on( 'post-load', function() {

						// Processing

							setTimeout( function() {

								// Processing

									$footerWidgetsContainer
										.masonry( 'reload' );

							}, 100 );

					} );

		}



		/**
		 * Masonry posts
		 */
		if ( $( document.body ).hasClass( 'has-posts-layout-masonry' ) ) {

			var
				$postsList = $( '.posts' );

			$postsList
				.imagesLoaded( function() {

					// Processing

						$postsList
							.masonry( {
								itemSelector    : '.entry',
								percentPosition : true,
								isOriginLeft    : ( 'rtl' !== $( 'html' ).attr( 'dir' ) )
							} );

				} );



			/**
			 * Jetpack Infinite Scroll posts loading
			 *
			 * @subpackage  Jetpack
			 */

				$( document.body )
					.on( 'post-load', function() {

						// Processing

							$postsList
								.imagesLoaded( function() {

									// Processing

										$postsList
											.masonry( 'reload' );

								} );

					} );

		}





	} // /masonry

} )( jQuery );
