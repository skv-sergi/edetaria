<?php
/*
 * Plugin Name: Fish and Ships
 * Plugin URI: https://www.wp-centrics.com/
 * Description: A WooCommerce conditional table rate shipping method. Easy to understand and easy to use, it gives you an incredible flexibility.
 * Version: 1.0.5
 * Author: wpcentrics
 * Author URI: https://www.wp-centrics.com
 * Text Domain: fish-and-ships
 * Domain Path: /languages
 * Requires at least: 4.4
 * Tested up to: 5.4.1
 * WC requires at least: 2.6
 * WC tested up to: 4.0.1
 * Requires PHP: 5.5
 * License: GPLv2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package Fish and Ships
*/

defined( 'ABSPATH' ) || exit;

// Prevent double plugin installation
if (defined('WC_FNS_VERSION')  ) {
	
	include_once dirname(__FILE__) . '/includes/double-installation.php';
	return;
}

define( 'WC_FNS_VERSION', '1.0.5' );
define ('WC_FNS_PATH', dirname(__FILE__) . '/' );
define ('WC_FNS_URL', plugin_dir_url( __FILE__ ) );

/**
 * The main Fish n Ships class (one instance).
 *
 */

class Fish_n_Ships {
		
	private $terms_cached           = array();
	private $options                = array();
	private $im_pro                 = false;
	private $is_wpml                = false;
	private $is_wpml_mc             = false; // WPML Multicurrency
	private $user_texts_translated  = NULL;
	
	/**
	 * Constructor.
	 *
	 */
	public function __construct() {

		$this->id = 'fish_n_ships';
		
		$this->load_options();
		
		$this->small_comments = array();

		// Add Fish n Ships method to the shipping methods list
		add_filter( 'woocommerce_shipping_methods', array($this, 'add_fish_n_ships_method') );

		// Admin-side styles and scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_load_styles_and_scripts' ) );

		// Generates the HTML for a table row
		add_filter('wc_fns_shipping_rules_table_row_html', array($this, 'get_shipping_rules_table_row_html'));

		add_action('woocommerce_after_shipping_rate', array($this, 'after_shipping_rate'), 2, 10);
		
		// Ajax
		add_action( 'wp_ajax_wc_fns_help',     array($this, 'wc_fns_help') );
		add_action( 'wp_ajax_wc_fns_logs',     array($this, 'wc_fns_logs') );
		add_action( 'wp_ajax_wc_fns_wizard',   array($this, 'wc_fns_wizard') );
		add_action( 'wp_ajax_wc_fns_fields',   array($this, 'wc_fns_fields') );
		add_action( 'wp_ajax_wc_fns_freemium', array($this, 'wc_fns_freemium') );
		
		
		// The selection methods
		require WC_FNS_PATH . 'includes/settings-form-fns.php';

		// Link to re-start wizard
		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array( $this, 'add_plugin_action_link' ) );

		// Link to website product
		add_filter( 'plugin_row_meta', array( $this, 'add_plugin_row_meta' ), 10, 2 );

