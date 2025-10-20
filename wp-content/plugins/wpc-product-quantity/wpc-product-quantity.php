<?php
/*
Plugin Name: WPC Product Quantity for WooCommerce
Plugin URI: https://wpclever.net/
Description: WPC Product Quantity provides powerful controls for product quantity.
Version: 1.2.2
Author: WPClever.net
Author URI: https://wpclever.net
Text Domain: wpc-product-quantity
Domain Path: /languages/
Requires at least: 4.0
Tested up to: 5.4.1
WC requires at least: 3.0
WC tested up to: 4.1.0
*/

defined( 'ABSPATH' ) || exit;

! defined( 'WOOPQ_VERSION' ) && define( 'WOOPQ_VERSION', '1.2.2' );
! defined( 'WOOPQ_URI' ) && define( 'WOOPQ_URI', plugin_dir_url( __FILE__ ) );
! defined( 'WOOPQ_PATH' ) && define( 'WOOPQ_PATH', plugin_dir_path( __FILE__ ) );
! defined( 'WOOPQ_REVIEWS' ) && define( 'WOOPQ_REVIEWS', 'https://wordpress.org/support/plugin/wpc-product-quantity/reviews/?filter=5' );
! defined( 'WOOPQ_CHANGELOG' ) && define( 'WOOPQ_CHANGELOG', 'https://wordpress.org/plugins/wpc-product-quantity/#developers' );
! defined( 'WOOPQ_DISCUSSION' ) && define( 'WOOPQ_DISCUSSION', 'https://wordpress.org/support/plugin/wpc-product-quantity' );
! defined( 'WPC_URI' ) && define( 'WPC_URI', WOOPQ_URI );

include 'includes/wpc-menu.php';
include 'includes/wpc-dashboard.php';

