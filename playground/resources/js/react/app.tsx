import '../../css/app.css'
import '@merq/datatable/react'
import { createInertiaApp, type ResolvedComponent } from '@inertiajs/react'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createRoot } from 'react-dom/client'

createInertiaApp({
  resolve: (name) =>
    resolvePageComponent<ResolvedComponent>(
      `./pages/${name}.tsx`,
      import.meta.glob<ResolvedComponent>('./pages/**/*.tsx', { import: 'default' }),
    ),
  setup({ el, App, props }) {
    createRoot(el).render(<App {...props} />)
  },
})
