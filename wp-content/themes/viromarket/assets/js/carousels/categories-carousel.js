/**
 * Categories Carousel Module
 * Handles the categories swiper initialization (mobile only with autoplay)
 */

let categoriesSwiper;

function initCategoriesSwiper() {
    const isMobile = window.innerWidth < 992;

    if (isMobile && !categoriesSwiper) {
        categoriesSwiper = new Swiper('.categories-swiper', {
            slidesPerView: 2,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-next-cat',
                prevEl: '.swiper-prev-cat',
            },
            breakpoints: {
                576: { slidesPerView: 3 },
                768: { slidesPerView: 4 }
            }
        });
    } else if (!isMobile && categoriesSwiper) {
        categoriesSwiper.destroy(true, true);
        categoriesSwiper = undefined;
    }
}

// Init on load and resize
initCategoriesSwiper();
window.addEventListener('resize', initCategoriesSwiper);
