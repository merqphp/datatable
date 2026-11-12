import '../../css/app.css'
import { DataTablePlugin } from '@merq/datatable/vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createApp, h, type DefineComponent } from 'vue'
import TextCell from './components/datatable/columns/TextCell.vue'
import EmailCell from './components/datatable/columns/EmailCell.vue'

createInertiaApp({
  resolve: (name) =>
    resolvePageComponent<DefineComponent>(
      `./pages/${name}.vue`,
      import.meta.glob<DefineComponent>('./pages/**/*.vue', { import: 'default' }),
    ),
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(DataTablePlugin, {
        components: {
          'DataTable::Columns/Text': TextCell,
          'App::Columns/Email': EmailCell,
        },
      })
      .mount(el)
  },
})
