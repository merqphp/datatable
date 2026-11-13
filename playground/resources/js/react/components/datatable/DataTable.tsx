import { DataTableRoot, type DataTableValue } from '@merq/datatable/react'
import type { ComponentProps } from 'react'
import DataTableCell from './DataTableCell'
import DataTableHeading from './DataTableHeading'
import DataTableRow from './DataTableRow'
import DataTablePagination from './DataTablePagination'

/**
 * Consumer table data and native table attributes.
 */
interface DataTableProps<ResourceValue> extends Omit<ComponentProps<'table'>, 'children'> {
  /**
   * Table definition and rows received from the backend.
   */
  table: DataTableValue<ResourceValue>

  /**
   * Inertia properties to reload when navigating between pages.
   */
  reloadOnly?: string[]
}

/**
 * Compose the headless primitives into a Tailwind table.
 */
export default function DataTable<ResourceValue = unknown>({
  table,
  className,
  reloadOnly,
  ...props
}: DataTableProps<ResourceValue>) {
  return (
    <DataTableRoot table={table} reloadOnly={reloadOnly}>
      {({ columns, rows }) => (
        <>
          <table
            className={`w-full border-collapse text-left text-sm ${className ?? ''}`}
            {...props}
          >
            <thead>
              <tr>
                {columns.map((column) => (
                  <DataTableHeading key={column.id} column={column} />
                ))}
              </tr>
            </thead>
            <tbody>
              {rows.map((row) => (
                <DataTableRow key={row.key} row={row}>
                  {({ cells }) =>
                    cells.map((cell) => <DataTableCell key={cell.column} cell={cell} />)
                  }
                </DataTableRow>
              ))}
            </tbody>
          </table>
          <DataTablePagination />
        </>
      )}
    </DataTableRoot>
  )
}