		// Add help tab
		add_action( 'current_screen', array( $this, 'add_tabs' ), 100 );
	}
	
	/**
	 * Load options at plugin inisialitation, and maybe first install.
	 *
	 */
	public function load_options() {
		
		$should_update = false;
		
		// Set default options, for first installation
		$options = array(
			'first_version'    => WC_FNS_VERSION,
			'show_wizard'      => time() - 1, // now
			'first_install'    => time(),
			'five_stars'       => time() + (60 * 60 * 24 * 5), // five days
			'current_version'  => '',
			'anytime_pro'      => 0,
			'serial'           => '',
			'close_freemium'   => 0,
		);
		
		// Load options from DB and overwrite defaults
		$opt_db = get_option( 'fish-and-ships-woocommerce', array() );
		if (is_array($opt_db)) {
			foreach ($opt_db as $key=>$value) {
				$options[$key] = $value;
			}
		}

		// First install?
		if ($options['current_version'] == '') {
			$options['current_version'] = WC_FNS_VERSION;
			$should_update = true;
		}
		
		// Plugin Update?
		if (version_compare($options['current_version'], WC_FNS_VERSION, '<') ) {
			$options['current_version'] = WC_FNS_VERSION;
			$should_update = true;
		}
		
		// Welcome to Pro?
		if ($this->im_pro() && $options['anytime_pro']==0) {
			$options['anytime_pro'] = time();
			$should_update = true;
		}
		
		$this->options = $options;
		if ($should_update) $this->set_options($options);
	}
	
	public function get_options() {
		
		return $this->options;
	}

	public function set_options($options) {

		update_option( 'fish-and-ships-woocommerce', $options, true );
		$this->options = $options;
	}

	/**
	 * Add Fish n Ships method to the shipping methods list
	 *
	 */
	public function add_fish_n_ships_method( $methods ) {
		if ( $this->is_wc() ) $methods[ $this->id ] = 'WC_Fish_n_Ships';
		return $methods;
	}

	/**
	 * Admin-side styles and scripts
	 *
	 */
	public function admin_load_styles_and_scripts () {

		wp_register_script( 'wcfns_admin_script_light', WC_FNS_URL . 'assets/js/admin-fns-light.js', array( 'jquery' ), WC_FNS_VERSION );
		wp_register_style( 'wcfns_admin_style', WC_FNS_URL . 'assets/css/admin-fns.css', array(), WC_FNS_VERSION );

		// Only on WC settings > shipping tab we will load the admin script, for performance reasons
		if ( isset($_GET['page'] ) && $_GET['page'] == 'wc-settings' && isset( $_GET['tab'] ) &&  $_GET['tab'] == 'shipping' ) {
						
			wp_register_script( 'wcfns_admin_script', WC_FNS_URL . 'assets/js/admin-fns.js', array( 'jquery-ui-dialog', 'jquery-ui-sortable' ), WC_FNS_VERSION );

			$data = require WC_FNS_PATH . 'includes/shipping_rules-data-js.php';

			wp_localize_script( 'wcfns_admin_script', 'wcfns_data', $data );

			//3rd party CSS and JS stuff
			do_action('wc-fns-styles-scripts-enqueue');

			wp_enqueue_script ( 'wcfns_admin_script' );
		}
		wp_enqueue_script ( 'wcfns_admin_script_light' );
		wp_enqueue_style  ( 'wcfns_admin_style' );
	}
	
	/**
	 * Small helper text after shipping method 
	 *
	 */
	public function after_shipping_rate($method, $index) {

		if ($this->rate_get_method_id($method) != $this->id) return;

		$id = $this->rate_get_instance_id($method);
		$fns_description = WC()->session->get('fns_description');
		
		if (is_array($fns_description) && isset($fns_description[$id]) && trim($fns_description[$id]) != '') {

			echo '<div class="fns-description">' . esc_html( $this->maybe_translated( $fns_description[$id] ) ) . '</div>';
		}
	}
	
	/**
	 * Not, my friend, change this will not help you to get the premium features ;) 
	 *
	 */
	public function im_pro() {
		return $this->im_pro === true;
	}
	
	/**
	 * Check if method is known
	 *
	 * @since 1.0.0
	 *
	 * @param $type (string)
	 * @param $method_id (string)
	 *
	 * return: 
	 
			true (boolean)
			or
			error text message
	 */
	public function is_known($type, $method_id) {

		switch ($type) {

			case 'selection' :
				// Get selectors
				$selectors = apply_filters( 'wc_fns_get_selection_methods', array () );
				
				if ( isset($selectors[$method_id]) ) {
					if ( $this->im_pro() || !$selectors[$method_id]['onlypro'] ) return true;
					
					return sprintf('Warning: The %s method [%s]: only is supported in the Fish and Ships Pro version', $type, $method_id);
				}
				
				return sprintf('Error: Unknown %s method [%s]: maybe you are downgroaded Fish n Ships?', $type, $method_id);
				
				break;

			case 'cost' :
				// Get costs
				$selectors = apply_filters( 'wc_fns_get_cost_methods', array () );
				
				if ( isset($selectors[$method_id]) ) {
					if ( $this->im_pro() || (!isset($selectors[$method_id]['onlypro']) || !$selectors[$method_id]['onlypro']) ) return true;
					
					return sprintf('Warning: The %s method [%s]: only is supported in the Fish and Ships Pro version', $type, $method_id);
				}
				
				return sprintf('Error: Unknown %s method [%s]: maybe you are downgroaded Fish n Ships?', $type, $method_id);
				
				break;

			case 'action' :
				// Get actions
				$selectors = apply_filters( 'wc_fns_get_actions', array () );
				
				if ( isset($selectors[$method_id]) ) {
					if ( $this->im_pro() || !$selectors[$method_id]['onlypro'] ) return true;
					
					return sprintf('Warning: The %s method [%s]: only is supported in the Fish and Ships Pro version', $type, $method_id);
				}
				
				return sprintf('Error: Unknown %s method [%s]: maybe you are downgroaded Fish n Ships?', $type, $method_id);
				
				break;
		}
		
		return 'Error: unknown ' . $type;
	}
	

	/**
	 * Gives the columns for a rule table row
	 *
	 * @since 1.0.0
	 *
	 * return an array with the indexes: 
	 
			tag: td or th. td by defalult.
			class: the tag class or classes
			content: the content, by default empty. The order-number will be replaced on rendering time
	 */
	
	public function shipping_rules_table_cells() {
		$cells = array();
		
		$cells['check-column'] = array('tag' => 'th', 'class' => 'check-column', 'content' => '<input type="checkbox" name="select">');
		$cells['order-number'] = array('class' => 'order-number', 'content' => '#');
		$cells['selection-rules-column'] = array('class' => 'selection-rules-column');
		$cells['shipping-costs-column'] = array('class' => 'shipping-costs-column');
		$cells['special-actions-column'] = array('class' => 'special-actions-column');
		$cells['column-handle'] = array('class' => 'handle column-handle', 'content');
	
		$cells['selection-rules-column']['content'] = '<div class="selectors">[selectors]</div><div class="add-selector"><a href="#" class="button button-small"><span class="dashicons dashicons-plus"></span> ' . esc_html__('Add a selector', 'fish-and-ships') . '</a></div>';

		$cells['shipping-costs-column']['content'] = '[cost_input_fields] [cost_method_field]';

		$cells['special-actions-column']['content'] = '<div class="actions">[actions]</div><div class="add-action"><a href="#" class="button button-small"><span class="dashicons dashicons-plus"></span> ' .esc_html__('Add an action', 'fish-and-ships') . '</a></div>';

		return apply_filters('wc_fns_shipping_rules_table_cells', $cells );
	}
	
	/*****************************************************************
	    WC getters & Cross WC-version safe functions
	 *****************************************************************/

	/* Check WooCommerce */
	function is_wc() {
		if ( !function_exists('WC') || version_compare( WC()->version, '2.6.0', '<') ) return false;
		return true;
	}

	/* getters */
	
	function get_name($product) {

		if ( version_compare( WC()->version, '2.0.0', '<' ) ) {

			if (isset($product->name)) return $product->name;
			if (isset($product['data']) && isset($product['data']->post) && isset($product['data']->post->post_title)) return $product['data']->post->post_title;

		} else {
			return $product['data']->get_name();
		}
		return 'unknown title';
	}

	/**
	 * Get rate method ID
	 *
	 * @since 1.0.0
	 * @version 1.0.4
	 */
	function rate_get_method_id($method) {
		
		if (method_exists($method, 'get_method_id')) {

			return $method->get_method_id();

		} else {
			return $method->method_id;
		}
	}

	public function get_quantity($product) {

		//get product quantity
		if ( 'wdm_bundle_product' === $product['data']->get_type() ) {
			// Support quantity for product bundle	
			$qty = $product['items_quantity'];
		} else {
			$qty = $product['quantity'];
		}
		return $qty;
	}

	/**
	 * Prevent prior PHP 5.5 parse error
	 *
	 * @since 1.0.1
	 */
	function get_weight($product) {
		
		$weight = method_exists ($product[ 'data' ], 'get_weight') ? $product[ 'data' ]->get_weight() : 0;
		if (is_null($weight) || $weight == false || $weight == '') $weight = 0;
		
		return $weight;
	}
	
	/**
	 * This function will generate an unique value if the SKU product are unset
	 *
	 * @since 1.0.0
	 */
	public function get_sku_safe($product) {

		if ($product['data']->get_sku() != '') return $product['data']->get_sku();

		if ($product['data']->get_type() == 'variation') {
			// every variation has his own ID, and parent_id value will group it
			return $product['data']->get_parent_id() . '-wc-fns-sku-' . $product['data']->get_id();
		} else {
			return 'wc-fns-sku-' . $product['data']->get_id();
		}
	}

	/**
	 * This function will return the product ID. On variations, will return the parent product ID.
	 *
	 * @since 1.0.0
	 */
	public function get_real_id($product) {
		if ($product['data']->get_type() == 'variation') {
			// every variation has his own ID, and parent_id value will group it
			return $product['data']->get_parent_id();
		} else {
			return $product['data']->get_id();
		}
	}

	/**
	 * This function will return the product or variation ID
	 *
	 * @since 1.0.4
	 */
	public function get_prod_or_variation_id($product) {
		return $product['data']->get_id();
	}

	/**
	 * Returns product dimensions 
	 *
	 * @since 1.0.0
	 *
	 * return an array on integers with ordered from big to small dimensions and as is introduced: 
	 
	 		0:      biggest dimension
			1:      mid dimension
			2:      smallest dimension
			lenght: the lenght dimension
			width:  the width dimension
			height: the height dimension
	 */
	function get_dimensions_ordered($product) {
		
		$dimensions = array();
		$dimensions['lenght'] = floatval( $product[ 'data' ]->get_length() );
		$dimensions['width']  = floatval( $product[ 'data' ]->get_width()  );
		$dimensions['height'] = floatval( $product[ 'data' ]->get_height() );

		$ordered = array($dimensions['lenght'], $dimensions['width'], $dimensions['height']);

		/* We will order by default, from big to small [I can't imagine why you can need to deactivate, however... ;) ] 
		   Deactivate this will assume lenght >= width >= height always in product dimensions, 
		   otherwise min/mid/max dimension comparison will make strange things, only if you know what you are doing */
		   if (defined('WC_FNS_SORT_DIMENSIONS') && 'WC_FNS_SORT_DIMENSIONS' == false) {} else { rsort($ordered); }
		
		return array_merge($ordered, $dimensions);
	}

	/**
	 * Get rate instance ID
	 *
	 * @since 1.0.0
	 * @version 1.0.4
	 */
	function rate_get_instance_id($method){

		if (method_exists($method, 'get_instance_id')) {

			return $method->get_instance_id();

		} else {

			// the format is: fish_n_ships:3
			$instance_id = 0;
			$id = explode(':', $method->id);
			if (is_array($id) && isset($id[1])) $instance_id = intval($id[1]);
			return $instance_id;

		}
	}

	/**
	 * Sanitize the cost fields [max_shipping_price and min_shipping_price] in a non-crashing way with old WC releases
	 *
	 */
	public function sanitize_cost( $value ) {

		return $this->sanitize_number($value, 'decimal');
	}

	/*****************************************************************
	    3rd party plugins compatibility
	 *****************************************************************/

	/**
	 * Check if WPML and WC Multilingual are active. Then, if we're on WCML multi-currency
	 *
	 * @since 1.0.0
	 * @version 1.0.5
	 *
	 */
	function check_wpml() {

		// Check if WPML + WCML is actived
		if ( defined('ICL_SITEPRESS_VERSION') && defined('WCML_VERSION') ) {

			global $woocommerce_wpml;

			$this->is_wpml = true;

			if ($woocommerce_wpml->settings['enable_multi_currency'] == WCML_MULTI_CURRENCIES_INDEPENDENT) {
				$this->is_wpml_mc = true;
			}
		}
	}

	public function is_wpml() {
		return $this->is_wpml;
	}

	/**
	 * Get the main language code: en, es, etc. (not locale like: en_US)
	 *
	 * @since 1.0.0
	 *
	 */
	function get_main_lang() {
		
		// Not multilingual?
		if ( !$this->is_wpml() ) return substr(get_locale(), 0, 2);

		global $sitepress;
		return $sitepress->get_default_language();
	}

	/**
	 * Try to translate text through WPML options way
	 *
	 * @since 1.0.0
	 *
	 */
	public function maybe_translated( $text ) {
		
		if ( !is_array($this->user_texts_translated) ) {
			$this->user_texts_translated = get_option( 'wc-fns-translatable', array() );
		}
		$key = md5($text);
		if (isset($this->user_texts_translated[$key])) return $this->user_texts_translated[$key];

		// Not translated?
		return $text;
	}


	/**
	 * Set the translatable texts into a common option, for WPML user texts translation
	 *
	 * @since 1.0.0
	 *
	 */
	function save_translatables($shipping_rules) {
		
		$translatables = get_option('wc-fns-translatable', array() );

		foreach ($shipping_rules as $rule) {
			foreach ($rule['actions'] as $action) {

				if (isset($action['method'])) {
					$trans_fields = apply_filters( 'wc_fns_get_translatable_action', array(), $action['method'] );

					foreach ($trans_fields as $field) {
						if (isset($action['values']) && isset($action['values'][$field]) && trim($action['values'][$field]) != '') {

							$string_translatable = trim($action['values'][$field]);
							$translatables[ md5($string_translatable) ] = $string_translatable;
						}
					}
				}
			}
		}
		update_option('wc-fns-translatable', $translatables, false );
	}

	/**
	 * Get AJAX URL with the main lang site param (only if needed)
	 *
	 * @since 1.0.0
	 *
	 */
	function get_unlocalised_ajax_url() {
	
		global $sitepress;
	
		$ajax_url = admin_url('admin-ajax.php');
	
		if ( $this->is_wpml() ) {
			$ajax_url = add_query_arg('lang', $this->get_main_lang(), $ajax_url);
		}
		return $ajax_url;
	}
	 
	/**
	 * Check if a product (maybe a variation) are in a term (any taxonomy)
	 *
	 * @since 1.0.0
	 * @version 1.0.4
	 *
	 * @param $product (array, info from the cart, language info aded by FnS)
	 * @param $taxonomy the taxonomy terms to look in for
	 * @param $terms (array of the terms ID (term_id)
	 * @param $shipping_class the instance who calls the function
	 *
	 * @return boolean
	 */
	function product_in_term($product, $taxonomy, $terms, $shipping_class) {

		global $wpdb, $Fish_n_Ships;
		
		$product_terms_id = array();
		
		if ( $taxonomy == 'product_shipping_class' ) {
			
			// Variations can be assigned to distinct shipping class than his parent
			// We will initialise the product object to get his shipping class trough WC
			// And store the only one ID into array in the same way as the other taxonomies
			$product_id = $Fish_n_Ships->get_prod_or_variation_id($product);
			$prod_object = wc_get_product($product_id);
			$product_terms_id = array($prod_object->get_shipping_class_id());

			//$shipping_class->debug_log('product: #' . $product['data']->get_id() . ' ' . $Fish_n_Ships->get_name($product) . ' has the shipping class: ' . $prod_object->get_shipping_class_id(), 3);

		} else {
			// The other taxonomies are assigned to parent products on variations
			$product_id = $Fish_n_Ships->get_real_id($product);
			$product_terms = get_the_terms($product_id, $taxonomy);
			foreach ($product_terms as $t) {
				$product_terms_id[] = $t->term_id;
			}
		}
		
		// We will work with lang codes: (en, es, de) etc. NOT locale codes (en_US, es_ES, de_DE) etc.
		if ( $Fish_n_Ships->is_wpml() ) {
			
			// Let's translate (if they are'nt) the product terms into the product language
			$product_terms_id = $this->translate_terms($product_terms_id, $taxonomy, $product['lang']['language_code']);

			// The product is'nt on the main lang? Let's translate (if they are'nt) the terms to seek
			if ( $product['lang']['language_code'] != $this->get_main_lang() ) {

				$terms = $this->translate_terms($terms, $taxonomy, $product['lang']['language_code']);
			
				$shipping_class->debug_log('. &gt; Untranslated product: #' . $product['data']->get_id() . ' ' . $Fish_n_Ships->get_name($product) . ', language: [' . $product['lang']['display_name'] . ']' , 3 );
				$shipping_class->debug_log('. &gt; so we will turn this terms into ' . $product['lang']['display_name'] . ' to match with it: [' . implode(', ', $terms) . ']', 3);
												
				$shipping_class->debug_log('. &gt; product terms turned to ' . $product['lang']['display_name'] . ': [' . implode(', ', $product_terms_id) . ']', 3);
			}
			
			
		}
		
		foreach ($terms as $term_id) {
			if ( in_array($term_id, $product_terms_id) ) return true;
		}
		
		return false;
	}
		
	/**
	 * Get language info of product
	 *
	 */
	function get_lang_info( $product ) {
		return apply_filters( 'wpml_post_language_details', array(), $this->get_real_id($product) );
	}
	
	/**
	 * Translate an array of terms_id from main language to other one (WPML)
	 *
	 * @since 1.0.0
	 *
	 */
	function translate_terms($terms, $taxonomy, $lang) {

		// The key to store into cache
	//	$index_cached = $taxonomy . ($hide_empty ? '-1' : '-0');
	//	if (isset($this->terms_cached[$index_cached])) return $this->terms_cached[$index_cached];

		foreach ($terms as $key=>$term_id) {
			$terms[$key] = apply_filters( 'wpml_object_id', $term_id, $taxonomy, TRUE, $lang );
		}
		return $terms;
	}

	/**
	 * Currency exchange rate abstraction for numerical comparisons (on multicurrency sites)
	 *
	 * @param currency_origin mixed, possible values: main-currency | cart-currency
	 *
	 * @since 1.0.5
	 */
	function currency_abstraction ($currency_origin, $value) {
		
		if ($this->is_wpml_mc && $currency_origin == 'main-currency') {
			// Let's ask WPML convert price to current currency cart
			return apply_filters( 'wcml_raw_price_amount', $value );
		}
		
		return $value;
	}

	/*****************************************************************
	    Sanitization
	 *****************************************************************/
	 
	/**
	 * Check the name of the log, comming from request
	 *
	 * @since 1.0.0
	 *
	 * @param $name raw
	 *
	 * @return boolean
	 */
	 function is_log_name($name) {
		
		if ($name !== sanitize_key($name)) return false;

		// The name of the log should start with wc_fns_log_
		if (strpos($name, 'wc_fns_log_') !== 0) return false;
		
		return true;
	 }

	/**
	 * Check if the value is 1 or 0.
	 *
	 */
	 function is_one_or_zero($what) {

		$what = sanitize_key($what);

		if ($what === '1' || $what === '0') return true;
		return false;
	 }

	/**
	 * Check if the method selector is valid
	 *
	 */
	public function is_valid_selector($method_id) {
		
		$method_id = sanitize_key($method_id);
		
		return $this->is_known('selection', $method_id) === true;
	}

	/**
	 * Sanitize the shipping rules from the admin options form (save)
	 *
	 * @since 1.0.0
	 *
	 * @param $raw_shipping_rules raw stuff from the $_POST object
	 *
	 * @return sanitizied info (array)
	 */
	function sanitize_shipping_rules ($raw_shipping_rules) {
		
		$shipping_rules = array();
		
		foreach ($raw_shipping_rules as $raw_rule) {
			
			if (is_array($raw_rule) && isset($raw_rule['sel'])) {
				

				/*************** Selection rules ****************/
				$rule_sel  = array();
				$sel_nr    = 0;

				foreach ($raw_rule['sel'] as $key=>$sel) {
					
					$values = array();

					// Only key numbers are really selectors
					if ($key === intval($key)) {
						
						$sel = sanitize_key( $sel );
						
						if (isset($raw_rule['sel'][$sel]) && is_array($raw_rule['sel'][$sel])) {
							foreach ($raw_rule['sel'][$sel] as $field=>$array_val) {

								$field = sanitize_key( $field );
								
								if (isset($array_val[$sel_nr])) $values[$field] = $array_val[$sel_nr];
							}
						}
						//error_log('method ' . $sel_nr . ': ' . $sel . ', values: ' . print_r($values, true));
						
						//Sanitize the selector auxiliary fields
						$sanitized = apply_filters('wc_fns_sanitize_selection_fields', array('method' => $sel, 'values' => $values) );
						if (false !== $sanitized) $rule_sel[] = $sanitized;

						$sel_nr++; //Start counting in 0
					}
				}
				
				/*************** Shipping costs ****************/
				
				$rule_costs = array();

				$cost_values = array();
				foreach ($raw_rule['cost'] as $key => $value) {
					$cost_values[sanitize_key($key)] = (is_array($value) && isset($value[0]) ? $value[0] : 0);
				}

				$cost_method = 'once';
				if (isset($raw_rule['cost_method']) && is_array($raw_rule['cost_method']) && isset($raw_rule['cost_method'][0]) ) 
					$cost_method = sanitize_key($raw_rule['cost_method'][0]);
				
				//Sanitize the cost fields
				$sanitized = apply_filters('wc_fns_sanitize_cost', array ('method' => $cost_method, 'values' => $cost_values) );
				if (false !== $sanitized) $rule_costs[] = $sanitized;
					

				/***************Special actions ***************/
				$rule_actions  = array();
				$action_nr     = 0;
				
				if (isset($raw_rule['actions']) && is_array($raw_rule['actions'])  ) {
					foreach ($raw_rule['actions'] as $key=>$action) {
						
						$values = array();

						// Only key numbers are really actions
						if ($key === intval($key)) {

							$action = sanitize_key( $action );
							
							if (isset($raw_rule['actions'][$action]) && is_array($raw_rule['actions'][$action])) {
								foreach ($raw_rule['actions'][$action] as $field=>$array_val) {

									$field = sanitize_key( $field );

									if (isset($array_val[$action_nr])) $values[$field] = $array_val[$action_nr];
								}
							}
							// Sanitize the action auxiliary fields
							$sanitized = apply_filters('wc_fns_sanitize_action', array('method' => $action, 'values' => $values));
							if (false !== $sanitized) $rule_actions[] = $sanitized;
							
							$action_nr++;
						}
					}
				}

				$shipping_rules[] = array('sel' => $rule_sel, 'cost' => $rule_costs, 'actions' => $rule_actions);
			}
		}
		return $shipping_rules;
	}

	/**
	 * Sanitize the field, should be in the array of allowed values
	 *
	 * @since 1.0.0
	 *
	 * @param $field (raw) 
	 * @param $allowed (array)
	 *
	 * @return sanitizied field (mixed)
	 *
	 */
	 
	 public function sanitize_allowed($field, $allowed) {
		 
		 if (in_array($field, $allowed, true)) return $field;
		 
		 //fallback, we will return the first allowed value
		 return reset($allowed);
	 }

	/**
	 * Sanitize HTML before save into database
	 *
	 * @since 1.0.0
	 *
	 * @param $field (raw) 
	 *
	 * @return sanitizied field (html)
	 *
	 */
	 
	 public function sanitize_html($field) {
		 
		 return wp_kses_post( wp_unslash( $field ) );
	 }

	/**
	 * Sanitize text before save into database
	 *
	 * @since 1.0.0
	 *
	 * @param $field (raw) 
	 *
	 * @return sanitizied field (html)
	 *
	 */
	 
	 public function sanitize_text($field) {
		 
		 return sanitize_text_field( wp_unslash( $field ) );
	 }


	/**
	 * Sanitize the numbers from form fields in the same way as WC does prior to database storage
	 *
	 * integers haven't decimals
	 * prices and decimals can have decimals 
	 *
	 * @since 1.0.0
	 *
	 * @param $number (raw) 
	 * @param $type (string) can be: integer | positive-integer | decimal | positive-decimal
	 *
	 * @return sanitizied number (integer)
	 *
	 */

	public function sanitize_number($number, $type = 'unknown') {
		
		if (is_array($number)) {
			trigger_error('Fish n Ships -> sanitize_number(): expects number, not array: ' . print_r($number, true) );
		
			return 0;
		}
		
		$number = wc_clean( wp_unslash( $number ) );
		
		switch ($type) {
			
			case 'integer':
				$number = intval(wc_format_decimal ($number, 0)); // decimals will be removed
				break;

			case 'positive-integer':
				$number = intval(wc_format_decimal ($number, 0)); // decimals will be removed
				if ($number < 0) $number = $number * -1;
				break;

			//case 'price':
			case 'decimal':
				$number = wc_format_decimal ($number); // decimals will be kept
				break;
			
			//case 'positive-price':
			case 'positive-decimal':
				$number = wc_format_decimal ($number); // decimals will be kept
				if ($number < 0) $number = $number * -1;
				break;
			
			case 'id':
				$number = trim($number) == intval($number) ? intval($number) : 0;
				if ($number < 0) $number = 0;
				break;

			default:
				trigger_error('Fish n Ships -> sanitize_number(): expects a known type of number: ' . $type);
		}
		
		return $number;
	}
	
	/**
	 * Format the numbers from database to form fields in the same way as WC does
	 *
	 * integers haven't decimals
	 * prices and decimals can has different decimal separators
	 *
	 * @since 1.0.0
	 *
	 * @param $number (raw) 
	 * @param $type (string) can be: integer | positive-integer | decimal | positive-decimal
	 *
	 * @return localized and maybe escaped number (html ready string)
	 *
	 */

	public function format_number($number, $type) {
		
		switch ($type) {
			
			case 'integer':
			case 'positive-integer':
				$number = intval ($number);
				break;

			case 'decimal':
			case 'positive-decimal':
				$number = wc_format_localized_decimal ($number);
				break;
			
			/*case 'price':
			case 'positive-price':
				$number = wc_format_localized_price ($number);
				break;*/
			
			default:
				trigger_error('Fish n Ships -> format_number(): expects a known type of number: ' . $type);
		}
		
		return esc_attr($number);
	}

	/*****************************************************************
	    Groups
	 *****************************************************************/

	/**
	 * Get the options to populate the select group-by
	 *
	 * @since 1.0.0
	 *
	 * @return options (array)
	 */
	function get_group_by_options () {
		
		$options = array(
							// Will be HTML escaped later
			'none'       => _x( 'None [no grouping]', 'cart objects group-by option', 'fish-and-ships' ),
			'id_sku'     => _x( 'Per ID / SKU', 'cart objects group-by option', 'fish-and-ships' ),
			'product_id' => _x( 'Per product [group variations]', 'cart objects group-by option', 'fish-and-ships' ),
			'class'      => _x( 'Per shipping class', 'cart objects group-by option', 'fish-and-ships' ),
			'all'        => _x( 'All grouped as one', 'cart objects group-by option', 'fish-and-ships' ),
		);
		
		return apply_filters('wc_fns_get_group_by_options', $options );
	}

	/**
	 * Unmatch group and his elements recursively into all groups
	 *
	 * @since 1.0.0
	 *
	 * @param $what_group  (reference) group reference
	 * @param $rule_groups (array) group reference set
	 *
	 * @return nothing 
	 *
	 */
	public function unmatch_group($what_group, $rule_groups) {

		// unmatch this group
		$unmatched = $what_group->get_elements();
		$what_group->unmatch_this_group();
				
		foreach ($rule_groups as $group_by=>$groups_of_groups) {
			foreach ($groups_of_groups as $subindex=>$group) {
				$group->unmatch_elements($unmatched);
			}
		}
	}

	/**
	 * Check if some group has changed
	 *
	 * @since 1.0.0
	 *
	 * @param $rule_groups (array) group set
	 *
	 * @return boolean 
	 *
	 */
	public function somegroup_changed($rule_groups) {
		
		$reply = false;
				
		foreach ($rule_groups as $group_by=>$groups_of_groups) {
			foreach ($groups_of_groups as $subindex=>$group) {
				if ($group->is_changed()) $reply = true;
			}
		}
		return $reply;
	}

	/**
	 * Check if some group matching
	 *
	 * @since 1.0.0
	 *
	 * @param $rule_groups (array) group set
	 *
	 * @return boolean 
	 *
	 */
	public function somegroup_matching($rule_groups) {
		
		$reply = false;
				
		foreach ($rule_groups as $group_by=>$groups_of_groups) {
			foreach ($groups_of_groups as $subindex=>$group) {
				if ($group->is_match()) $reply = true;
			}
		}
		return $reply;
	}


	/**
	 * Collect all non-unmatched products from all groups
	 *
	 * @since 1.0.0
	 *
	 * @param $rule_groups (array) group set
	 *
	 * @return collected products 
	 *
	 */
	public function get_selected_contents($rule_groups, $shipping_class) {
		
		$elements = array();
		
		$shipping_class->debug_log('*Currently matching products (accumulated checkings result):', 2);
				
		foreach ($rule_groups as $group_by=>$groups_of_groups) {

			if ($shipping_class->write_logs !== true) {
				// No writing log? We will save some resources here

				foreach ($groups_of_groups as $subindex=>$group) {
					// we use the overwrite feature of array_merge on coincident keys to avoid duplications
					$elements = array_merge($elements, $group->get_elements() );
				}
			
			} else if ($group_by == 'none') {
				// Let's show non grouped

				foreach ($groups_of_groups as $subindex=>$group) {
					// we use the overwrite feature of array_merge on coincident keys to avoid duplications
					$elements = array_merge($elements, $group->get_elements() );
				}

				$shipping_class->debug_log('Non-grouped > items: ' . count($elements), 3);

				foreach ($elements as $p) {
					$shipping_class->debug_log ('. ' . $this->get_name($p) . ' (' . $this->get_quantity($p) . ')', 4);
				}
			
			} else {
				// Show grouped
			
				foreach ($groups_of_groups as $subindex=>$group) {
					
					$i = $group->get_elements();
					// we use the overwrite feature of array_merge on coincident keys to avoid duplications
					$elements = array_merge($elements, $i );
					
					$shipping_class->debug_log($group_by . ' > ' . $subindex . ' > items: ' . count($i), 3);
					
					foreach ($i as $p) {
						$shipping_class->debug_log ('. ' . $this->get_name($p) . ' (' . $this->get_quantity($p). ')', 4);
					}
				}
			}
		}
		return $elements;
	}

	/**
	 * Count all matched groups
	 *
	 * @since 1.0.0
	 *
	 * @param $rule_groups (array) group set
	 *
	 * @return integer groups count 
	 *
	 */
	public function get_matched_groups($rule_groups) {
		
		$matched_groups = 0;

		foreach ($rule_groups as $group_by=>$groups_of_groups) {
			foreach ($groups_of_groups as $subindex=>$group) {
				if ( count($group->elements) > 0) {
					
					if ($group->group_by == 'none') {

						/* If there is more than one qty of same product and we select non-grouped, 
							we should considere the quantity as groups count */
						foreach ($group->elements as $el) {
							$matched_groups += $this->get_quantity($el);
						}

					} else {
						$matched_groups ++;
					}
				}
			}
		}
		return $matched_groups;
	}


	/*****************************************************************
	    HTML Helpers
	 *****************************************************************/


	/**
	 * Get the min - max HTML code used in most selection methods detail
	 *
	 * @since 1.0.0
	 *
	 * @param $rule_nr (integer) rule ordinal (starting 0)
	 * @param $sel_nr (integer) selector ordinal inside rule (starting 0)
	 * @param $method_id (mixed) method id
	 * @param $units for the input (mixed)
	 * @param $values (array) for populate fields
	 * @param $ambit_field (mixed) for class reference only
	 * @param $ambit(mixed) for class reference only
	 *
	 * @return $html (HTML code) form code for the fields min / max
	 *
	 */
	public function get_min_max_html($rule_nr, $sel_nr, $method_id, $units, $values, $ambit_field='sel', $ambit='selection', $tips = 'val_info') {
		
		// Securing output
		$rule_nr       = intval($rule_nr);
		$sel_nr        = intval($sel_nr);
		$method_id     = esc_attr($method_id);
		$units         = strip_tags($units, 'sup, sub');
		$ambit_field   = esc_attr($ambit_field);
		$ambit         = esc_attr($ambit);

		$html =   '<span class="envelope-fields">'
				. '<span class="field field-min '.$ambit.'-' . $method_id . ' '.$ambit.'-' . $method_id . '-min">' . esc_html_x('Min:', 'label, shorted, for minimum input number', 'fish-and-ships') . ' '
				. '<input type="text" name="shipping_rules[' . $rule_nr . ']['.$ambit_field.']['.$method_id.'][min]['.$sel_nr.']" size="4"'
				. (isset($values['min']) ? (' value="' . esc_attr($values['min']) . '"') : '') . ' data-wc-fns-tip="i18n_min_' . $tips . '"'
				. ' class="wc_fns_input_decimal wc_fns_input_tip" placeholder="0" autocomplete="off"><span class="units">'.$units.'</span></span>'
				. '<span class="field field-max '.$ambit.'-' . $method_id . ' '.$ambit.'-' . $method_id . '-max">' . esc_html_x('Max:', 'label, shorted, for maximum input number', 'fish-and-ships') . ' '
				. '<input type="text" name="shipping_rules[' . $rule_nr . ']['.$ambit_field.']['.$method_id.'][max]['.$sel_nr.']" size="4"'
				. (isset($values['max']) ? (' value="' . esc_attr($values['max']) . '"') : '') . ' data-wc-fns-tip="i18n_max_' . $tips . '"'
				. ' class="wc_fns_input_decimal wc_fns_input_tip" placeholder="[no max]" autocomplete="off"><span class="units">'.$units.'</span></span>'
				.'</span>';
			
		return $html;
	}

	/**
	 * Get a multiple selector field
	 *
	 * @since 1.0.0
	 *
	 * @param $rule_nr (integer) rule ordinal (starting 0)
	 * @param $sel_nr (integer) selector ordinal inside rule (starting 0)
	 * @param $method_id (mixed) method id
	 * @param $taxonomy (mixed) the taxonomy that will offer values
	 * @param $values (array) for populate fields
	 * @param $field_name (mixed) select name field
	 * @param $ambit_field (mixed) for class reference only
	 * @param $ambit(mixed) for class reference only
	 *
	 * @return $html (HTML code) form code for the fields min / max
	 *
	 */
	public function get_multiple_html($rule_nr, $sel_nr, $method_id, $taxonomy, $values, $field_name, $ambit_field='sel', $ambit='selection') {
		
		global $Fish_n_Ships;

		// Securing output
		$rule_nr       = intval($rule_nr);
		$sel_nr        = intval($sel_nr);
		$method_id     = esc_attr($method_id);
		$field_name    = esc_attr($field_name);
		$ambit_field   = esc_attr($ambit_field);
		$ambit         = esc_attr($ambit);

		$html = '<span class="field field-multiple '.$ambit.'-'.$method_id.' '.$ambit.'-'.$method_id.'-'.$field_name.'">
				<select multiple="multiple" class="multiselect chosen_select" autocomplete="off" required  
				name="shipping_rules['.$rule_nr.']['.$ambit_field.']['.$method_id.']['.$field_name.']['.$sel_nr.'][]">';

		$options = $Fish_n_Ships->get_terms($taxonomy);
		
		foreach ($options as $id => $caption) {

			$selected = (in_array($id, $values)) ? ' selected ' : '';

			$html .= '<option value="' . esc_attr($id) . '"'.$selected .'>' . esc_html($caption) . '</option>';
		}
		$html .= '</select></span>';

		return $html;
	}

	/**
	 * Get the selector method HTML 
	 *
	 * @since 1.0.0
	 *
	 * @param $rule_nr (integer) rule number
	 * @param $selection_methods who will populate it (array)
	 * @param $sel_method_id (string) the option selected
	 *
	 * @return $html (HTML code) form code for the selector
	 *
	 */
	function get_selector_method_html($rule_nr, $selection_methods, $sel_method_id = '') {

		// Securing output
		$rule_nr = intval($rule_nr);
				
		$html = '<div class="selection_wrapper"><span class="helper"></span>
					<select name="shipping_rules[' . $rule_nr . '][sel][]" class="wc-fns-selection-method" required>
						<option value="">' . esc_html__('Select one criterion', 'fish-and-ships') . '</option>';
		
		foreach ($selection_methods as $method_id=>$method) {
			
			if (!$this->im_pro() && $method['onlypro']) {

				$html .= '<option value="pro" ';
				if ($sel_method_id == $method_id) $html .= 'selected ';
				$html .= 'class="only_pro">' . esc_html($method['label'] . ' [PRO]') . '</option>';

			} else {

				$html .= '<option value="' . esc_attr($method_id) . '" ';
				if ($sel_method_id == $method_id) $html .= 'selected ';
				$html .= '>' . esc_html($method['label']) . '</option>';
			}
		}
	
		$html .= '	</select>
					<div class="selection_details">[selection_details]</div>
					<a href="#" class="delete" title="' . esc_attr_x('Remove selector', 'button caption', 'fish-and-ships') . '"><span class="dashicons dashicons-dismiss"></span></a>
				</div>';
			
		return $html;
	}

	/**
	 * Get the group-by method HTML 
	 *
	 * @since 1.0.0
	 *
	 * @param $rule_nr (integer) rule ordinal (starting 0)
	 * @param $sel_nr (integer) selector ordinal inside rule (starting 0)
	 * @param $method_id (string) the parent method_id
	 * @param $values (array) the selected values
	 *
	 * @return $html (HTML code) form code for the selector
	 *
	 */
	function get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values) {

		// Securing output
		$rule_nr       = intval($rule_nr);
		$sel_nr        = intval($sel_nr);
		$method_id     = esc_attr($method_id);
		
		$sel_opt = isset($values['group_by']) ? $values['group_by'] : '';

		$html =   '<span class="field field-group_by selection-' . $method_id . ' selection-' . $method_id . '-group_by">' . esc_html_x('Group by:', 'shorted, label for options field', 'fish-and-ships') . ' <a class="woocommerce-help-tip woocommerce-fns-help-popup" data-fns-tip="group_by" data-tip="' . esc_attr__('It will determine how the cart products should be grouped (or not) before analyze if it matches the selection conditions.', 'fish-and-ships') . ' ' . esc_attr('Click to open detailed help about Group by.', 'fish-and-ships') . '"></a> '
				. '<select name="shipping_rules[' . $rule_nr . '][sel]['.$method_id.'][group_by]['.$sel_nr.']">';
		
		foreach ($this->get_group_by_options() as $key=>$caption) {
			
			$html .= '<option value="' . esc_attr($key) . '"' . ($key == $sel_opt ? ' selected' : '') . '>' . esc_html($caption) . '</option>';
		}
		$html .=  '</select></span>';
				
		return $html;
	}

	function cant_get_group_by_method_html($rule_nr, $sel_nr, $method_id, $values) {
		
		$html =   '<span class="' . esc_attr('field field-group_by field-cant-group_by selection-' . $method_id . ' selection-' . $method_id . '-group_by') . '">'
				. '[' . esc_html__('This method can\'t group cart items and it will be compared one by one', 'fish-and-ships') . '] <a class="woocommerce-help-tip woocommerce-fns-help-popup" data-fns-tip="group_by" data-tip="' . esc_attr__('It will determine how the cart products should be grouped (or not) before analyze if it matches the selection conditions.', 'fish-and-ships'). ' ' . esc_attr('Click to open detailed help about Group by.', 'fish-and-ships') . '"></a></span>';
				
		return $html;
	}

	/**
	 * Get the cost method HTML 
	 *
	 * @since 1.0.0
	 *
	 * @param $rule_nr (integer) rule number
	 * @param $sel_method_id (string) the option selected
	 *
	 * @return $html (HTML code) form code for the selector
	 *
	 */
	function get_cost_method_html($rule_nr, $sel_method_id = '') {
		
		$html = '<span class="field"><select name="shipping_rules[' . intval($rule_nr) . '][cost_method][]" class="wc-fns-cost-method">';
		
		$cost_methods = apply_filters( 'wc_fns_get_cost_methods', array() );			
		
		foreach ($cost_methods as $method_id=>$method) {
			
			$html .= '<option value="' . esc_attr($method_id) . '" ';
			if ($sel_method_id == $method_id) $html .= 'selected ';
			$html .= '>' . esc_html($method['label']) . '</option>';
		}
	
		$html .= '	</select></span>';
			
		return $html;
	}

	/**
	 * Get the action method HTML 
	 *
	 * @since 1.0.0
	 *
	 * @param $rule_nr (integer) rule number
	 * @param $actions (array)
	 * @param $sel_action_id (string) the option selected
	 *
	 * @return $html (HTML code) form code for the selector
	 *
	 */
	function get_action_method_html($rule_nr, $actions, $sel_action_id = '') {

		$html = '<div class="action_wrapper">
					<span class="field"><select name="shipping_rules[' . intval($rule_nr) . '][actions][]" class="wc-fns-actions" required>
						<option value="">' . esc_attr__('Select one action', 'fish-and-ships') . '</option>';
		
		foreach ($actions as $action_id=>$action) {

			if (!$this->im_pro() && $action['onlypro']) {
			
				$html .= '<option value="pro" ';
				if ($sel_action_id == $action_id) $html .= 'selected ';
				$html .= 'class="only_pro">' . esc_html($action['label'] . ' [PRO]').'</option>';

			} else {

				$html .= '<option value="' . esc_attr($action_id) . '" ';
				if ($sel_action_id == $action_id) $html .= 'selected ';
				$html .= '>' . esc_html($action['label']) . '</option>';
			}
		}
	
		$html .= '	</select></span>
					<div class="action_details">[action_details]</div>
					<a href="#" class="delete" title="' . esc_attr_x('Remove action', 'button caption', 'fish-and-ships') . '"><span class="dashicons dashicons-dismiss"></span></a>
				</div>';
			
		return $html;
	}

	/**
	 * FILTER. NOT ACCESSED DIRECTLY
	 * 
	 * Generates the HTML for a table row
	 *
	 * @since 1.0.0
	 *
	 * @param array $cells: the cells indexed.
	 * @param array $tokens_to_replace: a pair keys/value to replace in the content.
	 *
	 */
	public function get_shipping_rules_table_row_html($cells = array(), $tokens_to_replace = array()) {
		
		$html = '';
		foreach ($cells as $cell) {
			
			// Tag must be td or th, td by default
			$tag = 'td'; if (isset($cell['tag']) && $cell['tag']=='th') $tag = 'th';
	
			// Class is optional
			$class = ''; if (isset($cell['class'])) $class = $cell['class'];
	
			// Content is optional
			$content = ''; if (isset($cell['content'])) $content = $cell['content'];
			
			// Maybe there is some token to replace
			foreach ($tokens_to_replace as $token=>$value) {
				$content = str_replace($token, $value, $content);	
			}
			
			// Securing output
			$class    = esc_attr($class);
			
			$html .= "<$tag class=\"$class\">$content</$tag>";
		}
		
		return '<tr>' . $html . '</tr>';
	}

	/*****************************************************************
	    Getting terms and nesting it
	 *****************************************************************/

	/**
	 * Get terms of some taxonomy (cached) 
	 * 
	 * We let store into array, for performance. Only once will be queried, ordered, and human readable rendered
	 *
	 * @since 1.0.0
	 *
	 * @param $taxonomy (string) 
	 *
	 * @return ordered array of terms
	 *
	 */
	function get_terms($taxonomy, $hide_empty = false) {
		
		// The key to store into cache
		$index_cached = $taxonomy . ($hide_empty ? '-1' : '-0');
		if (isset($this->terms_cached[$index_cached])) return $this->terms_cached[$index_cached];
			
		$terms = get_terms( array(
			'taxonomy'     => $taxonomy,
			'orderby'      => 'name',
			'pad_counts'   => false,
			'hierarchical' => 1,
			'hide_empty'   => $hide_empty,
		));
		
		//Prevent no taxonomy (earlier WC versions)
		if (!is_array($terms)) return array();
		
		$hierarchical = $this->order_terms_recursive($taxonomy, $terms, 0);
		
		$this->terms_cached[$index_cached] = $this->walk_terms_recursive($hierarchical, 0);
		
		return $this->terms_cached[$index_cached];
	}

	/**
	 * Auxiliary get_terms function: Recursive to order the terms 
	 *
	 * get_terms() use it
	 *
	 * @since 1.0.0
	 *
	 */
	function order_terms_recursive($taxonomy, $terms, $parent = 0) {
		$childs = array();
		foreach ($terms as $i=>$term) {
			if ($term->parent == $parent) {
				$childs[$term->term_id] = array('term' => $term, 'childs' => $this->order_terms_recursive($taxonomy, $terms, $term->term_id) );
			}
		}
		return $childs;
	}

	/**
	 * Auxiliary get_terms function: Recursive to walk the ordered terms and tab it into human readable format 
	 *
	 * get_terms() use it
	 *
	 * @since 1.0.0
	 *
	 */
	function walk_terms_recursive($terms, $indent = 0) {
		$walk = array();
		foreach ($terms as $i=>$term) {
			$walk[$term['term']->term_id] = str_repeat('- ', $indent) . $term['term']->name;
			$walk += $this->walk_terms_recursive($term['childs'], $indent + 1);
		}
		return $walk;
	}

	/*****************************************************************
	    AJAX
	 *****************************************************************/

	/**
	 * Reply help HTML file. Will try user language and, if the file doesn't exists, they take the english version
	 *
	 * @since 1.0.0
	 * @version 1.0.2
	 */
	function wc_fns_help() {
		$log = '';
		
		$what = isset($_GET['what']) ? sanitize_key($_GET['what']) : '';

		$lang = isset($_GET['lang']) ? sanitize_key($_GET['lang']) : 'en';

		//Not in user locale? let's try global lang or fallback to english
		if ($lang != 'en' && !is_file(WC_FNS_PATH . 'help/' . $lang . '/' . $what . '.html')) {
			
			$log = '<p>' . WC_FNS_URL . 'help/' . $lang . '/' . $what . '.html</p>';

			if (strlen($lang) > 2) {
				$lang = substr($lang, 0, 2);
	
				if ($lang != 'en' && !is_file(WC_FNS_PATH . 'help/' . $lang . '/' . $what . '.html')) {
	
					$log .= '<p>' . WC_FNS_URL . 'help/' . $lang . '/' . $what . '.html</p>';
					$lang = 'en';
				}
			} else {
				$lang = 'en';
			}
		}

		if (!is_file(WC_FNS_PATH . 'help/' . ($lang == 'en' ? '' : ($lang . '/') ) . $what . '.html')) {

			$log .= '<p>' . WC_FNS_URL . 'help/' . ($lang == 'en' ? '' : ($lang . '/') ) . $what . '.html</p>';
			echo '<html><head><title>Error</title></head><body><h1>Error</h1><div id="content">'
					. '<p>Help file(s) not found:</p>' . $log . '</div></body></html>';
			exit();
		}
		
		$help = file_get_contents(WC_FNS_PATH . 'help/' . ($lang == 'en' ? '' : ($lang . '/') ) . $what . '.html');
		
		$help = str_replace('<img src="img/', '<img src="' . WC_FNS_URL . 'help/img/', $help);
		$help = str_replace('<img src="../img/', '<img src="' . WC_FNS_URL . 'help/img/', $help);
		$help = str_replace('href="img/', 'href="' . WC_FNS_URL . 'help/img/', $help);
		$help = str_replace('href="../img/', 'href="' . WC_FNS_URL . 'help/img/', $help);
		echo $help;
		exit();
	}

	/**
	 * Reply the log file.
	 *
	 */
	function wc_fns_logs() {

		if ( isset($_GET['name']) && $this->is_log_name($_GET['name']) ) {
			
			// validated
			$name = sanitize_key($_GET['name']);
		
			$log_details = get_transient($name);
	
			if ($log_details === false) {
	
				echo 'Error' . "\r\n" . '&nbsp;&nbsp;Log not found. Maybe deleted?';
	
			} elseif (!is_array($log_details) || count($log_details) == 0) {
	
				echo 'Error' . "\r\n" . '&nbsp;&nbsp;Invalid log. Maybe corrupt?';
				
			} else {
	
				foreach ($log_details as $line) {
					$tab = strlen($line) - strlen(ltrim($line));
					$line = ltrim($line);
					$strong = false;
					if (substr($line, 0, 1) == '*') {
						$strong = true;
						$line = substr($line, 1);
					}
					echo apply_filters('the_content', str_repeat('&nbsp;', $tab) . ($strong ? '<strong>' : '') . $line . ($strong ? '</strong>' : '') );
				}
			}
			exit();

		} else {
			
			// fail validation
			echo 0;
			exit();
		}
	}

	/**
	 * Ajax wizard / five stars.
	 *
	 */
	function wc_fns_wizard() {

		$what  = isset($_GET['ajax'])  ? sanitize_key ( $_GET['ajax'] )  : '';
		$when  = isset($_GET['param']) ? sanitize_key ( $_GET['param'] ) : '';

		// Check params
		if ( !in_array($what, array('wizard', 'five-stars'), true ) || !in_array($when, array('now', 'later', 'off'), true ) ) {
			echo '0';
			exit();
		}
		
		$this->update_wizard_opts($what, $when, true);
	}

	/**
	 * Ajax freemium: open / close panel
	 *
	 */
	function wc_fns_freemium() {

		// Only 1 and 0 are allowed
		if ( isset($_GET['opened']) && $this->is_one_or_zero($_GET['opened']) ) {

			$opened  = sanitize_key($_GET['opened']);
			
			if ($opened === '1') {

				$this->options['close_freemium'] = time()-1;
			
			} elseif ($opened === '0') {
	
				$days_delay = 31; // 1 month
				if ( $this->im_pro() ) $days_delay * 11 * 31; // 11 months
				$this->options['close_freemium'] = time() + 60*60*24 * $days_delay;
			}

			$this->set_options($this->options);
			echo '1';
			exit();

		}
			
		// Unexpected parameter
		echo '0';
		exit();
	}

	/**
	 * Ajaxified table rules fields.
	 *
	 */
	function wc_fns_fields() {
		
		if ( isset($_GET['type']) && $_GET['type'] == 'selector' && isset($_GET['method_id']) && $this->is_valid_selector($_GET['method_id']) ) {
		
			$method_id = sanitize_key( $_GET['method_id'] );

			echo apply_filters('wc_fns_get_html_details_method', '', 0, 0, $method_id, array(), false );
			exit();
		}
		echo 'not supported.';
		exit();
	}

	/**
	 * Ajax or URL
	 *
	 * @param $what: wizard | five-stars
	 * @param $when: now | later | off
	 * @param $ajax: boolean
	 */
	function update_wizard_opts($what, $when, $ajax = false) {

		$options = $this->options;

		// We should show now / later / hide wizard forever?
		if ($what == 'wizard') {
		
			// Request 5 stars now can irritate
			$five_stars_time = time() * 60*60*24;
		
			if ($when=='now') $options['show_wizard'] = time() -1; // Now
		
			if ($when=='off') $options['show_wizard'] = time() * 2; // Hide forever

			if ($when=='later') {
				$options['show_wizard'] = time() * 60*60*24*7; // a week
				$five_stars_time = time() * 60*60*24*8; // 8 days
			}
			
			if ( $options['five_stars'] < $five_stars_time) $options['five_stars'] = $five_stars_time;
		
			$this->set_options($options);

		// We should show later / hide five stars forever? (failed AJAX)
		} elseif ($what == 'five-stars') {
		
			if ($when=='off')   $options['five_stars'] = time() * 2; // Hide forever
			if ($when=='later') $options['five_stars'] = time() * 60*60*24*7; // a week
		
			$this->set_options($options);
		}
		
		if ($ajax) {
			echo '1';
			exit();
		}
	}
	
	/*****************************************************************
	    Admin nav small things
	 *****************************************************************/

	/**
	* Add link on the plugin list, to re-start the wizard
	*
	*/
	public static function add_plugin_action_link( $links ){
	
		$start_link = array(
			'<a href="'. admin_url( 'admin.php?page=wc-settings&tab=shipping&wc-fns-wizard=now' )
			 .'" style="color: #a16696; font-weight: bold;">'. esc_html__( 'Start: run wizard', 'fish-and-ships') .'</a>',
		);
	
		return array_merge( $start_link, $links );
	}	

	/**
	* Add link on the plugins page
	*
	*/
	public static function add_plugin_row_meta( $links, $file ) {

		/*if ( strpos( $file, 'fish-and-ships-pro' ) !== false ) {

			$links[] = '<a href="https://www.wp-centrics.com/" target="_blank">
						<strong>'. esc_html__( 'Visit plugin site' ) .'</strong></a>';
		}*/

		if ( strpos( $file, 'fish-and-ships' ) !== false ) {
			$links[] = '<a href="https://www.wp-centrics.com/help/fish-and-ships/" target="_blank">
						<strong style="color:#a16696;">'. esc_html__( 'Plugin help' ) .'</strong></a>';
		}
		return $links;
	}

	/**
	 * Add help tabs (in the same way as WC does).
	 */
	public function add_tabs() {
		
		// WC old versions haven't this
		if ( function_exists('wc_get_screen_ids') ) {
		
		$screen = get_current_screen();

		if ( ! $screen || ! in_array( $screen->id, wc_get_screen_ids() ) ) {
			return;
		}

		$screen->add_help_tab(
			array(
				'id'      => 'wc_fish_n_ships_support_tab',
				'title'   => 'WC Fish and Ships',
				'content' =>
					'<h2>Fish and Ships for WooCommerce</h2>' .
					'<p>' . esc_html__('A WooCommerce shipping method. Easy to understand and easy to use, it gives you an incredible flexibility.', 'fish-and-ships') . '</p>' .
					'<p>&gt; <a href="https://www.wp-centrics.com/help/fish-and-ships/" target="_blank">' . esc_html__('Go to online help documentation', 'fish-and-ships') . '</a></p>' .
					'<p>&gt; <a href="https://wordpress.org/support/plugin/fish-and-ships/" target="_blank">' . esc_html__('Get support on WordPress repository', 'fish-and-ships') . '</a></p>' .
					
					'<p style="padding-top:1em;"><a href="' . admin_url('admin.php?page=wc-settings&tab=shipping&wc-fns-wizard=now') . '" class="button-primary">' . esc_html__('Start wizard', 'fish-and-ships') . '</a> &nbsp;<a href="https://www.wp-centrics.com/contact-support/" class="button" target="_blank">' . esc_html__('Get support about Fish and Ships Pro', 'fish-and-ships') . '</a></p>',
			)
		);
		}
	}
}

