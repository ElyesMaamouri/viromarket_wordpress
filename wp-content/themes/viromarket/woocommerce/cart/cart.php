<?php
/**
 * Cart Page Template — ViroMarket Theme
 * Matches EXACTLY the design/classes from ecommerce_wordpress_template/cart.html
 *
 * @package ViroMarket
 */

defined('ABSPATH') || exit;
?>
<!-- VIROMARKET_CART_TEMPLATE_LOADED_SUCCESSFULLY -->
<?php
// Force calculation to be sure we have the latest state during AJAX
if ( class_exists( 'WooCommerce' ) && WC()->cart ) {
    WC()->cart->calculate_totals();
}
$cart_count = WC()->cart->get_cart_contents_count();
$cart_items = WC()->cart->get_cart();
?>

<?php do_action('woocommerce_before_cart'); ?>

<!-- Breadcrumb -->
<nav class="cart-breadcrumb-nav" aria-label="Breadcrumb">
    <div class="container-website">
        <ul class="breadcrumb-list">
            <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'viromarket'); ?></a></li>
            <li aria-hidden="true"><?php echo viromarket_icon('chevron-right', 14); ?></li>
            <li class="active"><?php esc_html_e('Shopping Cart', 'viromarket'); ?></li>
        </ul>
    </div>
</nav>

