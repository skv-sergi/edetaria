<?php
/**
 * The WC_Fish_n_Ships class. 
 *
 * This is the shipping class that extends WC
 *
 * @package Fish and Ships
 * @version 1.0.1
 */

defined( 'ABSPATH' ) || exit;


class WC_Fish_n_Ships extends WC_Shipping_Method {

	public $log_calculate = array();
	public $log_totals = array();

	/**
	 * Constructor.
	 *
	 * @param int $instance_id Shipping method instance ID.
	 */
	public function __construct( $instance_id = 0 ) {
		
		global $Fish_n_Ships;

		$this->id                    = $Fish_n_Ships->id;
		$this->instance_id           = absint( $instance_id );
		$this->option_name           = 'woocommerce_'. $Fish_n_Ships->id .'_'. $this->instance_id .'_settings';

		$this->method_title          = $Fish_n_Ships->im_pro() ? 'Fish and Ships Pro' : 'Fish and Ships';
		$this->method_description    = __('A WooCommerce shipping method. Easy to understand and easy to use, it gives you an incredible flexibility.', 'fish-and-ships');
		$this->supports              = array(
			'shipping-zones',
			'instance-settings',
			//'instance-settings-modal', (surt la configuracio en un popup, caldria desenvolupar de les dues maneres 
		);
		
		
		$this->init();
		
		// Save the new shipping rules
		add_action( 'woocommerce_update_options_shipping_' . $Fish_n_Ships->id, array( $this, 'process_admin_options' ) );
	}
	
			
	/**
	 * Init user set variables.
	 */
	public function init() {
		
		$this->instance_form_fields = require WC_FNS_PATH . 'includes/settings-fns.php';

		$this->title                    = $this->get_option( 'title' );
		$this->tax_status               = $this->get_option( 'tax_status' );
		$this->global_group_by          = $this->get_option( 'global_group_by' );
		$this->global_group_by_method   = $this->get_option( 'global_group_by_method' );
		$this->volumetric_weight_factor = $this->get_option( 'volumetric_weight_factor' );
		$this->rules_charge             = $this->get_option( 'rules_charge' );
		$this->min_shipping_price       = $this->get_option( 'min_shipping_price' );
		$this->max_shipping_price       = $this->get_option( 'max_shipping_price' );
		$this->write_logs               = $this->get_option( 'write_logs' );

		$this->shipping_rules           = $this->get_shipping_rules();
		
		if ($this->write_logs == 'everyone') {
			$this->write_logs = true;

		} elseif ($this->write_logs == 'admins' && current_user_can( 'manage_options' ) ) {
			$this->write_logs = true;

		} else {
			$this->write_logs = false;
		}
	}
	
	/**
	 * Get the shipping rules 
	 */
	public function get_shipping_rules() {
		
		// a bit of performance
		if ($this->instance_id == 0) return array();
		
		$settings = get_option($this->option_name, array());
		if (is_array($settings) && isset($settings['shipping_rules'])) {
			if (is_array($settings['shipping_rules'])) return $settings['shipping_rules'];
		}
		return array();
	}

	/**
	 * The new shipping rules will be saved if we are editing this
	 */
	public function process_admin_options(){

		global $Fish_n_Ships;

		// The standard fields will be saved by WC
		parent::process_admin_options();
		
		// Now we will save the shipping rules table
		if (isset($_GET['instance_id']) && intval($_GET['instance_id']) == $this->instance_id && isset($_POST['shipping_rules'])) {
			
			// Must be sanitized
			$shipping_rules = $Fish_n_Ships->sanitize_shipping_rules($_POST['shipping_rules']);

			$settings = get_option($this->option_name, array() );
			$settings['shipping_rules'] = $shipping_rules;

			update_option($this->option_name , $settings );
			$this->shipping_rules = $shipping_rules;
			
			$Fish_n_Ships->save_translatables($shipping_rules);
		}
		
		// Reset the cached previous shipping costs (since version 1.0.4)
		WC()->shipping()->reset_shipping();
	}
				
	/**
	* It generates the shipping rules table HTML.
	*
	* @return HTML
	*/
	public function generate_shipping_rules_table_html() {
		require WC_FNS_PATH . 'includes/shipping_rules-table-fns.php';
		return $html;
	}

