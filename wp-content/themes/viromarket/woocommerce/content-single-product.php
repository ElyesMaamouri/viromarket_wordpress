<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * @package ViroMarket
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
    echo get_the_password_form();
    return;
}
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

    <?php
    /**
     * Hook: woocommerce_before_single_product_summary.
     */
    do_action( 'woocommerce_before_single_product_summary' );
    ?>

    <div class="summary entry-summary">
        <?php
        /**
         * Hook: woocommerce_single_product_summary.
         */
        do_action( 'woocommerce_single_product_summary' );
        ?>
    </div>

    <?php
    /**
     * Hook: woocommerce_after_single_product_summary.
     */
    do_action( 'woocommerce_after_single_product_summary' );
    ?>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
