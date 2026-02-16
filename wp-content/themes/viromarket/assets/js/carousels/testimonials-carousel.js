/**
 * Testimonials Carousel Module
 * Handles the customer reviews/testimonials swiper
 */

const testimonialsSwiper = new Swiper('.testimonials-swiper', {
    slidesPerView: 1,
    spaceBetween: 20,
    loop: true,
    speed: 800,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    navigation: {
        nextEl: '.testimonials-next',
        prevEl: '.testimonials-prev',
    },
    breakpoints: {
        768: {
            slidesPerView: 2,
            spaceBetween: 25,
        },
        1200: {
            slidesPerView: 3,
            spaceBetween: 30,
        },
    },
});
