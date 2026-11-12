import { DataTableRow as BaseDataTableRow, type DataTableRowProps } from '@merq/datatable/react'

/**
 * Style the headless table row with Tailwind utilities.
 */
export default function DataTableRow<ResourceValue = unknown>({
  className,
  ...props
}: DataTableRowProps<ResourceValue>) {
  return <BaseDataTableRow className={`border-b border-gray-200 ${className ?? ''}`} {...props} />
}
