import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    configureWebpack: {
        optimization: {
            splitChunks: {
                minSize: 10000,
                maxSize: 250000,
            },
        },
    },
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
        }),
    ],
})
