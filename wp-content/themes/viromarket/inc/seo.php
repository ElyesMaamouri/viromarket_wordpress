<?php
/**
 * SEO Optimization Functions
 * 
 * @package ViroMarket
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add structured data (Schema.org) for products
 */
function viromarket_product_schema() {
    if (!is_product()) {
        return;
    }
    
    global $product;
    
    $schema = array(
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        'name' => $product->get_name(),
        'description' => wp_strip_all_tags($product->get_short_description()),
        'sku' => $product->get_sku(),
        'offers' => array(
            '@type' => 'Offer',
            'url' => get_permalink($product->get_id()),
            'priceCurrency' => get_woocommerce_currency(),
            'price' => $product->get_price(),
            'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
        ),
    );
    
    // Add image if available
    if ($product->get_image_id()) {
        $schema['image'] = wp_get_attachment_url($product->get_image_id());
    }
    
    // Add rating if available
    if ($product->get_average_rating()) {
        $schema['aggregateRating'] = array(
            '@type' => 'AggregateRating',
            'ratingValue' => $product->get_average_rating(),
            'reviewCount' => $product->get_review_count(),
        );
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
}
add_action('wp_head', 'viromarket_product_schema');

/**
 * Add breadcrumb schema
 */
function viromarket_breadcrumb_schema() {
    if (is_front_page()) {
        return;
    }
    
    $items = array();
    $position = 1;
    
    // Home
    $items[] = array(
        '@type' => 'ListItem',
        'position' => $position++,
        'name' => 'Home',
        'item' => home_url('/'),
    );
    
    // Current page
    if (is_singular()) {
        $items[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'name' => get_the_title(),
            'item' => get_permalink(),
        );
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $items,
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
}
add_action('wp_head', 'viromarket_breadcrumb_schema');

/**
 * Add organization schema
 */
function viromarket_organization_schema() {
    if (!is_front_page()) {
        return;
    }
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => get_bloginfo('name'),
        'url' => home_url('/'),
        'logo' => get_theme_mod('custom_logo') ? wp_get_attachment_url(get_theme_mod('custom_logo')) : '',
        'contactPoint' => array(
            '@type' => 'ContactPoint',
            'telephone' => get_theme_mod('viromarket_phone', ''),
            'contactType' => 'customer service',
        ),
    );
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
}
add_action('wp_head', 'viromarket_organization_schema');

/**
 * Optimize meta tags
 */
function viromarket_meta_tags() {
    // Open Graph
    if (is_singular()) {
        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '">';
        echo '<meta property="og:type" content="article">';
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '">';
        
        if (has_post_thumbnail()) {
            echo '<meta property="og:image" content="' . esc_url(get_the_post_thumbnail_url(null, 'large')) . '">';
        }
        
        if (get_the_excerpt()) {
            echo '<meta property="og:description" content="' . esc_attr(wp_strip_all_tags(get_the_excerpt())) . '">';
        }
    }
    
    // Twitter Card
    echo '<meta name="twitter:card" content="summary_large_image">';
}
add_action('wp_head', 'viromarket_meta_tags', 5);

/**
 * Add canonical URL
 */
function viromarket_canonical_url() {
    if (is_singular()) {
        echo '<link rel="canonical" href="' . esc_url(get_permalink()) . '">';
    }
}
add_action('wp_head', 'viromarket_canonical_url', 1);

/**
 * Optimize robots meta
 */
function viromarket_robots_meta() {
    // Noindex for search, 404, etc.
    if (is_search() || is_404()) {
        echo '<meta name="robots" content="noindex,follow">';
    }
}
add_action('wp_head', 'viromarket_robots_meta', 1);
