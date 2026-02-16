<?php
/**
 * Support multi-langue (WPML, Polylang, etc.)
 * 
 * @package ViroMarket
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Vérifier si un plugin multi-langue est actif
 */
function viromarket_is_multilingual() {
    return (
        function_exists('icl_get_languages') || // WPML
        function_exists('pll_the_languages') || // Polylang
        class_exists('TRP_Translate_Press')     // TranslatePress
    );
}

/**
 * Obtenir les langues disponibles
 */
function viromarket_get_languages() {
    $languages = array();
    
    // WPML
    if (function_exists('icl_get_languages')) {
        $languages = icl_get_languages('skip_missing=0');
    }
    // Polylang
    elseif (function_exists('pll_the_languages')) {
        $languages = pll_the_languages(array('raw' => 1));
    }
    
    return $languages;
}

/**
 * Afficher le sélecteur de langue
 */
function viromarket_language_selector() {
    if (!viromarket_is_multilingual()) {
        return;
    }
    
    $languages = viromarket_get_languages();
    
    if (empty($languages)) {
        return;
    }
    
    $current_lang = '';
    $current_flag = '';
    
    // Trouver la langue courante
    foreach ($languages as $lang) {
        if (isset($lang['active']) && $lang['active']) {
            $current_lang = isset($lang['native_name']) ? $lang['native_name'] : $lang['name'];
            $current_flag = isset($lang['country_flag_url']) ? $lang['country_flag_url'] : '';
            break;
        }
    }
    
    ?>
    <div class="language-selector">
        <span class="lang-label"><?php echo esc_html($current_lang); ?></span>
        <?php if ($current_flag) : ?>
            <div class="flag-circle">
                <img src="<?php echo esc_url($current_flag); ?>" alt="<?php echo esc_attr($current_lang); ?>">
            </div>
        <?php endif; ?>
        
        <div class="language-dropdown">
            <ul>
                <?php foreach ($languages as $lang) : ?>
                    <li class="<?php echo isset($lang['active']) && $lang['active'] ? 'active' : ''; ?>">
                        <a href="<?php echo esc_url($lang['url']); ?>">
                            <?php if (isset($lang['country_flag_url'])) : ?>
                                <img src="<?php echo esc_url($lang['country_flag_url']); ?>" alt="<?php echo esc_attr($lang['name']); ?>">
                            <?php endif; ?>
                            <span><?php echo esc_html(isset($lang['native_name']) ? $lang['native_name'] : $lang['name']); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php
}

/**
 * Ajouter la classe RTL au body si nécessaire
 */
function viromarket_rtl_body_class($classes) {
    if (is_rtl()) {
        $classes[] = 'rtl';
    }
    return $classes;
}
add_filter('body_class', 'viromarket_rtl_body_class');

/**
 * Charger le CSS RTL si nécessaire
 */
function viromarket_rtl_styles() {
    if (is_rtl()) {
        wp_enqueue_style(
            'viromarket-rtl',
            get_template_directory_uri() . '/assets/css/rtl.css',
            array('viromarket-main'),
            VIROMARKET_VERSION
        );
    }
}
add_action('wp_enqueue_scripts', 'viromarket_rtl_styles', 20);
