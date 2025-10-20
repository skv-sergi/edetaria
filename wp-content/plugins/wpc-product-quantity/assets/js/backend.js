'use strict';

jQuery( document ).ready( function( $ ) {
	if ( $( "input[name='_woopq_quantity']:checked" ).val() == 'overwrite' ) {
		$( '.woopq_show_if_overwrite' ).css( 'display', 'flex' );
	}

	$( "input[name='_woopq_quantity']" ).on( 'change', function() {
		if ( $( this ).val() == 'overwrite' ) {
			$( '.woopq_show_if_overwrite' ).css( 'display', 'flex' );
		} else {
			$( '.woopq_show_if_overwrite' ).css( 'display', 'none' );
		}
	} );
} );