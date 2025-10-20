/**
 * Lightweight JS without UI dependencies for wizard and rate messages. 
 * It will be loaded on whole admin side, and is appart for performance reasons
 *
 * @package Fish and Ships
 * @version 1.0.5
 */

jQuery(document).ready(function($) {
	
	// There is a wizard message hidden by WC Admin JS?
	if ( $('.woocommerce-layout__notice-list-hide').length != 0 && $('div.wc-fns-wizard.must').length != 0 ) {

		if ( $('div.wrap.woocommerce').length != 0 ) {

			// Let's move the message to the visible wrapper	
			setTimeout(function () {
				$('div.wrap.woocommerce').prepend($('div.wc-fns-wizard.must'));
			}, 1);

		} else {
			// Fallback, old style
			$('body').addClass('show_wc_fns_wizard');
			
			// New releases can duplicate the wizard!
			setTimeout(function () {
				if ($('div.wc-fns-wizard.must').length > 1) {
					$('div.wc-fns-wizard.must').not(":last").remove();
				}
			}, 100);
		}
	}

	// Ajax for wizard / star buttons
	$(document).on('click', 'div.wc-fns-wizard a', function () {

		// No AJAX action?
		if (typeof $(this).attr('data-ajax') === "undefined" ) return true;
		
		var return_value  = $(this).attr('target') === "_blank";
		var html_link     = $(this).attr('href');

		$.ajax({
			url: ajaxurl,
			data: { action: 'wc_fns_wizard', ajax: $(this).attr('data-ajax'), param: $(this).attr('data-param') },
			error: function (xhr, status, error) {
				var errorMessage = xhr.status + ': ' + xhr.statusText
				console.log('Fish n Ships, AJAX error - ' + errorMessage);
				// fail? follow the link
				if (!return_value) location.href=html_link;
			},
			success: function (data) {
				if (data != '1') console.log('Fish n Ships, AJAX error - ' + data);
				// fail? follow the link
				if (data != '1' && !return_value) location.href=html_link;
			},
			dataType: 'html'
		});
		
		jQuery('div.wc-fns-wizard').slideUp(function () {
			jQuery('div.wc-fns-wizard').remove();
		});
		
		return return_value;
		//return false;
	});
});
