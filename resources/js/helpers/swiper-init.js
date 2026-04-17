import Swiper from 'swiper/bundle';

/**
 * Common Swiper Initialization Helper
 * Handles standard slider settings for the store
 */
export const initStandardSwiper = (selector, customOptions = {}) => {
    const element = document.querySelector(selector);
    if (!element) return null;

    const defaultOptions = {
        slidesPerView: 1.2,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            dynamicBullets: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            576: { slidesPerView: 2 },
            768: { slidesPerView: 3 },
            1200: { slidesPerView: 4 },
        },
    };

    return new Swiper(selector, { ...defaultOptions, ...customOptions });
};

/**
 * Initialize Brands Carousel with standard settings
 */
export const initBrandsSwiper = (selector = '.brands-swiper') => {
    return initStandardSwiper(selector, {
        slidesPerView: 2,
        navigation: false,
        pagination: false,
        breakpoints: {
            640: { slidesPerView: 3 },
            768: { slidesPerView: 4 },
            1024: { slidesPerView: 6 },
        },
    });
};
