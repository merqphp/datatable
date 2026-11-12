import {
  DataTableHeading as BaseDataTableHeading,
  type DataTableHeadingProps,
} from '@merq/datatable/react'

/**
 * Style the headless column heading with Tailwind utilities.
 */
export default function DataTableHeading({ className, ...props }: DataTableHeadingProps) {
  return (
    <BaseDataTableHeading
      className={`border-b-2 border-gray-300 px-4 py-3 font-semibold ${className ?? ''}`}
      {...props}
    />
  )
}