	/**
	* It generates the logs panel HTML.
	*
	* @return HTML
	*/
	public function generate_logs_panel_html() {
		require WC_FNS_PATH . 'includes/logs-panel.php';
		return $html;
	}

	/**
	* It generates the freemium panel HTML.
	*
	* @return HTML
	*/
	public function generate_freemium_panel_html() {
		require WC_FNS_PATH . 'includes/freemium-panel.php';
		return $html;
	}

	/**
	 * Calculate the shipping costs.
	 *
	 * @since 1.0.1
	 *
	 * @param array $package Package of items from cart.
	 */
	public function calculate_shipping( $package = array() ) {

		global $Fish_n_Ships, $wpdb;
		
		$errors = array();

		if ($this->write_logs === true) {
			
			$this->debug_log('*Starting Fish and Ships ' . ($Fish_n_Ships->im_pro() ? 'Pro' : '(free)') . ' calculation, for method: [' . $this->title . ']. Instance_id: [' . $this->instance_id . '], Local time: [' . current_time( 'mysql' ) . ']', 0);
			
			$this->debug_log('Fish and Ships version: [' . WC_FNS_VERSION . '], WP: [' . get_bloginfo('version') . '], WC: [' . WC()->version . '], Multilingual: ['. ($Fish_n_Ships->is_wpml()? 'YES': 'NO') . ']' . (defined('ICL_SITEPRESS_VERSION') ? ', WPML: ['.ICL_SITEPRESS_VERSION.']' : '') . (defined('WCML_VERSION') ? ', WCML: ['.WCML_VERSION.']' : '') , 0);
		
			$this->log_totals['memory']      = memory_get_usage();
			$this->log_totals['num_queries'] = $wpdb->num_queries;
			$this->log_totals['time_start']  = function_exists('microtime') ? microtime(true) : time();
		}

		$active = false; // Any matching rule will active it.
		$skip_n = 0; 
		$break_pending = false;
		$global_cost = 0;

		// Remove old description if there is one
		$fns_description = WC()->session->get('fns_description');
		if (is_array($fns_description) && isset($fns_description[$this->instance_id])) {
			unset($fns_description[$this->instance_id]);
			WC()->session->set('fns_description', $fns_description );
		}

		// Let's work with shippable contents only
		$n_shippable = 0;
		$n_non_shippable = 0;
		$shippable_contents = array();
		foreach ( $package['contents'] as $key => $product ) {
			
			if ($product['data']->needs_shipping()) {
			
				$shippable_contents[$key] = $product;
				
				// Multilingual? Let's add language information. 
				//   (Products ID not replaced on cart by the default language, haven't translated)
				if ( $Fish_n_Ships->is_wpml() ) $shippable_contents[$key]['lang'] = $Fish_n_Ships->get_lang_info( $product );
				
				$n_shippable += $Fish_n_Ships->get_quantity($product);
			
			} else {
				$n_non_shippable += $Fish_n_Ships->get_quantity($product);
			}
		}
		$this->log_totals['cart_qty'] = ($n_shippable + $n_non_shippable) . ' prods, ' . ($n_non_shippable == 0 ? 'all shippabble' : $n_shippable . ' shippable');

		$rate = array(
			'id'      => $this->get_rate_id(),
			'label'   => $this->title,
			'cost'    => 0,
			'package' => $package,
		);
		$rule = -1;
		$num_matches = 0; // Rules matching selection criteria
		
		// Global Group by is a must in the free version
		if ( $this->global_group_by == 'no' && !$Fish_n_Ships->im_pro()  ) {
			$errors['global-group-by'] = '1';
			$this->debug_log('*Error: Only the Pro version allow distinct grouping criteria on every selection condition');
		}

		foreach ($this->shipping_rules as $shipping_rule) {
			
			$rule++;
			$this->debug_log('*Rule #' . ($rule + 1), 1);
			
			// Support for skipping N rules
			if ($skip_n != 0) {
				$skip_n--;
				$this->debug_log('*Special action: [skip rule]', 1);
				continue;
			}
			
			$shippable_contents_rule = $shippable_contents;
			
			// Reference to all group objects will be stored here
			$rule_groups = array();	

			//if some group has been changed, we should repeat the iterations
			$iterations = 0;

			do {
				$iterations++;

				// On first iteration it's superfluous
				$this->unset_groups($rule_groups);
				
				/************************* Check if selection matches *************************/
				
				$selection_match = false;
				$unique = 0; // safely unique index
				
				if (isset($shipping_rule['sel'])) {
					foreach ($shipping_rule['sel'] as $selector) {
						if (is_array($selector) && isset($selector['method'])) {
							
							// Unknown method? Let's advice about it! (only if should write logs and once)
							$idx = 'selection-' . $selector['method'];
							if ( $this->write_logs === true && !isset( $errors[$idx] ) ) {
								$known = $Fish_n_Ships->is_known('selection', $selector['method']);
								if ($known !== true) {
									$errors[$idx] = '1';
									$this->debug_log('*'.$known, 1);
								}
							}
							
							/************************* Let's group elements *************************/
							$group_by = 'none';
							
							// The auxiliary fields method will be listed in log (if there is anyone)
							$aux_fields_log = '';
							foreach ($selector['values'] as $field=>$value) {
								if ($field != 'group_by') {
									$aux_fields_log .= ', ' . $field . ': [' . (is_array($value) ? implode(', ', $value) : $value) . ']';
								}
							}
							
							// Only this selection methods has group capabilities
							$groupable_sm = apply_filters('wc-fns-groupable-selection-methods', array('by-weight', 'by-price', 'by-volume', 'volumetric', 'quantity') );
							if ( in_array($selector['method'], $groupable_sm) ) {
								
								if ('yes' === $this->global_group_by) {
									// The global group-by option is enabled
									$group_by = $this->global_group_by_method;
								} else {
									// The selection has his own group_by option
									if (isset($selector['values']['group_by'])) $group_by = $selector['values']['group_by'];
								}
								$this->debug_log('Check matching selection. Method: [' . $selector['method'] . '], Group-by: [' . $group_by . ']' . $aux_fields_log, 2);
							} else {
								
								$this->debug_log('Check matching selection. Method: [' . $selector['method'] . '], Group-by: [none] (This method can\'t be grouped)' . $aux_fields_log, 2);
							}

							//$this->debug_log('[start-collapsable]', 2);

							foreach ($shippable_contents_rule as $key=>$product) {
								
								switch ($group_by) {
									case 'id_sku' :
										$subindex = $Fish_n_Ships->get_sku_safe($product);
										break;
	
									case 'product_id' :
										$subindex = $Fish_n_Ships->get_real_id($product);
										break;
	
									case 'class' :
										$subindex = $product['data']->get_shipping_class_id();
										break;
	
									case 'all' :
										$subindex = '';
										break;
	
									case 'none' :
									default :
										$group_by = 'none';
										//$subindex = 'unique-' . $unique;
										$subindex = 'unique-' . $Fish_n_Ships->get_sku_safe($product); //we don't need to create more groups on every selection iteration
										$unique ++;
										break;
								}

								// if the group isn't created, let's create it
								if (!isset($rule_groups[$group_by])) $rule_groups[$group_by] = array();
								if (!isset($rule_groups[$group_by][$subindex])) {
									$rule_groups[$group_by][$subindex] = new Fish_n_Ships_group($group_by, $this);

									//$this->debug_log('creating new group: ' . $group_by . ' > ' . $subindex);
								}
								
								// We will add the product in the right group
								$rule_groups[$group_by][$subindex]->add_element($key, $product, false);
							}

							// no matching products? let's create it empty
							if (!isset($rule_groups[$group_by])) $rule_groups[$group_by] = array();
							if (!isset($rule_groups[$group_by][$subindex])) {
								$rule_groups[$group_by][$subindex] = new Fish_n_Ships_group($group_by, $this);
								
								//$this->debug_log('creating new group: ' . $group_by . ' > ' . $subindex);
							}
													
							$rule_groups = apply_filters('wc_fns_check_matching_selection_method', $rule_groups, $selector, $group_by, $this);

							//$this->debug_log('[end-collapsable]', 2);
							
							// Only matching contents must be evaluated on the next selection or iteration (if needed)
							$shippable_contents_rule = $Fish_n_Ships->get_selected_contents($rule_groups, $this);

							//$this->debug_log('$rule_groups: ' . print_r($rule_groups, true));

						}
					}
					
				}

				// If some group has been changed, we should repeat the iterations
				if ( $repeat = $Fish_n_Ships->somegroup_changed($rule_groups) ) {

					$this->debug_log('All match checking must be reevaluated for rule #' . ($rule + 1) , 2);
				}

				// Prevent infinite loop on error
				if ($iterations > (defined('WC_FNS_MAX_ITERATIONS') ? WC_FNS_MAX_ITERATIONS : 10) ) {
					
					$this->debug_log('Too much iterations. Break to prevent timeout error' , 1);
					trigger_error('WC Fish and Ships: Too much iterations. Break to prevent timeout error');
					$repeat = false;
				}

			} while ($repeat);
			
			// No products match selectors? Skip this rule
			// (crec que es pot aprofitar) if (!$Fish_n_Ships->somegroup_matching($rule_groups) ) {
			if (count($shippable_contents_rule) == 0) {
			
				$this->debug_log('No product matches for this rule', 2);
				$this->unset_groups($rule_groups);
				continue;
			}

 			// This rule will be applied.
 			$active = true;
			$num_matches ++;
			
			// Let's calculate the cost of this rule
			$rule_cost = 0;			
			if (isset($shipping_rule['cost'])) {
				foreach ($shipping_rule['cost'] as $cost) {
					if (is_array($cost) && isset($cost['method'])) {

						// Unknown method? Let's advice about it! (only if should write logs and once)
						$idx = 'cost-' . $cost['method'];
						if ( $this->write_logs === true && !isset( $errors[$idx] ) ) {
							$known = $Fish_n_Ships->is_known('cost', $cost['method']);
							if ($known !== true) {
								$errors[$idx] = '1';
								$this->debug_log('*'.$known, 1);
							}
						}

						$rule_cost = apply_filters('wc_fns_calculate_cost_rule', $rule_cost, $cost, $shippable_contents_rule, $rule_groups, $this);
					}
				}
			}
			
			/*************************Special actions if there are any *************************/

			if (isset($shipping_rule['actions'])) {
				foreach ($shipping_rule['actions'] as $action) {
					if (is_array($action) && isset($action['method'])) {

						// Unknown method? Let's advice about it! (only if should write logs and once)
						$idx = 'action-' . $action['method'];
						if ( $this->write_logs === true && !isset( $errors[$idx] ) ) {
							$known = $Fish_n_Ships->is_known('action', $action['method']);
							if ($known !== true) {
								$errors[$idx] = '1';
								$this->debug_log('*'.$known, 1);
							}
						}
						
						$action_result = array( 
							
							'instance_id' => $this->instance_id,
							
							'abort' => false,			// true will abort this shipping
							'break' => false,			// true will ignore the next rules
							'skip_n' => 0,				// support for skip N rules

							'rule_cost' => $rule_cost,
							
							// Actions can use and overwrite this
							'rate' => $rate,
							'global_cost' => $global_cost, //this rule_cost not added yet
							'shippable_contents_rule' => $shippable_contents_rule,
							'shippable_contents' => $shippable_contents,
							'rule_groups' => $rule_groups //please, unset the group class if you need to unset some register on it
						);
						
						$action_result = apply_filters('wc_fns_apply_action', $action_result, $action, $this);
						
						// Apply the filtered values
						
						if ($action_result['abort']) {
							$active = false;
							$this->debug_log('*Special action: [Abort method]', 1);
							break 2; // Exit two loops: actions and rules
						}

						$skip_n = $action_result['skip_n'];

						$rule_cost = $action_result['rule_cost'];

						$rate = $action_result['rate'];
						$global_cost = $action_result['global_cost'];
						$shippable_contents_rule = $action_result['shippable_contents_rule'];
						$shippable_contents = $action_result['shippable_contents'];
						$rule_groups = $action_result['rule_groups'];

						if ($action_result['break']) {
							$break_pending = true;
							break; // Exit first loop only, I need to add the cost
						}
					}
				} // end loop actions

			}
			
			// Let's apply the cost:
			if ($this->rules_charge == 'max') {
				// only most expensive rule
				if ($rule_cost > $global_cost) $global_cost = $rule_cost;
			
			} elseif ($this->rules_charge == 'min') {
				// only most cheap rule
				if ($rule_cost < $global_cost || $num_matches == 1) $global_cost = $rule_cost;
			
			} elseif ($this->rules_charge == 'all') {
				// all rules will be added
				$global_cost += $rule_cost;
			}
			
			$this->debug_log('*Calculated rule #' . ($rule + 1) . ' cost: ' . strip_tags(wc_price($rule_cost, array('currency' => get_woocommerce_currency() ) )), 1);
			
			$this->unset_groups($rule_groups);
			
			if ($break_pending) {
				$this->debug_log('*Special action: [ignore below rules]', 1);
				break;
			}
			
		} // end main loop rules
		
		if ($active) {
			
			// Finally maybe the global cost is less than the minimum or much than the maximum:
			if (trim($this->min_shipping_price) != '' && $this->min_shipping_price > $global_cost) {
				$global_cost = $this->min_shipping_price;
				$this->debug_log('Force minimum cost: ' . $global_cost, 1);
			}

			if (trim($this->max_shipping_price) != '' && $this->max_shipping_price < $global_cost) {
				$global_cost = $this->max_shipping_price;
				$this->debug_log('Force maximum cost: ' . $global_cost, 1);
			}

			$rate['cost'] += $global_cost;
			$this->add_rate( $rate );

			if ($this->write_logs === true) {
				$this->debug_log('*FINAL COST: ' . strip_tags(wc_price($rate['cost'], array('currency' => get_woocommerce_currency() ) ) ) . ' '
									. ($this->tax_status == 'taxable' ? ' + TAX' : ' [non-taxable]')
									. ($this->rules_charge == 'once' ? ' [only the most expensive rule applied]' : ''), 0);
	
				$this->log_totals['final_cost'] = strip_tags( wc_price($rate['cost'], array('currency' => get_woocommerce_currency() ) ) ) . ' ' . ($this->tax_status == 'taxable' ? ' + TAX' : ' [non-taxable]');
			}

		} else {
			
			$this->debug_log('*Method not applicable', 0);

			$this->log_totals['final_cost'] = '[non-applicable]';
		}
		
		if ($this->write_logs === true) {

			// There is some error? Let's advice it on summary log
			if ( count($errors) > 0 ) $this->log_totals['final_cost'] = '<span class="wc-fns-error-text">[ERROR]</span> ' . $this->log_totals['final_cost'];
			
			// Calculate total resources used
			$kb = floor((memory_get_usage() - $this->log_totals['memory']) / 100) / 10;
			$secs = function_exists('microtime') ? substr(microtime(true) - $this->log_totals['time_start'], 0, 6) : ( time() - $this->log_totals['time_start']);
			$this->debug_log('Usage on calculation: Memory: [' . $kb . 'KB], DB queries: [' . ($wpdb->num_queries  - $this->log_totals['num_queries']) . '], Time elapsed: [' . $secs . ' sec.]', 0);
		
			// Save the log
			$this->save_debug_log();
		}

	}

