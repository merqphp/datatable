import '../../css/app.css'
import { DataTableProvider, type DataTableComponents } from '@merq/datatable/react'
import { createInertiaApp, type ResolvedComponent } from '@inertiajs/react'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createRoot } from 'react-dom/client'
import TextCell from './components/datatable/columns/TextCell'
import EmailCell from './components/datatable/columns/EmailCell'

/**
 * Cell renderers owned by the playground application.
 */
const components = {
  'DataTable::Columns/Text': TextCell,
  'App::Columns/Email': EmailCell,
} satisfies DataTableComponents

createInertiaApp({
  resolve: (name) =>
    resolvePageComponent<ResolvedComponent>(
      `./pages/${name}.tsx`,
      import.meta.glob<ResolvedComponent>('./pages/**/*.tsx', { import: 'default' }),
    ),
  setup({ el, App, props }) {
    createRoot(el).render(
      <DataTableProvider components={components}>
        <App {...props} />
      </DataTableProvider>,
    )
  },
})
