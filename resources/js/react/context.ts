import { createContext, useContext } from 'react'
import type { DataTableCellValue, DataTablePaginationValue } from '../shared'

/**
 * Internal operations shared by the root and its rows.
 */
interface DataTableContextValue {
  /**
   * Retrieve the ordered cells of a row.
   */
  getCells: (key: string | number) => DataTableCellValue[]

  /**
   * Pagination resolved by the latest Inertia response.
   */
  pagination: DataTablePaginationValue | null

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
 * Context isolating each table instance.
 */
export const DataTableContext = createContext<DataTableContextValue | null>(null)

/**
 * Retrieve the nearest table root context.
 */
export function useDataTableContext(): DataTableContextValue {
  const context = useContext(DataTableContext)

  if (!context) {
    throw new Error('DataTable components must be used inside DataTableRoot.')
  }

  return context
}
