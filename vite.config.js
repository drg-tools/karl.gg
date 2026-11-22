import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

const commentsAssets = 'resources/js/comments';

export default defineConfig({
    resolve: {
        alias: {
            '@': fileURLToPath(new URL(commentsAssets, import.meta.url)),
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/builder.js',
                'resources/js/calculator.js',
                `${commentsAssets}/app.js`,
            ],
            refresh: true,
            // public/build would shadow the /build loadout builder route.
            buildDirectory: 'dist',
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
