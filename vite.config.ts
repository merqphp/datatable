import vue from 'unplugin-vue/rolldown'
import { defineConfig } from 'vite-plus'
import { esmExternalRequirePlugin } from 'vite/rolldown/plugins'

export default defineConfig({
  fmt: {
    semi: false,
    singleQuote: true,
    tabWidth: 2,
    useTabs: false,
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
    onSuccess: 'vp fmt dist --no-error-on-unmatched-pattern',
    dts: {
      vue: true,
    },
    plugins: [
      esmExternalRequirePlugin({
        external: [/^react(?:\/|$)/, /^react-dom(?:\/|$)/, /^vue(?:\/|$)/],
      }),
      vue({ isProduction: true }),
    ],
    deps: {
      alwaysBundle: [/^@tanstack\//],
    },
  },
})
