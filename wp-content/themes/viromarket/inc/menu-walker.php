<?php
/**
 * Custom Menu Walker for categories with mega menu support
 * 
 * @package ViroMarket
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Walker for the category menu with mega menu support (Desktop)
 */
class ViroMarket_Category_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        if ($depth === 0) {
            $output .= "\n$indent<div class=\"mega-menu\">\n";
            $output .= "$indent\t<div class=\"mega-menu-content\">\n";
        } else {
            $output .= "\n$indent<ul>\n";
        }
    }
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        if ($depth === 0) {
            $output .= "$indent\t</div>\n";
            $output .= "$indent</div>\n";
        } else {
            $output .= "$indent</ul>\n";
        }
    }
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        
        $has_children = false;
        if (isset($args->walker->has_children) && $args->walker->has_children) {
            $has_children = true;
        } elseif (in_array('menu-item-has-children', $classes)) {
             $has_children = true;
        }

        if ($depth === 0) {
            $classes[] = 'mega-menu-trigger';
            if ($has_children) {
                $classes[] = 'has-dropdown';
            }
        }
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        $atts = array();
        $atts['href'] = !empty($item->url) ? $item->url : '';
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
        $attributes = '';
        foreach ($atts as $attr => $value) {
            $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
            $attributes .= ' ' . $attr . '="' . $value . '"';
        }
        $title = apply_filters('the_title', $item->title, $item->ID);
        if ($depth === 0) {
            $output .= $indent . '<li' . $class_names . '>';
            $output .= '<a' . $attributes . '>' . $title . '</a>';
        } elseif ($depth === 1) {
            if (in_array('menu-image', $classes) || !empty($item->description)) {
                if (filter_var($item->description, FILTER_VALIDATE_URL)) {
                    $output .= $indent . '<div class="mega-menu-image">';
                    $output .= '<img src="' . esc_url($item->description) . '" alt="' . esc_attr($title) . '">';
                } else {
                    $output .= $indent . '<div class="mega-menu-column">';
                    $output .= '<h4 class="column-title"><a' . $attributes . '>' . $title . '</a></h4>';
                }
            } else {
                $output .= $indent . '<div class="mega-menu-column">';
                $output .= '<h4 class="column-title"><a' . $attributes . '>' . $title . '</a></h4>';
            }
        } else {
            $output .= $indent . '<li><a' . $attributes . '>' . $title . '</a>';
        }
    }
    function end_el(&$output, $item, $depth = 0, $args = null) {
        if ($depth === 1) {
            $output .= "</div>\n";
        } else {
            $output .= "</li>\n";
        }
    }
}

/**
 * Walker for the Mobile Menu to match products.html structure
 */
class ViroMarket_Mobile_Walker extends Walker_Nav_Menu {
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"submenu\">\n";
    }
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        
        $has_children = false;
        if (isset($args->walker->has_children) && $args->walker->has_children) {
            $has_children = true;
        } elseif (in_array('menu-item-has-children', $classes)) {
             $has_children = true;
        }

        if ($has_children) {
            $classes[] = 'has-submenu';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';
        $output .= $indent . '<li' . $class_names . '>';
        
        $atts = array();
        $atts['href'] = !empty($item->url) ? $item->url : '';
        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);
        $attributes = '';
        foreach ($atts as $attr => $value) {
            $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
            $attributes .= ' ' . $attr . '="' . $value . '"';
        }
        
        $title = apply_filters('the_title', $item->title, $item->ID);
        
        // Icon mapping helper
        $icon = 'layout-grid'; // Default
        $title_lower = strtolower($title);
        if (strpos($title_lower, 'elect') !== false) $icon = 'monitor';
        elseif (strpos($title_lower, 'mobi') !== false || strpos($title_lower, 'phone') !== false) $icon = 'smartphone';
        elseif (strpos($title_lower, 'cam') !== false) $icon = 'video';
        elseif (strpos($title_lower, 'watch') !== false) $icon = 'watch';
        elseif (strpos($title_lower, 'access') !== false) $icon = 'headphones';
        elseif (strpos($title_lower, 'home') !== false) $icon = 'home';
        elseif (strpos($title_lower, 'fash') !== false) $icon = 'shopping-bag';

        if ($has_children) {
            $output .= '<div class="menu-item-flex">';
            $output .= '<a' . $attributes . '>';
            $output .= '<div class="nav-label"><i data-lucide="' . $icon . '"></i><span>' . $title . '</span></div>';
            $output .= '</a>';
            $output .= '<i data-lucide="chevron-down" class="submenu-toggle"></i>';
            $output .= '</div>';
        } else {
            $output .= '<a' . $attributes . '>';
            if ($depth === 0) {
                $output .= '<div class="nav-label"><i data-lucide="' . $icon . '"></i><span>' . $title . '</span></div>';
            } else {
                $output .= $title;
            }
            $output .= '</a>';
        }
    }
}

