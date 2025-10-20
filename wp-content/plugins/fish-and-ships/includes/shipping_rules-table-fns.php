<?php
/**
 * The Shipping rules table. 
 *
 * @package Fish and Ships
 * @version 1.0.0
 */
 
defined( 'ABSPATH' ) || exit;

global $Fish_n_Ships;

$errors = array();

// Global Group by is a must in the free version
if ( $this->global_group_by == 'no' && !$Fish_n_Ships->im_pro()  ) {
	$errors['global-group-by'] = 'Error: Only the Pro version allow distinct grouping criteria on every selection condition';
}

// Table header, used also on footer
$header = '
			<td class="manage-column column-cb check-column">
				<label class="screen-reader-text" for="cb-select-all">' . esc_html__('Select all') . '</label>
				<input class="cb-select-all" type="checkbox">
			</td>
			<th class="order-number-head">N.</th>
			<th>'.esc_html__('Selection conditions', 'fish-and-ships').' <a class="woocommerce-help-tip woocommerce-fns-help-popup" data-fns-tip="sel_conditions" data-tip="' . esc_attr__('Here you can set one or more selection conditions for each rule. Only if all conditions in a rule matches with some cart content, shipping costs and special actions will be applied. Click to open detailed help about every selection method.', 'fish-and-ships') . '"></a></th>
			<th>'.esc_html( __('Shipping costs', 'fish-and-ships').' (' . get_woocommerce_currency_symbol() . ')') . ' <a class="woocommerce-help-tip woocommerce-fns-help-popup" data-fns-tip="shipping_costs" data-tip="' . esc_attr__('The shipping costs will be applied only if the rule selection conditions matches. Click to open detailed help about shipping costs.', 'fish-and-ships') . '"></a></th>
			<th>'.esc_html__('Special actions', 'fish-and-ships').' <a class="woocommerce-help-tip woocommerce-fns-help-popup" data-fns-tip="special_actions" data-tip="' . esc_attr__('The actions will be executed only if the rule selection conditions matches. Click to open detailed help about every special action.', 'fish-and-ships') . '"></a></th>
			<th scope="col" class="manage-column column-handle"></th>
		';

//First let's close table, envelope and clearfix:
$html = '
</table>
</div>
</div>
<div id="wrapper-shipping-rules-table-fns">
<table class="widefat striped css-conditions-table-fns" id="shipping-rules-table-fns">
	<thead>
		<tr>' . $header . '</tr>
	</thead>
	<tbody>
';

// Let's load once the selection methods and actions
$selection_methods = apply_filters('wc_fns_get_selection_methods', array());
$all_actions = apply_filters('wc_fns_get_actions', array());

// There is no rules yet? Let's put empty one
if (!is_array($this->shipping_rules) || count($this->shipping_rules)==0) {
	$this->shipping_rules = array();
}

$rule_nr = 0;
foreach ($this->shipping_rules as $shipping_rule) {

	// request the cells info
	$new_row = $Fish_n_Ships->shipping_rules_table_cells();
	
	$new_row['order-number']['content'] = '#' . ($rule_nr + 1); // Human count starts on 1, not 0
	
	// The selection rules cell
	$sel_html = '';
	if (isset($shipping_rule['sel'])) {
		$sel_nr = 0;
		foreach ($shipping_rule['sel'] as $sel) {
			if (is_array($sel) && isset($sel['method'])) {
				
				// Unknown method? Let's advice about it! (once)
				$idx = 'selection-' . $sel['method'];
				if ( !isset( $errors[$idx] ) ) {
					$known = $Fish_n_Ships->is_known('selection', $sel['method']);
					if ($known !== true) $errors[$idx] = $known;
				}
				
				// The selector
				$this_sel_html = $Fish_n_Ships->get_selector_method_html(0, $selection_methods, $sel['method']);

				// His auxiliary fields
				$selection_details = '';
				if (isset($sel['values']) && is_array($sel['values'])) {
					$selection_details = apply_filters('wc_fns_get_html_details_method', '', $rule_nr, $sel_nr, $sel['method'], $sel['values'], false);
				}
				
				$sel_html .= str_replace('[selection_details]', $selection_details, $this_sel_html);
	
				$sel_nr++;
			}
		}
	}
	
	// There is not any selection method? put an empty one
	if ($sel_html=='') {
		$sel_html = $Fish_n_Ships->get_selector_method_html(0, $selection_methods);
		$sel_html = str_replace('[selection_details]', '', $sel_html);
	}
	
	$new_row['selection-rules-column']['content'] = str_replace('[selectors]', $sel_html, $new_row['selection-rules-column']['content']);


	// The cost cell

	$cost_method = '';
	$cost_values = array();

	if (isset($shipping_rule['cost']) && is_array($shipping_rule['cost'])) {
		foreach ($shipping_rule['cost'] as $cost) {
			if (is_array($cost) && isset($cost['method']) && isset($cost['values'])) {

				// Unknown method? Let's advice about it! (once)
				$idx = 'cost-' . $cost['method'];
				if ( !isset( $errors[$idx] ) ) {
					$known = $Fish_n_Ships->is_known('cost', $cost['method']);
					if ($known !== true) $errors[$idx] = $known;
				}

				$cost_method = $cost['method'];
				$cost_values = $cost['values'];
				if ( !is_array($cost_values) ) $cost_values = array();
				break;
			}
		}
	}

	$new_row['shipping-costs-column']['content'] = str_replace(
								'[cost_method_field]', 
								$Fish_n_Ships->get_cost_method_html(0, $cost_method), 
								$new_row['shipping-costs-column']['content']);

	$new_row['shipping-costs-column']['content'] = str_replace(
								'[cost_input_fields]', 
								apply_filters( 'wc_fns_get_html_price_fields', '', $rule_nr, $cost_values ),
								$new_row['shipping-costs-column']['content']);


	// The actions cell
	$actions_html = '';
	$action_nr = 0;
	if (isset($shipping_rule['actions'])) {
		foreach ($shipping_rule['actions'] as $action) {
			if (is_array($action) && isset($action['method'])) {

				// Unknown method? Let's advice about it! (once)
				$idx = 'action-' . $action['method'];
				if ( !isset( $errors[$idx] ) ) {
					$known = $Fish_n_Ships->is_known('action', $action['method']);
					if ($known !== true) $errors[$idx] = $known;
				}
				
				// The selector
				$this_action_html = $Fish_n_Ships->get_action_method_html(0, $all_actions, $action['method']);

				// His auxiliary fields
				$action_details = '';
				if (isset($action['values']) && is_array($action['values'])) {
					$action_details = apply_filters('wc_fns_get_html_details_action', '', $rule_nr, $action_nr, $action['method'], $action['values']);
				}
				
				$actions_html .= str_replace('[action_details]', $action_details, $this_action_html);

				$action_nr++;
			}
		}
	}	
	$new_row['special-actions-column']['content'] = str_replace('[actions]', $actions_html, $new_row['special-actions-column']['content']);


	// ...and parse it as HTML
	$html .= apply_filters('wc_fns_shipping_rules_table_row_html', $new_row);
	
	$rule_nr++;
}

