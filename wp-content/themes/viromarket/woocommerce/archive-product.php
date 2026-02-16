<?php
/**
 * The Template for displaying product archives with filters
 *
 * @package ViroMarket
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$currency_symbol = get_woocommerce_currency_symbol();

// Get dynamic prices from the database
global $wpdb;
$prices_query = $wpdb->get_col( "SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = '_price' AND meta_value != ''" );
$max_price_db = !empty($prices_query) ? ceil(max($prices_query)) : 10000;

// Safeguards
$min_price_slider = 0; // Always start from 0 per user requirement
$max_price_slider = ($max_price_db > 0) ? $max_price_db : 1000;
?>

<main>
    <div class="container-website page-content">
        
        <!-- Breadcrumbs -->
        <nav class="breadcrumbs" aria-label="Breadcrumb">
            <?php woocommerce_breadcrumb(); ?>
        </nav>

        <!-- Page Header -->
        <div class="page-header-simple">
            <?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
            <?php endif; ?>
        </div>

        <!-- Mobile Filter Overlay -->
        <div class="filter-overlay" id="filterOverlay"></div>

        <!-- Products Page Layout -->
        <div class="products-page-layout">
            
            <!-- Sidebar Filters -->
            <aside class="products-sidebar" id="productsSidebar">
                <div class="sidebar-header-mobile">
                    <h3><?php _e( 'Filters', 'viromarket' ); ?></h3>
                    <button id="closeFilters"><i data-lucide="x"></i></button>
                </div>
                
                <!-- Categories Filter -->
                <?php
                $product_categories = get_terms( array(
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => true,
                    'parent'     => 0,
                ) );
                
                if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) :
                    $current_term = get_queried_object();
                    $current_term_id = ( isset( $current_term->term_id ) && is_product_category() ) ? $current_term->term_id : 0;
                    $shop_link = get_permalink( wc_get_page_id( 'shop' ) );
                ?>
                <div class="filter-group">
                    <h3 class="filter-title">
                        <?php _e( 'Categories', 'viromarket' ); ?>
                        <i data-lucide="chevron-up"></i>
                    </h3>
                    <div class="filter-options">
                        <?php
                        $selected_cats = isset( $_GET['product_cat'] ) ? explode( ',', sanitize_text_field( $_GET['product_cat'] ) ) : array();
                        $is_all_checked = empty( $selected_cats ) && $current_term_id === 0;
                        ?>
                        <label class="filter-item">
                            <input type="checkbox" class="category-filter-checkbox reset-all" name="category" value="all" <?php checked( $is_all_checked ); ?>>
                            <span class="item-label"><?php _e( 'All', 'viromarket' ); ?></span>
                        </label>
                        <?php
                        $initial_cats = array_slice($product_categories, 0, 3);
                        $extra_cats = array_slice($product_categories, 3);

                        foreach ( $initial_cats as $category ) :
                            $is_active = in_array( $category->slug, $selected_cats ) || ( $current_term_id === $category->term_id );
                        ?>
                            <label class="filter-item">
                                <input type="checkbox" class="category-filter-checkbox" name="category" value="<?php echo esc_attr( $category->slug ); ?>" <?php checked( $is_active ); ?>>
                                <span class="item-label"><?php echo esc_html( $category->name ); ?></span>
                            </label>
                        <?php endforeach; ?>

                        <?php if ( ! empty( $extra_cats ) ) : ?>
                            <div class="view-more-container" id="categoryMore">
                                <?php foreach ( $extra_cats as $category ) :
                                    $is_active = in_array( $category->slug, $selected_cats ) || ( $current_term_id === $category->term_id );
                                ?>
                                    <label class="filter-item">
                                        <input type="checkbox" class="category-filter-checkbox" name="category" value="<?php echo esc_attr( $category->slug ); ?>" <?php checked( $is_active ); ?>>
                                        <span class="item-label"><?php echo esc_html( $category->name ); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <a href="javascript:void(0)" class="view-more-link" id="toggleCategoryMore"><?php _e( 'View All', 'viromarket' ); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Price Filter (Dual Range) -->
                <style>
                    .input-with-currency {
                        position: relative;
                        display: flex;
                        align-items: center;
                        background: #f8f9fa;
                        border: 1px solid #e5e7eb;
                        border-radius: 6px;
                        padding: 0 10px;
                        height: 40px;
                        overflow: hidden;
                    }
                    .input-with-currency span {
                        color: #6b7280;
                        font-size: 0.85rem;
                        margin-right: 5px;
                        white-space: nowrap;
                    }
                    .input-with-currency input {
                        border: none !important;
                        background: transparent !important;
                        padding: 0 !important;
                        margin: 0 !important;
                        width: 100%;
                        font-family: inherit;
                        font-size: 0.95rem;
                        color: #1f2937;
                        outline: none !important;
                        box-shadow: none !important;
                        min-width: 0;
                    }
                    /* Remove arrows from number input */
                    .input-with-currency input::-webkit-outer-spin-button,
                    .input-with-currency input::-webkit-inner-spin-button {
                        -webkit-appearance: none;
                        margin: 0;
                    }
                    .input-with-currency input[type=number] {
                        -moz-appearance: textfield;
                    }
                </style>
                <div class="filter-group">
                    <h3 class="filter-title"><?php _e( 'Price Range', 'viromarket' ); ?> <i data-lucide="chevron-up"></i></h3>
                    <div class="price-slider-container">
                        <div class="price-range-slider-wrapper">
                            <div class="slider-track" id="sliderTrack"></div>
                            <input type="range" class="min-range" min="<?php echo esc_attr($min_price_slider); ?>" max="<?php echo esc_attr($max_price_slider); ?>" value="<?php echo isset($_GET['min_price']) ? esc_attr($_GET['min_price']) : esc_attr($min_price_slider); ?>" id="slider-1" aria-label="Minimum Price">
                            <input type="range" class="max-range" min="<?php echo esc_attr($min_price_slider); ?>" max="<?php echo esc_attr($max_price_slider); ?>" value="<?php echo isset($_GET['max_price']) ? esc_attr($_GET['max_price']) : esc_attr($max_price_slider); ?>" id="slider-2" aria-label="Maximum Price">
                        </div>

                        <div class="range-inputs-styled">
                            <div class="input-field">
                                <label for="priceFrom"><?php _e( 'From', 'viromarket' ); ?></label>
                                <div class="input-with-currency">
                                    <span><?php echo esc_html( $currency_symbol ); ?></span>
                                    <input type="number" id="priceFrom" value="<?php echo isset($_GET['min_price']) ? esc_attr($_GET['min_price']) : esc_attr($min_price_slider); ?>" min="0" max="<?php echo esc_attr($max_price_slider); ?>">
                                </div>
                            </div>
                            <div class="input-field">
                                <label for="priceMax"><?php _e( 'To', 'viromarket' ); ?></label>
                                <div class="input-with-currency">
                                    <span><?php echo esc_html( $currency_symbol ); ?></span>
                                    <input type="number" id="priceTo" value="<?php echo isset($_GET['max_price']) ? esc_attr($_GET['max_price']) : esc_attr($max_price_slider); ?>" min="0" max="<?php echo esc_attr($max_price_slider); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Brands Filter (Dynamic from product_brand taxonomy) -->
                <?php
                $product_brands = get_terms( array(
                    'taxonomy'   => 'product_brand',
                    'hide_empty' => false,
                ) );
                
                if ( ! empty( $product_brands ) && ! is_wp_error( $product_brands ) ) :
                    $current_brand = get_queried_object();
                    $current_brand_id = ( isset( $current_brand->term_id ) && $current_brand->taxonomy === 'product_brand' ) ? $current_brand->term_id : 0;
                ?>
                <div class="filter-group">
                    <h3 class="filter-title"><?php _e( 'Brands', 'viromarket' ); ?> <i data-lucide="chevron-up"></i></h3>
                    <div class="filter-options">
                        <?php
                        $selected_brands = isset( $_GET['filter_brand'] ) ? explode( ',', sanitize_text_field( $_GET['filter_brand'] ) ) : array();
                        $is_all_brands = empty( $selected_brands ) && $current_brand_id === 0;
                        ?>
                        <label class="filter-item">
                            <input type="checkbox" class="brand-filter-checkbox reset-all" name="brand_filter" value="all" <?php checked( $is_all_brands ); ?>>
                            <span class="item-label"><?php _e( 'All Brands', 'viromarket' ); ?></span>
                        </label>
                        <?php foreach ( $product_brands as $brand ) : 
                            $is_active = in_array( $brand->slug, $selected_brands ) || ( $current_brand_id === $brand->term_id );
                        ?>
                        <label class="filter-item">
                            <input type="checkbox" class="brand-filter-checkbox" name="brand_filter" value="<?php echo esc_attr( $brand->slug ); ?>" <?php checked( $is_active ); ?>>
                            <span class="item-label"><?php echo esc_html( $brand->name ); ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="filter-actions-sidebar" style="margin-top: 25px; display: flex; flex-direction: column; gap: 10px;">
                    <button type="button" class="btn-apply-price" id="applyPriceFilter" style="width: 100%;">
                        <?php _e( 'Apply Filter', 'viromarket' ); ?>
                    </button>
                    <a href="<?php echo esc_url( $shop_link ); ?>" class="btn-reset-filters" style="width: 100%; text-align: center; justify-content: center;">
                        <i data-lucide="refresh-cw"></i>
                        <?php _e( 'Reset Filters', 'viromarket' ); ?>
                    </a>
                </div>

                <!-- Color Filter (Attributes пау pausing color filter as it requires pa_color) -->
                <?php
                $attribute_taxonomies = wc_get_attribute_taxonomies();
                $color_taxonomy = '';
                foreach ( $attribute_taxonomies as $tax ) {
                    if ( $tax->attribute_name == 'color' || $tax->attribute_name == 'colour' ) {
                        $color_taxonomy = wc_attribute_taxonomy_name( $tax->attribute_name );
                        break;
                    }
                }
                
                if ( $color_taxonomy ) :
                    $colors = get_terms( array( 'taxonomy' => $color_taxonomy, 'hide_empty' => true ) );
                    if ( ! empty( $colors ) && ! is_wp_error( $colors ) ) :
                        $current_color = get_queried_object();
                        $current_color_id = ( isset( $current_color->term_id ) && $current_color->taxonomy === $color_taxonomy ) ? $current_color->term_id : 0;
                ?>
                <div class="filter-group">
                    <h3 class="filter-title"><?php _e( 'Color', 'viromarket' ); ?> <i data-lucide="chevron-up"></i></h3>
                    <div class="filter-options">
                        <?php
                        $selected_colors = isset( $_GET['filter_color'] ) ? explode( ',', sanitize_text_field( $_GET['filter_color'] ) ) : array();
                        $is_all_colors = empty( $selected_colors ) && $current_color_id === 0;
                        ?>
                        <label class="filter-item">
                            <input type="checkbox" class="color-filter-checkbox reset-all" name="color_filter" value="all" <?php checked( $is_all_colors ); ?>>
                            <span class="item-label"><?php _e( 'All', 'viromarket' ); ?></span>
                        </label>
                        <?php foreach ( $colors as $color ) : 
                            $is_active = in_array( $color->slug, $selected_colors ) || ( $current_color_id === $color->term_id );
                        ?>
                        <label class="filter-item">
                            <input type="checkbox" class="color-filter-checkbox" name="color_filter" value="<?php echo esc_attr( $color->slug ); ?>" <?php checked( $is_active ); ?>>
                            <span class="item-label"><?php echo esc_html( $color->name ); ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; endif; ?>

                <?php 
                $show_banner = get_theme_mod( 'viromarket_show_sidebar_banner', true );
                if ( $show_banner ) :
                    $banner_img = get_theme_mod( 'viromarket_sidebar_banner_img', 'https://images.unsplash.com/photo-1542491595-62e922f81bd3?q=80&w=500&auto=format&fit=crop' );
                    $banner_label = get_theme_mod( 'viromarket_sidebar_banner_label', __( 'NEW ARRIVAL', 'viromarket' ) );
                    $banner_title = get_theme_mod( 'viromarket_sidebar_banner_title', __( 'Smart Watch Series 8', 'viromarket' ) );
                    $banner_link = get_theme_mod( 'viromarket_sidebar_banner_link', '#' );
                    $banner_bg = get_theme_mod( 'viromarket_sidebar_banner_bg_color', '#333333' );
                    $banner_txt = get_theme_mod( 'viromarket_sidebar_banner_text_color', '#ffffff' );
                ?>
                <div class="sidebar-banner" style="background-color: <?php echo esc_attr($banner_bg); ?>;">
                    <?php if ( $banner_img ) : ?>
                    <div class="banner-img-wrapper">
                        <img src="<?php echo esc_url( $banner_img ); ?>" alt="Promo">
                    </div>
                    <?php endif; ?>
                    <div class="banner-text">
                        <span style="color: <?php echo esc_attr($banner_txt); ?>; opacity: 0.8;"><?php echo esc_html( $banner_label ); ?></span>
                        <h4 style="color: <?php echo esc_attr($banner_txt); ?>;"><?php echo esc_html( $banner_title ); ?></h4>
                        <a href="<?php echo esc_url( $banner_link ); ?>" class="shop-now" style="color: var(--primary-color);"><?php _e( 'Shop Now', 'viromarket' ); ?></a>
                    </div>
                </div>
                <?php endif; ?>

            </aside>

            <!-- Main Products Section -->
            <section class="products-grid-section">
                
                <!-- Toolbar -->
                <div class="products-toolbar">
                    <div class="toolbar-left">
                        <div class="sort-dropdown-custom">
                            <span class="sort-label"><?php _e( 'Sort By:', 'viromarket' ); ?></span>
                            <?php
                            remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
                            do_action( 'woocommerce_before_shop_loop' );
                            ?>
                        </div>
                    </div>
                    <div class="toolbar-right">
                        <button class="btn-mobile-filter" id="openMobileFilters">
                            <i data-lucide="filter"></i>
                            <span><?php _e( 'Filters', 'viromarket' ); ?></span>
                        </button>
                        <?php
                        $current_view = isset( $_GET['view'] ) ? sanitize_text_field( $_GET['view'] ) : 'grid';
                        ?>
                        <div class="view-toggles">
                            <button class="view-btn <?php echo ( $current_view !== 'list' ) ? 'active' : ''; ?>" id="gridView" aria-label="Grid View">
                                <i data-lucide="grid"></i>
                            </button>
                            <button class="view-btn <?php echo ( $current_view === 'list' ) ? 'active' : ''; ?>" id="listView" aria-label="List View">
                                <i data-lucide="list"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <?php
                if ( woocommerce_product_loop() ) {
                    ?>
                    <div class="products-grid <?php echo ( $current_view === 'list' ) ? 'list-view' : ''; ?>" id="productGrid">
                        <?php
                        if ( wc_get_loop_prop( 'total' ) ) {
                            while ( have_posts() ) {
                                the_post();
                                do_action( 'woocommerce_shop_loop' );
                                wc_get_template_part( 'content', 'product' );
                            }
                        }
                        ?>
                    </div>
                    <?php
                    do_action( 'woocommerce_after_shop_loop' );
                } else {
                    ?>
                    <div class="no-results-container active" id="noResults">
                        <img src="<?php echo VIROMARKET_THEME_URI; ?>/assets/img/empty.svg" alt="No results found" class="no-results-icon">
                        <h2 class="no-results-title"><?php _e( 'No Products Found', 'viromarket' ); ?></h2>
                        <p class="no-results-text"><?php _e( 'We couldn\'t find any products matching your current filters. Try adjusting your selection or resetting the filters.', 'viromarket' ); ?></p>
                        <a href="<?php echo esc_url( $shop_link ); ?>" class="btn-reset-filters-empty">
                            <i data-lucide="rotate-ccw"></i>
                            <?php _e( 'Reset All Filters', 'viromarket' ); ?>
                        </a>
                    </div>
                    <?php
                }
                ?>

            </section>

        </div>
    </div>

    <!-- Newsletter & Apps Section (bottom side as in products.html) -->
    <section class="newsletter-apps-section" aria-label="Newsletter and mobile applications">
        <div class="container-website">
            <div class="newsletter-apps-grid">

                <div class="info-column">
                    <div class="icon-circle">
                        <i data-lucide="mail"></i>
                    </div>
                    <div class="newsletter-text">
                        <h2 class="column-title"><?php _e( 'Subscribe to Newsletter', 'viromarket' ); ?></h2>
                        <p class="subtitle"><?php _e( 'Join now and get 10% off on your next purchase!', 'viromarket' ); ?></p>
                    </div>
                </div>

                <div class="form-column">
                    <p class="notice"><?php _e( 'You can unsubscribe at any time', 'viromarket' ); ?></p>
                    <div class="newsletter-form">
                        <form action="#">
                            <input type="email" placeholder="<?php esc_attr_e( 'Enter your email address', 'viromarket' ); ?>" required="" aria-label="Email address">
                            <button type="submit" class="btn-subscribe"><?php _e( 'Subscribe', 'viromarket' ); ?></button>
                        </form>
                    </div>
                </div>

                <div class="apps-column">
                    <h3 class="apps-title"><?php _e( 'Mobile Apps', 'viromarket' ); ?></h3>
                    <div class="app-buttons">
                        <a href="#" class="app-btn-link">
                            <img src="<?php echo VIROMARKET_THEME_URI; ?>/assets/img/apple.svg" alt="App Store">
                        </a>
                        <a href="#" class="app-btn-link">
                            <img src="<?php echo VIROMARKET_THEME_URI; ?>/assets/img/google.svg" alt="Google Play">
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>

<?php
get_footer( 'shop' );
?>
