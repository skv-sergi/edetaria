<?php
/**
 * The Javascript data object. 
 *
 * @package Fish and Ships
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $Fish_n_Ships;

// get all selection methods
$selection_methods = apply_filters('wc_fns_get_selection_methods', array());
$actions = apply_filters('wc_fns_get_actions', array());

// get an empty row HTML for the "add new rule" action

	// request the cells info 
	$empty_row = $Fish_n_Ships->shipping_rules_table_cells();

	//the [selectors] and [actions] tokens will be removed here:
	$empty_row['selection-rules-column']['content'] = str_replace('[selectors]', '', $empty_row['selection-rules-column']['content']);
	$empty_row['special-actions-column']['content'] = str_replace('[actions]', '', $empty_row['special-actions-column']['content']);

	//the cost method and fields will be inserted:
	$empty_row['shipping-costs-column']['content'] = str_replace(
								'[cost_input_fields]', 
								apply_filters( 'wc_fns_get_html_price_fields', '', 0, array() ),
								$empty_row['shipping-costs-column']['content']);

	$empty_row['shipping-costs-column']['content'] = str_replace(
								'[cost_method_field]',
								$Fish_n_Ships->get_cost_method_html(0, ''), 
								$empty_row['shipping-costs-column']['content']);

	//...and parse it as HTML
	$empty_row = apply_filters('wc_fns_shipping_rules_table_row_html', $empty_row); 


// all into a data array:
$data = array(
	
	'id' => $Fish_n_Ships->id,
	'im_pro' => $Fish_n_Ships->im_pro(),
	'empty_row_html' => $empty_row,
	'new_selection_method_html' => str_replace('[selection_details]', '', $Fish_n_Ships->get_selector_method_html(0, $selection_methods)),
	'new_action_html' => str_replace('[action_details]', '', $Fish_n_Ships->get_action_method_html(0, $actions)),
	
	'i18n_where' => _x('WHERE', 'VERY shorted, logic operator (maybe better leave in english)', 'fish-and-ships'),
	'i18n_and' => _x('AND', 'VERY shorted, logic operator (maybe better leave in english)', 'fish-and-ships'),
	'i18n_unsaved' => __('You have unsaved changes. If you proceed, they will be lost.'),
	
	'i18n_fns_integer_error' => __('Please enter without decimals or thousand separators.', 'fish-and-ships'),
	'i18n_min_val_info' =>  _x('Will match values equal or bigger than', 'Min field tip', 'fish-and-ships'),
	'i18n_max_val_info' =>  _x('Will match values BELOW than, e.g., put 101 to match from Min to 100.99', 'Max (below value) field tip', 'fish-and-ships'),
	'i18n_min_val_info_action' =>  _x('If the calculated rule cost is less than this, it will be set to this value.', 'Min field tip', 'fish-and-ships'),
	'i18n_max_val_info_action' =>  _x('If the calculated rule cost is greater than this, it will be set to this value.', 'Max field tip', 'fish-and-ships'),
	
	'ajax_url_main_lang' => $Fish_n_Ships->get_unlocalised_ajax_url(), // main site lang attribute will be added on multiligual
	'admin_lang' => function_exists('get_user_locale') ? get_user_locale() : get_locale(), // The preferred language to show the help
	'help_url' => WC_FNS_URL . 'help/'
);

// the html code details for each selection method
foreach ($selection_methods as $method_id=>$method) {
	if ( $Fish_n_Ships->im_pro() || !$method['onlypro'] ) {
		$data['selection_' . $method_id . '_html'] = apply_filters('wc_fns_get_html_details_method', '', 0, 0, $method_id, array(), true );
	}
}

// the html code details for each action
foreach ($actions as $action_id=>$action) {
	if ( $Fish_n_Ships->im_pro() || !$action['onlypro'] ) {
		$data['action_' . $action_id . '_html'] = apply_filters('wc_fns_get_html_details_action', '', 0, 0, $action_id, array() );
	}
}

return $data;
