<?php
/**
 * Wizard to guide new users and 5 star rating stuff
 *
 * @package Fish and Ships
 * @version 1.0.0
 */

// We should show now / later / hide wizard forever?
if (isset($_GET['wc-fns-wizard']) ) {
	
	$when = sanitize_key( $_GET['wc-fns-wizard'] );
	
	if ( in_array( $when, array( 'now', 'later', 'off' ), true ) ) {
		$Fish_n_Ships->update_wizard_opts('wizard', $when);
	}
}

// We should show later / hide five stars forever? (failed AJAX)
if (isset($_GET['wc-fns-five-stars']) ) {

	$when = sanitize_key( $_GET['wc-fns-five-stars'] );
	
	if ( in_array( $when, array( 'later', 'off' ), true ) ) {
		$Fish_n_Ships->update_wizard_opts('five-stars', $when);
	}
}

$wc_fns_options = $Fish_n_Ships->get_options();

// Is wizard pending to show?
if ( $wc_fns_options['show_wizard'] < time() ) {

	// We are on WooCommerce>Settings>Shipping>Shipping zones ?
	if ( isset($_GET['page'] ) && $_GET['page'] == 'wc-settings' && 
		 isset( $_GET['tab'] ) &&  $_GET['tab'] == 'shipping' && 
		 (!isset( $_GET['section'] ) ||  $_GET['section'] == '') ) {

		// We are on shipping method configuration screen?
		if (isset($_GET['instance_id'])) {

			if ( !function_exists('woocommerce_fns_wizard_notice_4') ) {
			
				function woocommerce_fns_wizard_notice_4() {

					echo '<div class="notice wc-fns-wizard must wc-fns-wizard-notice-4">'
						. '<h3>' . esc_html__('Fish and Ships Wizard:', 'fish-and-ships') . '</h3>' 
						. '<p>' . esc_html__('And finally, here you can configure your Fish and Ships shipping method. Please, open the main help:', 'fish-and-ships') . '</p>'
						. '<p><a href="#" class="button-primary woocommerce-fns-help-popup" data-fns-tip="main">' . esc_html__('Open main help', 'fish-and-ships') . '</a> &nbsp;' 
						. '<a href="' . add_query_arg('wc-fns-wizard', 'later') . '" class="button" data-ajax="wizard" data-param="later">' . esc_html__('Remind later', 'fish-and-ships') . '</a> &nbsp;'
						. '<a href="' . add_query_arg('wc-fns-wizard', 'off') . '" class="button" data-ajax="wizard" data-param="off">' . esc_html__('Thanks, I know how to use it', 'fish-and-ships') . '</a></p>'
						. '</div>';
				}
				add_action('admin_notices', 'woocommerce_fns_wizard_notice_4');
			}
			
		// We are on a shipping zone creation screen?
		} elseif (isset($_GET['zone_id']) && $_GET['zone_id']=='new') {

			if ( !function_exists('woocommerce_fns_wizard_notice_3') ) {
	
				function woocommerce_fns_wizard_notice_3() {

					echo '<div class="notice wc-fns-wizard must wc-fns-wizard-notice-3">'
						. '<h3>' . esc_html__('Fish and Ships Wizard:', 'fish-and-ships') . '</h3>' 
						. '<p>' . esc_html__('Configure the new zone, and then:', 'fish-and-ships') . ' ' .
						wp_kses( __('add <strong>Fish and Ships</strong>, and edit it.', 'fish-and-ships'),
								 array('strong'=>array())
						) . '</p></div>';
				}
				add_action('admin_notices', 'woocommerce_fns_wizard_notice_3');
			}
			
		// We are on a shipping zone edition screen?
		} elseif (isset($_GET['zone_id'])) {

			if ( !function_exists('woocommerce_fns_wizard_notice_2') ) {
	
				function woocommerce_fns_wizard_notice_2() {
	
					echo '<div class="notice wc-fns-wizard must wc-fns-wizard-notice-2">'
						. '<h3>' . esc_html__('Fish and Ships Wizard:', 'fish-and-ships') . '</h3>' 
						. '<p>' . esc_html__('Now add a new shipping method:', 'fish-and-ships') . ' ' .
						wp_kses( __('add <strong>Fish and Ships</strong>, and edit it.', 'fish-and-ships'),
								 array('strong'=>array())
						) . '</p></div>';
				}
				add_action('admin_notices', 'woocommerce_fns_wizard_notice_2');
			}

		// We are on the main shipping zones screen
		} else {

			if ( !function_exists('woocommerce_fns_wizard_notice_1') ) {
	
				function woocommerce_fns_wizard_notice_1() {
	
					echo '<div class="notice wc-fns-wizard must wc-fns-wizard-notice-1">'
						. '<h3>' . esc_html__('Fish and Ships Wizard:', 'fish-and-ships') . '</h3>' 
						. '<p>' . esc_html__('First, select one shipping zone, or create a new one:', 'fish-and-ships')
						. '</p></div>';
				}
				add_action('admin_notices', 'woocommerce_fns_wizard_notice_1');
			}
		}
	} else {

		if ( !function_exists('woocommerce_fns_wizard_notice_0') ) {
	
			function woocommerce_fns_wizard_notice_0() {

				global $Fish_n_Ships;

				if ( !current_user_can('manage_options') || !$Fish_n_Ships->is_wc() ) return;
	
				echo '<div class="notice wc-fns-wizard must wc-fns-wizard-notice-0">'
					. '<h3>'. esc_html__('Welcome to Fish and Ships:', 'fish-and-ships') . '</h3>'
					. '<p>' . esc_html__('A WooCommerce shipping method. Easy to understand and easy to use, it gives you an incredible flexibility.', 'fish-and-ships') . '</p>'
				  . '<p><a href="' . admin_url('admin.php?page=wc-settings&tab=shipping&wc-fns-wizard=now') . '" class="button-primary">' . esc_html__('Start wizard', 'fish-and-ships') . '</a> &nbsp;'
					. '<a href="' . add_query_arg('wc-fns-wizard', 'later') . '" class="button" data-ajax="wizard" data-param="later">' . esc_html__('Remind later', 'fish-and-ships') . '</a> &nbsp;'
					. '<a href="' . add_query_arg('wc-fns-wizard', 'off') . '" class="button" data-ajax="wizard" data-param="off">' . esc_html__('Thanks, I know how to use it', 'fish-and-ships') . '</a></p>'
				  . '</div>';
			}
			add_action('admin_notices', 'woocommerce_fns_wizard_notice_0');
		}
	}

} else {
	
	// Is wordpress repository rate pending to show?
	if ( $wc_fns_options['five_stars'] < time() ) {

		if ( !function_exists('woocommerce_fns_five_stars_notice') ) {
	
			function woocommerce_fns_five_stars_notice() {

				global $Fish_n_Ships;
				
				if ( !current_user_can('manage_options') || !$Fish_n_Ships->is_wc() ) return;
	
				echo '<div class="notice wc-fns-wizard wc-fns-five-stars">'
					//. '<a class="notice-dismiss" href="#">' . esc_html__('Dismiss') . '</a>'
					. '<h3>'. esc_html__('Do you like Fish and Ships?', 'fish-and-ships') . '</h3>'
					. '<p>' . esc_html__('We are very pleased that you by now have been using our plugin a few days.', 'fish-and-ships') . '</p><p>' . 
					wp_kses( __('Please, rate <strong>Fish and Ships</strong> on WordPress repository, it will help us a lot :)', 'fish-and-ships'),
								 array('strong'=>array())
					) . '</p>'
					. '<p><a href="' . esc_url('https://wordpress.org/support/plugin/fish-and-ships/reviews/?rate=5#new-post') . '" class="button-primary" target="_blank" data-ajax="five-stars" data-param="later">' . esc_html__('Rate the plugin', 'fish-and-ships') . '</a> &nbsp;'
					  . '<a href="' . add_query_arg('wc-fns-five-stars', 'later') . '" class="button" data-ajax="five-stars" data-param="later">' . esc_html__('Remind later', 'fish-and-ships') . '</a> &nbsp;'
					 . '<a href="' . add_query_arg('wc-fns-five-stars', 'off') . '" class="button" data-ajax="five-stars" data-param="off">' . esc_html__('Don\'t show again', 'fish-and-ships') . '</a>'
					
					  . '</p></div>';
			}
			add_action('admin_notices', 'woocommerce_fns_five_stars_notice');
		}
	}
}