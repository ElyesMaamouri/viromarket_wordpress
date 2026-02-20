<?php
/**
 * Gestion des variables CSS personnalisables
 * 
 * @package ViroMarket
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Générer le CSS personnalisé depuis le Customizer
 */
function viromarket_customizer_css() {
    // Récupérer les valeurs du customizer
    $primary_color = get_theme_mod('viromarket_primary_color', '#62D0B6');
    $secondary_color = get_theme_mod('viromarket_secondary_color', '#333333');
    $accent_red = get_theme_mod('viromarket_accent_red', '#F55157');
    $accent_green = get_theme_mod('viromarket_accent_green', '#27AE60');
    
    // Border radius
    $radius_sm = get_theme_mod('viromarket_radius_sm', '4px');
    $radius_md = get_theme_mod('viromarket_radius_md', '8px');
    $radius_lg = get_theme_mod('viromarket_radius_lg', '12px');
    
    // Container
    $container_width = get_theme_mod('viromarket_container_width', '1200px');
    
    // Typographie
    $font_size_base = get_theme_mod('viromarket_font_size_base', '0.875rem');
    
    // Générer le CSS
    ?>
    <style type="text/css" id="viromarket-custom-css">
        :root {
            /* Couleurs personnalisées du Customizer */
            --primary-color: <?php echo esc_attr($primary_color); ?>;
            --secondary-color: <?php echo esc_attr($secondary_color); ?>;
            --accent-red: <?php echo esc_attr($accent_red); ?>;
            --accent-green: <?php echo esc_attr($accent_green); ?>;
            
            /* Couleurs de base (Template) */
            --color-white: #ffffff;
            --color-black: #000000;
            --bg-main: #FFFFFF;
            --color-grey: #666666;
            --color-text-muted: #999999;
            --color-text-light: #888888;
            --color-text-lighter: #cccccc;
            --color-light-grey: #F8F8F8;
            --color-bg-light: #f5f5f5;
            --color-bg-white: #fff;
            --color-border: #E8E8E8;
            --color-border-light: #D6D6D6;
            --color-border-lighter: #eeeeee;
            --color-border-lightest: #f0f0f0;
            --color-placeholder: #A0A0A0;
            
            /* Border Radius */
            --radius-sm: <?php echo esc_attr($radius_sm); ?>;
            --radius-md: <?php echo esc_attr($radius_md); ?>;
            --radius-lg: <?php echo esc_attr($radius_lg); ?>;
            
            /* Spacing & Layout */
            --container-max-width: <?php echo esc_attr($container_width); ?>;
            --transition-fast: 0.2s ease;
            --transition-normal: 0.3s ease;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
            
            /* Typographie */
            --font-family: 'Gabarito', 'Tajawal', sans-serif;
            --font-size-base: <?php echo esc_attr($font_size_base); ?>;
            --font-size-sm: 0.75rem;
            --font-size-md: 0.9rem;
            --font-size-lg: 1rem;
            --font-size-xl: 1.15rem;
        }
    </style>
    <?php
}
add_action('wp_head', 'viromarket_customizer_css', 100);
