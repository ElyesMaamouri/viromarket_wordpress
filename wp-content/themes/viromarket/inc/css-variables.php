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
            /* Couleurs personnalisées */
            --primary-color: <?php echo esc_attr($primary_color); ?>;
            --secondary-color: <?php echo esc_attr($secondary_color); ?>;
            --accent-red: <?php echo esc_attr($accent_red); ?>;
            --accent-green: <?php echo esc_attr($accent_green); ?>;
            
            /* Border Radius personnalisés */
            --radius-sm: <?php echo esc_attr($radius_sm); ?>;
            --radius-md: <?php echo esc_attr($radius_md); ?>;
            --radius-lg: <?php echo esc_attr($radius_lg); ?>;
            
            /* Container personnalisé */
            --container-max-width: <?php echo esc_attr($container_width); ?>;
            
            /* Typographie personnalisée */
            --font-size-base: <?php echo esc_attr($font_size_base); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'viromarket_customizer_css', 100);
