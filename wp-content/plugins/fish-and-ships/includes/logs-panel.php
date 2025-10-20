<?php
/**
 * The logs panel. 
 *
 * It will show logs, delete old ones and user deletion also.
 *
 * Logs will be deleted after 7 days, but you can change this defining this constant into your wp-config.php:<br />
 * define('WC_FNS_DAYS_LOG', 15); // for 15 days
 *
 * @package Fish and Ships
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $Fish_n_Ships;

$html = '</table>';

// The panel

// Get the logs index
$logs_index = get_option('wc_fns_logs_index', array() );

// Remove logs (user selected & expirated)
$del_logs = '';
$deleted = 0;
if ( isset($_POST['fns-remove_logs']) ) {

	// We will check the WC nonce
	check_admin_referer( 'woocommerce-settings' );

	$del_logs = isset($_POST['log']) ? $_POST['log'] : '';
	unset($_POST['fns-remove_logs']); // prevent multiinstance repeat
}

foreach ($logs_index as $key=>$log) {
	// Remove from index the missing transients (WP expired)
	if (get_transient($log['name']) === false) {
		unset ($logs_index[$key]);
	} else {
		if (is_array($del_logs)) {
			// Remove transient by user petition
			if ( $Fish_n_Ships->is_log_name( $log['name'] ) && in_array( $log['name'], $del_logs, true ) ) {
				unset ($logs_index[$key]);
				delete_transient($log['name']);
				$deleted ++;
			}
		}
	}
}
if ($deleted !=0) echo '<div id="message" class="updated notice inline"><p>' . esc_html( sprintf(__('%s Logs has been deleted.', 'fish-and-ships'), $deleted ) ) . '</p></div>';

// Save updated index
update_option('wc_fns_logs_index', $logs_index, false);

// Remove other instances logs
foreach ($logs_index as $key=>$log) {
	if ($log['instance_id'] != $this->instance_id) unset ($logs_index[$key]);
}

// No logs yet
if ( count($logs_index) == 0 ) {

	$html .= '<div id="fnslogs"><div id="wc_fns_logs_list" class="updated woocommerce-message inline" style="display:none"><p>' 
			. esc_html__('This shipping method have not logs yet (or maybe should refresh this page to see it new ones).', 'fish-and-ships')
			. '</p></div></div>';
} else {

	// Table header
	$html .= '<table class="widefat striped" id="fnslogs">
	<thead>
		<tr><td class="manage-column column-cb check-column">
			<label class="screen-reader-text" for="cb-select-all">' . esc_html__('Select all') . '</label>
			<input class="cb-select-all" type="checkbox">
		</td>
		<th class="thin">' . esc_html_x('When', 'table cell title, human date', 'fish-and-ships') . '</th>
		<th class="thin">' . esc_html__('User') . '</th>
		<th class="thin">' . esc_html__('See', 'fish-and-ships') . '</th>
		<th class="thin">' . esc_html__('Shipping cost', 'fish-and-ships') . '</th>
		<th>' . esc_html_x('Cart Items', 'table cell title, number of items', 'fish-and-ships') . '</th>
	</thead>
	<tbody>';
	
	$log_name = '';
	
	if ( isset($_GET['fns_see_log']) && $Fish_n_Ships->is_log_name($_GET['fns_see_log']) ) {
		$log_name = sanitize_key($_GET['fns_see_log']);
	}

	// Reverse to show: newest before
	$n = 0;

	foreach (array_reverse($logs_index) as $log) {
		
		$n++;
		
		$user = get_userdata($log['user_id']);

		$active = false;
		
		if ($log_name === $log['name']) {

			// Let's read the log
			$active = true;
			$log_details = get_transient($log['name']);
			if ( $log_details === false || !is_array($log_details) || count($log_details) == 0 ) $active = false;
		}
		
		// A row
		$html .= '<tr ' . ( $active ? 'class="fns-open-log loaded"' : '' ) . '>
					<th class="check-column"><input type="checkbox" name="log[]" value="' . esc_attr($log['name']) . '"></th>
					<td class="thin">' . esc_html( sprintf(__('%s ago'), human_time_diff( $log['time'], time() ) ) ) . '</td>
					<td class="thin">' . esc_html( $user->data->display_name ) . '</td>';
					
		$html .= '<td class="thin"><a href="' . add_query_arg(array('fns_remove_log' => false, 'fns_see_log' => $active ? false : $log['name'])) . '#fnslogs" data-fns-log="' . esc_attr($log['name']) . '" class="open_close">';
		
		$html .= '<span class="open">[' . esc_html__('Open', 'fish-and-ships') . ']</span><span class="close">[' . esc_html__('Close', 'fish-and-ships') . ']</span></a></td>';
		
		$html .= '<td class="thin">' . esc_html($log['final_cost']) . '</td>
				  <td>' . esc_html($log['cart_qty']) . '</td></tr>';

		// Display the log via server (no AJAX)
		if ( $active ) {

			$html .= '<tr class="log_content"><td colspan="6"><div class="fns-log-details"><div class="wrap">';

			foreach ($log_details as $line) {
				$tab = strlen($line) - strlen(ltrim($line));
				$html .= apply_filters('the_content', str_repeat('&nbsp;', $tab) . $line);
			}
			$html .= '</div></div></td></tr>';
		}
		
		$max_logs = 30;
		if ($n == $max_logs) break;
	}
	
	// Footer table
	$html .= '</tbody><tfoot><tr><td class="manage-column column-cb check-column">
			<label class="screen-reader-text" for="cb-select-all">' . esc_html__('Select all') . '</label>
			<input class="cb-select-all" type="checkbox">
		</td>
		<td colspan="4">
			<button name="fns-remove_logs" class="button woocommerce-save-button" type="submit" value="' . esc_attr__('Remove selected logs', 'fish-and-ships') . '">' . esc_html__('Remove selected logs', 'fish-and-ships') . '</button>
		</td><td style="text-align:right">';
	
	if ($n < count($logs_index)) {
		
		$html .= esc_html ( sprintf(__('Shown the last %s logs of %s', 'fish-and-ships'), $max_logs, count($logs_index) ) );
	}

	$html .= '</td></tr></tfoot></table><p>' . esc_html ( sprintf(__('* The logs will be deleted after %s days', 'fish-and-ships'), (defined('WC_FNS_DAYS_LOG') ? WC_FNS_DAYS_LOG : 7) ) ) . '</p>';
}

// Re-open the table
$html .= '<table class="form-table">';
