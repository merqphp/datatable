import { inject, provide, type InjectionKey, type ComputedRef } from 'vue'
import type { DataTableCellValue, DataTablePaginationValue } from '../shared'

/**
 * Internal operations shared by the root and its rows.
 */
interface DataTableContext {
  /**
   * Retrieve the ordered cells of a row.
   */
  getCells: (key: string | number) => DataTableCellValue[]

  /**
   * Pagination resolved by the latest Inertia response.
   */
  pagination: ComputedRef<DataTablePaginationValue | null>

  /**
   * Visit a pagination URL through Inertia.
   */
  visit: (url: string) => void

  /**
   * Change the page size and reset the current page.
   */
  setPerPage: (value: number) => void
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
    throw new Error('DataTable components must be used inside DataTableRoot.')
  }

  return context
}
