import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [
                'resources/views/**',
                'app/Livewire/**',
                'app/View/Components/**',
                'routes/**',
            ],
        }),
        tailwindcss(),
    ],
    server: {
        host: '127.0.0.1', // ou '0.0.0.0' selon ton setup
        port: 5173,
        hmr: {
            host: 'localhost',
        },
    },
});
