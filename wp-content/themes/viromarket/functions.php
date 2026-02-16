<?php
/**
 * ViroMarket Theme Functions
 * 
 * @package ViroMarket
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Définir les constantes du thème
define('VIROMARKET_VERSION', '1.0.0');
define('VIROMARKET_THEME_DIR', get_template_directory());
define('VIROMARKET_THEME_URI', get_template_directory_uri());

/**
 * Charger les fichiers d'inclusion
 */
require VIROMARKET_THEME_DIR . '/inc/customizer.php';
require VIROMARKET_THEME_DIR . '/inc/css-variables.php';
require VIROMARKET_THEME_DIR . '/inc/woocommerce.php';
require VIROMARKET_THEME_DIR . '/inc/template-functions.php';
require VIROMARKET_THEME_DIR . '/inc/multilingual.php';
require VIROMARKET_THEME_DIR . '/inc/menu-walker.php';
require VIROMARKET_THEME_DIR . '/inc/seo.php';
require VIROMARKET_THEME_DIR . '/inc/performance.php';
require VIROMARKET_THEME_DIR . '/inc/brands.php';

/**
 * Configuration du thème
 */
function viromarket_setup() {
    // Support de la traduction
    load_theme_textdomain('viromarket', VIROMARKET_THEME_DIR . '/languages');
    
    // Support automatique du titre
    add_theme_support('title-tag');
    
    // Support des images à la une
    add_theme_support('post-thumbnails');
    
    // Support HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // Support du logo personnalisé
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Support WooCommerce
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Support des menus
    register_nav_menus(array(
        'primary'    => __('Menu Principal', 'viromarket'),
        'categories' => __('Menu Catégories', 'viromarket'),
        'footer'     => __('Menu Pied de page', 'viromarket'),
        'account'    => __('Menu Compte', 'viromarket'),
        'account_mobile' => __('Menu Compte Mobile', 'viromarket'),
    ));
    
    // Tailles d'images personnalisées
    add_image_size('viromarket-product-thumb', 300, 300, true);
    add_image_size('viromarket-product-large', 800, 800, true);
    add_image_size('viromarket-hero', 1920, 800, true);
}
add_action('after_setup_theme', 'viromarket_setup');

/**
 * Enregistrer les scripts et styles
 */
function viromarket_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'viromarket-fonts',
        'https://fonts.googleapis.com/css2?family=Gabarito:wght@400;500;600;700;800;900&family=Tajawal:wght@200;300;400;500;700;800;900&display=swap',
        array(),
        null
    );
    
    // Style principal du thème
    wp_enqueue_style(
        'viromarket-style',
        get_stylesheet_uri(),
        array(),
        VIROMARKET_VERSION
    );
    
    // CSS principal depuis le template
    wp_enqueue_style(
        'viromarket-main',
        VIROMARKET_THEME_URI . '/assets/css/styles.css',
        array('viromarket-style'),
        VIROMARKET_VERSION
    );
    
    // Lucide Icons
    wp_enqueue_script(
        'lucide-icons',
        'https://unpkg.com/lucide@latest',
        array(),
        null,
        true
    );
    
    // JavaScript principal
    wp_enqueue_script(
        'viromarket-main',
        VIROMARKET_THEME_URI . '/assets/js/main.js',
        array('jquery'),
        VIROMARKET_VERSION,
        true
    );
    
    // Swiper pour les sliders
    wp_enqueue_style(
        'swiper-css',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        array(),
        '11.0.0'
    );
    
    wp_enqueue_script(
        'swiper-js',
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        array(),
        '11.0.0',
        true
    );
    
    // Localisation JavaScript
    wp_localize_script('viromarket-main', 'viromarketData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('viromarket-nonce'),
        'isRTL'   => is_rtl(),
        'lang'    => get_locale(),
        'viewAll' => __( 'View All', 'viromarket' ),
        'viewLess'=> __( 'View Less', 'viromarket' ),
    ));
    
    // Support des commentaires imbriqués
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'viromarket_scripts');

/**
 * Enregistrer les zones de widgets
 */
function viromarket_widgets_init() {
    // Sidebar principale
    register_sidebar(array(
        'name'          => __('Sidebar Principale', 'viromarket'),
        'id'            => 'sidebar-1',
        'description'   => __('Widgets pour la sidebar principale', 'viromarket'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
    
    // Sidebar shop
    register_sidebar(array(
        'name'          => __('Sidebar Boutique', 'viromarket'),
        'id'            => 'sidebar-shop',
        'description'   => __('Widgets pour la page boutique', 'viromarket'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    // Footer - Colonne 1
    register_sidebar(array(
        'name'          => __('Footer Colonne 1', 'viromarket'),
        'id'            => 'footer-1',
        'description'   => __('Widgets pour la première colonne du footer', 'viromarket'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
    
    // Footer - Colonne 2
    register_sidebar(array(
        'name'          => __('Footer Colonne 2', 'viromarket'),
        'id'            => 'footer-2',
        'description'   => __('Widgets pour la deuxième colonne du footer', 'viromarket'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
    
    // Footer - Colonne 3
    register_sidebar(array(
        'name'          => __('Footer Colonne 3', 'viromarket'),
        'id'            => 'footer-3',
        'description'   => __('Widgets pour la troisième colonne du footer', 'viromarket'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
    
    // Footer - Colonne 4
    register_sidebar(array(
        'name'          => __('Footer Colonne 4', 'viromarket'),
        'id'            => 'footer-4',
        'description'   => __('Widgets pour la quatrième colonne du footer', 'viromarket'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'viromarket_widgets_init');

/**
 * Ajouter des classes body personnalisées
 */
function viromarket_body_classes($classes) {
    // Ajouter classe si WooCommerce est actif
    if (class_exists('WooCommerce')) {
        $classes[] = 'woocommerce-active';
    }
    
    // Ajouter classe RTL si nécessaire
    if (is_rtl()) {
        $classes[] = 'rtl-layout';
    }
    
    // Ajouter classe pour la langue
    $locale = get_locale();
    $classes[] = 'lang-' . $locale;
    
    return $classes;
}
add_filter('body_class', 'viromarket_body_classes');

/**
 * Custom WooCommerce Catalog Sorting Options
 */
function viromarket_custom_catalog_orderby( $options ) {
    $options = array(
        'date'       => __( 'Newest Arrivals', 'viromarket' ),
        'price'      => __( 'Price: Low to High', 'viromarket' ),
        'price-desc' => __( 'Price: High to Low', 'viromarket' ),
        'popularity' => __( 'Most Popular', 'viromarket' ),
    );
    return $options;
}
add_filter( 'woocommerce_catalog_orderby', 'viromarket_custom_catalog_orderby' );
add_filter( 'woocommerce_default_catalog_orderby_options', 'viromarket_custom_catalog_orderby' );

/**
 * Modifier l'excerpt length
 */
function viromarket_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'viromarket_excerpt_length');

/**
 * Modifier l'excerpt more
 */
function viromarket_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'viromarket_excerpt_more');
