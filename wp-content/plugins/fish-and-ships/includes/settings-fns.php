<?php
/**
 * The WC-way form for the shipping method options.
 *
 * @package Fish and Ships
 * @version 1.0.1
 */

defined( 'ABSPATH' ) || exit;

global $Fish_n_Ships;

$free = '';
if ( !$Fish_n_Ships->im_pro() ) {
	$free = '<br><br>' 
			. wp_kses( __('Only the <strong>Pro version</strong> allow distinct grouping criteria on every selection condition.', 'fish-and-ships'),
						 array('strong'=>array())
					);
}

$settings = array(

	// The freemium panel
	'freemium' => array(
		'type'          => 'freemium_panel',
		'default'       => ''
	),

	'title' => array(
							// WooCommerce will escape HTML later 
							
		'title'         => __( 'Method title', 'woocommerce' ),
		'type'          => 'text',
		'description'   => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ) . ' ' . __('Click to open detailed help about all input fields.', 'fish-and-ships' ),
		'default'       => 'Fish and Ships',
		'desc_tip'      => true,
	),
	
	'tax_status' => array(
		'title'         => __( 'Tax status', 'woocommerce' ),
		'type'          => 'select',
		//'class'         => 'wc-enhanced-select',
		'default'       => 'taxable',
		'options' => array(
			'taxable'   => __( 'Taxable', 'woocommerce' ),
			'none'      => _x( 'None', 'Tax status', 'woocommerce' ),
		),
		'description'   => __( 'Should apply taxes over the calculated shipping cost?', 'fish-and-ships' ) . ' ' . __('Click to open detailed help about all input fields.', 'fish-and-ships' ),
		'desc_tip'      => true,
	),

	'global_group_by' => array(
		'title' 		=> _x( 'Global group-by', 'shorted, label for global group-by activation', 'fish-and-ships' ),
		'type' 			=> 'checkbox',
		'label'         => __( 'All the selection methods will use the same group product criterion (just below)', 'fish-and-ships') . ($Fish_n_Ships->im_pro() ? '' : '[PRO]'),
		'description' 	=> __( 'Uncheck it and you can set the group-by option for every selector (a bit messy but much powerful).', 'fish-and-ships' ) . $free . ' ' . __('Click to open detailed help about Group by.', 'fish-and-ships'),
		'class'         => $Fish_n_Ships->im_pro() ? '' : 'onlypro',
		'default' 		=> 'yes',
		'desc_tip'		=> true,
	),

	'global_group_by_method' => array(
		'title'         => _x( 'Group by [for all selectors]', 'shorted, label for global group-by method select', 'fish-and-ships' ),
		'description'   => __( 'It will determine how the cart products should be grouped (or not) before analyze if it matches the selection conditions.', 'fish-and-ships' ) . ' ' . __('Click to open detailed help about Group by.', 'fish-and-ships'),
		'type'          => 'select',
		//'class'         => 'wc-enhanced-select group-by-global-select',
		'class'         => 'group-by-global-select',
		'default'       => 'none',
		'options'       => $Fish_n_Ships->get_group_by_options(),
		'desc_tip'		=> true,
	),
	
	'rules_charge' => array(
		'title'         => __( 'Calculation type', 'woocommerce' ),
		'type'          => 'select',
		//'class'         => 'wc-enhanced-select',
		'default'       => 'all',
		'options' => array(
			'all'       => __( 'Charge all matching rules', 'fish-and-ships' ),
			'max'       => __( 'Charge only the most expensive matching rule', 'fish-and-ships' ),
			'min'       => __( 'Charge only the most cheap matching rule', 'fish-and-ships' ),
		),
		'description'   => __( 'You can choose between charge all rules or only the most expensive/cheap one.', 'fish-and-ships' ) . ' ' . __('Click to open detailed help about all input fields.', 'fish-and-ships' ),
		'desc_tip'		=> true,
	),

	'special_rate' => array(
		'title'         => _x( 'Shipping rules', 'the table title', 'fish-and-ships' ),
		'type'          => 'title',
		'description'   => str_replace(array('(',')'), array('&nbsp; <a href="https://www.wp-centrics.com/help/fish-and-ships/" class="woocommerce-fns-help-popup button-primary" data-fns-tip="main" target="_blank">','</a></span>'), __( 'Set up the shipping rules below. Here the (Main Help)', 'fish-and-ships') ),
		'default'       => ''
	),
	
	// The shipping rules table
	'shipping_rules' => array(
		'type'		    => 'shipping_rules_table',
		'default'       => ''
	),

	'volumetric_weight_factor' => array(
		'title'         => __( 'Volumetric Weight Factor', 'fish-and-ships' ) . ' (' . get_option('woocommerce_weight_unit') . '/' . get_option('woocommerce_dimension_unit') . '<sup style="font-size:0.75em; vertical-align:0.25em">3</sup>)',
		'type'          => 'decimal',
		'description'   => __( 'The factor value to calculate the volumetric weight. Click to open detailed help about.', 'fish-and-ships' ),
		'desc_tip'		=> true,
		'placeholder'   => __( 'i.e. 5000', 'fish-and-ships' ),
		'default'       => '',
	),

	'min_shipping_price'       => array(
		'title'             => __( 'Min shipping cost', 'fish-and-ships' ) . ' (' . get_woocommerce_currency_symbol() . ')',
		'type'              => 'text',
		'class'				=> 'wc_fns_input_decimal',
		'placeholder'       => '[none]',
		'description'       => __('The minimum shipping cost in any case (if any shipping rule matches).', 'fish-and-ships') . ' ' . __('Click to open detailed help about all input fields.', 'fish-and-ships' ),
		'default'           => '',
		'desc_tip'          => true,
		'sanitize_callback' => array( $Fish_n_Ships, 'sanitize_cost' ),
	),

	'max_shipping_price'       => array(
		'title'             => __( 'Max shipping cost', 'fish-and-ships' ) . ' (' . get_woocommerce_currency_symbol() . ')',
		'type'              => 'text',
		'class'				=> 'wc_fns_input_decimal',
		'placeholder'       => '[none]',
		'description'       => __('The maximum shipping cost in any case (if any shipping rule matches).', 'fish-and-ships') . ' ' . __('Click to open detailed help about all input fields.', 'fish-and-ships' ),
		'default'           => '',
		'desc_tip'          => true,
		'sanitize_callback' => array( $Fish_n_Ships, 'sanitize_cost' ),
	),

	'write_logs' => array(
		'title'         => __( 'Write logs', 'fish-and-ships' ),
		'type'          => 'select',
		//'class'         => '__wc-enhanced-select',
		'options' => array(
			'off'       => _x( 'Off', 'switch on/off', 'fish-and-ships' ),
			'admins'    => __( 'Save logs only from Admin users', 'fish-and-ships' ),
			'everyone'  => __( 'Save logs from all users (only on pre-production)', 'fish-and-ships' ),
		),
		'default'       => 'off',
		'description'   => __('Really useful if you are testing your new-configured shipping method.', 'fish-and-ships') . '<br>' . __('The logs will be stored in your database and don\'t send anywhere.', 'fish-and-ships'),
		'desc_tip' 		=> __( 'It will help a lot to understand why the shipping costs don\'t does what you expect, and/or help us to debug.', 'fish-and-ships' ) . ' ' . __('Click to open detailed help about all input fields.', 'fish-and-ships' ),
	),

	// The logs panel
	'the_logs'              => array(
		'type'		        => 'logs_panel',
		'default'           => ''
	),
);

return $settings;
