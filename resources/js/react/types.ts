import type { useRender } from '@base-ui/react/use-render'
import type { ReactNode } from 'react'
import type {
  DataTableCellValue,
  DataTableColumnValue,
  DataTableRootSlotProps,
  DataTableRowSlotProps,
  DataTableRowValue,
  DataTableValue,
} from '../shared'

/**
 * Data and content provided to the table root.
 */
export interface DataTableRootProps<ResourceValue = unknown> {
  /**
   * Table definition and rows received from the backend.
   */
  table: DataTableValue<ResourceValue>

  /**
   * Table layout with access to the resolved columns and rows.
   */
  children: ReactNode | ((props: DataTableRootSlotProps<ResourceValue>) => ReactNode)
}

/**
 * Column definition, rendering options and native heading attributes.
 */
export interface DataTableHeadingProps extends Omit<useRender.ComponentProps<'th'>, 'children'> {
  /**
   * Column definition used to render the heading.
   */
  column: DataTableColumnValue

  /**
   * Custom heading content, falling back to the column title.
   */
  children?: ReactNode | ((props: { column: DataTableColumnValue }) => ReactNode)
}

/**
 * Row data, rendering options and native row attributes.
 */
export interface DataTableRowProps<ResourceValue = unknown> extends Omit<
  useRender.ComponentProps<'tr'>,
  'children'
> {
  /**
   * Row and its optional resource received from the backend.
   */
  row: DataTableRowValue<ResourceValue>

  /**
   * Row layout with access to its ordered cells.
   */
  children: ReactNode | ((props: DataTableRowSlotProps<ResourceValue>) => ReactNode)
}

/**
 * Cell value, rendering options and native cell attributes.
 */
export interface DataTableCellProps extends Omit<useRender.ComponentProps<'td'>, 'children'> {
  /**
   * Cell value received from the backend.
   */
  cell: DataTableCellValue

  /**
   * Custom cell content, falling back to its plain text value.
   */
  children?: ReactNode | ((props: { cell: DataTableCellValue }) => ReactNode)
}
