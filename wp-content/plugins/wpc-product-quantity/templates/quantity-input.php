<?php
defined( 'ABSPATH' ) || exit;

if ( $max_value && $min_value === $max_value ) {
	?>
    <div class="quantity hidden">
        <input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" class="qty"
               name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>"/>
    </div>
	<?php
} else {
	if ( $min_value && ( $input_value < $min_value ) ) {
		$input_value = $min_value;
	}

	if ( $max_value && ( $input_value > $max_value ) ) {
		$input_value = $max_value;
	}

	$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'wpc-product-quantity' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'wpc-product-quantity' );

	// $product_id from woopq_quantity_input_args()
	$woopq_type     = '';
	$woopq_values   = array();
	$woopq_quantity = get_post_meta( $product_id, '_woopq_quantity', true ) ?: 'default';

	if ( $woopq_quantity === 'overwrite' ) {
		$woopq_type   = get_post_meta( $product_id, '_woopq_type', true );
		$woopq_values = get_post_meta( $product_id, '_woopq_values', true );
	} elseif ( $woopq_quantity === 'default' ) {
		$woopq_type   = get_option( '_woopq_type', 'default' );
		$woopq_values = get_option( '_woopq_values' );
	}
	?>
    <div class="quantity <?php echo esc_attr( 'woopq-quantity woopq-quantity-' . $woopq_quantity . ' woopq-type-' . $woopq_type ); ?>"
         data-min="<?php echo esc_attr( $min_value ); ?>" data-max="<?php echo esc_attr( $max_value ); ?>"
         data-step="<?php echo esc_attr( $step ); ?>">
		<?php do_action( 'woocommerce_before_quantity_input_field' ); ?>
        <label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>">
			<?php echo esc_attr( $label ); ?>
        </label>
		<?php
		if ( $woopq_type === 'select' ) {
			$woopq_values = WPCleverWoopq::woopq_values( $woopq_values );
			?>
            <select id="<?php echo esc_attr( $input_id ); ?>"
                    class="qty"
                    name="<?php echo esc_attr( $input_name ); ?>"
                    title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'wpc-product-quantity' ); ?>">
				<?php foreach ( $woopq_values as $woopq_value ) {
					echo '<option value="' . esc_attr( $woopq_value['value'] ) . '" ' . ( $input_value == $woopq_value['value'] ? 'selected' : '' ) . '>' . $woopq_value['name'] . '</option>';
				} ?>
            </select>
			<?php
		} elseif ( $woopq_type === 'radio' ) {
			$woopq_values = WPCleverWoopq::woopq_values( $woopq_values );

			foreach ( $woopq_values as $woopq_value ) {
				echo '<input type="radio" name="' . esc_attr( $input_name ) . '" value="' . esc_attr( $woopq_value['value'] ) . '" ' . ( $input_value == $woopq_value['value'] ? 'checked' : '' ) . '/> ' . $woopq_value['name'] . '<br/>';
			}
		} else {
			// default
			if ( get_option( '_woopq_plus_minus', 'hide' ) === 'show' ) {
				echo '<div class="woopq-quantity-input">';
				echo '<div class="woopq-quantity-input-minus">-</div>';
			}
			?>
            <input
                    type="number"
                    id="<?php echo esc_attr( $input_id ); ?>"
                    class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
                    step="<?php echo esc_attr( $step ); ?>"
                    min="<?php echo esc_attr( $min_value ); ?>"
                    max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>"
                    name="<?php echo esc_attr( $input_name ); ?>"
                    value="<?php echo esc_attr( $input_value ); ?>"
                    title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'wpc-product-quantity' ); ?>"
                    size="4"
                    inputmode="<?php echo esc_attr( $inputmode ); ?>"/>
			<?php
			if ( get_option( '_woopq_plus_minus', 'hide' ) === 'show' ) {
				echo '<div class="woopq-quantity-input-plus">+</div>';
				echo '</div><!-- /woopq-quantity-input -->';
			}
		} ?>
		<?php do_action( 'woocommerce_after_quantity_input_field' ); ?>
    </div>
	<?php
}
