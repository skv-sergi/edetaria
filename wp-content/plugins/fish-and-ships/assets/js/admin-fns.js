/**
 * Javascript for the shipping method functionality.
 *
 * @package Fish and Ships
 * @version 1.0.0
 */

jQuery(document).ready(function($) {

	/*******************************************************
	    1. Global functions
	 *******************************************************/
	 
	// We're on Fish and ships form?
	if ($("#shipping-rules-table-fns").length != 0) {
		$('body').addClass('wc-fish-and-ships');
		if ($('#wc-fns-freemium-panel.pro-version.closed').length != 0) $('body').addClass('wc-fish-and-ships-pro-closed');
	}

	// Field validation error tips
	$( document.body )
	.on( 'keyup change', '.wc_fns_input_decimal[type=text], .wc_fns_input_positive_decimal[type=text], .wc_fns_input_integer[type=text], .wc_fns_input_positive_integer[type=text]', function() {
		
		var regex, error;
		var value = $( this ).val();
		
		if ( $( this ).is( '.wc_fns_input_decimal' )) {

			regex = new RegExp('^-?[0-9]+(\\' + woocommerce_admin.decimal_point + '?[0-9]*)?$');
			error = 'i18n_decimal_error';

		} else if ( $( this ).is( '.wc_fns_input_positive_decimal' ) ) {

			regex = new RegExp('^[0-9]+(\\' + woocommerce_admin.decimal_point + '?[0-9]*)?$');
			error = 'i18n_decimal_error';

		} else if ( $( this ).is( '.wc_fns_input_integer' ) ) {

			regex = new RegExp( '^-?[0-9]+$', 'gi' );
			error = 'i18n_fns_integer_error';
			woocommerce_admin[error] = wcfns_data[error];

		} else {
			regex = new RegExp( '^[0-9]+$', 'gi' ); //wc_fns_input_positive_integer
			error = 'i18n_fns_integer_error';
			woocommerce_admin[error] = wcfns_data[error];
		}

		if ( value != '' && !regex.test(value)) {
			$( document.body ).triggerHandler( 'wc_add_error_tip', [ $( this ), error ] );
		} else {
			$( document.body ).triggerHandler( 'wc_remove_error_tip', [ $( this ), error ] );
		}
	});

	// Field info tips
	$( document.body ).on( 'focus', '.wc_fns_input_tip', function() {
		
		info_type = $(this).attr('data-wc-fns-tip');
		if (typeof info_type === typeof undefined || info_type === false) return;

		var offset = $(this).position();

		if ( $(this).parent().find( '.wc_fns_info_tip' ).length === 0 ) {
			$('.wc_fns_info_tip').remove();
			$(this).after( '<div class="wc_fns_info_tip ' + info_type + '">' + wcfns_data[info_type] + '</div>' );
			$(this).parent().find( '.wc_fns_info_tip' )
				.css( 'left', offset.left + $(this).width() - ( $(this).width() / 2 ) - ( $( '.wc_fns_info_tip' ).width() / 2 ) )
				.css( 'top', offset.top + $(this).height() )
				.fadeIn( '100' );
		}
	});

	// Field info tips (exit)
	$( document.body ).on( 'blur', '.wc_fns_input_tip', function() {
		
		$(this).parent().find( '.wc_fns_info_tip' ).remove();
	});
	
	/* Unsaved alert */
	if ($("#shipping-rules-table-fns").length != 0) {

		var unsaved = false;
		var sending = false;
		
		$(window).on( 'beforeunload', function() {
			if (unsaved && !sending) return wcfns_data.i18n_unsaved;
		});
		
		$("#shipping-rules-table-fns, table.form-table").on('change', 'input, select, textarea', function () {
			unsaved = true;
		});
		
		$('#mainform').submit(function () {
			sending = true;
		});
	}
	

	/*******************************************************
	    2. Global shipping rules table
	 *******************************************************/

	/*	Make the rules sortable. 
		Get it from:
		woocommerce/assets/js/admin/term-ordering.js
	*/

	function fix_cell_colors($where) {
		// Fix cell colors
		$( $where ).each( function(index, element) {
			if ( index%2 === 0 ) {
				jQuery( element ).addClass( 'alternate' );
			} else {
				jQuery( element ).removeClass( 'alternate' );
			}
		});
	}

	$("#shipping-rules-table-fns .column-handle").css('display', 'table-cell');

	$("#shipping-rules-table-fns tbody").sortable({

		//items: item_selector,
		cursor: 'move',
		handle: '.column-handle',
		axis: 'y',
		forcePlaceholderSize: true,
		helper: 'clone',
		opacity: 0.65,
		placeholder: 'fns-rule-placeholder',
		scrollSensitivity: 40,

		// This event is triggered when the user stopped sorting and the DOM position has changed.
		update: function( event, ui ) {

			fix_cell_colors('#shipping-rules-table-fns tbody tr');

			refresh_rules();
		}
	});
	
	/* Add new rule */
	$('#shipping-rules-table-fns a.add-rule').click(function () {
		
		//add new row
		$('#shipping-rules-table-fns tbody').append(wcfns_data.empty_row_html);
		
		//Mark background in yellow and fadeout
		$('#shipping-rules-table-fns tbody tr:last')
			.addClass('animate-bg');
		setTimeout(function() {
			$('#shipping-rules-table-fns tbody tr').removeClass('animate-bg');
		}, 50);

		//add a new selector in it
		$('#shipping-rules-table-fns tbody .add-selector a').last().trigger('click');

		/* renum and make sortable the new items */
		refresh_rules();
		$("#shipping-rules-table-fns .column-handle").css('display', 'table-cell');
		$("#shipping-rules-table-fns tbody").sortable( "refresh" );
		return false;
	});

	/* Duplicate selected rules */
	$('#shipping-rules-table-fns a.duplicate-rules').click(function () {

		// Save select values (jQuery bug at clone)
		$("#shipping-rules-table-fns select").each(function(index, element) {
			$(element).attr('data-save', $(element).val());
		});

		$("#shipping-rules-table-fns tbody .check-column input:checked").closest('tr')
			.clone()
			.addClass('animate-bg')
			.appendTo("#shipping-rules-table-fns tbody ");

		// fadeOut by CSS
		setTimeout(function() {
			$('#shipping-rules-table-fns tbody tr').removeClass('animate-bg');
		}, 50);
		
		// Fix select values (jQuery bug at clone)
		$("#shipping-rules-table-fns select").each(function(index, element) {
			$(element).val($(element).attr('data-save'));
		});
		
		$("#shipping-rules-table-fns .check-column input").prop( "checked", false );
		
		/* renum and make sortable the new items */

		unsaved = true;
		refresh_rules();
		$("#shipping-rules-table-fns .column-handle").css('display', 'table-cell');
		$("#shipping-rules-table-fns tbody").sortable( "refresh" );
		return false;
	});

	/* Delete selected rules */
	$('#shipping-rules-table-fns a.delete-rules').click(function () {

		$("#shipping-rules-table-fns tbody .check-column input:checked").closest('tr')
			
			// Mark red, fadeout and then remove and refresh
			.css('background', '#c91010')
			.fadeOut(function () {
			
				$("#shipping-rules-table-fns tbody .check-column input:checked").closest('tr')
					.remove();
	
				// All rules are deleted? let's put an empty one
				if ($("#shipping-rules-table-fns tbody tr").length == 0) {
					$('#shipping-rules-table-fns .add-rule').last().trigger('click');
				}

				$("#shipping-rules-table-fns .check-column input").prop( "checked", false );

				/* renum and make sortable the new items */
				refresh_rules();
				$("#shipping-rules-table-fns .column-handle").css('display', 'table-cell');
				$("#shipping-rules-table-fns tbody").sortable( "refresh" );
			});
		
		unsaved = true;

		return false;
	});

	function refresh_rules() {
		
		check_volumetric();

		$("#shipping-rules-table-fns tbody tr").each(function(index, element) {
			
			// renum the rules. Only cosmethic. I miss BASIC coding... XDD
			$('td.order-number', element).html('#' + (index+1) );
			
			// refresh helpers
			$(".helper", element).each(function(idx, helper) {
				$(helper).html(idx == 0 ? wcfns_data.i18n_where : wcfns_data.i18n_and);
			});
			
			// when there are only one selector on a rule, they can't be erased (only in the selection column!!)
			$(".selection-rules-column .delete", element).css('display', $(".selection-rules-column .delete", element).length == 1 ? 'none' : 'block');
			
			// rename the fields (rule number, first occurrence)
			$('input, select', element).each(function(idx, field) {
				name = $(field).attr('name');
				$(field).attr('name', name.replace(/\[[0-9]+\]/, '['+index+']'));
			});

			// second match for selections
			$(".selection_details", element).each(function(index_det, element_det) {

				// rename the fields (selection number, the second occurrence)
				$('input, select, textarea', element_det).each(function(idx_det, field_det) {
					name = $(field_det).attr('name');
					t=0;
					$(field_det).attr('name', name.replace(/\[[0-9]+\]/g, function (match) {
						t++;
						if (t==2) return '['+index_det+']'
						if (t!=1) console.log('Fish n Ships: error on replacement key number (selection)');
						return match;
					}));
				});
			});
			// second match for actions
			$(".action_details", element).each(function(index_det, element_det) {

				// rename the fields (selection number, the second occurrence)
				$('input, select, textarea', element_det).each(function(idx_det, field_det) {
					name = $(field_det).attr('name');
					t=0;
					$(field_det).attr('name', name.replace(/\[[0-9]+\]/g, function (match) {
						t++;
						if (t==2) return '['+index_det+']'
						if (t!=1) console.log('Fish n Ships: error on replacement key number (action)');
						return match;
					}));
				});
			});
		});

		// Show/hide the bottom header if there is much info
		if ($("#shipping-rules-table-fns tbody").outerHeight() > 300) {
			$("#shipping-rules-table-fns .fns-footer-header").show();
		} else {
			$("#shipping-rules-table-fns .fns-footer-header").hide();
		}

		// equalize helpers
		max_width = 0;
		$("#shipping-rules-table-fns .helper").each(function(idx, helper) {
			w = $(helper).width();
			if (max_width < w) max_width = w;
		});
		$("#shipping-rules-table-fns .selection_wrapper").css('padding-left', max_width + 10);
		
		// restart tips
		$( document.body ).trigger( 'init_tooltips' );
	}


	/*******************************************************
	    2.1. Selection rules column
	 *******************************************************/
	
	/* Add new selection condition */
	$('#shipping-rules-table-fns tbody').on('click', '.add-selector .button', function () {
		cont = $(this).closest('td');
		$('.selectors', cont).append(wcfns_data.new_selection_method_html);
		
		//Mark background in yellow and fadeout
		$('.selection_wrapper:last', cont)
			.addClass('animate-bg');
		setTimeout(function() {
			$('.selection_wrapper:last', cont).removeClass('animate-bg');
		}, 50);


		unsaved = true;
		refresh_rules();

		return false;
	});
	
	/* Delete selection condition */
	$('#shipping-rules-table-fns tbody').on('click', '.selection_wrapper .delete', function () {
		
		//$(this).closest('.selection_wrapper').remove();

		// Mark red, fadeout and then remove and refresh
		$(this).closest('.selection_wrapper')
		
			.css('background', '#c91010')
			.fadeOut(function () {

				$(this).remove();
				refresh_rules();
			});

		unsaved = true;

		return false;
	});
	
	// Saving the previous selection value
	$('#shipping-rules-table-fns tbody').on('focus', '.wc-fns-selection-method', function () {
		$(this).attr('data-last-value', $(this).val() );
	});
	
	/* Change the auxiliary fields at change one selection condition */
	$('#shipping-rules-table-fns tbody').on('change', '.wc-fns-selection-method', function () {

		val = $(this).val();

		if (val == 'pro') {
			// Set the last valid option on select
			$(this).val( $(this).attr('data-last-value') );
			// Show pro help popup
			show_help( 'pro', false, wcfns_data['admin_lang'] );

		} else {

			cont = $(this).closest('.selection_wrapper');

			$('.selection_details', cont).html('');
			if (val != '') $('.selection_details', cont).html(wcfns_data['selection_' + val + '_html']);
			
			ajax_div = $('.selection_details .wc-fns-ajax-fields', cont);
			if (ajax_div.length != 0) {
	
				// Ajaxified option
				
				$.ajax({
					url: wcfns_data['ajax_url_main_lang'],
					data: { action: 'wc_fns_fields', type: ajax_div.attr('data-type'), method_id: ajax_div.attr('data-method-id') },
					error: function (xhr, status, error) {
						var errorMessage = xhr.status + ': ' + xhr.statusText
						alert('Error loading auxiliary fields - ' + errorMessage);
					},
					success: function (data) {
						
						// Put the HTML and remember it for next usage
						wcfns_data['selection_' + val + '_html'] = data;
						$('.selection_details', cont).html(data);

						check_global_group_by();
						if ($('.selection_details select.chosen_select', cont).length != 0) {
							/* enharce select if there is one on it */
							$( document.body ).trigger('wc-enhanced-select-init');
						}
						refresh_rules();
					},
					dataType: 'html'
				});

			} else {
				
				// Non-ajaxified or previous ajax-loaded

				check_global_group_by();
				
				if ($('.selection_details select.chosen_select', cont).length != 0) {
					/* enharce select if there is one on it */
					$( document.body ).trigger('wc-enhanced-select-init');
				}
				refresh_rules();
			}
	
			// Save last value
			$(this).attr('data-last-value', $(this).val() );
	
			unsaved = true;
		}
		return false;
	});

	// Show or hide the general volumetric weight factor field if some selector use it
	function check_volumetric() {
		var volumetric = false;
		$('#shipping-rules-table-fns select.wc-fns-selection-method').each(function(index, element) {
			if ($(element).val()=='volumetric') volumetric = true;
		});
		if ( volumetric ) {
			$( '#woocommerce_fish_n_ships_volumetric_weight_factor' ).attr('required', 'required').closest( 'tr' ).show();
		} else {
			$( '#woocommerce_fish_n_ships_volumetric_weight_factor' ).removeAttr('required').closest( 'tr' ).hide();
		}
	}
	check_volumetric();

	// Show or hide the global group method field
	function check_global_group_by() {
		if( $( '#woocommerce_fish_n_ships_global_group_by' ).is( ':checked' ) ) {
			// Global group
			$( '#woocommerce_fish_n_ships_global_group_by_method' ).closest( 'tr' ).show();
			$( '#shipping-rules-table-fns .field-group_by' ).not('#shipping-rules-table-fns .field-cant-group_by').hide();
			if ($('#woocommerce_fish_n_ships_global_group_by_method').val() == 'none') {
				$('#shipping-rules-table-fns .field-cant-group_by').hide();
			} else {
				$('#shipping-rules-table-fns .field-cant-group_by').css('display', 'block');
			}
		} else {
			// Local selection group
			if ( $( '#woocommerce_fish_n_ships_global_group_by' ).hasClass('onlypro') ) {
				// Free version, keep checked and open the help about pro
				$( '#woocommerce_fish_n_ships_global_group_by' ).attr( 'checked', true );
				show_help( 'pro', false, wcfns_data['admin_lang'] );
			} else {
				$( '#woocommerce_fish_n_ships_global_group_by_method' ).closest( 'tr' ).hide();
				$( '#shipping-rules-table-fns .field-group_by' ).css('display','block');
			}
		}
	}
	$( '#woocommerce_fish_n_ships_global_group_by, #woocommerce_fish_n_ships_global_group_by_method' ).change( function(){
		check_global_group_by();
	});
	check_global_group_by();
	
	/*******************************************************
	    2.2. Shipping costs column
	 *******************************************************/
	
	// Show or hide the simple/composite cost fields
	function check_composite_cost() {
		$('#shipping-rules-table-fns select.wc-fns-cost-method').each(function(index, element) {

			cont = $(element).closest('td');

			if ($(element).val() == 'composite') {

				$('.cost_simple', cont).hide();
				$('.cost_composite', cont).show();
			} else {

				$('.cost_simple', cont).show();
				$('.cost_composite', cont).hide();
			}
		});
	}
	
	// Move the right value to the right field on change from siple to composite price
	$('#shipping-rules-table-fns tbody').on({
		focus: function () {
			$(this).attr('data-last-value', $(this).val() );
		},
		change: function () {

			check_composite_cost();

			// Move value on select change
			cont = $(this).closest('td');
			last_val = $(this).attr('data-last-value');

			if ($(this).val() == 'composite') {
				$('div.cost_composite input', cont).val(0);
				$('input.fns-cost-' + last_val, cont).val($('input.fns-cost', cont).val());
			} else if (last_val == 'composite') {
				$('input.fns-cost', cont).val($('input.fns-cost-' + $(this).val(), cont).val());
			}
			// Save last value
			$(this).attr('data-last-value', $(this).val() );
		}
	}, '.wc-fns-cost-method');
	
	check_composite_cost();
	
	/*******************************************************
	    2.3. Special actions column
	 *******************************************************/

	/* Add new action */
	$('#shipping-rules-table-fns tbody').on('click', '.add-action .button', function () {
		cont = $(this).closest('td');
		$('.actions', cont).append(wcfns_data.new_action_html);

		//Mark background in yellow and fadeout
		$('.action_wrapper:last', cont)
			.addClass('animate-bg');
		setTimeout(function() {
			$('.action_wrapper:last', cont).removeClass('animate-bg');
		}, 50);

		refresh_rules();
		unsaved = true;

		return false;
	});

	/* Delete action */
	$('#shipping-rules-table-fns tbody').on('click', '.action_wrapper .delete', function () {

		//$(this).closest('.action_wrapper').remove();

		// Mark red, fadeout and then remove and refresh
		$(this).closest('.action_wrapper')
		
			.css('background', '#c91010')
			.fadeOut(function () {
				
				$(this).remove();
				refresh_rules();
			});

		unsaved = true;

		return false;
	});


	// Saving the previous action value
	$('#shipping-rules-table-fns tbody').on('focus', '.wc-fns-actions', function () {
		$(this).attr('data-last-value', $(this).val() );
	});
	

	/* Change the auxiliary fields at change one action */
	$('#shipping-rules-table-fns tbody').on('change', '.wc-fns-actions', function () {

		val = $(this).val();
		if (val == 'pro') {
			// Set the last valid option on select
			$(this).val( $(this).attr('data-last-value') );
			// Show pro help popup
			show_help( 'pro', false, wcfns_data['admin_lang'] );

		} else {

			cont = $(this).closest('.action_wrapper');
	
			$('.action_details', cont).html('');
			if (val != '') $('.action_details', cont).html(wcfns_data['action_' + val + '_html']);
	
			$(this).attr('data-last-value', $(this).val() );
			refresh_rules();
			unsaved = true;
		}
		return false;
	});
	

	/*******************************************************
	    3. Logs
	 *******************************************************/
	
	$('#woocommerce_fish_n_ships_write_logs').change( function () {
		update_debug_advice();
	});
	
	function update_debug_advice() {
		if( $( '#woocommerce_fish_n_ships_write_logs' ).val() != 'off' ) {
			$('#wc_fns_debug_mode, #wc_fns_logs_list').show();
		} else {
			$('#wc_fns_debug_mode, #wc_fns_logs_list').hide();
		}
	}
	update_debug_advice();
	
	
	//Open/close logs
	jQuery('#fnslogs .open_close').click(function() {
		cont = $(this).closest('tr');
		if (cont.hasClass('fns-open-log')) {
			// Close log
			log_cont = $(cont).next();
			if (log_cont.hasClass('log_content')) {
				$('div.fns-log-details', log_cont).slideUp(function () {
					setTimeout(function () {
						$(cont).removeClass('fns-open-log');
					}, 300);
					$(log_cont).hide();
					$('<tr class="fix_stripped"><td colspan="6"></td></tr>').insertAfter(log_cont);
				});
			}
		} else {
			if (cont.hasClass('loaded')) {
				// Reopen log
				log_cont = $(cont).next();
				$(log_cont).show();
				$('div.fns-log-details', log_cont).slideDown(function () {
					$(cont).addClass('fns-open-log');
					fix = $(log_cont).next();
					if (fix.hasClass('fix_stripped')) fix.remove();
				});
			} else {
				// prevent double click
				if (!cont.hasClass('loading')) {
					cont.addClass('loading'); 
					// Load log
					$('<tr class="loading_log"><td colspan="6"><div class="fns-log-details"><span class="wc-fns-spinner"></span></div></td></tr>').insertAfter(cont);
	
					$.ajax({
						url: ajaxurl,
						data: { action: 'wc_fns_logs', name: $(this).attr('data-fns-log') },
						error: function (xhr, status, error) {
							var errorMessage = xhr.status + ': ' + xhr.statusText
							alert('Error loading log - ' + errorMessage);
							cont.removeClass('loading');
							log_content_tr = $(cont).next();
							if (log_content_tr.hasClass('loading_log')) log_content_tr.remove();
						},
						success: function (data) {
							$(cont).addClass('fns-open-log loaded').removeClass('');
	
							log_content_tr = $(cont).next();
							$(log_content_tr).addClass('log_content').removeClass('loading_log');
	
							div_log = $('.fns-log-details', log_content_tr);
							div_log.css({height: 60, overflow: 'hidden'}).html('<div class="wrap">' + data + '</div>');
	
							setTimeout(function () {
								$(div_log).animate({height: $('.wrap', div_log).height()}, function () {
									$(div_log).css({overflow: '', height: ''});
									cont.removeClass('loading'); 
								});
							},10);
						},
						dataType: 'html'
					});
				}
			}
		}
		return false;
	});
	
	

	/*******************************************************
	    4. Dialogs and Help
	 *******************************************************/

	/* popup dialogs */
	$('#shipping-rules-table-fns tbody').on('click', '.fns_open_fields_popup', function () {
		
		close_popup_dialog();
		close_popup_help();
		
		//Let's create a wrapper and copy the fields on it
		cont = $(this).closest('.action_details');
		$('.fns_fields_popup_wrap', cont).addClass('fns_popup_opened')
		
		$('body').append('<div id="fns_dialog"></div>');
		$('.fns_fields_popup', cont).appendTo('#fns_dialog');
		
		//Open the copy
		$('#fns_dialog').dialog({
			title: $('#fns_dialog .fns_fields_popup').attr('data-title'),
			dialogClass: 'wp-dialog',
			autoOpen: false,
			draggable: false,
			width: 'auto',
			modal: true,
			resizable: false,
			closeOnEscape: true,
			position: {
			  my: "center",
			  at: "center",
			  of: window
			},
			open: function () {
			  // close dialog by clicking the overlay behind it
			  $('.ui-widget-overlay').bind('click', function(){
				//$(the_dialog).dialog('close');
				close_popup_dialog();
			  })
			},
			create: function () {
			  // style fix for WordPress admin
			  $('.ui-dialog-titlebar-close').addClass('ui-button');
			},
			buttons: {
				"Close": close_popup_dialog,
			},
			close: function() {
				unsaved = true;
				close_popup_dialog()
			}
		});
		/* bind a button or a link to open the dialog
		$('a.open-my-dialog').click(function(e) {
			e.preventDefault();
			$('#my-dialog').dialog('open');
		});*/
		$('#fns_dialog').dialog('open');
		
		$('.ui-widget-overlay').css('opacity', 0).animate({opacity:1});
		$('body').addClass('fns-popup');
		
		$('.ui-dialog .ui-dialog-buttonset button').attr('class', 'button button-primary button-large');
		
		return false;
	});

	function close_popup_dialog() {

		$('body.fns-popup .ui-dialog, .ui-widget-overlay').fadeOut(function () {
		
			$('body').removeClass('fns-popup');
			if ($('#fns_dialog').length==0) return;
			
			$('#fns_dialog .fns_fields_popup').appendTo('.fns_popup_opened');
			$('.fns_popup_opened').removeClass('fns_popup_opened');
			
			$('#fns_dialog')
				.dialog('close')
				.remove();
		});
	}

	/* special help popup global group_by */
	main_cont = $('#woocommerce_fish_n_ships_global_group_by, #woocommerce_fish_n_ships_global_group_by_method').closest('tr');
	$('.woocommerce-help-tip', main_cont).click(function () {
		show_help( 'group_by', false, wcfns_data['admin_lang'] );
		return false;
	});

	/* outside table rules fields help popup */
	main_cont = $('#woocommerce_fish_n_ships_title, #woocommerce_fish_n_ships_tax_status, #woocommerce_fish_n_ships_rules_charge, #woocommerce_fish_n_ships_min_shipping_price, #woocommerce_fish_n_ships_max_shipping_price, #woocommerce_fish_n_ships_write_logs').closest('tr');
	$('.woocommerce-help-tip', main_cont).click(function () {
		show_help( 'other_fields', false, wcfns_data['admin_lang'] );
		return false;
	});
	
	/* selection conditions */
	main_cont = $('#woocommerce_fish_n_ships_volumetric_weight_factor').closest('tr');
	$('.woocommerce-help-tip', main_cont).click(function () {
		show_help( 'sel_conditions', false, wcfns_data['admin_lang'] );
		return false;
	});
	
	/* help popups */
	$(document).on('click', 'a.woocommerce-fns-help-popup', function () {

		tip = $(this).attr('data-fns-tip');
		if (typeof tip !== typeof undefined && tip !== false) {
			
			// Clicked the button in the wizard? We will close it forever
			if ($(this).closest('.wc-fns-wizard-notice-4').length != 0) {

				$('.wc-fns-wizard-notice-4').slideUp(function () {
					$('div.wc-fns-wizard').remove();
				});
		
				$.ajax({
					url: ajaxurl,
					data: { action: 'wc_fns_wizard', ajax: 'wizard', param: 'off' },
					error: function (xhr, status, error) {
						var errorMessage = xhr.status + ': ' + xhr.statusText
						console.log('Fish n Ships, AJAX error - ' + errorMessage);
					},
					success: function (data) {
						if (data != '1') console.log('Fish n Ships, AJAX error - ' + data);
					},
					dataType: 'html'
				});
			}

			show_help(tip, false, wcfns_data['admin_lang'] );
			return false;
		}
	});
	
	/* links through help documents */
	$(document).on('click', 'nav.wc_fns_nav_popup a, nav.lang_switch a, a.wc_fns_nav_popup', function () {

		//tip = $(this).attr('href');
		//tip = tip.substr(0, tip.indexOf('-'));
		tip = $(this).attr('data-fns-tip');
		
		lang = $(this).attr('data-fns-lang');
		if (typeof lang !== typeof undefined && lang !== false) {

			// Remember for the future
			wcfns_data['admin_lang'] = lang;

		} else {

			// Get the admin lang (or previously changed by the user)
			lang = wcfns_data['admin_lang'];
		}
		
		if ($('#fns_help').length!=0) {
		
			$('#fns_help')
				.dialog('close')
				.remove();
		}
		show_help(tip, true, lang);
		return false;
	});
	
	/* main help */
	function show_help($what, $concatenated, $lang) {

		if (!$concatenated) {
			
			close_popup_dialog();
			close_popup_help();
		}

		$('body').append('<div class="ui-widget-overlay ui-front fns-loading" style="z-index: 100;"><span class="wc-fns-spinner"></span></div>');

		if (!$concatenated) {
			$('.ui-widget-overlay').css('opacity', 0).animate({opacity:1});
		}
		
		$.ajax({
			url: ajaxurl,
			data: { action: 'wc_fns_help', lang: $lang, what: $what },
			error: function (xhr, status, error) {
		    	var errorMessage = xhr.status + ': ' + xhr.statusText
				alert('Fns Help Error - ' + errorMessage);
				$('.ui-widget-overlay.fns-loading').remove();
			},
			success: function (data) {
				
				var parsed = $('<div/>').append(data);
				
				$('body').append('<div id="fns_help"><div class="popup_scroll_control">' + parsed.find("#content").html() + '</div></div>');

				// Set the right URL to the img tags
				$('#fns_help .popup_scroll_control img').each(function(index, element) {
					url = $(element).attr('src');
					if ( typeof url !== typeof undefined && url !== false && url.indexOf('http') !== 0) $(element).attr('src', wcfns_data['help_url'] + url);
				});

				// Put the right URL to the A tags
				$('#fns_help .popup_scroll_control a').each(function(index, element) {
					url = $(element).attr('href');
					if ( typeof url !== typeof undefined && url !== false && url.indexOf('http') !== 0) $(element).attr('href', wcfns_data['help_url'] + url);
				});
				
				//Open the copy
				$('#fns_help').dialog({
					title: parsed.find("h1").html(),
					dialogClass: 'wp-dialog',
					autoOpen: false,
					draggable: true,
					width: $('#wpcontent').width() * 0.95,
					height: $(window).height() * 0.7,
					modal: true,
					resizable: true,
					closeOnEscape: true,
					position: {
					  my: "center",
					  at: "center",
					  of: window
					},
					open: function () {
					  // close dialog by clicking the overlay behind it
					  $('.ui-widget-overlay').bind('click', function(){
						//$(the_dialog).dialog('close');
						close_popup_help();
					  })
					},
					create: function () {
					  // style fix for WordPress admin
					  $('.ui-dialog-titlebar-close').addClass('ui-button');
					},
					buttons: {
						"Close": close_popup_help,
					},
					close: function() {
						close_popup_help()
					}
				});
		
				$('#fns_help').dialog('open');
				$('body').addClass('fns-popup');
				$('.ui-widget-overlay.fns-loading').remove();
				$('.ui-dialog .ui-dialog-buttonset button').attr('class', 'button button-primary button-large');
			},
			dataType: 'html'
		});
				
		return false;
	}

	function close_popup_help() {

		$('body.fns-popup .ui-dialog, .ui-widget-overlay').fadeOut(function () {

			$('body').removeClass('fns-popup');
			if ($('#fns_help').length==0) return;
			
			$('#fns_help')
				.dialog('close')
				.remove();
		});
	}

	/*******************************************************
	    5. Start
	 *******************************************************/

	setTimeout(function () {
		$('#wrapper-shipping-rules-table-fns .overlay').animate({opacity: 0}, function () {
			$(this).remove();
		});
	}, 10);
	
	/*******************************************************
	    6. Freemium panel
	 *******************************************************/
	
	$('#wc-fns-freemium-panel .close_panel').click(function () {

		$.ajax({
			url: ajaxurl,
			data: { action: 'wc_fns_freemium', opened: '0' },
			error: function (xhr, status, error) {
				var errorMessage = xhr.status + ': ' + xhr.statusText
				console.log('Fish n Ships, AJAX error - ' + errorMessage);
			},
			success: function (data) {
				if (data != '1') console.log('Fish n Ships, AJAX error - ' + data);
			},
			dataType: 'html'
		});
		
		$('#wc-fns-freemium-panel').addClass('closed').removeClass('opened');

		if ($('#wc-fns-freemium-panel.pro-version').length != 0) {
			$('body').addClass('wc-fish-and-ships-pro-closed');
		}
		return false;
	});
	
	$('#wc-fns-freemium-panel .open_panel').click(function () {

		$.ajax({
			url: ajaxurl,
			data: { action: 'wc_fns_freemium', opened: '1' },
			error: function (xhr, status, error) {
				var errorMessage = xhr.status + ': ' + xhr.statusText
				console.log('Fish n Ships, AJAX error - ' + errorMessage);
			},
			success: function (data) {
				if (data != '1') console.log('Fish n Ships, AJAX error - ' + data);
			},
			dataType: 'html'
		});
		
		$('#wc-fns-freemium-panel').addClass('opened').removeClass('closed');

		if ($('#wc-fns-freemium-panel.pro-version').length != 0) {
			$('body').removeClass('wc-fish-and-ships-pro-closed');
		}
		return false;
	});
	
	
	$('div.wc_fns_change_serial a').click(function () {
		$('div.wc_fns_change_serial').slideUp();
		$('div.wc_fns_hide_serial').slideDown();
		return false;
	})
	
	refresh_rules();
});