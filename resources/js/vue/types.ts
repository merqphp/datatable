import type {
  DataTableCellValue,
  DataTableColumnValue,
  DataTableRowValue,
  DataTableValue,
} from '../shared'

export type { DataTableRootSlotProps, DataTableRowSlotProps } from '../shared'

/**
 * Data provided to the table root.
 */
export interface DataTableRootProps<ResourceValue = unknown> {
  /**
   * Table definition and rows received from the backend.
   */
  table: DataTableValue<ResourceValue>
}

/**
 * Column represented by a heading.
 */
export interface DataTableHeadingProps {
  /**
   * Column definition used to render the heading.
   */
  column: DataTableColumnValue
}

/**
 * Row represented by a table row element.
 */
export interface DataTableRowProps<ResourceValue = unknown> {
  /**
   * Row and its optional resource received from the backend.
   */
  row: DataTableRowValue<ResourceValue>
}

/**
 * Cell represented by a table cell element.
 */
export interface DataTableCellProps {
  /**
   * Cell value received from the backend.
   */
  cell: DataTableCellValue
}
