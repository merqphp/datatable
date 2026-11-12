import { createContext, useContext } from 'react'
import type { DataTableCellValue } from '../shared'

/**
 * Internal operations shared by the root and its rows.
 */
interface DataTableContextValue {
  /**
   * Retrieve the ordered cells of a row.
   */
  getCells: (key: string | number) => DataTableCellValue[]
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
    throw new Error('DataTableRow must be used inside DataTableRoot.')
  }

  return context
}
