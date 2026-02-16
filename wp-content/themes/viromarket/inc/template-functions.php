<?php
/**
 * Custom Template Functions
 * 
 * @package ViroMarket
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display site logo
 */
function viromarket_site_logo() {
    if (has_custom_logo()) {
        the_custom_logo();
    } else {
        ?>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo">
            <h1 class="logo-text"><?php bloginfo('name'); ?></h1>
        </a>
        <?php
    }
}

/**
 * Display contact info in top nav
 */
function viromarket_contact_info() {
    $phone = get_theme_mod('viromarket_phone', '+966 55 675 4472');
    $phone_raw = str_replace(' ', '', $phone);
    
    ?>
    <div class="contact-info">
        <div class="phone">
            <i data-lucide="phone-call"></i>
            <span><?php _e('Need Help?', 'viromarket'); ?> <a href="tel:<?php echo esc_attr($phone_raw); ?>"><?php echo esc_html($phone); ?></a></span>
        </div>
    </div>
    <?php
}

/**
 * Display cart counter
 */
function viromarket_cart_count() {
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    $count = WC()->cart->get_cart_contents_count();
    ?>
    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart">
        <div class="cart-icon">
            <i data-lucide="shopping-cart"></i>
            <span class="badge cart-count"><?php echo esc_html($count); ?></span>
        </div>
        <div class="cart-info">
            <span class="cart-txt"><?php esc_html_e('Shopping Cart', 'viromarket'); ?></span>
            <span class="cart-money"><?php echo WC()->cart->get_cart_total(); ?></span>
        </div>
    </a>
    <?php
}

/**
 * Display user account info
 */
function viromarket_user_account() {
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        ?>
        <div class="user">
            <div class="user-icon">
                <i data-lucide="user"></i>
            </div>
            <div class="user-name">
                <span><?php _e('Welcome', 'viromarket'); ?></span>
                <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>">
                    <?php echo esc_html($current_user->display_name); ?>
                </a>
            </div>
        </div>
        <?php
    } else {
        ?>
        <div class="user">
            <div class="user-icon">
                <i data-lucide="user"></i>
            </div>
            <div class="user-name">
                <span><?php _e('Welcome', 'viromarket'); ?></span>
                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>">
                    <?php _e('Login', 'viromarket'); ?>
                </a>
            </div>
        </div>
        <?php
    }
}

/**
 * Display search bar
 */
function viromarket_search_form() {
    if (class_exists('WooCommerce')) {
        ?>
        <form role="search" method="get" class="search-bar" action="<?php echo esc_url(home_url('/')); ?>">
            <input type="search" 
                   name="s" 
                   placeholder="<?php esc_attr_e('Search for what you want', 'viromarket'); ?>" 
                   value="<?php echo get_search_query(); ?>"
                   required>
            <input type="hidden" name="post_type" value="product">
            <button type="submit" aria-label="<?php esc_attr_e('Search', 'viromarket'); ?>">
                <i data-lucide="search"></i>
            </button>
        </form>
        <?php
    } else {
        get_search_form();
    }
}

/**
 * Display custom pagination
 */
function viromarket_pagination() {
    global $wp_query;
    
    if ($wp_query->max_num_pages <= 1) {
        return;
    }
    
    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
    $max   = intval($wp_query->max_num_pages);
    
    $links = paginate_links( array(
        'base'         => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
        'format'       => '',
        'add_args'     => false,
        'current'      => $paged,
        'total'        => $max,
        'prev_text'    => '<i data-lucide="chevron-left"></i>',
        'next_text'    => '<i data-lucide="chevron-right"></i>',
        'type'         => 'array',
        'end_size'     => 1,
        'mid_size'     => 1,
    ) );

    if ( is_array( $links ) ) {
        echo '<nav class="pagination-container" aria-label="Product pagination">';
        echo '<ul class="pagination-list">';
        
        foreach ( $links as $link ) {
            if ( strpos( $link, 'dots' ) !== false ) {
                echo '<li><span class="pagination-dots">...</span></li>';
                continue;
            }
            
            // Determine item class based on link type
            $item_class = 'pagination-link';
            if ( strpos( $link, 'current' ) !== false ) {
                $item_class .= ' active';
            }
            
            // Previous/Next specific styling
            if ( strpos( $link, 'prev' ) !== false ) {
                $link = str_replace( 'page-numbers', 'pagination-btn prev', $link );
            } elseif ( strpos( $link, 'next' ) !== false ) {
                $link = str_replace( 'page-numbers', 'pagination-btn next', $link );
            } else {
                $link = str_replace( 'page-numbers', $item_class, $link );
            }
            
            echo '<li>' . $link . '</li>';
        }
        
        echo '</ul>';
        echo '</nav>';
    }
}
