import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/client.scss', 
                'resources/scss/admin/app.scss', 
                'resources/js/app.js', 
                'resources/js/sale.js',
                'resources/js/client.js',
                'resources/js/admin/app.js'
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
