import { inject, provide, type InjectionKey } from 'vue'
import type { DataTableCellValue } from '../shared'

/**
 * Internal operations shared by the root and its rows.
 */
interface DataTableContext {
  /**
   * Retrieve the ordered cells of a row.
   */
  getCells: (key: string | number) => DataTableCellValue[]
}

/**
 * Context key isolating each table instance.
 */
const dataTableContextKey: InjectionKey<DataTableContext> = Symbol('DataTable')

/**
 * Provide table operations to descendant components.
 */
export function provideDataTableContext(context: DataTableContext): void {
  provide(dataTableContextKey, context)
}

/**
 * Retrieve the nearest table root context.
 */
export function useDataTableContext(): DataTableContext {
  const context = inject(dataTableContextKey)

  if (!context) {
    throw new Error('DataTableRow must be used inside DataTableRoot.')
  }

  return context
}
