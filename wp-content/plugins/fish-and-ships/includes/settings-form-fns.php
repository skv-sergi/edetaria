<?php
/**
 * The Pluggable table rules stuff 
 *
 * @package Fish and Ships
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Filter to get all selection methods
 *
 * @since 1.0.0
 *
 * @param $methods (array) maybe incomming  a pair method-id / method-name array
 *
 * @return $methods (array) a pair method-id / method-name array
 *
 */

add_filter('wc_fns_get_selection_methods', 'wc_fns_get_selection_methods_fn', 1, 10);

function wc_fns_get_selection_methods_fn($methods = array()) {

	if (!is_array($methods)) $methods = array();
																			// will be HTML escaped later
	$methods['by-price']           = array('onlypro' => false, 'label' => _x('Price', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['by-weight']          = array('onlypro' => false, 'label' => _x('Weight', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['by-volume']          = array('onlypro' => false, 'label' => _x('Volume', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['volumetric']         = array('onlypro' => true,  'label' => _x('Volumetric', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['quantity']           = array('onlypro' => false, 'label' => _x('Cart items', 'shorted, select-by conditional', 'fish-and-ships'));

	$methods['min-dimension']      = array('onlypro' => false, 'label' => _x('Min dimension', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['mid-dimension']      = array('onlypro' => false, 'label' => _x('Mid dimension', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['max-dimension']      = array('onlypro' => false, 'label' => _x('Max dimension', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['lwh-dimensions']     = array('onlypro' => true,  'label' => _x('Length+Width+Height', 'shorted, select-by conditional', 'fish-and-ships'));

	$methods['in-category']        = array('onlypro' => true,  'label' => _x('In category', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['not-in-category']    = array('onlypro' => true,  'label' => _x('NOT In category', 'shorted, select-by conditional', 'fish-and-ships'));
	
	$methods['in-tag']             = array('onlypro' => true,  'label' => _x('Tagged as', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['not-in-tag']         = array('onlypro' => true,  'label' => _x('NOT Tagged as', 'shorted, select-by conditional', 'fish-and-ships'));
	
	$methods['in-class']           = array('onlypro' => false, 'label' => _x('In shipping class', 'shorted, select-by conditional', 'fish-and-ships'));
	$methods['not-in-class']       = array('onlypro' => false, 'label' => _x('NOT In shipping class', 'shorted, select-by conditional', 'fish-and-ships'));
	
	return $methods;
}


/**
 * Filter to get the HTML selection fields for one method (centralised for all methods)
 *
 * @since 1.0.0
 *
 * @param $html (HTML) maybe incomming html
 * @param $rule_nr (integer) the rule number
 * @param sel_nr (integer) the selection number inside rule or total?
 * @param $method_id (mixed) the method-id
 * @param $values (array) the saved values 
 * @param $previous (bootlean) true: JS array of empty fields | false: real field or AJAX insertion
 *
 * @return $html (HTML) the HTML selection fields
 *
 */

add_filter('wc_fns_get_html_details_method', 'wc_fns_get_html_details_method_fn', 6, 10);

function wc_fns_get_html_details_method_fn($html, $rule_nr, $sel_nr, $method_id, $values, $previous) {

	global $Fish_n_Ships;

	switch ($method_id) {
		
		case 'by-weight':
			$html .= $Fish_n_Ships->get_min_max_html($rule_nr, $sel_nr, $method_id, get_option('woocommerce_weight_unit'), $values)
					. $Fish_n_Ships->get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values);
			break;

		case 'by-price':
			$html .= $Fish_n_Ships->get_min_max_html($rule_nr, $sel_nr, $method_id, get_woocommerce_currency_symbol(), $values)
					. $Fish_n_Ships->get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values);
			break;
			
		case 'by-volume':
			$unit = get_option('woocommerce_dimension_unit') . '<sup style="font-size:0.75em; vertical-align:0.25em">3</sup>';
			$html .= $Fish_n_Ships->get_min_max_html($rule_nr, $sel_nr, $method_id, $unit, $values)
					. $Fish_n_Ships->get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values);
			break;
			
		case 'min-dimension':
		case 'mid-dimension':
		case 'max-dimension':
			$html .= $Fish_n_Ships->get_min_max_html($rule_nr, $sel_nr, $method_id, get_option('woocommerce_dimension_unit'), $values)
					. $Fish_n_Ships->cant_get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values);
			break;

		case 'quantity':
			$html .= $Fish_n_Ships->get_min_max_html($rule_nr, $sel_nr, $method_id, _x('El.', 'Elements, shorted'), $values)
					. $Fish_n_Ships->get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values);
			break;

		case 'in-class':
		case 'not-in-class':

			if ($previous) {
				// Will be loaded ajaxfied in the JS array
				$html = '<div class="wc-fns-ajax-fields" data-type="selector" data-method-id="'.$method_id.'"><span class="wc-fns-spinner"></span></div>';
			} else {
				$values = is_array($values) && isset($values['classes']) ? $values['classes'] : array();
	
				$html .= $Fish_n_Ships->get_multiple_html($rule_nr, $sel_nr, $method_id, 'product_shipping_class', $values, 'classes')
						. $Fish_n_Ships->cant_get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values);
			}
			break;
	}
	
	return $html;
}

/**
 * Filter to sanitize one selection criterion and his auxiliary fields prior to save in the database (centralised for all methods)
 *
 * @since 1.0.0
 *
 * @param $rule_sel (array) 
 *
 * @return $rule_sel sanitized (array) or false
 *
 */

add_filter('wc_fns_sanitize_selection_fields', 'wc_fns_sanitize_selection_fields_fn', 1, 10);

function wc_fns_sanitize_selection_fields_fn($rule_sel) {
	
	//Prior failed?
	if (!is_array($rule_sel)) return $rule_sel;

	global $Fish_n_Ships;
	
	// Only known methods
	$allowed_methods_ids = array_keys(apply_filters('wc_fns_get_selection_methods', array()));
	if (!is_array($allowed_methods_ids) || !isset($rule_sel['method']) || !in_array($rule_sel['method'], $allowed_methods_ids, true)) return false;

	// Only allowed auxiliary fields
	$allowed = false;

	switch ($rule_sel['method']) {
		
		case 'by-weight':
		case 'by-price':
		case 'by-volume':
		case 'quantity':
			$allowed = array('min','max','group_by');
			break;
			
		case 'min-dimension':
		case 'mid-dimension':
		case 'max-dimension':
			$allowed = array('min','max');
			break;


		case 'in-class':
		case 'not-in-class':
			$allowed = array('classes');
			break;
	}
	
	if (is_array($allowed)) {
		foreach ($rule_sel['values'] as $field => $val) {
			if (!in_array($field, $allowed)) unset($rule_sel['values'][$field]);
		}
	
		// sanitize expected values
		switch ($rule_sel['method']) {
			
			case 'by-weight':
			case 'by-price':
			case 'by-volume':
			case 'quantity':
				$rule_sel['values']['min'] = $Fish_n_Ships->sanitize_number($rule_sel['values']['min'], 'positive-decimal');
				$rule_sel['values']['max'] = $Fish_n_Ships->sanitize_number($rule_sel['values']['max'], 'positive-decimal');
				$rule_sel['values']['group_by'] = $Fish_n_Ships->sanitize_allowed($rule_sel['values']['group_by'],
																					array_keys($Fish_n_Ships->get_group_by_options()));
				break;

			case 'min-dimension':
			case 'mid-dimension':
			case 'max-dimension':
				$rule_sel['values']['min'] = $Fish_n_Ships->sanitize_number($rule_sel['values']['min'], 'positive-decimal');
				$rule_sel['values']['max'] = $Fish_n_Ships->sanitize_number($rule_sel['values']['max'], 'positive-decimal');
				break;

			case 'in-class':
			case 'not-in-class':
	
				if ( !is_array($rule_sel['values']['classes']) ) {
					unset ( $rule_sel['values']['classes'] );
				} else {
					foreach ($rule_sel['values']['classes'] as $key=>$val) {
						$rule_sel['values']['classes'][$key] = $Fish_n_Ships->sanitize_number($rule_sel['values']['classes'][$key], 'id');
					}
				}
	
				break;
		}
	}
	
	return $rule_sel;
}

/**
 * Filter to check matching elements for selection method
 *
 * @since 1.0.0
 * @version 1.0.5
 *
 * @param $rule_groups (array) all the groups of current rule
 * @param $selector (array) the selector criterion
 * @param $group_by (mixed) the group method 
 * @param $shipping_class (reference) the class reference 
 *
 * @return $rule_groups (array)
 *
 */

add_filter('wc_fns_check_matching_selection_method', 'wc_fns_check_matching_selection_method_fn', 4, 10);

function wc_fns_check_matching_selection_method_fn($rule_groups, $selector, $group_by, $shipping_class) {

	global $Fish_n_Ships;
		
	// Prepare the selection auxiliary fields
	switch ($selector['method']) {
		
		// Prepare the variables for comparison
		case 'by-weight':
		case 'by-price':
		case 'by-volume':
		case 'min-dimension':
		case 'mid-dimension':
		case 'max-dimension':
		case 'quantity':

			$min = 0; if (isset($selector['values']['min'])) $min = $selector['values']['min'];
			$max = '*'; if (isset($selector['values']['max'])) $max = $selector['values']['max'];
			if (trim($min)=='') $min = 0;
			if (trim($max)=='') $max = '*';
		
			break;
	}

	// Let's iterate in his group_by groups
	foreach ($rule_groups[$group_by] as $group) {
		
		// empty or previously unmatched? bypass for performance
		if ($group->is_empty() || !$group->is_match()) continue;

		switch ($selector['method']) {

			case 'by-price':

				$value = $group->get_total($selector['method']);

									$min   = $Fish_n_Ships->currency_abstraction ('main-currency', $min);
									$value = $Fish_n_Ships->currency_abstraction ('cart-currency', $value);
				if ($max !== '*') 	$max   = $Fish_n_Ships->currency_abstraction ('main-currency', $max);

				if (($min != 0 && $min > $value) || ($max !== '*' && $max <= $value)) {	
					// unmatch this group
					$Fish_n_Ships->unmatch_group($group, $rule_groups);
				}
				break;

			case 'by-weight':
			case 'by-volume':
			case 'min-dimension':
			case 'mid-dimension':
			case 'max-dimension':
			case 'quantity':
			
				$value = $group->get_total($selector['method']);

				if (($min != 0 && $min > $value) || ($max !== '*' && $max <= $value)) {	
					// unmatch this group
					$Fish_n_Ships->unmatch_group($group, $rule_groups);
				}

				break;

			case 'in-class':
			case 'not-in-class':

				$classes = ( isset($selector['values']['classes']) && is_array($selector['values']['classes']) ) ? $selector['values']['classes'] : array();

				if (!$group->check_term($selector['method'], 'product_shipping_class', $classes)) {
					// unmatch this group
					$Fish_n_Ships->unmatch_group($group, $rule_groups);
				}
				break;
	
		}
	}
	
	return $rule_groups;
}

/**
 * Filter to get all cost methods
 *
 * @since 1.0.0
 *
 * @param $actions (array) maybe incomming  a pair action-id / action-name array
 *
 * @return $actions (array) a pair action-id / action-name array
 *
 */

add_filter('wc_fns_get_cost_methods', 'wc_fns_get_cost_methods_fn', 1, 10);

function wc_fns_get_cost_methods_fn($cost_methods = array()) {

	if (!is_array($cost_methods)) $cost_methods = array();
													// Will be HTML escaped later
	$cost_methods['once']       = array('label' => _x('(once)', 'VERY shorted, once price application', 'fish-and-ships'));
	$cost_methods['qty']        = array('label' => '* [qty]');
	$cost_methods['weight']     = array('label' => sprintf(_x('* weight (%s)', 'shorted, per weight price application', 'fish-and-ships'), get_option('woocommerce_weight_unit')) );
	$cost_methods['group']      = array('label' => '* [group]');
	$cost_methods['percent']    = array('label' => '%');
	$cost_methods['composite']  = array('label' => _x('composite', 'VERY shorted, composite price application', 'fish-and-ships'));
	
	return $cost_methods;
}

/**
 * Filter to get the HTML fields for price rules
 *
 * @since 1.0.0
 * @version 1.0.2
 *
 * @param $html (HTML) maybe incomming html
 * @param $rule_nr (integer) the rule number
 * @param $values (array) the saved values 
 *
 * @return $html (HTML) the HTML price fields
 *
 */
add_filter('wc_fns_get_html_price_fields', 'wc_fns_get_html_price_fields_fn', 3, 10);

function wc_fns_get_html_price_fields_fn($html, $rule_nr, $values) {
	
	// Securing output
	$rule_nr = intval( $rule_nr );
	
	$cost         = 0; if (isset($values['cost']))          $cost = $values['cost'];
	$cost_once    = 0; if (isset($values['cost_once']))     $cost_once = $values['cost_once'];
	$cost_qty     = 0; if (isset($values['cost_qty']))      $cost_qty = $values['cost_qty'];
	$cost_weight  = 0; if (isset($values['cost_weight']))   $cost_weight = $values['cost_weight'];
	$cost_group   = 0; if (isset($values['cost_group']))    $cost_group = $values['cost_group'];
	$cost_percent = 0; if (isset($values['cost_percent']))  $cost_percent = $values['cost_percent'];

	$html .= '<span class="field cost_simple"><input type="text" name="shipping_rules[' . $rule_nr . '][cost][cost][]" value="' . esc_attr( $cost ) . '" placeholder="0" size="4" class="wc_fns_input_decimal fns-cost" autocomplete="off"></span><div class="cost_composite">';

	$html .= '<span class="field_wrapper"><input type="text" name="shipping_rules[' . $rule_nr . '][cost][cost_once][]" value="' . esc_attr( $cost_once ) . '" placeholder="0" size="4" class="wc_fns_input_decimal fns-cost-once" autocomplete="off"> ' . esc_html( get_woocommerce_currency_symbol() . ' ' . _x('(once)', 'shorted, once price application', 'fish-and-ships') ) . '</span>';

	$html .= '<span class="field_wrapper"><input type="text" name="shipping_rules[' . $rule_nr . '][cost][cost_qty][]" value="' . esc_attr( $cost_qty ) . '" placeholder="0" size="4" class="wc_fns_input_decimal fns-cost-qty" autocomplete="off"> ' .esc_html ( get_woocommerce_currency_symbol() . ' ' . '* [qty]' ) . '</span>';

	$html .= '<span class="field_wrapper"><input type="text" name="shipping_rules[' . $rule_nr . '][cost][cost_weight][]" value="' . esc_attr( $cost_weight ) . '" placeholder="0" size="4" class="wc_fns_input_decimal fns-cost-weight" autocomplete="off"> ' . esc_html( get_woocommerce_currency_symbol() . ' ' . sprintf(_x('* weight (%s)', 'shorted, per weight price application', 'fish-and-ships'), get_option('woocommerce_weight_unit')) ) . '</span>';

	$html .= '<span class="field_wrapper"><input type="text" name="shipping_rules[' . $rule_nr . '][cost][cost_group][]" value="' . esc_attr( $cost_group ) . '" placeholder="0" size="4" class="wc_fns_input_decimal fns-cost-group" autocomplete="off"> ' . esc_html ( get_woocommerce_currency_symbol() . ' ' . '* [group]' ) . '</span>';

	$html .= '<span class=" field field_wrapper"><input type="text" name="shipping_rules[' . $rule_nr . '][cost][cost_percent][]" value="' . esc_attr( $cost_percent ) . '" placeholder="0" size="4" class="wc_fns_input_decimal fns-cost-percent" autocomplete="off"> %</span></div>';

	return $html;
}

/**
 * Filter to sanitize cost
 *
 * @since 1.0.0
 *
 * @param $rule_cost (array) 
 *
 * @return $rule_cost sanitized (array) or false
 *
 */

add_filter('wc_fns_sanitize_cost', 'wc_fns_sanitize_cost_fn', 1);

function wc_fns_sanitize_cost_fn($rule_cost) {
		
	//Prior failed?
	if (!is_array($rule_cost)) return $rule_cost;

	global $Fish_n_Ships;
	
	// Only known methods
	$allowed_methods_ids = array_keys(apply_filters('wc_fns_get_cost_methods', array()));
	if (!is_array($allowed_methods_ids) || !isset($rule_cost['method']) || !in_array($rule_cost['method'], $allowed_methods_ids, true)) return false;

	// Only allowed price fields
	$allowed = false;
	
	switch ($rule_cost['method']) {
		
		case 'once':
		case 'qty':
		case 'weight':
		case 'group':
		case 'percent':
			$allowed = array('cost');
			break;

		case 'composite':
			$allowed = array('cost_once', 'cost_qty', 'cost_weight', 'cost_group', 'cost_percent');
			break;
	}
	
	if (is_array($allowed)) {	
	
		foreach ($rule_cost['values'] as $field => $val) {
			if (!in_array($field, $allowed)) unset($rule_cost['values'][$field]);
		}

		// sanitize expected values
		foreach ($allowed as $field) {
			$rule_cost['values'][$field] = $Fish_n_Ships->sanitize_number($rule_cost['values'][$field], 'decimal');
		}
	}
	

	return $rule_cost;
}

/**
 * Filter to calculate the shipping cost rule
 *
 * @since 1.0.0
 *
 * @param $prev_cost (integer) 0 or maybe the previous filtered cost
 * @param $cost (array) The rule cost
 * @param $shippable_contents_rule (array) the contents to looking for
 * @param $rule_groups (array) all groups of current rule
 * @param $shipping_class (reference) the class reference 
 *
 * @return $cost (integer) The calculated cost of the rule
 *
 */

add_filter('wc_fns_calculate_cost_rule', 'wc_fns_calculate_cost_rule_fn', 5, 10);

function wc_fns_calculate_cost_rule_fn($prev_cost, $cost, $shippable_contents_rule, $rule_groups, $shipping_class) {
	
	global $Fish_n_Ships;

	$calculated_cost = 0;

	$cost_field = 0; if (isset($cost['values']['cost'])) $cost_field = floatval($cost['values']['cost']);

	// We need calculate the matched products quantity
	if ($cost['method'] == 'qty' || $cost['method'] == 'composite') {

		$qty = 0;
		foreach ( $shippable_contents_rule as $key => $product ) {

			$qty += $Fish_n_Ships->get_quantity($product);
		}
	}


	// We need calculate the matched products total price
	if ($cost['method'] == 'percent' || $cost['method'] == 'composite') {

		$total_price = 0;
		foreach ( $shippable_contents_rule as $key => $product ) {

			$total_price += $product[ 'data' ]->get_price() * $Fish_n_Ships->get_quantity($product);
		}
	}


	// We need calculate the matched products total weight
	if ($cost['method'] == 'weight' || $cost['method'] == 'composite') {

		$weight = 0;
		foreach ( $shippable_contents_rule as $key => $product ) {

			$weight += $Fish_n_Ships->get_weight($product) * $Fish_n_Ships->get_quantity($product);
		}
	}


	// We need calculate the number of matched groups
	if ($cost['method'] == 'group' || $cost['method'] == 'composite') {

		$matched_groups = $Fish_n_Ships->get_matched_groups($rule_groups);
	}
	
	
	switch ($cost['method']) {
		
		case 'once':
			// nothing to calculate
			$calculated_cost = $cost_field;
			break;

		case 'qty':
			
			$calculated_cost = $cost_field * $qty;
			break;

		case 'group':

			$calculated_cost = $cost_field * $matched_groups;
			break;

		case 'weight':

			$calculated_cost = $cost_field * $weight;
			break;

		case 'percent':

			$calculated_cost = $cost_field * $total_price * 0.01; // The percentage comes into humnan format: 0-100%
			break;
			
		case 'composite':

			$cost_once     = 0; if (isset($cost['values']['cost_once'])) 
									$cost_once     = floatval($cost['values']['cost_once']);
								
			$cost_qty      = 0; if (isset($cost['values']['cost_qty'])) 
									$cost_qty      = floatval($cost['values']['cost_qty']);

			$cost_weight   = 0; if (isset($cost['values']['cost_weight'])) 
									$cost_weight   = floatval($cost['values']['cost_weight']);
								
			$cost_group    = 0; if (isset($cost['values']['cost_group'])) 
									$cost_group    = floatval($cost['values']['cost_group']);
								
			$cost_percent  = 0; if (isset($cost['values']['cost_percent']))
									$cost_percent  = floatval($cost['values']['cost_percent']);

			// Waha! all methods together
			$calculated_cost  = $cost_once;
			$calculated_cost += $cost_qty      * $qty;
			$calculated_cost += $cost_weight   * $weight;
			$calculated_cost += $cost_group    * $matched_groups;
			$calculated_cost += $cost_percent  * $total_price * 0.01; // The percentage comes into humnan format: 0-100%;

			$shipping_class->debug_log('Composite cost (once + qty + weight + group + %): ' . $cost_once . ' + ' . $cost_qty . '*' . $qty . ' + ' . $cost_weight . '*' . $weight . ' + ' . $cost_group . '*' . $matched_groups . ' + ' . $total_price . '*' . $cost_percent . '%', 2);
			
			break;
	}
	
	return $prev_cost + $calculated_cost;
}


/**
 * Filter to get all actions
 *
 * @since 1.0.0
 *
 * @param $actions (array) maybe incomming  a pair action-id / action-name array
 *
 * @return $actions (array) a pair action-id / action-name array
 *
 */

add_filter('wc_fns_get_actions', 'wc_fns_get_actions_fn', 1, 10);

function wc_fns_get_actions_fn($actions = array()) {

	if (!is_array($actions)) $actions = array();
																	// will be HTML scaped later 
	$actions['abort']        = array('onlypro' => false, 'label' => _x('Abort shipping method', 'shorted, action name', 'fish-and-ships'));
	$actions['skip']         = array('onlypro' => true,  'label' => _x('Skip N rules', 'shorted, action name', 'fish-and-ships'));
	$actions['reset']        = array('onlypro' => true,  'label' => _x('Reset previous costs', 'shorted, action name', 'fish-and-ships'));
	$actions['break']        = array('onlypro' => false, 'label' => _x('Ignore below rules', 'shorted, action name', 'fish-and-ships'));
	$actions['min_max']      = array('onlypro' => true,  'label' => _x('Set min/max rule costs', 'shorted, action name', 'fish-and-ships'));
	$actions['unset']        = array('onlypro' => true,  'label' => _x('Unset match prods for next rules', 'shorted, action name', 'fish-and-ships'));
	$actions['notice']       = array('onlypro' => true,  'label' => _x('Show notice message', 'shorted, action name', 'fish-and-ships'));
	$actions['rename']       = array('onlypro' => true,  'label' => _x('Rename method title', 'shorted, action name', 'fish-and-ships'));
	$actions['description']  = array('onlypro' => true,  'label' => _x('Add subtitle (text under)', 'shorted, action name', 'fish-and-ships'));
	
	return $actions;
}

/**
 * Filter to get the HTML action details for one action (centralised for all actions)
 *
 * @since 1.0.0
 *
 * @param $html (HTML) maybe incomming html
 * @param $rule_nr (integer) the ordinal rule number (starts at 0)
 * @param $action_nr (integer) the action ordinal inside rule (starts at 0)
 * @param $action_id (mixed) the method-id
 * @param $values (array) the saved values 
 *
 * @return $html (HTML) the HTML selection fields
 *
 */

add_filter('wc_fns_get_html_details_action', 'wc_fns_get_html_details_action_fn', 5, 10);

function wc_fns_get_html_details_action_fn($html, $rule_nr, $action_nr, $action_id, $values) {
	
	return $html;
}


/**
 * Filter to sanitize one action and his auxiliary fields prior to save in the database (centralised for all methods)
 *
 * @since 1.0.0
 *
 * @param $rule_sel (array) 
 *
 * @return $rule_sel sanitized (array) or false
 *
 */

add_filter('wc_fns_sanitize_action', 'wc_fns_sanitize_action_fn', 1);

function wc_fns_sanitize_action_fn($rule_action) {
		
	//Prior failed?
	if (!is_array($rule_action)) return $rule_action;

	global $Fish_n_Ships;
	
	// Only known methods
	$allowed_methods_ids = array_keys(apply_filters('wc_fns_get_actions', array()));
	if (!is_array($allowed_methods_ids) || !isset($rule_action['method']) || !in_array($rule_action['method'], $allowed_methods_ids, true)) return false;

	// Only allowed auxiliary fields
	$allowed = false;

	switch ($rule_action['method']) {

		case 'abort':
		case 'break':
			$allowed = array();
			break;

	}
	
	if (is_array($allowed)) {
		foreach ($rule_action['values'] as $field => $val) {
			if (!in_array($field, $allowed)) unset($rule_action['values'][$field]);
		}

	}
	
	return $rule_action;
}

/**
 * Filter to get translatable texts for actions (centralised for all methods)
 *
 * @since 1.0.0
 *
 * @param $action_id (text) 
 *
 * @return $array with translatable field names if there is any
 *
 */

add_filter('wc_fns_get_translatable_action', 'wc_fns_get_translatable_action_fn', 10, 2);

function wc_fns_get_translatable_action_fn($translatables, $action_id) {
	
	if (!is_array($translatables)) $translatables = array();
		
	switch ($action_id) {

		case 'notice':
			$translatables[] = 'message';
			break;

		case 'rename':
			$translatables[] = 'name';
			break;

		case 'description':
			$translatables[] = 'description';
			break;
	}
	return $translatables;
}


/**
 * Filter to perfom the special actions
 *
 * @since 1.0.0
 *
 * @param $action_result (array) data array that can be modified by the action
 * @param $action (array) action parameters
 * @param $shipping_class (reference) reference to the shipping class
 *
 * @return $action_result (array) maybe modified
 *
 */

add_filter('wc_fns_apply_action', 'wc_fns_apply_action_fn', 3, 10);

function wc_fns_apply_action_fn($action_result, $action, $shipping_class) {

	global $Fish_n_Ships;

	switch ($action['method']) {
		
		case 'abort':
			$action_result['abort'] = true;
			break;

		case 'break':
			$action_result['break'] = true;
			break;

	}
	
	return $action_result;
}

