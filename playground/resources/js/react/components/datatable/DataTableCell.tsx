import { DataTableCell as BaseDataTableCell, type DataTableCellProps } from '@merq/datatable/react'

/**
 * Style the headless table cell with Tailwind utilities.
 */
export default function DataTableCell({ className, ...props }: DataTableCellProps) {
  return <BaseDataTableCell className={`px-4 py-3 ${className ?? ''}`} {...props} />
}
