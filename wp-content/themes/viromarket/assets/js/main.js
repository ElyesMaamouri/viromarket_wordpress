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
            'openPagesMobile': 'mobileMenuPages'
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

        // View Toggles (Grid / List)
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

    });

})(jQuery);
