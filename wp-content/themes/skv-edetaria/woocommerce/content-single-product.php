<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked wc_print_notices - 10
 */
//do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>

<section class="separator-header"></section>

<section class="page-wrapper">
    <div  class="spotlight" id="product-<?php the_ID(); ?>" <?php wc_product_class(); ?>>

        <div class="image">
            <div class="slider">
                <div class="flexslider-product">
                    <div class="slides">
                    <?php
                        /**
                         * Hook: woocommerce_before_single_product_summary.
                         *
                         * @hooked woocommerce_show_product_sale_flash - 10
                         * @hooked woocommerce_show_product_images - 20
                         */
                        do_action( 'woocommerce_before_single_product_summary' );
                    ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="content">
            <?php
                /**
                 * Hook: woocommerce_single_product_summary.
                 *
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_rating - 10
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked woocommerce_template_single_meta - 40
                 * @hooked woocommerce_template_single_sharing - 50
                 * @hooked WC_Structured_Data::generate_product_data() - 60
                 */
                do_action( 'woocommerce_single_product_summary' );
                
                /**
                 * Hook: woocommerce_before_single_product.
                 *
                 * @hooked wc_print_notices - 10
                 */
                do_action( 'woocommerce_before_single_product' );
            ?>
            
                <div class="share-icons">
                    <p>Comparteix</p>
                    <ul class="icons">
                        <li>
                            <a href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title_attribute(); ?>" title="Comparte en Twitter" class="twitter" target="_blank">
                                <svg class="icon"><use xlink:href="<?php echo get_template_directory_uri(); ?>/assets/images/symbol-defs.svg#icon-social-twitter"></use></svg>
                                <span class="label">Twitter</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" class="facebook" target="_blank">
                                <svg class="icon"><use xlink:href="<?php echo get_template_directory_uri(); ?>/assets/images/symbol-defs.svg#icon-social-facebook"></use></svg>
                                <span class="label">Facebook</span>
                            </a>
                        </li>
                        <li>
                            <a href="whatsapp://send?text=<?php the_permalink(); ?>" data-action="share/whatsapp/share" title="Comparte en WhatsApp" class="whatsapp" target="_blank">
                                <svg class="icon"><use xlink:href="<?php echo get_template_directory_uri(); ?>/assets/images/symbol-defs.svg#icon-social-whatsapp"></use></svg>
                                <span class="label">WhatsApp</span>
                            </a>
                        </li>
                    </ul>
                </div><!-- /.share-icons -->

            </div>
        </div>
    </div>
</section>

<section class="wrapper wrapper-margin">
    <article class="entry-content">
        
        <?php the_content(); ?>
        
    </article>

    <article class="entry-content">
        
        <?php the_field('tast'); ?>
        
    </article>
</section>


        <?php
            /**
             * Hook: woocommerce_after_single_product_summary.
             *
             * @hooked woocommerce_output_product_data_tabs - 10
             * @hooked woocommerce_upsell_display - 15
             * @hooked woocommerce_output_related_products - 20
             */
            do_action( 'woocommerce_after_single_product_summary' );
        ?>


<?php do_action( 'woocommerce_after_single_product' ); ?>

