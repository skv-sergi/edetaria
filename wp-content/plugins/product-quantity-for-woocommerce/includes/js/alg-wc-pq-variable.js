/**
 * alg-wc-pq-variable.js
 *
 * @version 1.7.0
 * @since   1.0.0
 */

/**
 * check_qty
 *
 * @version 1.7.0
 * @since   1.0.0
 * @todo    [dev] (maybe) `jQuery( '[name=quantity]' ).val( '0' )` on `jQuery.isEmptyObject( product_quantities[ variation_id ] )` (i.e. instead of `return`)
 */
function check_qty() {
	var variation_id = jQuery( this ).val();
	if ( 0 == variation_id || jQuery.isEmptyObject( product_quantities[ variation_id ] ) ) {
		return;
	}
	var quantity_input = jQuery( this ).parent().find( '[name=quantity]' );
	var quantity_dropdown = jQuery( this ).parent().find( 'select[name=quantity_pq_dropdown]' );
	
	// Step
	var step = parseFloat( product_quantities[ variation_id ][ 'step' ] );
	var default_qty = ( !isNaN (parseFloat( product_quantities[ variation_id ][ 'default' ] ) ) ? parseFloat( product_quantities[ variation_id ][ 'default' ] ) : 1  );

	if ( 0 != step ) {
		quantity_input.attr( 'step', step );
	}
	// Min/max
	var current_qty = quantity_input.val();
	if ( quantities_options[ 'reset_to_min' ] ) {
		quantity_input.val( product_quantities[ variation_id ][ 'min_qty' ] );
	} else if ( quantities_options[ 'reset_to_max' ] ) {
		quantity_input.val( product_quantities[ variation_id ][ 'max_qty' ] );
	} else if ( current_qty < parseFloat( product_quantities[ variation_id ][ 'min_qty' ] ) ) {
		quantity_input.val( product_quantities[ variation_id ][ 'min_qty' ] );
	} else if ( current_qty > parseFloat( product_quantities[ variation_id ][ 'max_qty' ] ) ) {
		quantity_input.val( product_quantities[ variation_id ][ 'max_qty' ] );
	}
	
	if (default_qty > 0) {
		if ( default_qty < parseFloat( product_quantities[ variation_id ][ 'min_qty' ] ) ) {
			default_qty = ( !isNaN (parseFloat( product_quantities[ variation_id ][ 'min_qty' ] ) ) ? parseFloat( product_quantities[ variation_id ][ 'min_qty' ] ) : 1  );
			quantity_input.attr( 'value', default_qty );
		} else if ( default_qty > parseFloat( product_quantities[ variation_id ][ 'max_qty' ] ) ) {
			default_qty = ( !isNaN (parseFloat( product_quantities[ variation_id ][ 'max_qty' ] ) ) ? parseFloat( product_quantities[ variation_id ][ 'max_qty' ] ) : 100  );
			quantity_input.attr( 'value', default_qty );
		} else {
			quantity_input.attr( 'value', default_qty );
		}
	}
	
	if ( quantity_dropdown.length > 0 ) {
		change_select_options( variation_id, quantity_dropdown, default_qty );
	}
}

/**
 * change_select_options
 *
 * @version 1.7.0
 * @since   1.7.0
 */
function change_select_options( variation_id, quantity_dropdown, default_qty ) {
	
	var step = parseFloat( product_quantities[ variation_id ][ 'step' ] );
	var max_value_fallback = parseFloat( quantities_options[ 'max_value_fallback' ]);
	var max_qty = ( !isNaN (parseFloat( product_quantities[ variation_id ][ 'max_qty' ] ) ) ? parseFloat( product_quantities[ variation_id ][ 'max_qty' ] ) : max_value_fallback  );
	var min_qty = ( !isNaN (parseFloat( product_quantities[ variation_id ][ 'min_qty' ] ) ) ? parseFloat( product_quantities[ variation_id ][ 'min_qty' ] ) : 1  );
	var label_singular = product_quantities[ variation_id ][ 'label' ][ 'singular' ];
	var label_plural = product_quantities[ variation_id ][ 'label' ][ 'plural' ];
	
	var html = '';
	var selected = '';
	for (i = min_qty; i <= max_qty; i = i + step) {
		var option_label_txt = ( i > 1 ? label_plural : label_singular );
		var option_txt = option_label_txt.replace("%qty%", i);
		selected = (i===default_qty ? 'selected="selected"' : '' );
		html += '<option value="' + i + '" ' + selected + '>' + option_txt + '</option>';
	}

	var sel_id = quantity_dropdown.attr('id');
	jQuery( "#"+sel_id ).html(html).change();
}


/**
 * check_qty_all
 *
 * @version 1.7.0
 * @since   1.7.0
 */
function check_qty_all() {
	jQuery( '[name=variation_id]' ).each( check_qty );
}

/**
 * document ready
 *
 * @version 1.7.0
 * @since   1.0.0
 */
jQuery( document ).ready( function() {
	if ( quantities_options[ 'do_load_all_variations' ] ) {
		jQuery( 'body' ).on( 'change', check_qty_all );
	} else {
		jQuery( '[name=variation_id]' ).on( 'change', check_qty );
	}
} );
