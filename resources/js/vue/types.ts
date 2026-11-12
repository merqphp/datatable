import type { PrimitiveProps } from 'reka-ui'
import type { Component } from 'vue'
import type {
  DataTableCellValue,
  DataTableColumnValue,
  DataTableRowValue,
  DataTableValue,
} from '../shared'

export type { DataTableRootSlotProps, DataTableRowSlotProps } from '../shared'

/**
 * Consumer components indexed by the names sent by the backend.
 */
export type DataTableComponents = Readonly<Record<string, Component>>

/**
 * Components registered for a Vue application.
 */
export interface DataTablePluginOptions {
  /**
   * Cell components supplied by the consumer.
   */
  components: DataTableComponents
}

/**
 * Cell whose registered component should be rendered.
 */
export interface DataTableCellContentProps {
  /**
   * Component name and props received from the backend.
   */
  cell: DataTableCellValue
}

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
export interface DataTableHeadingProps extends PrimitiveProps {
  /**
   * Column definition used to render the heading.
   */
  column: DataTableColumnValue
}

/**
 * Row represented by a table row element.
 */
export interface DataTableRowProps<ResourceValue = unknown> extends PrimitiveProps {
  /**
   * Row and its optional resource received from the backend.
   */
  row: DataTableRowValue<ResourceValue>
}

/**
 * Cell represented by a table cell element.
 */
export interface DataTableCellProps extends PrimitiveProps {
  /**
   * Cell value received from the backend.
   */
  cell: DataTableCellValue
}
