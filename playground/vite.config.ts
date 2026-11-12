import { fileURLToPath, URL } from 'node:url'
import react from '@vitejs/plugin-react'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'
import { defineConfig } from 'vite-plus'

export default defineConfig({
    fmt: {
        semi: false,
    },
    plugins: [
        laravel({
            input: ['resources/js/react/app.tsx', 'resources/js/vue/app.ts'],
            refresh: ['app/Http/**', 'resources/views/**', 'routes/**'],
        }),
        react(),
        vue(),
    ],
    resolve: {
        alias: {
            '@merq/datatable/react': fileURLToPath(new URL('./vendor/merq/datatable/dist/react/index.js', import.meta.url)),
            '@merq/datatable/vue': fileURLToPath(new URL('./vendor/merq/datatable/dist/vue/index.js', import.meta.url)),
        },
        dedupe: ['react', 'react-dom', 'vue'],
    },
})
