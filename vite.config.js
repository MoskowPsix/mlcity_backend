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
        vue({
            publicPath:
                process.env.APP_URL === 'production'
                    ? 'https://api-dev.vokrug.city/'
                    : '/',
        }),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
        }),
    ],
})
