import { inject, type InjectionKey, type Plugin } from 'vue'
import type { DataTableComponents, DataTablePluginOptions } from './types'

/**
 * Context key isolating the component registry of each application.
 */
const dataTableComponentsKey: InjectionKey<DataTableComponents> = Symbol('DataTableComponents')

/**
 * Provide consumer cell components to a Vue application.
 */
export const DataTablePlugin: Plugin<[DataTablePluginOptions]> = {
  install(app, { components }) {
    app.provide(dataTableComponentsKey, components)
  },
}

/**
 * Retrieve the components registered for the current application.
 */
export function useDataTableComponents(): DataTableComponents {
  return inject(dataTableComponentsKey, {})
}