	/**
	 * The groups must be deleted and memory can be liberated.
	 *
	 * @param  array $rule_groups
	 */
	public function unset_groups($rule_groups) {
				
		foreach ($rule_groups as $group_by) {
			foreach ($group_by as $group) {
				unset($group);
			}
		}
	}

	/**
	 * Store a new text line into logs array if log are activated.
	 *
	 * @param  text $message
	 * @param  integer $tab
	 */
	 public function debug_log($message, $tab = 0) {

		 if ($this->write_logs !== true) return;
		 
		 $this->log_calculate[] = str_repeat('  ', $tab) . sanitize_text_field($message);
	 }

	/**
	 * Save the debug log at end shipping calculation process
	 *
	 */
	 public function save_debug_log() {
		 
		 if ($this->write_logs !== true || count($this->log_calculate) == 0) return;
		 
		 // create an unique name
		 do {
			 $name = 'wc_fns_log_' . get_current_user_id() . '_' . $this->instance_id . '_' . rand(0, 9999999);
		} while (false !== get_transient($name) );
		
		// save log in transient
		set_transient($name, $this->log_calculate, 60*60*24 * (defined('WC_FNS_DAYS_LOG') ? WC_FNS_DAYS_LOG : 7) );
		
		// store in main list
		$logs_index = get_option('wc_fns_logs_index', array() );

		$logs_index[] = array(
						'time' => time(),
						'user_id' => get_current_user_id(),
						'instance_id' => $this->instance_id,
						'name' => $name,
						'final_cost' => $this->log_totals['final_cost'],
						'cart_qty' => $this->log_totals['cart_qty'],
						);
		
		update_option('wc_fns_logs_index', $logs_index, false);
	 }

} // End WC_Fish_n_Ships class.
