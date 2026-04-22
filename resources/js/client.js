import 'swiper/css/bundle';
import { animate, createTimeline, stagger } from 'animejs';

const initClientScripts = () => {
    const navbar = document.querySelector('.navbar-tech');
    const searchToggle = document.querySelector('[data-search-toggle]');
    const searchPanel = document.querySelector('[data-search-panel]');

    if (navbar) {
        const updateNavbarState = () => {
            navbar.classList.toggle('is-scrolled', window.scrollY > 12);
        };

        updateNavbarState();
        window.addEventListener('scroll', updateNavbarState);
    }

    if (searchToggle && searchPanel) {
        searchToggle.addEventListener('click', () => {
            searchPanel.classList.toggle('is-open');
        });
    }

    if (document.querySelector('.tech-hero')) {
        createTimeline({ defaults: { ease: 'outExpo' } })
            .add({
                targets: '.tech-hero h1',
                y: [50, 0], opacity: [0, 1],
                duration: 1200, delay: 200
            })
            .add({
                targets: '.tech-hero p, .tech-hero .tech-badge, .tech-hero__metrics > div',
                y: [20, 0], opacity: [0, 1],
                duration: 900,
                delay: stagger(80)
            }, '-=800')
            .add({
                targets: '.tech-hero .btn-tech-primary, .tech-hero .btn-tech-secondary, .tech-float-card',
                scale: [0.9, 1], opacity: [0, 1],
                duration: 800, delay: stagger(100)
            }, '-=600');
    }

    if (document.querySelector('.product-card--tech')) {
        animate('.product-card--tech', {
            y: [60, 0], opacity: [0, 1],
            delay: stagger(60, { start: 250 }),
            ease: 'outQuad', duration: 800
        });
    }
};

// Handle Preloader Dismissal
window.addEventListener('load', () => {
    const preloader = document.getElementById('preloader');
    if (preloader) {
        preloader.classList.add('fade-out');
        // Ensure it is removed from DOM after animation
        setTimeout(() => {
            preloader.style.display = 'none';
        }, 600);
    }
});

// Application Launch
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initClientScripts);
} else {
    initClientScripts();
}
