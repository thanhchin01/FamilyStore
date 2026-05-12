import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/scss/admin/app.scss',
                'resources/js/admin/app.js',
                'resources/js/sale.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'public/build-admin',
        emptyOutDir: true,
    }
});
