import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/admin-rich-text.js',
                'resources/js/course-builder.js',
                'resources/js/course-programs.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
