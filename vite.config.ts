import vue from 'unplugin-vue/rolldown'
import { defineConfig } from 'vite-plus'

export default defineConfig({
    fmt: {
        semi: false,
    },
    pack: {
        entry: {
            'react/index': 'resources/js/react/index.ts',
            'vue/index': 'resources/js/vue/index.ts',
        },
        outDir: 'dist',
        format: 'esm',
        platform: 'neutral',
        target: 'es2022',
        clean: true,
        dts: {
            tsc: { vue: true },
        },
        plugins: [vue({ isProduction: true })],
        deps: {
            neverBundle: [/^react(?:\/|$)/, /^react-dom(?:\/|$)/, /^vue(?:\/|$)/],
        },
    },
})