/**
 * Helper to display the category menu (Desktop)
 */
function viromarket_category_menu() {
    $menu_html = '';
    if (has_nav_menu('categories')) {
        $menu_html = wp_nav_menu(array(
            'theme_location' => 'categories',
            'menu_class'     => 'categories-list',
            'container'      => false,
            'walker'         => new ViroMarket_Category_Walker(),
            'fallback_cb'    => false,
            'echo'           => false,
        ));
    }

    if (!empty($menu_html)) {
        echo $menu_html;
    } else {
        // Fallback: Show actual WooCommerce categories with subcategories support
        if (class_exists('WooCommerce')) {
            $categories = get_terms(array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'parent'     => 0,
            ));
            if (!empty($categories) && !is_wp_error($categories)) {
                echo '<ul class="categories-list">';
                foreach ($categories as $cat) {
                    $subcategories = get_terms(array(
                        'taxonomy'   => 'product_cat',
                        'hide_empty' => true,
                        'parent'     => $cat->term_id,
                    ));
                    
                    $has_children = !empty($subcategories) && !is_wp_error($subcategories);
                    $classes = $has_children ? 'mega-menu-trigger has-dropdown' : '';
                    
                    echo '<li class="' . esc_attr($classes) . '">';
                    echo '<a href="' . get_term_link($cat) . '">' . esc_html($cat->name) . '</a>';
                    
                    if ($has_children) {
                        echo '<div class="mega-menu"><div class="mega-menu-content"><div class="mega-menu-column">';
                        echo '<h4 class="column-title">' . esc_html($cat->name) . ' ' . __('Subcategories', 'viromarket') . '</h4>';
                        echo '<ul>';
                        foreach ($subcategories as $subcat) {
                            echo '<li><a href="' . get_term_link($subcat) . '">' . esc_html($subcat->name) . '</a></li>';
                        }
                        echo '</ul>';
                        echo '</div></div></div>';
                    }
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p class="menu-empty-msg" style="padding: 10px; color: var(--color-white);">' . __('Please set up categories in WooCommerce.', 'viromarket') . '</p>';
            }
        }
    }
}

/**
 * Helper to display the primary menu
 */
function viromarket_primary_menu() {
    wp_nav_menu(array(
        'theme_location' => 'primary',
        'menu_class'     => 'primary-menu',
        'container'      => false,
        'fallback_cb'    => '__return_false',
    ));
}

/**
 * Helper to display the mobile menu
 */
function viromarket_mobile_menu() {
    $menu_html = '';
    if (has_nav_menu('categories')) {
        $menu_html = wp_nav_menu(array(
            'theme_location' => 'categories',
            'menu_class'     => 'mobile-nav-list',
            'container'      => false,
            'walker'         => new ViroMarket_Mobile_Walker(),
            'fallback_cb'    => false,
            'depth'          => 2,
            'echo'           => false,
        ));
    }

    if (!empty($menu_html)) {
        echo $menu_html;
    } else {
        // Fallback: Show actual WooCommerce categories if no menu assigned or menu is empty
        if (class_exists('WooCommerce')) {
            $categories = get_terms(array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
                'parent'     => 0,
            ));
            if (!empty($categories) && !is_wp_error($categories)) {
                echo '<ul>';
                foreach ($categories as $cat) {
                    $icon = 'layout-grid';
                    $cat_slug = strtolower($cat->slug);
                    if (strpos($cat_slug, 'elect') !== false) $icon = 'monitor';
                    elseif (strpos($cat_slug, 'mobi') !== false || strpos($cat_slug, 'phone') !== false) $icon = 'smartphone';
                    elseif (strpos($cat_slug, 'watch') !== false) $icon = 'watch';
                    
                    echo '<li><a href="' . get_term_link($cat) . '"><div class="nav-label"><i data-lucide="' . $icon . '"></i><span>' . $cat->name . '</span></div></a></li>';
                }
                echo '</ul>';
            } else {
                 echo '<p class="menu-empty-msg" style="padding: 20px; text-align: center; color: var(--color-grey);">' . __('Add product categories to see them here.', 'viromarket') . '</p>';
            }
        }
    }
}
