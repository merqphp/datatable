import { tableFeatures, useTable } from '@tanstack/react-table'
import { useMemo } from 'react'
import type { DataTableCellValue, DataTableRowValue } from '../shared'
import { DataTableContext } from './context'
import type { DataTableRootProps } from './types'

/**
 * Core table capabilities used for rendering.
 */
const features = tableFeatures({})

/**
 * Provide the table model and resolved data to its descendants.
 */
export function DataTableRoot<ResourceValue = unknown>({
  table: value,
  children,
}: DataTableRootProps<ResourceValue>) {
  /**
   * Stable column accessors derived from the backend definitions.
   */
  const columnDefinitions = useMemo(
    () =>
      value.columns.map((column) => ({
        id: column.id,
        accessorFn: (row: DataTableRowValue<ResourceValue>) =>
          row.cells.find((cell) => cell.column === column.id),
      })),
    [value.columns],
  )

  /**
   * Core table model updated with the current backend payload.
   */
  const table = useTable<typeof features, DataTableRowValue<ResourceValue>>({
    features,
    columns: columnDefinitions,
    data: value.rows,
    getRowId: (row) => String(row.key),
  })

  /**
   * Backend definitions indexed by their column identifiers.
   */
  const definitions = useMemo(
    () => new Map(value.columns.map((column) => [column.id, column])),
    [value.columns],
  )

  /**
   * Column definitions in the order resolved by the table model.
   */
  const columns = table.getAllLeafColumns().map((column) => definitions.get(column.id)!)

  /**
   * Backend rows in the order resolved by the table model.
   */
  const rows = table.getRowModel().rows.map((row) => row.original)

  return (
    <DataTableContext
      value={{
        getCells: (key) =>
          table
            .getRow(String(key))
            .getAllCells()
            .map((cell) => cell.getValue<DataTableCellValue>()),
      }}
    >
      {typeof children === 'function' ? children({ columns, rows }) : children}
    </DataTableContext>
  )
}