$Fish_n_Ships = new Fish_n_Ships();

// Load auxiliary group class
require WC_FNS_PATH . 'includes/group-class.php';

// Load the wizard and wordpress repository rate
if ( is_admin() ) require WC_FNS_PATH . 'includes/wizard.php';

/**
 * After all plugins are loaded, we will initialise everything
 *
 */
 if (!function_exists('wocommerce_fish_n_ships_init')) {
	add_action( 'plugins_loaded', 'wocommerce_fish_n_ships_init', 0 );
	
	function wocommerce_fish_n_ships_init() {
				
		global $Fish_n_Ships;
		
		// Check if we're on multilingual website
		$Fish_n_Ships->check_wpml();

		// Register plugin text domain for translations files
		load_plugin_textdomain( 'fish-and-ships', false, basename( dirname( __FILE__ ) ) . '/languages' );
		
		// PHP prior to 5.5 or WooCommerce not active / old version?
		if ( version_compare( phpversion(), '5.5', '<') || !$Fish_n_Ships->is_wc() ) {
			require WC_FNS_PATH . 'includes/woocommerce-required.php';
			return;
		}
		
		// Load the shipping FnS class (multiple instances).
		if (class_exists('WC_Shipping_Method') && !class_exists('WC_Fish_n_Ships')) {
	
			require WC_FNS_PATH . 'includes/shipping-class.php';
		}
	}
 }
