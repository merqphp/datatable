import { fileURLToPath, URL } from 'node:url'
import tailwindcss from '@tailwindcss/vite'
import react from '@vitejs/plugin-react'
import vue from '@vitejs/plugin-vue'
import laravel from 'laravel-vite-plugin'
import { defineConfig } from 'vite-plus'

export default defineConfig({
  plugins: [
    tailwindcss(),
    laravel({
      input: ['resources/js/react/app.tsx', 'resources/js/vue/app.ts'],
      refresh: ['app/Http/**', 'resources/views/**', 'routes/**'],
    }),
    react({ include: /\/react\/.*\.[jt]sx?$/ }),
    vue(),
    {
      // Avoid React refresh transforms in Vue SFCs until vitejs/vite-plugin-vue#798 is fixed.
      name: 'disable-oxc-jsx-refresh',
      enforce: 'post',
      config: () => ({ oxc: { jsx: { refresh: false } } }),
    },
  ],
  resolve: {
    alias: {
      '@merq/datatable/react': fileURLToPath(
        new URL('./vendor/merq/datatable/dist/react/index.js', import.meta.url),
      ),
      '@merq/datatable/vue': fileURLToPath(
        new URL('./vendor/merq/datatable/dist/vue/index.js', import.meta.url),
      ),
    },
    dedupe: ['react', 'react-dom', 'vue', '@inertiajs/core', '@inertiajs/react', '@inertiajs/vue3'],
  },
})
