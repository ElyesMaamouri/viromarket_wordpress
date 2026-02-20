<?php
/**
 * The template for displaying product content within loops (using template classes)
 *
 * @package ViroMarket
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}
?>

<article <?php wc_product_class( 'product-card', $product ); ?>>
    
    <!-- Product Image -->
    <div class="product-image-wrapper">
        <?php
        // Sale badge
        if ( $product->is_on_sale() ) {
            $percentage = '';
            if ( $product->get_regular_price() && $product->get_sale_price() ) {
                $percentage = round( ( ( floatval($product->get_regular_price()) - floatval($product->get_sale_price()) ) / floatval($product->get_regular_price()) ) * 100 );
                echo '<span class="badge-discount">' . $percentage . '% ' . __( 'OFF', 'viromarket' ) . '</span>';
            } else {
                echo '<span class="badge-discount">' . __( 'SALE', 'viromarket' ) . '</span>';
            }
        }
        ?>
        
        <a href="<?php the_permalink(); ?>">
            <?php echo $product->get_image( 'woocommerce_thumbnail' ); ?>
        </a>
    </div>

    <!-- Product Info -->
    <div class="product-info">
        <?php
        // Product Category & New Badge
        $categories = get_the_terms( $product->get_id(), 'product_cat' );
        
        // New badge logic
        $post_date = get_the_date( 'U' );
        $current_date = current_time( 'timestamp' );
        $days_old = ( $current_date - $post_date ) / DAY_IN_SECONDS;
        $is_new = ( $days_old < 30 );
        ?>
        
        <div class="category-new-row">
            <?php if ( $categories && ! is_wp_error( $categories ) ) : 
                $category = array_shift( $categories ); ?>
                <span class="product-category"><?php echo esc_html( $category->name ); ?></span>
            <?php endif; ?>
            
            <?php if ( $is_new ) : ?>
                <span class="badge-new-inline"><?php _e( 'New', 'viromarket' ); ?></span>
            <?php endif; ?>
        </div>

        <!-- Product Title -->
        <h3 class="product-title">
            <a href="<?php the_permalink(); ?>" class="stretched-link">
                <?php the_title(); ?>
            </a>
        </h3>

        <!-- Product Meta (Short Description) -->
        <?php if ( $product->get_short_description() ) : ?>
            <p class="product-meta"><?php echo wp_trim_words( $product->get_short_description(), 8 ); ?></p>
        <?php endif; ?>

        <!-- Product Rating -->
        <?php if ( $product->get_average_rating() ) : ?>
            <div class="product-rating">
                <div class="stars">
                    <?php
                    $rating = $product->get_average_rating();
                    $full_stars = floor( $rating );
                    $half_star = ( $rating - $full_stars ) >= 0.5;
                    
                    for ( $i = 0; $i < $full_stars; $i++ ) {
                        echo '<i data-lucide="star" class="star-filled"></i>';
                    }
                    
                    if ( $half_star ) {
                        echo '<i data-lucide="star-half" class="star-half"></i>';
                        $full_stars++;
                    }
                    
                    for ( $i = $full_stars; $i < 5; $i++ ) {
                        echo '<i data-lucide="star" class="star-empty"></i>';
                    }
                    ?>
                </div>
                <span class="rating-count">(<?php echo number_format( $rating, 1 ); ?>)</span>
            </div>
        <?php endif; ?>

        <!-- Product Price -->
        <div class="product-price">
            <?php if ( $product->is_on_sale() ) : ?>
                <span class="current-price"><?php echo wc_price( $product->get_sale_price() ); ?></span>
                <span class="old-price"><?php echo wc_price( $product->get_regular_price() ); ?></span>
            <?php else : ?>
                <span class="current-price"><?php echo $product->get_price_html(); ?></span>
            <?php endif; ?>
        </div>

        <!-- Product Actions -->
        <div class="product-actions">
            <button class="btn-wishlist" aria-label="<?php esc_attr_e('Add to wishlist', 'viromarket'); ?>">
                <i data-lucide="heart"></i>
            </button>
            <?php
            // Build the AJAX-compatible add to cart link directly
            // (bypassing woocommerce_loop_add_to_cart_link filter which can strip our classes)
            if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
                printf(
                    '<a href="%s" data-quantity="1" data-product_id="%s" data-product_sku="%s" aria-label="%s" class="add_to_cart_button ajax_add_to_cart btn-add-cart">
                        <i data-lucide="shopping-cart"></i><span>%s</span>
                    </a>',
                    esc_url( $product->add_to_cart_url() ),
                    esc_attr( $product->get_id() ),
                    esc_attr( $product->get_sku() ),
                    esc_attr( $product->add_to_cart_description() ),
                    esc_html( $product->add_to_cart_text() )
                );
            } else {
                // For variable/external products, use a regular link to the product page
                printf(
                    '<a href="%s" class="btn-add-cart"><i data-lucide="shopping-cart"></i><span>%s</span></a>',
                    esc_url( get_permalink( $product->get_id() ) ),
                    esc_html( $product->add_to_cart_text() )
                );
            }
            ?>
        </div>
    </div>

</article>
