<?php
/**
 * Intégration et personnalisation WooCommerce
 * 
 * @package ViroMarket
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Fix Cart Empty on Initial Load & Session Sync
 */
add_action('init', function() {
    if ( ! is_admin() && class_exists('WooCommerce') ) {
        if ( ! WC()->session->has_session() ) {
            WC()->session->set_customer_session_cookie( true );
        }
    }
});

add_action('template_redirect', function() {
    if ( (is_cart() || is_checkout()) && class_exists('WooCommerce') ) {
        // Force calculation to ensure is_empty() refers to fresh data
        WC()->cart->calculate_totals();

        // Prevent caching of these pages
        if (!is_admin()) {
            nocache_headers();
        }
    }
}, 10);

/**
 * Force WooCommerce to use our custom cart template from the theme
 */
add_filter('woocommerce_locate_template', function($template, $template_name, $template_path) {
    if ($template_name === 'cart/cart.php') {
        $theme_template = VIROMARKET_THEME_DIR . '/woocommerce/cart/cart.php';
        if (file_exists($theme_template)) {
            return $theme_template;
        }
    }
    return $template;
}, 20, 3);

/**
 * Force the use of the classic [woocommerce_cart] shortcode.
 * This ensures our custom templates are used even if the page was created with blocks.
 */
add_filter('the_content', function($content) {
    if ( is_cart() || strpos($_SERVER['REQUEST_URI'], '/cart/') !== false ) {
        return do_shortcode('[woocommerce_cart]');
    }
    return $content;
}, 9999);

/**
 * Ensure CART template is detected properly
 */
add_action('template_redirect', function() {
    if ( is_cart() || (is_page() && get_the_ID() == 8) ) {
        // Enforce the use of classic cart even if block meta is present
        add_filter('woocommerce_is_checkout', '__return_false');
    }
}, 5);

/**
 * Désactiver les styles WooCommerce par défaut
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Modifier le nombre de produits par page
 */
function viromarket_products_per_page() {
    return 12; // Multiple of 3
}
add_filter('loop_shop_per_page', 'viromarket_products_per_page', 20);

/**
 * Modifier le nombre de colonnes de produits
 */
function viromarket_loop_columns() {
    return 3; // 3 colonnes sur desktop per user request
}
add_filter('loop_shop_columns', 'viromarket_loop_columns');

/**
 * Modifier les colonnes de produits liés
 */
function viromarket_related_products_args($args) {
    $args['posts_per_page'] = 4;
    $args['columns'] = 4;
    return $args;
}
add_filter('woocommerce_output_related_products_args', 'viromarket_related_products_args');

/**
 * Désactiver les wrappers WooCommerce par défaut
 * Notre template archive-product.php gère la structure complète
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
add_action('woocommerce_after_shop_loop', 'viromarket_pagination', 10);

/**
 * Personnaliser le breadcrumb WooCommerce pour correspondre au design du template
 */
function viromarket_woocommerce_breadcrumbs() {
    return array(
        'delimiter'   => '',
        'wrap_before' => '<ol>',
        'wrap_after'  => '</ol>',
        'before'      => '<li>',
        'after'       => '</li>',
        'home'        => _x('Home', 'breadcrumb', 'viromarket'),
    );
}
add_filter('woocommerce_breadcrumb_defaults', 'viromarket_woocommerce_breadcrumbs');

/**
 * Ajouter des classes personnalisées aux produits
 */
function viromarket_product_classes($classes, $product) {
    if ($product->is_on_sale()) {
        $classes[] = 'product-on-sale';
    }
    
    if (!$product->is_in_stock()) {
        $classes[] = 'product-out-of-stock';
    }
    
    return $classes;
}
add_filter('woocommerce_post_class', 'viromarket_product_classes', 10, 2);

/**
 * Modifier le texte "Ajouter au panier"
 */
function viromarket_add_to_cart_text($text, $product) {
    if ($product->is_type('simple')) {
        return __('Add to cart', 'viromarket');
    }
    return $text;
}
add_filter('woocommerce_product_add_to_cart_text', 'viromarket_add_to_cart_text', 10, 2);
add_filter('woocommerce_product_single_add_to_cart_text', 'viromarket_add_to_cart_text', 10, 2);

/**
 * Support multiple categories and attributes in the URL
 */
function viromarket_handle_sidebar_filters( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( is_shop() || is_product_category() || is_product_taxonomy() ) {
        $tax_query = (array) $query->get( 'tax_query' );

        // 1. Handle Multiple Categories
        $product_cat = isset( $_GET['product_cat'] ) ? sanitize_text_field( $_GET['product_cat'] ) : '';
        if ( ! empty( $product_cat ) ) {
            $categories = explode( ',', $product_cat );
            $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => $categories,
                'operator' => 'IN',
            );
            $query->set( 'product_cat', '' ); // Prevent conflict
        }

        // 2. Handle Multiple Brands
        $brands = isset( $_GET['filter_brand'] ) ? sanitize_text_field( $_GET['filter_brand'] ) : '';
        if ( ! empty( $brands ) ) {
            $brand_list = explode( ',', $brands );
            $tax_query[] = array(
                'taxonomy' => 'product_brand',
                'field'    => 'slug',
                'terms'    => $brand_list,
                'operator' => 'IN',
            );
        }

        // 3. Handle Multiple Colors (Attribute)
        $attribute_taxonomies = wc_get_attribute_taxonomies();
        $color_tax = '';
        foreach ( $attribute_taxonomies as $tax ) {
            if ( $tax->attribute_name == 'color' || $tax->attribute_name == 'colour' ) {
                $color_tax = wc_attribute_taxonomy_name( $tax->attribute_name );
                break;
            }
        }

        $colors = isset( $_GET['filter_color'] ) ? sanitize_text_field( $_GET['filter_color'] ) : '';
        if ( ! empty( $colors ) && ! empty( $color_tax ) ) {
            $color_list = explode( ',', $colors );
            $tax_query[] = array(
                'taxonomy' => $color_tax,
                'field'    => 'slug',
                'terms'    => $color_list,
                'operator' => 'IN',
            );
        }

        $query->set( 'tax_query', $tax_query );
    }
}
add_action( 'pre_get_posts', 'viromarket_handle_sidebar_filters' );

/**
 * Mettre à jour le compteur du panier via AJAX
 */
function viromarket_cart_count_fragments($fragments) {
    if ( ! class_exists( 'WooCommerce' ) || ! WC()->cart ) return $fragments;

    // Ensure cart is loaded and totaled
    WC()->cart->calculate_totals();

    // Top bar cart fragment - Include the ID wrapper
    ob_start();
    ?>
    <div id="openCart">
        <?php viromarket_cart_count(); ?>
    </div>
    <?php
    $fragments['#openCart'] = ob_get_clean();

    // Mobile bar cart fragment - Include the ID wrapper
    ob_start();
    ?>
    <div id="openCartMobile">
        <div class="open-pages">
            <i data-lucide="shopping-cart"></i>
            <span class="badge cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
        </div>
    </div>
    <?php
    $fragments['#openCartMobile'] = ob_get_clean();

    // Mini cart content fragment
    ob_start();
    ?>
    <div class="widget_shopping_cart_content">
        <?php woocommerce_mini_cart(); ?>
    </div>
    <?php
    $fragments['div.widget_shopping_cart_content'] = ob_get_clean();
    
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'viromarket_cart_count_fragments');


