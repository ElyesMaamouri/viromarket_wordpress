/**
 * Arrivals Carousel Module
 * Handles the new arrivals products swiper
 */

const arrivalsSwiper = new Swiper('.arrivals-swiper', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    speed: 600,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    navigation: {
        nextEl: '.arrivals-next',
        prevEl: '.arrivals-prev',
    },
    breakpoints: {
        476: {
            slidesPerView: 2,
            spaceBetween: 20,
        },
        992: {
            slidesPerView: 3,
            spaceBetween: 25,
        },
        1200: {
            slidesPerView: 4,
            spaceBetween: 30,
        },
    },
});
