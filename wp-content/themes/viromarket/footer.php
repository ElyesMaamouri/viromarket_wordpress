<?php
echo "<!-- FOOTER_PHP_LOADED -->";
/**
 * The template for displaying the footer
 *
 * @package ViroMarket
 */
?>

    <footer id="colophon" class="footer-section">
        <div class="footer-top">
            <div class="container-website">
                <div class="footer-grid">
                    
                    <!-- Column 1: About -->
                    <div class="footer-col">
                        <h4 class="footer-title"><?php _e( 'About Our Store', 'viromarket' ); ?></h4>
                        <p class="footer-desc">
                            <?php echo esc_html( get_theme_mod( 'viromarket_footer_about', __( 'Ecommerce store is one of the best stores that sell digital products at the best prices and international brands. Shop now and check more designs and enjoy the best offers and discounts.', 'viromarket' ) ) ); ?>
                        </p>
                    </div>

                    <!-- Column 2: Account -->
                    <div class="footer-col">
                        <h4 class="footer-title"><?php _e( 'Account', 'viromarket' ); ?></h4>
                        <ul class="footer-links">
                            <li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><span>»</span> <?php _e( 'My Account', 'viromarket' ); ?></a></li>
                            <li><a href="<?php echo esc_url( wc_get_endpoint_url( 'orders', '', wc_get_page_permalink( 'myaccount' ) ) ); ?>"><span>»</span> <?php _e( 'My Orders', 'viromarket' ); ?></a></li>
                            <li><a href="<?php echo esc_url( wc_get_cart_url() ); ?>"><span>»</span> <?php _e( 'Shopping Cart', 'viromarket' ); ?></a></li>
                            <li><a href="#"><span>»</span> <?php _e( 'Favorites', 'viromarket' ); ?></a></li>
                        </ul>
                    </div>

                    <!-- Column 3: Links -->
                    <div class="footer-col">
                        <h4 class="footer-title"><?php _e( 'Important Links', 'viromarket' ); ?></h4>
                        <ul class="footer-links">
                            <li><a href="#"><span>»</span> <?php _e( 'Who Are We', 'viromarket' ); ?></a></li>
                            <li><a href="#"><span>»</span> <?php _e( 'Privacy Policy', 'viromarket' ); ?></a></li>
                            <li><a href="#"><span>»</span> <?php _e( 'Terms & Conditions', 'viromarket' ); ?></a></li>
                            <li><a href="#"><span>»</span> <?php _e( 'Technical Support', 'viromarket' ); ?></a></li>
                        </ul>
                    </div>

                    <!-- Column 4: Contact -->
                    <div class="footer-col">
                        <h4 class="footer-title"><?php _e( 'Contact Us', 'viromarket' ); ?></h4>
                        <div class="contact-compact">
                            <div class="contact-row">
                                <span class="label">» <?php _e( 'Whatsapp :', 'viromarket' ); ?></span>
                                <span class="val"><?php echo esc_html( get_theme_mod( 'viromarket_whatsapp', '009612345678932' ) ); ?></span>
                            </div>
                            <div class="contact-row">
                                <span class="label">» <?php _e( 'Mobile :', 'viromarket' ); ?></span>
                                <span class="val"><?php echo esc_html( get_theme_mod( 'viromarket_phone', '009612345678932' ) ); ?></span>
                            </div>
                            <div class="contact-row">
                                <span class="label">» <?php _e( 'Email :', 'viromarket' ); ?></span>
                                <span class="val"><?php echo esc_html( get_theme_mod( 'viromarket_email', 'contact@ecommerce.com' ) ); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Column 5: Social -->
                    <div class="footer-col">
                        <h4 class="footer-title"><?php _e( 'Follow Us', 'viromarket' ); ?></h4>
                        <div class="social-circles">
                            <a href="#" class="social-icon fb" aria-label="Facebook"><i data-lucide="facebook"></i></a>
                            <a href="#" class="social-icon tw" aria-label="Twitter"><i data-lucide="twitter"></i></a>
                            <a href="#" class="social-icon ig" aria-label="Instagram"><i data-lucide="instagram"></i></a>
                            <a href="#" class="social-icon sc" aria-label="Snapchat"><i data-lucide="ghost"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom-theme">
            <div class="container-website">
                <div class="bottom-flex">
                    <p class="copyright-txt">
                        <?php printf( __( 'All Rights Reserved to %s Platform &copy; %s', 'viromarket' ), get_bloginfo( 'name' ), date( 'Y' ) ); ?>
                    </p>

                    <div class="footer-bottom-group">
                        <div class="tax-info">
                            <span class="tax-number"><?php _e( 'VAT Number :', 'viromarket' ); ?> 546987552</span>
                            <img src="<?php echo VIROMARKET_THEME_URI; ?>/assets/img/vat.jpg" alt="VAT" class="vat-badge">
                        </div>

                        <div class="payment-methods-footer">
                            <img src="https://flagcdn.com/w20/sa.png" alt="SAR">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer><!-- #colophon -->

    <!-- Cart Sidebar Overlay -->
    <div class="mobile-menu-overlay overlay-right" id="cartOverlay">
        <div class="mobile-menu-content">
            <div class="mobile-menu-header">
                <h3 class="section-title"><?php _e('Shopping Cart', 'viromarket'); ?></h3>
                <button class="close-menu-container" id="closeCart">
                    <span><?php _e('Close', 'viromarket'); ?></span>
                    <i data-lucide="x"></i>
                </button>
            </div>
            <div class="cart-overlay-body">
                <div class="widget_shopping_cart_content">
                    <?php woocommerce_mini_cart(); ?>
                </div>
            </div>
        </div>
    </div>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
