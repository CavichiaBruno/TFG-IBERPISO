import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/css/pages/listing.css',
                'resources/js/pages/listing.js',
                'resources/css/pages/scroll.css',
                'resources/js/pages/scroll.js',
                'resources/css/pages/saved.css',
                'resources/css/pages/detail.css',
                'resources/js/pages/detail.js'
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
