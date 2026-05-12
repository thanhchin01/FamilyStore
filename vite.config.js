import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                'resources/scss/app.scss', 
                'resources/css/client.scss', 
                'resources/js/app.js', 
                'resources/js/client.js',
                'resources/js/client/cart.js',
                
                // Page Specific Assets
                'resources/scss/client/pages/product-detail.scss',
                'resources/js/client/pages/product-detail.js',
                'resources/scss/client/pages/profile.scss',
                'resources/js/client/pages/profile.js',
                'resources/scss/client/pages/wishlist.scss',
                'resources/js/client/pages/wishlist.js'

            ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
