<?php
/**
 * WordPress Customizer Configuration
 * 
 * @package ViroMarket
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enregistrer les paramètres du Customizer
 */
function viromarket_customize_register($wp_customize) {
    
    // ========================================
    // SECTION: COULEURS VIROMARKET
    // ========================================
    $wp_customize->add_section('viromarket_colors', array(
        'title'    => __('Couleurs ViroMarket', 'viromarket'),
        'priority' => 30,
    ));
    
    // Couleur primaire
    $wp_customize->add_setting('viromarket_primary_color', array(
        'default'           => '#62D0B6',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'viromarket_primary_color', array(
        'label'       => __('Couleur Primaire', 'viromarket'),
        'description' => __('Couleur principale du thème (boutons, liens, accents)', 'viromarket'),
        'section'     => 'viromarket_colors',
        'settings'    => 'viromarket_primary_color',
    )));
    
    // Couleur secondaire
    $wp_customize->add_setting('viromarket_secondary_color', array(
        'default'           => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'viromarket_secondary_color', array(
        'label'       => __('Couleur Secondaire', 'viromarket'),
        'description' => __('Couleur secondaire (textes, titres)', 'viromarket'),
        'section'     => 'viromarket_colors',
        'settings'    => 'viromarket_secondary_color',
    )));
    
    // Accent rouge
    $wp_customize->add_setting('viromarket_accent_red', array(
        'default'           => '#F55157',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'viromarket_accent_red', array(
        'label'       => __('Accent Rouge', 'viromarket'),
        'description' => __('Couleur pour les badges, alertes, promotions', 'viromarket'),
        'section'     => 'viromarket_colors',
        'settings'    => 'viromarket_accent_red',
    )));
    
    // Accent vert
    $wp_customize->add_setting('viromarket_accent_green', array(
        'default'           => '#27AE60',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'viromarket_accent_green', array(
        'label'       => __('Accent Vert', 'viromarket'),
        'description' => __('Couleur pour les succès, disponibilité', 'viromarket'),
        'section'     => 'viromarket_colors',
        'settings'    => 'viromarket_accent_green',
    )));
    
    // ========================================
    // SECTION: DESIGN VIROMARKET
    // ========================================
    $wp_customize->add_section('viromarket_design', array(
        'title'    => __('Design ViroMarket', 'viromarket'),
        'priority' => 31,
    ));
    
    // Border Radius Small
    $wp_customize->add_setting('viromarket_radius_sm', array(
        'default'           => '4px',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('viromarket_radius_sm', array(
        'label'       => __('Border Radius (Petit)', 'viromarket'),
        'description' => __('Arrondi des coins pour petits éléments (ex: 4px)', 'viromarket'),
        'section'     => 'viromarket_design',
        'type'        => 'text',
    ));
    
    // Border Radius Medium
    $wp_customize->add_setting('viromarket_radius_md', array(
        'default'           => '8px',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('viromarket_radius_md', array(
        'label'       => __('Border Radius (Moyen)', 'viromarket'),
        'description' => __('Arrondi des coins pour éléments moyens (ex: 8px)', 'viromarket'),
        'section'     => 'viromarket_design',
        'type'        => 'text',
    ));
    
    // Border Radius Large
    $wp_customize->add_setting('viromarket_radius_lg', array(
        'default'           => '12px',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('viromarket_radius_lg', array(
        'label'       => __('Border Radius (Grand)', 'viromarket'),
        'description' => __('Arrondi des coins pour grands éléments (ex: 12px)', 'viromarket'),
        'section'     => 'viromarket_design',
        'type'        => 'text',
    ));
    
    // Container Width
    $wp_customize->add_setting('viromarket_container_width', array(
        'default'           => '1200px',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('viromarket_container_width', array(
        'label'       => __('Largeur du Container', 'viromarket'),
        'description' => __('Largeur maximale du contenu (ex: 1200px)', 'viromarket'),
        'section'     => 'viromarket_design',
        'type'        => 'text',
    ));
    
    // ========================================
    // SECTION: TYPOGRAPHIE
    // ========================================
    $wp_customize->add_section('viromarket_typography', array(
        'title'    => __('Typographie', 'viromarket'),
        'priority' => 32,
    ));
    
    // Font Size Base
    $wp_customize->add_setting('viromarket_font_size_base', array(
        'default'           => '0.875rem',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'postMessage',
    ));
    
    $wp_customize->add_control('viromarket_font_size_base', array(
        'label'       => __('Taille de Police de Base', 'viromarket'),
        'description' => __('Taille de police par défaut (ex: 0.875rem = 14px)', 'viromarket'),
        'section'     => 'viromarket_typography',
        'type'        => 'text',
    ));

    // ========================================
    // SECTION: SHOP SETTINGS
    // ========================================
    $wp_customize->add_section('viromarket_shop_sidebar', array(
        'title'    => __('Shop Sidebar', 'viromarket'),
        'priority' => 33,
    ));

    // Show/Hide Banner
    $wp_customize->add_setting('viromarket_show_sidebar_banner', array(
        'default'           => true,
        'sanitize_callback' => 'viromarket_sanitize_checkbox',
    ));

    $wp_customize->add_control('viromarket_show_sidebar_banner', array(
        'label'    => __('Show Sidebar Banner', 'viromarket'),
        'section'  => 'viromarket_shop_sidebar',
        'type'     => 'checkbox',
    ));

    // Banner Image
    $wp_customize->add_setting('viromarket_sidebar_banner_img', array(
        'default'           => 'https://images.unsplash.com/photo-1542491595-62e922f81bd3?q=80&w=500&auto=format&fit=crop',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'viromarket_sidebar_banner_img', array(
        'label'    => __('Banner Image', 'viromarket'),
        'section'  => 'viromarket_shop_sidebar',
    )));

    // Banner Label
    $wp_customize->add_setting('viromarket_sidebar_banner_label', array(
        'default'           => __('NEW ARRIVAL', 'viromarket'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('viromarket_sidebar_banner_label', array(
        'label'    => __('Banner Label', 'viromarket'),
        'section'  => 'viromarket_shop_sidebar',
        'type'     => 'text',
    ));

    // Banner Title
    $wp_customize->add_setting('viromarket_sidebar_banner_title', array(
        'default'           => __('Smart Watch Series 8', 'viromarket'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('viromarket_sidebar_banner_title', array(
        'label'    => __('Banner Title', 'viromarket'),
        'section'  => 'viromarket_shop_sidebar',
        'type'     => 'text',
    ));

    // Banner Link
    $wp_customize->add_setting('viromarket_sidebar_banner_link', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('viromarket_sidebar_banner_link', array(
        'label'    => __('Banner Link', 'viromarket'),
        'section'  => 'viromarket_shop_sidebar',
        'type'     => 'text',
    ));

    // Banner Background Color
    $wp_customize->add_setting('viromarket_sidebar_banner_bg_color', array(
        'default'           => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'viromarket_sidebar_banner_bg_color', array(
        'label'    => __('Banner Background Color', 'viromarket'),
        'section'  => 'viromarket_shop_sidebar',
    )));

    // Banner Text Color
    $wp_customize->add_setting('viromarket_sidebar_banner_text_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'viromarket_sidebar_banner_text_color', array(
        'label'    => __('Banner Text Color', 'viromarket'),
        'section'  => 'viromarket_shop_sidebar',
    )));
}
add_action('customize_register', 'viromarket_customize_register');

/**
 * Sanitize Checkbox
 */
function viromarket_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Enqueue Customizer scripts
 */
function viromarket_customize_preview_js() {
    wp_enqueue_script(
        'viromarket-customizer',
        get_template_directory_uri() . '/assets/js/customizer.js',
        array('customize-preview'),
        VIROMARKET_VERSION,
        true
    );
}
add_action('customize_preview_init', 'viromarket_customize_preview_js');
