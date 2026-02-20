/**
 * ViroMarket Main Theme Scripts
 */
(function ($) {
    "use strict";

    // Debugging
    console.log('ViroMarket Scripts Starting...');

    // Function to safely initialize Lucide icons
    function createViroIcons() {
        if (typeof lucide !== 'undefined') {
            console.log('Lucide: Creating icons');
            lucide.createIcons();
        } else {
            console.log('Lucide: Waiting for library...');
            setTimeout(createViroIcons, 250);
        }
    }

    $(document).ready(function () {
        // Initial icon load
        createViroIcons();

        // Mobile Menu Interactivity
        const menuIds = {
            'menuToggle': 'mobileMenuPages',
            'openCategories': 'mobileMenuCategories',
            'openProfile': 'mobileMenuProfile',
            'openCategoriesMobile': 'mobileMenuCategories',
            'openProfileMobile': 'mobileMenuProfile',
            'openPagesMobile': 'mobileMenuPages',
            'openCart': 'cartOverlay',
            'openCartMobile': 'cartOverlay'
        };

        function toggleMenu(menuId, show) {
            if (show) {
                const $menu = $('#' + menuId);
                if ($menu.length) {
                    $menu.addClass('active');
                    $('body').addClass('no-scroll');
                }
            } else {
                $('.mobile-menu-overlay').removeClass('active');
                $('body').removeClass('no-scroll');
            }
        }

        // Bind clicks
        $.each(menuIds, function (btnId, menuId) {
            $(document).on('click', '#' + btnId, function (e) {
                e.preventDefault();
                toggleMenu(menuId, true);
            });
        });

        // Close buttons
        $(document).on('click', '.close-menu, .mobile-menu-overlay', function (e) {
            // Close if clicking overlay itself or close button
            if (e.target === this || $(this).hasClass('close-menu') || $(this).closest('.close-menu').length) {
                toggleMenu(null, false);
            }
        });

        // Custom Dropdown UI Enhancement
        function initCustomSelects() {
            $('.select-wrapper').each(function () {
                const $wrapper = $(this);
                const $select = $wrapper.find('select');
                if (!$select.length || $wrapper.hasClass('custom-select-init')) return;

                $wrapper.addClass('custom-select-init');
                const $current = $('<div class="select-selected"></div>');
                const $options = $('<div class="select-items select-hide"></div>');

                // Set initial text
                $current.text($select.find('option:selected').text());
                $wrapper.append($current);

                // Build options list
                $select.find('option').each(function () {
                    const $opt = $(this);
                    const $div = $('<div></div>').text($opt.text());
                    if ($opt.is(':selected')) $div.addClass('same-as-selected');

                    $div.on('click', function (e) {
                        e.stopPropagation();
                        $select.val($opt.val()).trigger('change');
                        $current.text($opt.text());
                        $div.siblings().removeClass('same-as-selected');
                        $div.addClass('same-as-selected');
                        $options.addClass('select-hide');
                        $wrapper.removeClass('select-arrow-active');
                    });
                    $options.append($div);
                });

                $wrapper.append($options);

                $current.on('click', function (e) {
                    e.stopPropagation();
                    $('.select-items').not($options).addClass('select-hide');
                    $('.select-wrapper').not($wrapper).removeClass('select-arrow-active');
                    $options.toggleClass('select-hide');
                    $wrapper.toggleClass('select-arrow-active');
                });
            });

            $(document).on('click', function () {
                $('.select-items').addClass('select-hide');
                $('.select-wrapper').removeClass('select-arrow-active');
            });
        }

        initCustomSelects();

        // Already exists: View Toggles (Grid / List)
        const $productGrid = $('#productGrid');
        const $viewBtns = $('.view-btn');

        function setProductView(view) {
            if (!$productGrid.length) return;

            if (view === 'list') {
                $productGrid.addClass('list-view');
                $('.view-btn#listView').addClass('active');
                $('.view-btn#gridView').removeClass('active');
            } else {
                $productGrid.removeClass('list-view');
                $('.view-btn#gridView').addClass('active');
                $('.view-btn#listView').removeClass('active');
            }

            // Sync URL
            const url = new URL(window.location.href);
            url.searchParams.set('view', view);
            window.history.replaceState({}, '', url);
        }

        $(document).on('click', '.view-btn', function () {
            const view = $(this).attr('id') === 'listView' ? 'list' : 'grid';
            setProductView(view);
        });

        // Mobile Filters Toggle
        $(document).on('click', '#openMobileFilters', function () {
            $('#productsSidebar, #filterOverlay').addClass('active');
            $('body').addClass('no-scroll');
        });

        $(document).on('click', '#closeFilters, #filterOverlay', function () {
            $('#productsSidebar, #filterOverlay').removeClass('active');
            $('body').removeClass('no-scroll');
        });

        // Price Slider
        const $s1 = $('#slider-1'), $s2 = $('#slider-2');
        const $f1 = $('#priceFrom'), $f2 = $('#priceTo');
        const $track = $('#sliderTrack');

        function updateSliderTrack() {
            if (!$s1.length || !$s2.length || !$track.length) return;
            const min = parseInt($s1.attr('min')), max = parseInt($s1.attr('max'));
            const v1 = parseInt($s1.val()), v2 = parseInt($s2.val());
            const p1 = ((v1 - min) / (max - min)) * 100;
            const p2 = ((v2 - min) / (max - min)) * 100;
            $track.css('background', 'linear-gradient(to right, #e5e7eb ' + p1 + '%, #62D0B6 ' + p1 + '%, #62D0B6 ' + p2 + '%, #e5e7eb ' + p2 + '%)');
            if (!$f1.is(':focus')) $f1.val(v1);
            if (!$f2.is(':focus')) $f2.val(v2);
        }

        if ($s1.length && $s2.length) {
            $s1.on('input', updateSliderTrack);
            $s2.on('input', updateSliderTrack);
            updateSliderTrack();
        }

        // Filter Logic
        $(document).on('change', '.category-filter-checkbox, .brand-filter-checkbox', function () {
            const cls = $(this).attr('class').split(' ')[0];
            if ($(this).hasClass('reset-all') && $(this).is(':checked')) {
                $('.' + cls).not('.reset-all').prop('checked', false);
            } else if ($(this).is(':checked')) {
                $('.' + cls + '.reset-all').prop('checked', false);
            }
        });

        $(document).on('click', '#applyPriceFilter', function () {
            const url = new URL(window.location.origin + window.location.pathname);
            const view = new URLSearchParams(window.location.search).get('view');
            if (view) url.searchParams.set('view', view);

            if ($s1.length) url.searchParams.set('min_price', $s1.val());
            if ($s2.length) url.searchParams.set('max_price', $s2.val());

            const getSel = (q) => {
                let s = [];
                $(q + ':checked').each(function () { if ($(this).val() !== 'all') s.push($(this).val()); });
                return s;
            };

            const cats = getSel('.category-filter-checkbox');
            if (cats.length) url.searchParams.set('product_cat', cats.join(','));

            const brands = getSel('.brand-filter-checkbox');
            if (brands.length) url.searchParams.set('filter_brand', brands.join(','));

            window.location.href = url.toString();
        });

        // Filter Groups Accordion
        $(document).on('click', '.filter-title', function () {
            $(this).next('.filter-options, .price-slider-container').slideToggle(200);
            $(this).find('svg, i').toggleClass('rotate-180');
        });

        // Category "View More"
        $(document).on('click', '#toggleCategoryMore', function () {
            const $extra = $('#categoryMore');
            $extra.toggleClass('show');
            const data = typeof viromarketData !== 'undefined' ? viromarketData : { viewAll: 'View All', viewLess: 'View Less' };
            $(this).text($extra.hasClass('show') ? data.viewLess : data.viewAll);
        });

        // --- WooCommerce Interactivity ---

        // 1. Auto-hide WooCommerce Notices
        function autoHideNotices() {
            const $notices = $('.woocommerce-message, .woocommerce-info, .woocommerce-error');
            if ($notices.length) {
                setTimeout(function () {
                    $notices.fadeOut(500, function () {
                        $(this).remove();
                    });
                }, 5000); // 5 seconds
            }
        }
        autoHideNotices();

        // Re-run auto-hide when AJAX happens (like add to cart or cart update)
        $(document.body).on('updated_wc_div added_to_cart updated_cart_totals', function () {
            autoHideNotices();
        });

        // 2. Sync Cart Count in Navbar after standard WooCommerce events
        $(document.body).on('added_to_cart removed_from_cart updated_cart_totals updated_wc_div', function () {
            console.log('Cart updated: refreshing Navbar');
            $(document.body).trigger('wc_fragment_refresh');
            autoHideNotices();
        });

        // Force refresh on cart page specifically when fragments are loaded
        $(document.body).on('wc_fragments_refreshed wc_fragments_loaded', function () {
            createViroIcons(); // Refresh icons if any new ones were added via fragments
        });

        // 2b. WooCommerce BLOCKS cart sync (no polling - polling caused session conflicts)
        // Only trigger a single fragment refresh after Blocks cart DOM settles
        if (document.body.classList.contains('woocommerce-cart')) {
            var blocksRefreshTimer = null;

            function syncNavAfterBlocksChange() {
                clearTimeout(blocksRefreshTimer);
                blocksRefreshTimer = setTimeout(function () {
                    $(document.body).trigger('wc_fragment_refresh');
                    console.log('WC Blocks: synced nav after cart change');
                }, 1000);
            }

            function observeBlocksCart() {
                var bc = document.querySelector('.wc-block-cart, .wp-block-woocommerce-cart, .wc-block-cart__items');
                if (bc) {
                    new MutationObserver(syncNavAfterBlocksChange).observe(bc, { childList: true, subtree: true });
                    return true;
                }
                return false;
            }

            if (!observeBlocksCart()) {
                var waitForBlocks = new MutationObserver(function (muts, obs) {
                    if (observeBlocksCart()) { obs.disconnect(); }
                });
                waitForBlocks.observe(document.body, { childList: true, subtree: true });
            }
        }


        // 3. AJAX Add to Cart for Single Product Page
        $(document).on('submit', 'form.cart', function (e) {
            if ($(this).closest('.product-details-container').length) {
                e.preventDefault();

                const $form = $(this);
                const $btn = $form.find('button[type="submit"]');

                $btn.addClass('loading').prop('disabled', true);

                $.ajax({
                    url: window.location.href,
                    data: $form.serialize(),
                    type: 'POST',
                    success: function (response) {
                        // Refresh fragments
                        $(document.body).trigger('wc_fragment_refresh');
                        $(document.body).trigger('added_to_cart');

                        // Show notice (if any in the response or just generic)
                        const $notices = $(response).find('.woocommerce-notices-wrapper').first();
                        if ($notices.length) {
                            $('.woocommerce-notices-wrapper').replaceWith($notices);
                            autoHideNotices();
                        }

                        $btn.removeClass('loading').prop('disabled', false);
                    },
                    error: function () {
                        window.location.reload();
                    }
                });
            }
        });

        // 4. Quantity buttons in cart (AJAX Update)
        $(document.on ? document : $(document)).on('click', '.cart-qty-btn', function (e) {
            e.preventDefault();
            const $btn = $(this);
            const $wrapper = $btn.closest('.cart-qty-selector');
            let $qtyInput = $wrapper.find('input.qty, input[name*="[qty]"], input[type="number"]').first();

            if (!$qtyInput.length) return;

            let val = parseInt($qtyInput.val()) || 0;
            const min = parseInt($qtyInput.attr('min')) || 0;
            const max = parseInt($qtyInput.attr('max')) || 9999;
            const step = parseInt($qtyInput.attr('step')) || 1;

            if ($btn.hasClass('plus')) {
                if (val < max) $qtyInput.val(val + step).trigger('change');
            } else {
                if (val > min) $qtyInput.val(val - step).trigger('change');
            }

            const $form = $btn.closest('form');
            if ($form.length) {
                clearTimeout(window.cartUpdateTimer);
                window.cartUpdateTimer = setTimeout(function () {
                    updateCartForm($form);
                }, 800);
            }
        });

        // Function to update cart via AJAX
        function updateCartForm($form) {
            const $container = $('.cart-page-section');
            if (!$container.length) return;

            $container.css({ 'opacity': '0.5', 'pointer-events': 'none' });

            $.ajax({
                type: 'POST',
                url: $form.attr('action') || window.location.href,
                data: $form.serialize() + '&update_cart=1',
                dataType: 'html',
                success: function (response) {
                    const $resp = $('<div>').html(response);
                    const $newContent = $resp.find('.cart-page-section');

                    if ($newContent.length) {
                        $('.cart-page-section').replaceWith($newContent);
                        $(document.body).trigger('wc_fragment_refresh');
                        $(document.body).trigger('updated_wc_div');
                        createViroIcons();
                        autoHideNotices();
                    } else {
                        window.location.reload();
                    }
                },
                error: function () {
                    window.location.reload();
                },
                complete: function () {
                    $('.cart-page-section').css({ 'opacity': '', 'pointer-events': '' });
                }
            });
        }

        // 5. AJAX Cart Item Removal
        $(document.on ? document : $(document)).on('click', '.remove-cart-item, .remove-item-cart, .woocommerce-cart-form .remove, .mini_cart_item .remove', function (e) {
            e.preventDefault();
            const $link = $(this);
            const removeUrl = $link.attr('href');
            if (!removeUrl || removeUrl === '#' || removeUrl === 'javascript:void(0)') return;

            const $row = $link.closest('.cart_item, .cart-item-mini, .mini_cart_item');
            const $container = $('.cart-page-section');

            $row.css({ 'opacity': '0.3', 'pointer-events': 'none' });
            if ($container.length) $container.css('pointer-events', 'none');

            $.ajax({
                type: 'GET',
                url: removeUrl,
                dataType: 'html',
                success: function (response) {
                    const $resp = $('<div>').html(response);

                    // Update Page Cart
                    const $newMainCart = $resp.find('.cart-page-section');
                    if ($newMainCart.length && $('.cart-page-section').length) {
                        $('.cart-page-section').replaceWith($newMainCart);
                    } else if ($('.cart-page-section').length) {
                        // If we are on cart page but response doesn't have the section, it might be empty now
                        window.location.reload();
                    }

                    // Force fragment refresh (updates mini-cart and counts)
                    $(document.body).trigger('wc_fragment_refresh');
                    $(document.body).trigger('removed_from_cart');

                    createViroIcons();
                    autoHideNotices();
                },
                error: function () {
                    window.location.reload();
                },
                complete: function () {
                    $('.cart_item, .cart-item-mini, .mini_cart_item').css({ 'opacity': '', 'pointer-events': '' });
                    $('.cart-page-section').css('pointer-events', '');
                }
            });
        });

        // 6. Handle manual "Update Cart" button
        $(document.on ? document : $(document)).on('submit', 'form.woocommerce-cart-form', function (e) {
            const $form = $(this);
            if ($form.find('input[name="update_cart"]').length || (e.originalEvent && $(e.originalEvent.submitter).hasClass('btn-update-cart'))) {
                e.preventDefault();
                updateCartForm($form);
            }
        });

        // 7. Explicitly handle fragment updates to avoid icons breaking or stale data
        $(document.body).on('wc_fragments_refreshed wc_fragments_loaded added_to_cart removed_from_cart', function () {
            createViroIcons();
            console.log('ViroMarket: Mini-cart and UI fragments updated');
        });

        // 8. Fix: Ensure fragments are loaded on first visit if they look empty but might not be
        const $cartContent = $('.widget_shopping_cart_content');
        if ($('.cart-count').first().text().trim() === '0' || ($cartContent.length && $.trim($cartContent.html()) === '')) {
            setTimeout(function () {
                $(document.body).trigger('wc_fragment_refresh');
            }, 600);
        }

        // 9. Close any overlay when clicking the close button specifically
        $(document).on('click', '#closeCart', function (e) {
            e.preventDefault();
            toggleMenu(null, false);
        });


    });
})(jQuery);