<main id="primary">
    <div id="viromarket-cart-section-container">
        <section class="cart-page-section">
            <div class="container-website">

            <!-- Section Header -->
            <header class="cart-header">
                <h1 class="cart-title"><?php esc_html_e('My Shopping Cart', 'viromarket'); ?></h1>
                <p class="cart-subtitle">
                    <?php if ($cart_count > 0) : ?>
                        <?php
                        printf(
                            /* translators: %s = number of items */
                            esc_html__('You have %s in your cart', 'viromarket'),
                            '<span class="highlight">' . esc_html(
                                sprintf(
                                    _n('%d item', '%d items', $cart_count, 'viromarket'),
                                    $cart_count
                                )
                            ) . '</span>'
                        );
                        ?>
                    <?php else : ?>
                        <?php esc_html_e('Your cart is currently empty.', 'viromarket'); ?>
                    <?php endif; ?>
                </p>
            </header>

            <?php do_action('woocommerce_before_cart_table'); ?>

            <?php if (WC()->cart->is_empty()) : ?>
                <div class="cart-empty-state" style="text-align:center; padding: 100px 0;">
                     <div style="margin-bottom:30px; opacity:0.1;"><?php echo viromarket_icon('shopping-cart', 120); ?></div>
                     <h2 style="margin-bottom:20px;"><?php esc_html_e('Your cart is empty', 'viromarket'); ?></h2>
                     <a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>" class="btn-checkout-full" style="max-width:300px; margin: 0 auto;">
                         <?php esc_html_e('Return to Shop', 'viromarket'); ?>
                     </a>
                </div>
            <?php else : ?>

                <div class="cart-container-grid">
                    <!-- Left: Items -->
                    <div class="cart-items-wrapper">
                        <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                            <div class="cart-list-container">
                                <div class="cart-list-header">
                                    <span><?php esc_html_e('Product', 'viromarket'); ?></span>
                                    <span><?php esc_html_e('Price', 'viromarket'); ?></span>
                                    <span><?php esc_html_e('Quantity', 'viromarket'); ?></span>
                                    <span><?php esc_html_e('Subtotal', 'viromarket'); ?></span>
                                    <span></span>
                                </div>

                                <div class="cart-items-list">
                                    <?php foreach ($cart_items as $cart_item_key => $cart_item) :
                                        $_product   = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                                        if (!$_product || !$_product->exists() || $cart_item['quantity'] === 0) continue;

                                        $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                                        $thumbnail         = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail'), $cart_item, $cart_item_key);
                                        $product_price     = apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                                        $product_subtotal  = apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                                    ?>
                                        <div class="cart-item-card cart_item">
                                            <!-- Product Info -->
                                            <div class="cart-col col-product">
                                                <div class="cart-product-cell">
                                                    <div class="cart-img-box">
                                                        <?php if ($product_permalink) : ?>
                                                            <a href="<?php echo esc_url($product_permalink); ?>"><?php echo $thumbnail; ?></a>
                                                        <?php else : ?>
                                                            <?php echo $thumbnail; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="cart-product-info">
                                                        <h3>
                                                            <?php if ($product_permalink) : ?>
                                                                <a href="<?php echo esc_url($product_permalink); ?>"><?php echo $_product->get_name(); ?></a>
                                                            <?php else : ?>
                                                                <?php echo $_product->get_name(); ?>
                                                            <?php endif; ?>
                                                        </h3>
                                                        <div class="cart-product-meta">
                                                            <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Price -->
                                            <div class="cart-col col-price" data-label="<?php esc_attr_e('Price', 'viromarket'); ?>">
                                                <span class="price-val"><?php echo $product_price; ?></span>
                                            </div>

                                            <!-- Quantity -->
                                            <div class="cart-col col-qty" data-label="<?php esc_attr_e('Quantity', 'viromarket'); ?>">
                                                <div class="cart-qty-selector" data-cart_item_key="<?php echo esc_attr($cart_item_key); ?>">
                                                    <button type="button" class="cart-qty-btn minus"> - </button>
                                                    <?php
                                                    if ($_product->is_sold_individually()) {
                                                        $product_quantity = sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1">', $cart_item_key);
                                                    } else {
                                                        $product_quantity = woocommerce_quantity_input([
                                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                                            'input_value'  => $cart_item['quantity'],
                                                            'max_value'    => $_product->get_max_purchase_quantity(),
                                                            'min_value'    => '0',
                                                            'product_name' => $_product->get_name(),
                                                            'classes'      => ['cart-qty-input'],
                                                        ], $_product, false);
                                                    }
                                                    echo $product_quantity;
                                                    ?>
                                                    <button type="button" class="cart-qty-btn plus"> + </button>
                                                </div>
                                            </div>

                                            <!-- Subtotal -->
                                            <div class="cart-col col-subtotal" data-label="<?php esc_attr_e('Subtotal', 'viromarket'); ?>">
                                                <span class="subtotal-val"><?php echo $product_subtotal; ?></span>
                                            </div>

                                            <!-- Remove -->
                                            <div class="cart-col col-action">
                                                <?php
                                                echo sprintf(
                                                    '<a href="%s" class="remove remove-cart-item" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-nonce="%s">%s</a>',
                                                    esc_url(wc_get_cart_remove_url($cart_item_key)),
                                                    esc_attr__('Remove this item', 'woocommerce'),
                                                    esc_attr($_product->get_id()),
                                                    esc_attr($cart_item_key),
                                                    wp_create_nonce('viromarket_cart_nonce'),
                                                    viromarket_icon('trash-2', 18)
                                                );
                                                ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="cart-actions-row">
                                <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                                <button type="submit" class="btn-update-cart" name="update_cart" value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>">
                                    <?php echo viromarket_icon('refresh-cw', 14); ?>
                                    <?php esc_html_e('Update cart', 'woocommerce'); ?>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Right: Summary -->
                    <aside class="cart-summary-wrapper">
                        <div class="cart-summary-card">
                            <h2 class="summary-title"><?php esc_html_e('Order Summary', 'viromarket'); ?></h2>

                            <?php if (wc_coupons_enabled()) : ?>
                                <div class="coupon-box">
                                    <label class="summary-label"><?php esc_html_e('Do you have a discount code?', 'viromarket'); ?></label>
                                    <form class="coupon-input-group" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                                        <input type="text" name="coupon_code" id="coupon_code" value="" placeholder="<?php esc_attr_e('Coupon Code', 'viromarket'); ?>" />
                                        <button type="submit" class="btn-apply-coupon" name="apply_coupon" value="<?php esc_attr_e('Apply', 'viromarket'); ?>">
                                            <?php esc_html_e('Apply', 'viromarket'); ?>
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>

                            <div class="summary-details">
                                <div class="summary-item">
                                    <span class="label"><?php esc_html_e('Subtotal', 'woocommerce'); ?></span>
                                    <span class="value"><?php wc_cart_totals_subtotal_html(); ?></span>
                                </div>

                                <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                                    <div class="summary-item coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                                        <span class="label"><?php esc_html_e('Coupon:', 'woocommerce'); ?> <?php echo esc_html($code); ?></span>
                                        <span class="value">-<?php wc_cart_totals_coupon_html($coupon); ?></span>
                                    </div>
                                <?php endforeach; ?>

                                <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
                                    <div class="summary-item shipping">
                                        <span class="label"><?php esc_html_e('Shipping', 'woocommerce'); ?></span>
                                        <span class="value"><?php wc_cart_totals_shipping_html(); ?></span>
                                    </div>
                                <?php else : ?>
                                    <div class="summary-item shipping">
                                        <span class="label"><?php esc_html_e('Shipping', 'woocommerce'); ?></span>
                                        <span class="value"><?php esc_html_e('Calculated at checkout', 'viromarket'); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="summary-total">
                                <span class="label"><?php esc_html_e('Total', 'woocommerce'); ?></span>
                                <span class="value"><?php wc_cart_totals_order_total_html(); ?></span>
                            </div>

                            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="btn-checkout-full">
                                <span><?php esc_html_e('Proceed to Checkout', 'woocommerce'); ?></span>
                                <?php echo viromarket_icon('arrow-right', 20); ?>
                            </a>
                        </div>
                    </aside>
                </div>

            <?php endif; ?>

        </div>
    </section>
</div>
</main>

<?php do_action('woocommerce_after_cart'); ?>
