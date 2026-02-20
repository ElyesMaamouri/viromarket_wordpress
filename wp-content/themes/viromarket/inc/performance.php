<?php
/**
 * Performance Optimization Functions
 * 
 * @package ViroMarket
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Defer JavaScript loading
 */
function viromarket_defer_scripts($tag, $handle, $src) {
    // Skip if it's an admin page
    if (is_admin()) {
        return $tag;
    }
    
    // Scripts to defer
    $defer_scripts = array(
        'viromarket-main',
        'lucide-icons',
        'swiper-js',
    );
    
    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'viromarket_defer_scripts', 10, 3);

/**
 * Preload critical assets
 */
function viromarket_preload_assets() {
    // Preload main CSS
    echo '<link rel="preload" href="' . get_template_directory_uri() . '/assets/css/styles.css" as="style">';
    
    // Preload fonts
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    
    // Preload logo if exists
    if (has_custom_logo()) {
        $logo_id = get_theme_mod('custom_logo');
        $logo_url = wp_get_attachment_image_url($logo_id, 'full');
        if ($logo_url) {
            echo '<link rel="preload" href="' . esc_url($logo_url) . '" as="image">';
        }
    }
}
add_action('wp_head', 'viromarket_preload_assets', 1);

/**
 * Add lazy loading to images
 */
function viromarket_add_lazy_loading($attr) {
    if (!is_admin()) {
        $attr['loading'] = 'lazy';
    }
    return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'viromarket_add_lazy_loading');

/**
 * Force wc-cart-fragments to load on ALL pages so the navbar cart count
 * always stays in sync after add/remove actions.
 */
/**
 * Force wc-cart-fragments to load on ALL pages so the navbar cart count
 * always stays in sync after add/remove actions.
 */
function viromarket_force_cart_fragments() {
    if ( class_exists('WooCommerce') ) {
        wp_enqueue_script('wc-cart-fragments');

        // Cart page dedicated stylesheet
        // Check by is_cart(), ID 8, or slug
        $queried_id = get_queried_object_id();
        $is_cart_page = is_cart() || $queried_id == 8;

        if ( $is_cart_page || is_checkout() || (is_page() && strpos(get_post_field('post_name', $queried_id), 'cart') !== false) ) {
            $css_path = VIROMARKET_THEME_DIR . '/assets/css/cart.css';
            $version = file_exists($css_path) ? filemtime($css_path) : VIROMARKET_VERSION;
            
            wp_enqueue_style(
                'viromarket-cart',
                VIROMARKET_THEME_URI . '/assets/css/cart.css',
                array(),
                $version
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'viromarket_force_cart_fragments', 999);

/**
 * Remove query strings from static resources
 */
function viromarket_remove_query_strings($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'viromarket_remove_query_strings', 10, 1);
add_filter('script_loader_src', 'viromarket_remove_query_strings', 10, 1);

/**
 * Disable WordPress emojis
 */
function viromarket_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'viromarket_disable_emojis');

/**
 * Remove unnecessary WordPress features
 */
function viromarket_cleanup_head() {
    // Remove RSD link
    remove_action('wp_head', 'rsd_link');
    
    // Remove Windows Live Writer link
    remove_action('wp_head', 'wlwmanifest_link');
    
    // Remove WordPress version
    remove_action('wp_head', 'wp_generator');
    
    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Remove REST API link
    remove_action('wp_head', 'rest_output_link_wp_head');
    
    // Remove oEmbed discovery links
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
}
add_action('init', 'viromarket_cleanup_head');

/**
 * Optimize database queries
 */
function viromarket_optimize_queries() {
    // Limit post revisions
    if (!defined('WP_POST_REVISIONS')) {
        define('WP_POST_REVISIONS', 3);
    }
    
    // Increase autosave interval
    if (!defined('AUTOSAVE_INTERVAL')) {
        define('AUTOSAVE_INTERVAL', 300); // 5 minutes
    }
}
add_action('init', 'viromarket_optimize_queries');

/**
 * Enable Gzip compression
 */
function viromarket_enable_gzip() {
    if (!is_admin() && !ini_get('zlib.output_compression')) {
        if (extension_loaded('zlib')) {
            ob_start('ob_gzhandler');
        }
    }
}
add_action('init', 'viromarket_enable_gzip');

/**
 * Add cache control headers
 */
function viromarket_cache_headers() {
    if (!is_admin()) {
        header('Cache-Control: public, max-age=31536000');
    }
}
add_action('send_headers', 'viromarket_cache_headers');

/**
 * Optimize image sizes
 */
function viromarket_optimize_image_sizes() {
    // Disable big image threshold (WordPress 5.3+)
    add_filter('big_image_size_threshold', '__return_false');
    
    // Set JPEG quality
    add_filter('jpeg_quality', function() { return 85; });
    add_filter('wp_editor_set_quality', function() { return 85; });
}
add_action('after_setup_theme', 'viromarket_optimize_image_sizes');

/**
 * Minify HTML output (optional - can be heavy)
 */
function viromarket_minify_html($html) {
    if (is_admin() || (defined('DOING_AJAX') && DOING_AJAX)) {
        return $html;
    }
    
    // Remove comments
    $html = preg_replace('/<!--(?!<!)[^\[>].*?-->/s', '', $html);
    
    // Remove whitespace
    $html = preg_replace('/\s+/', ' ', $html);
    
    return $html;
}
// Uncomment to enable HTML minification
// add_action('wp_loaded', function() {
//     ob_start('viromarket_minify_html');
// });
