import 'swiper/css/bundle';
import * as animeModule from 'animejs';
import { initStandardSwiper, initBrandsSwiper } from './helpers/swiper-init';

// Handle animejs v4 named export or default export
const anime = animeModule.anime || animeModule.default || animeModule;
window.anime = anime;

const initClientScripts = () => {
    console.log('--- K-Q Store Client Scripts Initializing ---');

    // 1. Initialize Carousels via Helpers
    initBrandsSwiper('.brands-swiper');
    
    initStandardSwiper('.featured-swiper', {
        slidesPerView: 1.5,
        autoplay: { delay: 3500 },
        breakpoints: {
            576: { slidesPerView: 2 },
            768: { slidesPerView: 3 },
            1200: { slidesPerView: 4 },
        }
    });

    // 2. Anime.js: Hero Animation
    if (document.querySelector('.hero-section')) {
        anime.timeline({ easing: 'easeOutExpo' })
            .add({
                targets: '.hero-section h1',
                translateY: [50, 0], opacity: [0, 1],
                duration: 1200, delay: 200
            })
            .add({
                targets: '.hero-section p',
                translateY: [20, 0], opacity: [0, 1],
                duration: 1000
            }, '-=800')
            .add({
                targets: '.hero-section .btn',
                scale: [0.9, 1], opacity: [0, 1],
                duration: 800, delay: anime.stagger(100)
            }, '-=600');
    }

    // 3. Anime.js: Featured Stagger
    if (document.querySelector('.animate-product-card')) {
        anime({
            targets: '.animate-product-card',
            translateY: [60, 0], opacity: [0, 1],
            delay: anime.stagger(80, { start: 600 }),
            easing: 'easeOutQuad', duration: 800
        });
    }
};

// Application Launch
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initClientScripts);
} else {
    initClientScripts();
}
