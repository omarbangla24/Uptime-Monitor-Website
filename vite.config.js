import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', // ✅ add your page script(s)
        'resources/js/pages/blog-create.js'],

            refresh: true,
        }),
    ],
});
