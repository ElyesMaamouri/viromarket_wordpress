/**
 * Brands Carousel Module
 * Handles the brands showcase swiper
 */

const brandsSwiper = new Swiper('.brands-swiper', {
    slidesPerView: 2,
    spaceBetween: 20,
    loop: true,
    speed: 800,
    autoplay: {
        delay: 4000,
        disableOnInteraction: false,
    },
    navigation: {
        nextEl: '.brands-next',
        prevEl: '.brands-prev',
    },
    breakpoints: {
        576: {
            slidesPerView: 3,
            spaceBetween: 20,
        },
        992: {
            slidesPerView: 4,
            spaceBetween: 25,
        },
        1200: {
            slidesPerView: 5,
            spaceBetween: 30,
        },
    },
});
