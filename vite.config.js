import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    // server: {
    //     host: '0.0.0.0',
    //     hmr: {
    //         host:'192.168.0.39'
    //     },
    //     cors: {
    //         origin: ['http://192.168.0.39:8080', 'http://127.0.0.1:8000'],
    //         methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
    //         credentials: true
    //     }
    // },
});