// there is no rules yet? let's put an empty one
if ($rule_nr == 0) {

	// request the cells info
	$new_row = $Fish_n_Ships->shipping_rules_table_cells();
	
	$new_row['order-number']['content'] = '#1';
	
	$selector = $Fish_n_Ships->get_selector_method_html(0, $selection_methods);
	$selector = str_replace('[selection_details]', '', $selector);

	$new_row['selection-rules-column']['content'] = str_replace('[selectors]', $selector, $new_row['selection-rules-column']['content']);
	
	$new_row['shipping-costs-column']['content'] = str_replace(
								'[cost_input_fields]', 
								apply_filters( 'wc_fns_get_html_price_fields', '', 0, array() ),
								$new_row['shipping-costs-column']['content']);

	$new_row['shipping-costs-column']['content'] = str_replace(
								'[cost_method_field]',
								$Fish_n_Ships->get_cost_method_html(0, ''), 
								$new_row['shipping-costs-column']['content']);
	
	$new_row['special-actions-column']['content'] = str_replace('[actions]', '', $new_row['special-actions-column']['content']);

	// ...and parse it as HTML
	$html .= apply_filters('wc_fns_shipping_rules_table_row_html', $new_row);
}




$html .= '
	</tbody>
	<tfoot>
		<tr class="fns-footer-header">' . $header . '</tr>
		<tr>
			<td colspan="6">
				<a href="#" class="button add-rule"><span class="dashicons dashicons-plus"></span> ' . esc_html__('Add a new rule', 'fish-and-ships') . '</a>
				<a href="#" class="button duplicate-rules"><span class="dashicons dashicons-admin-page"></span> ' . esc_html__('Duplicate selected rules', 'fish-and-ships') . '</a>
				<a href="#" class="button delete-rules"><span class="dashicons dashicons-no"></span> ' . esc_html__('Delete selected rules', 'fish-and-ships') . '</a>
			</td>
		</tr>
	</tfoot>
</table>
<div class="overlay"><span class="wc-fns-spinner"></span></div>
</div>
<table class="form-table">
';

// Errors? put it before
if (count($errors) > 0) {
	
	$err_message  = '<div class="error inline"><h3>Warning! Errors found!</h3>';
	$err_message .= '<p><strong>Please, solve this issues before save this method options, otherwise you will lose your configuration.</strong></p>';
	
	foreach ($errors as $error) {
	
		$error = esc_html($error);
		$error = str_replace('Error:', '<span class="wc-fns-error-text">Error:</span>', $error);
		$error = str_replace('Warning:', '<span class="wc-fns-error-text">Warning:</span>', $error);
		$err_message .= '<p>&#8226; ' . $error . '</p>';
	}
	$err_message .= '<p><a href="https://wordpress.org/support/plugin/fish-and-ships/" class="button" target="_blank">' . esc_html__('Get support on WordPress repository', 'fish-and-ships') . '</a> &nbsp; <a href="https://www.wp-centrics.com/contact-support/" class="button" target="_blank">' . esc_html__('Get support about Fish and Ships Pro', 'fish-and-ships') . '</a></p></div>';
	
	$html = $err_message . $html;
}
