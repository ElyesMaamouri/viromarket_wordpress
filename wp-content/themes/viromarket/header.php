<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'viromarket'); ?></a>

    <header id="masthead" class="site-header" role="banner">
        
        <!-- Top Navigation Bar -->
        <div class="top-nav-container">
            <div class="container-website">
                <?php viromarket_contact_info(); ?>
                
                <nav class="page-info" aria-label="<?php esc_attr_e('Secondary navigation', 'viromarket'); ?>">
                    <?php if (has_nav_menu('account')) : ?>
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'account',
                            'container'      => false,
                            'menu_class'     => 'account-links',
                            'fallback_cb'    => false,
                        ));
                        ?>
                    <?php else : ?>
                        <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="link"><?php _e('Favorites', 'viromarket'); ?></a>
                        <a href="#" class="link"><?php _e('Exchange & Return Policy', 'viromarket'); ?></a>
                        <a href="#" class="link"><?php _e('Contact Us', 'viromarket'); ?></a>
                    <?php endif; ?>
                    
                    <div class="language-selector">
                        <span class="lang-label">العربية - رس</span>
                        <div class="flag-circle">
                            <img src="https://flagcdn.com/w80/sa.png" alt="Saudi Arabia Flag">
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Main Navigation -->
        <div class="main-nav-container container-website">
            <!-- Mobile Menu Toggle (Burger) -->
            <button class="menu-toggle mobile-menu-toggle" id="menuToggle" aria-label="<?php esc_attr_e('Open main menu', 'viromarket'); ?>">
                <i data-lucide="menu"></i>
            </button>

            <!-- Logo -->
            <div class="logo">
                <?php viromarket_site_logo(); ?>
            </div>

            <!-- Search Bar -->
            <?php viromarket_search_form(); ?>

            <!-- User Actions -->
            <div class="user-actions">
                <?php viromarket_user_account(); ?>
                <?php viromarket_cart_count(); ?>
            </div>
        </div>

        <!-- Categories Navigation Bar (Desktop) -->
        <nav class="categories-nav-bar" role="navigation" aria-label="<?php esc_attr_e('Main categories navigation', 'viromarket'); ?>">
            <div class="container-website">
                <?php viromarket_category_menu(); ?>
            </div>
        </nav>

    </header><!-- #masthead -->

    <!-- Mobile Menu: Profile Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuProfile">
        <div class="mobile-menu-content">
            <div class="mobile-menu-header">
                <button class="close-menu">
                    <i data-lucide="x"></i>
                </button>
                <h3 class="section-title"><?php _e('My Account', 'viromarket'); ?></h3>
            </div>
            <div class="mobile-menu-body">
                <?php if (is_user_logged_in()) : 
                    $current_user = wp_get_current_user();
                ?>
                    <div class="mobile-user-profile">
                        <div class="profile-info">
                            <div class="profile-avatar">
                                <?php echo get_avatar($current_user->ID, 150); ?>
                                <span class="online-status"></span>
                            </div>
                            <div class="profile-details">
                                <span class="welcome-msg"><?php _e('Welcome back', 'viromarket'); ?></span>
                                <h4 class="user-display-name"><?php echo esc_html($current_user->display_name); ?></h4>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <nav class="mobile-nav account-nav">
                    <?php if (has_nav_menu('account_mobile')) : ?>
                        <?php wp_nav_menu(array('theme_location' => 'account_mobile', 'container' => false, 'menu_class' => 'account-nav-list')); ?>
                    <?php else : ?>
                        <ul>
                            <li><a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>"><div class="nav-label"><i data-lucide="package"></i><span><?php _e('Orders', 'viromarket'); ?></span></div></a></li>
                            <li><a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>"><div class="nav-label"><i data-lucide="user"></i><span><?php _e('Profile Settings', 'viromarket'); ?></span></div></a></li>
                            <li><a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>"><div class="nav-label"><i data-lucide="heart"></i><span><?php _e('My Favorites', 'viromarket'); ?></span></div></a></li>
                            <?php if (is_user_logged_in()) : ?>
                                <li><a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" class="logout-link"><div class="nav-label"><i data-lucide="log-out"></i><span><?php _e('Logout', 'viromarket'); ?></span></div></a></li>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </div>

    <!-- Mobile Menu: Categories Overlay -->
    <div class="mobile-menu-overlay" id="mobileMenuCategories">
        <div class="mobile-menu-content">
            <div class="mobile-menu-header">
                <button class="close-menu">
                    <i data-lucide="x"></i>
                </button>
                <h3 class="section-title"><?php _e('All Categories', 'viromarket'); ?></h3>
            </div>
            <div class="mobile-menu-body">
                <nav class="mobile-nav main-nav-mobile">
                    <?php viromarket_mobile_menu(); ?>
                </nav>
            </div>
        </div>
    </div>

    <!-- Mobile Menu: Pages Overlay (Burger) -->
    <div class="mobile-menu-overlay" id="mobileMenuPages">
        <div class="mobile-menu-content">
            <div class="mobile-menu-header">
                <button class="close-menu">
                    <i data-lucide="x"></i>
                </button>
                <h3 class="section-title"><?php _e('Site Menu', 'viromarket'); ?></h3>
            </div>
            <div class="mobile-menu-body">
                <nav class="mobile-nav site-pages-nav">
                    <?php if (has_nav_menu('primary')) : ?>
                        <?php wp_nav_menu(array('theme_location' => 'primary', 'container' => false, 'menu_class' => 'site-pages-nav-list')); ?>
                    <?php else : ?>
                        <ul>
                            <li><a href="<?php echo esc_url(home_url('/')); ?>"><div class="nav-label"><i data-lucide="home"></i><span><?php _e('Home Page', 'viromarket'); ?></span></div></a></li>
                            <li><a href="#"><div class="nav-label"><i data-lucide="info"></i><span><?php _e('About Us', 'viromarket'); ?></span></div></a></li>
                            <li><a href="#"><div class="nav-label"><i data-lucide="mail"></i><span><?php _e('Contact Us', 'viromarket'); ?></span></div></a></li>
                        </ul>
                    <?php endif; ?>
                </nav>
            </div>
            <div class="mobile-menu-footer">
                <div class="footer-btns">
                    <div class="lang-currency">
                        <img src="https://flagcdn.com/w20/sa.png" alt="SAR">
                        <span><?php _e('Arabic - SAR', 'viromarket'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    <div class="mobile-bottom-nav">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="nav-item <?php echo is_front_page() ? 'active' : ''; ?>">
            <i data-lucide="home"></i>
            <span><?php _e('Home', 'viromarket'); ?></span>
        </a>
        <a href="javascript:void(0)" class="nav-item" id="openCategoriesMobile">
            <i data-lucide="layout-grid"></i>
            <span><?php _e('Categories', 'viromarket'); ?></span>
        </a>
        <a href="javascript:void(0)" class="nav-item" id="openProfileMobile">
            <i data-lucide="user"></i>
            <span><?php _e('Profile', 'viromarket'); ?></span>
        </a>
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="nav-item">
            <i data-lucide="shopping-cart"></i>
            <span><?php _e('Cart', 'viromarket'); ?></span>
        </a>
        <a href="javascript:void(0)" class="nav-item" id="openPagesMobile">
            <i data-lucide="menu"></i>
            <span><?php _e('Menu', 'viromarket'); ?></span>
        </a>
    </div>
