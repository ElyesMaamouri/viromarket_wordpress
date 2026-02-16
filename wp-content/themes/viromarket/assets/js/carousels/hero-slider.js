/*--------------------------------------------------------------
# Hero Swiper Logic
--------------------------------------------------------------*/
document.addEventListener('DOMContentLoaded', () => {
    const heroSwiper = new Swiper(".hero-swiper", {
        loop: false,
        speed: 800,
        effect: "fade", // Fade effect is more premium for hero banners
        fadeEffect: {
            crossFade: true
        },
        autoplay: false,
        pagination: {
            el: ".hero-swiper .swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".hero-swiper .swiper-button-next",
            prevEl: ".hero-swiper .swiper-button-prev",
        },
    });

    const promoSwiper = new Swiper(".promo-swiper", {
        loop: true,
        speed: 800,
        effect: "fade",
        fadeEffect: {
            crossFade: true
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".promo-swiper .swiper-pagination",
            clickable: true,
        },
        navigation: {
            nextEl: ".promo-swiper .swiper-button-next",
            prevEl: ".promo-swiper .swiper-button-prev",
        },
    });
});
