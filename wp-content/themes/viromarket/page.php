<?php
/**
 * The template for displaying all pages
 *
 * @package ViroMarket
 */

get_header();
echo "<!-- PAGE_PHP_LOADED -->";
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <?php
        while (have_posts()) :
            the_post();

            $is_woo_page = false;
            $current_id = get_the_ID();
            $is_cart_by_id = ($current_id == 8 || (function_exists('is_cart') && is_cart()));
            
            if ( $is_cart_by_id || (function_exists('is_checkout') && is_checkout()) || (function_exists('is_account_page') && is_account_page()) ) {
                $is_woo_page = true;
            }
            
            echo "<!-- DEBUG: IS_WOO_PAGE=" . ($is_woo_page ? 'TRUE' : 'FALSE') . " ID=" . $current_id . " -->";

            if (!$is_woo_page) :
                ?>
                <div class="container-website">
                    <header class="entry-header" style="margin-bottom: 40px; text-align: center;">
                        <?php the_title('<h1 class="entry-title" style="font-size: 2.5rem; font-weight: 900;">', '</h1>'); ?>
                    </header>
                </div>
                <?php
            endif;
            ?>

            <div class="entry-content">
                <?php
                if ( $is_cart_by_id ) {
                    echo do_shortcode('[woocommerce_cart]');
                } else {
                    the_content();
                }

                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'viromarket'),
                    'after'  => '</div>',
                ));
                ?>
            </div>

        <?php
        endwhile;
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer();