if ( ! function_exists( 'woopq_init' ) ) {
	add_action( 'plugins_loaded', 'woopq_init', 11 );

	function woopq_init() {
		// load text-domain
		load_plugin_textdomain( 'wpc-product-quantity', false, basename( __DIR__ ) . '/languages/' );

		if ( ! function_exists( 'WC' ) || ! version_compare( WC()->version, '3.0.0', '>=' ) ) {
			add_action( 'admin_notices', 'woopq_notice_wc' );

			return;
		}

		if ( ! class_exists( 'WPCleverWoopq' ) && class_exists( 'WC_Product' ) ) {
			class WPCleverWoopq {
				function __construct() {
					// enqueue backend
					add_action( 'admin_enqueue_scripts', array( $this, 'woopq_admin_enqueue_scripts' ) );

					// enqueue frontend
					add_action( 'wp_enqueue_scripts', array( $this, 'woopq_wp_enqueue_scripts' ) );

					// settings page
					add_action( 'admin_menu', array( $this, 'woopq_admin_menu' ) );

					// settings link
					add_filter( 'plugin_action_links', array( $this, 'woopq_action_links' ), 10, 2 );
					add_filter( 'plugin_row_meta', array( $this, 'woopq_row_meta' ), 10, 2 );

					// args
					add_filter( 'woocommerce_quantity_input_args', array( $this, 'woopq_quantity_input_args' ), 10, 2 );
					add_filter( 'woocommerce_loop_add_to_cart_args', array(
						$this,
						'woopq_loop_add_to_cart_args'
					), 10, 2 );

					// default input
					add_filter( 'woocommerce_quantity_input_min', array( $this, 'woopq_quantity_input_min' ), 10, 2 );
					add_filter( 'woocommerce_quantity_input_max', array( $this, 'woopq_quantity_input_max' ), 10, 2 );
					add_filter( 'woocommerce_quantity_input_step', array( $this, 'woopq_quantity_input_step' ), 10, 2 );

					// decimal
					if ( get_option( '_woopq_decimal', 'no' ) === 'yes' ) {
						remove_filter( 'woocommerce_stock_amount', 'intval' );
						add_filter( 'woocommerce_stock_amount', 'floatval' );
					}

					// template
					add_filter( 'wc_get_template', array( $this, 'woopq_quantity_input_template' ), 10, 2 );

					// Product data tabs
					add_filter( 'woocommerce_product_data_tabs', array( $this, 'woopq_product_data_tabs' ), 10, 1 );
					add_action( 'woocommerce_product_data_panels', array( $this, 'woopq_product_data_panels' ) );
				}

				function woopq_admin_enqueue_scripts() {
					wp_enqueue_style( 'woopq-backend', WOOPQ_URI . 'assets/css/backend.css' );
					wp_enqueue_script( 'woopq-backend', WOOPQ_URI . 'assets/js/backend.js', array( 'jquery' ), WOOPQ_VERSION, true );
				}

				function woopq_wp_enqueue_scripts() {
					wp_enqueue_style( 'woopq-frontend', WOOPQ_URI . 'assets/css/frontend.css' );
					wp_enqueue_script( 'woopq-frontend', WOOPQ_URI . 'assets/js/frontend.js', array( 'jquery' ), WOOPQ_VERSION, true );
				}

				function woopq_admin_menu() {
					add_submenu_page( 'wpclever', esc_html__( 'WPC Product Quantity', 'wpc-product-quantity' ), esc_html__( 'Product Quantity', 'wpc-product-quantity' ), 'manage_options', 'wpclever-woopq', array(
						$this,
						'woopq_settings_page'
					) );
				}

				function woopq_settings_page() {
					$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'settings';
					?>
                    <div class="wpclever_settings_page wrap">
                        <h1 class="wpclever_settings_page_title"><?php echo esc_html__( 'WPC Product Quantity', 'wpc-product-quantity' ) . ' ' . WOOPQ_VERSION; ?></h1>
                        <div class="wpclever_settings_page_desc about-text">
                            <p>
								<?php printf( esc_html__( 'Thank you for using our plugin! If you are satisfied, please reward it a full five-star %s rating.', 'wpc-product-quantity' ), '<span style="color:#ffb900">&#9733;&#9733;&#9733;&#9733;&#9733;</span>' ); ?>
                                <br/>
                                <a href="<?php echo esc_url( WOOPQ_REVIEWS ); ?>"
                                   target="_blank"><?php esc_html_e( 'Reviews', 'wpc-product-quantity' ); ?></a> | <a
                                        href="<?php echo esc_url( WOOPQ_CHANGELOG ); ?>"
                                        target="_blank"><?php esc_html_e( 'Changelog', 'wpc-product-quantity' ); ?></a>
                                | <a href="<?php echo esc_url( WOOPQ_DISCUSSION ); ?>"
                                     target="_blank"><?php esc_html_e( 'Discussion', 'wpc-product-quantity' ); ?></a>
                            </p>
                        </div>
                        <div class="wpclever_settings_page_nav">
                            <h2 class="nav-tab-wrapper">
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woopq&tab=settings' ); ?>"
                                   class="<?php echo $active_tab === 'settings' ? 'nav-tab nav-tab-active' : 'nav-tab'; ?>">
									<?php esc_html_e( 'Settings', 'wpc-product-quantity' ); ?>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=wpclever-woopq&tab=premium' ); ?>"
                                   class="<?php echo $active_tab === 'premium' ? 'nav-tab nav-tab-active' : 'nav-tab'; ?>"
                                   style="color: #c9356e">
									<?php esc_html_e( 'Premium Version', 'wpc-product-quantity' ); ?>
                                </a>
                            </h2>
                        </div>
                        <div class="wpclever_settings_page_content">
							<?php if ( $active_tab === 'settings' ) {
								$woopq_step = '1';

								if ( get_option( '_woopq_decimal', 'no' ) === 'yes' ) {
									$woopq_step = '0.000001';
								}
								?>
                                <form method="post" action="options.php">
									<?php wp_nonce_field( 'update-options' ) ?>
                                    <table class="form-table">
                                        <tr class="heading">
                                            <th>
												<?php esc_html_e( 'General', 'wpc-product-quantity' ); ?>
                                            </th>
                                            <td>

                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
												<?php esc_html_e( 'Decimal quantities', 'wpc-product-quantity' ); ?>
                                            </th>
                                            <td>
                                                <select name="_woopq_decimal">
                                                    <option value="no" <?php echo( get_option( '_woopq_decimal', 'no' ) === 'no' ? 'selected' : '' ); ?>><?php esc_html_e( 'No', 'wpc-product-quantity' ); ?></option>
                                                    <option value="yes" <?php echo( get_option( '_woopq_decimal', 'no' ) === 'yes' ? 'selected' : '' ); ?>><?php esc_html_e( 'Yes', 'wpc-product-quantity' ); ?></option>
                                                </select> <span
                                                        class="description"><?php esc_html_e( 'Press "Update Options" after enabling this option, then you can enter decimal quantities in min, max, step quantity options.', 'wpc-product-quantity' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr class="heading">
                                            <th>
												<?php esc_html_e( 'Quantity', 'wpc-product-quantity' ); ?>
                                            </th>
                                            <td>

                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Type', 'wpc-product-quantity' ); ?></th>
                                            <td>
                                                <select name="_woopq_type">
                                                    <option value="default" <?php echo( get_option( '_woopq_type', 'default' ) === 'default' ? 'selected' : '' ); ?>><?php esc_html_e( 'Input (Default)', 'wpc-product-quantity' ); ?></option>
                                                    <option value="select" <?php echo( get_option( '_woopq_type', 'default' ) === 'select' ? 'selected' : '' ); ?>><?php esc_html_e( 'Select', 'wpc-product-quantity' ); ?></option>
                                                    <option value="radio" <?php echo( get_option( '_woopq_type', 'default' ) === 'radio' ? 'selected' : '' ); ?>><?php esc_html_e( 'Radio', 'wpc-product-quantity' ); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Plus/minus button', 'wpc-product-quantity' ); ?></th>
                                            <td>
                                                <select name="_woopq_plus_minus">
                                                    <option
                                                            value="show" <?php echo( get_option( '_woopq_plus_minus', 'hide' ) === 'show' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Show', 'wpc-product-quantity' ); ?>
                                                    </option>
                                                    <option
                                                            value="hide" <?php echo( get_option( '_woopq_plus_minus', 'hide' ) === 'hide' ? 'selected' : '' ); ?>>
														<?php esc_html_e( 'Hide', 'wpc-product-quantity' ); ?>
                                                    </option>
                                                </select> <span
                                                        class="description"><?php esc_html_e( 'Show the plus/minus button for the input type to increase/decrease the quantity.', 'wpc-product-quantity' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Minimum', 'wpc-product-quantity' ); ?></th>
                                            <td>
                                                <input type="number" name="_woopq_min" min="0"
                                                       step="<?php echo esc_attr( $woopq_step ); ?>"
                                                       value="<?php echo get_option( '_woopq_min' ); ?>"/> <span
                                                        class="description"><?php esc_html_e( 'Leave blank or zero to disable.', 'wpc-product-quantity' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Step', 'wpc-product-quantity' ); ?></th>
                                            <td>
                                                <input type="number" name="_woopq_step" min="0"
                                                       step="<?php echo esc_attr( $woopq_step ); ?>"
                                                       value="<?php echo get_option( '_woopq_step' ); ?>"/> <span
                                                        class="description"><?php esc_html_e( 'Leave blank or zero to disable.', 'wpc-product-quantity' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Maximum', 'wpc-product-quantity' ); ?></th>
                                            <td>
                                                <input type="number" name="_woopq_max" min="0"
                                                       step="<?php echo esc_attr( $woopq_step ); ?>"
                                                       value="<?php echo get_option( '_woopq_max' ); ?>"/> <span
                                                        class="description"><?php esc_html_e( 'Leave blank or zero to disable.', 'wpc-product-quantity' ); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><?php esc_html_e( 'Values', 'wpc-product-quantity' ); ?></th>
                                            <td>
                                                <textarea name="_woopq_values" rows="10"
                                                          cols="50"><?php echo get_option( '_woopq_values' ); ?></textarea>
                                                <p class="description">
													<?php esc_html_e( 'These values will be used for select/radio type. Enter each value in one line and can use the range e.g "10-20".', 'wpc-product-quantity' ); ?>
                                                </p>
                                            </td>
                                        </tr>
                                        <tr class="submit">
                                            <th colspan="2">
                                                <input type="submit" name="submit" class="button button-primary"
                                                       value="<?php esc_html_e( 'Update Options', 'wpc-product-quantity' ); ?>"/>
                                                <input type="hidden" name="action" value="update"/>
                                                <input type="hidden" name="page_options"
                                                       value="_woopq_decimal,_woopq_type,_woopq_plus_minus,_woopq_min,_woopq_max,_woopq_step,_woopq_values"/>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
							<?php } elseif ( $active_tab === 'premium' ) { ?>
                                <div class="wpclever_settings_page_content_text">
                                    <p>
                                        Get the Premium Version just $29! <a
                                                href="https://wpclever.net/downloads/wpc-product-quantity?utm_source=pro&utm_medium=woopq&utm_campaign=wporg"
                                                target="_blank">https://wpclever.net/downloads/wpc-product-quantity</a>
                                    </p>
                                    <p><strong>Extra features for Premium Version:</strong></p>
                                    <ul style="margin-bottom: 0">
                                        <li>- Add quantity settings at product basis.</li>
                                        <li>- Get the lifetime update & premium support.</li>
                                    </ul>
                                </div>
							<?php } ?>
                        </div>
                    </div>
					<?php
				}

				function woopq_action_links( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$settings_link = '<a href="' . admin_url( 'admin.php?page=wpclever-woopq&tab=settings' ) . '">' . esc_html__( 'Settings', 'wpc-product-quantity' ) . '</a>';
						$links[]       = '<a href="' . admin_url( 'admin.php?page=wpclever-woopq&tab=premium' ) . '">' . esc_html__( 'Premium Version', 'wpc-product-quantity' ) . '</a>';
						array_unshift( $links, $settings_link );
					}

					return (array) $links;
				}

				function woopq_row_meta( $links, $file ) {
					static $plugin;

					if ( ! isset( $plugin ) ) {
						$plugin = plugin_basename( __FILE__ );
					}

					if ( $plugin === $file ) {
						$row_meta = array(
							'support' => '<a href="https://wpclever.net/support?utm_source=support&utm_medium=woopq&utm_campaign=wporg" target="_blank">' . esc_html__( 'Premium support', 'wpc-product-quantity' ) . '</a>',
						);

						return array_merge( $links, $row_meta );
					}

					return (array) $links;
				}

				function woopq_loop_add_to_cart_args( $args, $product ) {
					if ( $product ) {
						if ( $product->is_type( 'variation' ) && $product->get_parent_id() ) {
							$product_id = $product->get_parent_id();
						} else {
							$product_id = $product->get_id();
						}

						$woopq_quantity = get_post_meta( $product_id, '_woopq_quantity', true ) ?: 'default';
						$woopq_min      = (float) get_post_meta( $product_id, '_woopq_min', true );

						if ( ( $woopq_quantity === 'disable' ) || ( ( $woopq_quantity === 'overwrite' ) && empty( $woopq_min ) ) ) {
							$woopq_min = 0;
						}

						if ( $woopq_quantity === 'default' ) {
							$woopq_min = (float) get_option( '_woopq_min', 0 );
						}

						if ( get_option( '_woopq_decimal', 'no' ) !== 'yes' ) {
							$woopq_min = ceil( $woopq_min );
						}

						if ( ! empty( $woopq_min ) ) {
							$args['quantity'] = $woopq_min;
						}
					}

					return $args;
				}

				function woopq_quantity_input_args( $args, $product ) {
					if ( $product ) {
						if ( $product->is_type( 'variation' ) && $product->get_parent_id() ) {
							$product_id = $product->get_parent_id();
						} else {
							$product_id = $product->get_id();
						}

						$args['product_id'] = $product_id;

						$woopq_quantity = get_post_meta( $product_id, '_woopq_quantity', true ) ?: 'default';

						switch ( $woopq_quantity ) {
							case 'disable':
								return $args;

								break;
							case 'default':
								$woopq_min  = (float) get_option( '_woopq_min', 0 );
								$woopq_max  = (float) get_option( '_woopq_max', 0 );
								$woopq_step = (float) get_option( '_woopq_step', 0 );

								break;
							case 'overwrite':
								$woopq_min  = (float) get_post_meta( $product_id, '_woopq_min', true );
								$woopq_max  = (float) get_post_meta( $product_id, '_woopq_max', true );
								$woopq_step = (float) get_post_meta( $product_id, '_woopq_step', true );

								break;
						}

						if ( get_option( '_woopq_decimal', 'no' ) !== 'yes' ) {
							$woopq_min  = ceil( $woopq_min );
							$woopq_max  = ceil( $woopq_max );
							$woopq_step = ceil( $woopq_step );
						}

						if ( ! empty( $woopq_min ) ) {
							$args['min_value'] = $woopq_min;
						}

						if ( ! empty( $woopq_max ) ) {
							$args['max_value'] = $woopq_max;
						}

						if ( ! empty( $woopq_step ) ) {
							$args['step'] = $woopq_step;
						}
					}

					return $args;
				}

				function woopq_quantity_input_min( $min, $product ) {
					if ( $product ) {
						if ( $product->is_type( 'variation' ) && $product->get_parent_id() ) {
							$product_id = $product->get_parent_id();
						} else {
							$product_id = $product->get_id();
						}

						$woopq_quantity = get_post_meta( $product_id, '_woopq_quantity', true ) ?: 'default';
						$woopq_min      = (float) get_post_meta( $product_id, '_woopq_min', true );

						if ( ( $woopq_quantity === 'disable' ) || ( ( $woopq_quantity === 'overwrite' ) && empty( $woopq_min ) ) ) {
							return $min;
						}

						if ( $woopq_quantity === 'default' ) {
							$woopq_min = (float) get_option( '_woopq_min', 0 );
						}

						if ( get_option( '_woopq_decimal', 'no' ) !== 'yes' ) {
							$woopq_min = ceil( $woopq_min );
						}

						if ( ! empty( $woopq_min ) ) {
							return $woopq_min;
						}
					}

					return $min;
				}

				function woopq_quantity_input_max( $max, $product ) {
					if ( $product ) {
						if ( $product->is_type( 'variation' ) && $product->get_parent_id() ) {
							$product_id = $product->get_parent_id();
						} else {
							$product_id = $product->get_id();
						}

						$woopq_quantity = get_post_meta( $product_id, '_woopq_quantity', true ) ?: 'default';
						$woopq_max      = (float) get_post_meta( $product_id, '_woopq_max', true );

						if ( ( $woopq_quantity === 'disable' ) || ( ( $woopq_quantity === 'overwrite' ) && empty( $woopq_max ) ) ) {
							return $max;
						}

						if ( $woopq_quantity === 'default' ) {
							$woopq_max = (float) get_option( '_woopq_max', 0 );
						}

						if ( get_option( '_woopq_decimal', 'no' ) !== 'yes' ) {
							$woopq_max = ceil( $woopq_max );
						}

						if ( ! empty( $woopq_max ) ) {
							return $woopq_max;
						}
					}

					return $max;
				}

				function woopq_quantity_input_step( $step, $product ) {
					if ( $product ) {
						if ( $product->is_type( 'variation' ) && $product->get_parent_id() ) {
							$product_id = $product->get_parent_id();
						} else {
							$product_id = $product->get_id();
						}

						$woopq_quantity = get_post_meta( $product_id, '_woopq_quantity', true ) ?: 'default';
						$woopq_step     = (float) get_post_meta( $product_id, '_woopq_step', true );

						if ( ( $woopq_quantity === 'disable' ) || ( ( $woopq_quantity === 'overwrite' ) && empty( $woopq_step ) ) ) {
							return $step;
						}

						if ( $woopq_quantity === 'default' ) {
							$woopq_step = (float) get_option( '_woopq_step', 0 );
						}

						if ( get_option( '_woopq_decimal', 'no' ) !== 'yes' ) {
							$woopq_step = ceil( $woopq_step );
						}

						if ( ! empty( $woopq_step ) ) {
							return $woopq_step;
						}
					}

					return $step;
				}

				function woopq_quantity_input_template( $located, $template_name ) {
					if ( $template_name === 'global/quantity-input.php' ) {
						return WOOPQ_PATH . '/templates/quantity-input.php';
					}

					return $located;
				}

				function woopq_product_data_tabs( $tabs ) {
					$tabs['woopq'] = array(
						'label'  => esc_html__( 'Quantity', 'wpc-product-quantity' ),
						'target' => 'woopq_settings',
					);

					return $tabs;
				}

				function woopq_product_data_panels() {
					?>
                    <div id='woopq_settings' class='panel woocommerce_options_panel woopq_table'>
                        <div class="woopq_tr">
                            <div class="woopq_td"><?php esc_html_e( 'Quantity', 'wpc-product-quantity' ); ?></div>
                            <div class="woopq_td">
                                <input name="_woopq_quantity" type="radio"
                                       value="default"
                                       checked/> <?php esc_html_e( 'Default', 'wpc-product-quantity' ); ?>
                                (<a
                                        href="<?php echo admin_url( 'admin.php?page=wpclever-woopq&tab=settings' ); ?>"
                                        target="_blank"><?php esc_html_e( 'settings', 'wpc-product-quantity' ); ?></a>)
                                &nbsp;
                                <input name="_woopq_quantity" type="radio"
                                       value="disable"/> <?php esc_html_e( 'Disable', 'wpc-product-quantity' ); ?>
                                &nbsp;
                                <input name="_woopq_quantity" type="radio"
                                       value="overwrite"/> <?php esc_html_e( 'Overwrite', 'wpc-product-quantity' ); ?>
                                <p style="color: red; padding-left: 0; padding-right: 0">You only can use the <a
                                            href="<?php echo admin_url( 'admin.php?page=wpclever-woopq&tab=settings' ); ?>"
                                            target="_blank">default settings</a> for all products.<br/>Quantity settings
                                    at a product
                                    basis only available on the Premium
                                    Version. <a
                                            href="https://wpclever.net/downloads/wpc-product-quantity?utm_source=pro&utm_medium=woopq&utm_campaign=wporg"
                                            target="_blank">Click here</a> to buy, just $29!</p>
                            </div>
                        </div>
                        <div class="woopq_tr woopq_show_if_overwrite">
                            <div class="woopq_td"><?php esc_html_e( 'Type', 'wpc-product-quantity' ); ?></div>
                            <div class="woopq_td">
                                <select name="_woopq_type">
                                    <option value="default"><?php esc_html_e( 'Input (Default)', 'wpc-product-quantity' ); ?></option>
                                    <option value="select"><?php esc_html_e( 'Select', 'wpc-product-quantity' ); ?></option>
                                    <option value="radio"><?php esc_html_e( 'Radio', 'wpc-product-quantity' ); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="woopq_tr woopq_show_if_overwrite">
                            <div class="woopq_td"><?php esc_html_e( 'Minimum', 'wpc-product-quantity' ); ?></div>
                            <div class="woopq_td">
                                <input type="number" name="_woopq_min" min="0"
                                       step="1"
                                       value="0"
                                       style="width: 120px"/> <span
                                        class="description"><?php esc_html_e( 'Leave blank or zero to disable.', 'wpc-product-quantity' ); ?></span>
                            </div>
                        </div>
                        <div class="woopq_tr woopq_show_if_overwrite">
                            <div class="woopq_td"><?php esc_html_e( 'Maximum', 'wpc-product-quantity' ); ?></div>
                            <div class="woopq_td">
                                <input type="number" name="_woopq_max" min="0"
                                       step="1"
                                       value="100"
                                       style="width: 120px"/> <span
                                        class="description"><?php esc_html_e( 'Leave blank or zero to disable.', 'wpc-product-quantity' ); ?></span>
                            </div>
                        </div>
                        <div class="woopq_tr woopq_show_if_overwrite">
                            <div class="woopq_td"><?php esc_html_e( 'Step', 'wpc-product-quantity' ); ?></div>
                            <div class="woopq_td">
                                <input type="number" name="_woopq_step" min="0"
                                       step="1"
                                       value="1"
                                       style="width: 120px"/> <span
                                        class="description"><?php esc_html_e( 'Leave blank or zero to disable.', 'wpc-product-quantity' ); ?></span>
                            </div>
                        </div>
                        <div class="woopq_tr woopq_show_if_overwrite">
                            <div class="woopq_td"><?php esc_html_e( 'Values', 'wpc-product-quantity' ); ?></div>
                            <div class="woopq_td">
                                                <textarea name="_woopq_values" rows="10"
                                                          cols="50"
                                                          style="float: none; width: 100%; height: 200px"></textarea>
                                <p class="description" style="margin-left: 0">
									<?php esc_html_e( 'These values will be used for select/radio type. Enter each value in one line and can use the range e.g "10-20".', 'wpc-product-quantity' ); ?>
                                </p>
                            </div>
                        </div>
                    </div>
					<?php
				}

				public static function woopq_values( $values ) {
					$woopq_values  = array();
					$woopq_decimal = get_option( '_woopq_decimal', 'no' );
					$values_arr    = explode( "\n", $values );

					if ( count( $values_arr ) > 0 ) {
						foreach ( $values_arr as $item ) {
							$item_value = self::woopq_clean_values( $item );

							if ( strpos( $item_value, '-' ) ) {
								$item_value_arr = explode( '-', $item_value );

								for ( $i = (int) $item_value_arr[0]; $i <= (int) $item_value_arr[1]; $i ++ ) {
									$woopq_values[] = array( 'name' => $i, 'value' => $i );
								}
							} elseif ( is_numeric( $item_value ) ) {
								if ( $woopq_decimal !== 'yes' ) {
									$woopq_values[] = array(
										'name'  => esc_html( trim( $item ) ),
										'value' => (int) $item_value
									);
								} else {
									$woopq_values[] = array(
										'name'  => esc_html( trim( $item ) ),
										'value' => (float) $item_value
									);
								}
							}
						}
					}

					if ( empty( $woopq_values ) ) {
						// default values
						$woopq_values = array(
							array( 'name' => '1', 'value' => 1 ),
							array( 'name' => '2', 'value' => 2 ),
							array( 'name' => '3', 'value' => 3 ),
							array( 'name' => '4', 'value' => 4 ),
							array( 'name' => '5', 'value' => 5 ),
							array( 'name' => '6', 'value' => 6 ),
							array( 'name' => '7', 'value' => 7 ),
							array( 'name' => '8', 'value' => 8 ),
							array( 'name' => '9', 'value' => 9 ),
							array( 'name' => '10', 'value' => 10 )
						);
					} else {
						$woopq_values = array_intersect_key( $woopq_values, array_unique( array_map( 'serialize', $woopq_values ) ) );
					}

					return $woopq_values;
				}

				public static function woopq_clean_values( $str ) {
					return preg_replace( '/[^.\-0-9]/', '', $str );
				}
			}

			new WPCleverWoopq();
		}
	}
} else {
	add_action( 'admin_notices', 'woopq_notice_premium' );
}

if ( ! function_exists( 'woopq_notice_wc' ) ) {
	function woopq_notice_wc() {
		?>
        <div class="error">
            <p><strong>WPC Product Quantity</strong> requires WooCommerce version 3.0.0 or greater.</p>
        </div>
		<?php
	}
}

if ( ! function_exists( 'woopq_notice_premium' ) ) {
	function woopq_notice_premium() {
		?>
        <div class="error">
            <p>Seems you're using both free and premium version of <strong>WPC Product Quantity</strong>. Please
                deactivate the free version when using the premium version.</p>
        </div>
		<?php
	}
}