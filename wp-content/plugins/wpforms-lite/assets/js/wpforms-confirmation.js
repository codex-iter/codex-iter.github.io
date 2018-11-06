/* globals jQuery */
;( function( $ ){
	$( function(){
		if ( $( 'div.wpforms-confirmation-scroll' ).length ) {
			$( 'html,body' ).animate( {
				scrollTop: ( $( 'div.wpforms-confirmation-scroll' ).offset().top ) - 100
			}, 1000 );
		}
	} );
}( jQuery ) );
