/**
 * Column definition provided by the table.
 */
export interface DataTableColumnValue {
  /**
   * Resolved identifier of the column.
   */
  id: string

  /**
   * Display title of the column.
   */
  title: string

  /**
   * Type of the column, such as text.
   */
  type: string
}

/**
 * Cell value associated with a column.
 */
export interface DataTableCellValue {
  /**
   * Identifier of the column this cell belongs to.
   */
  column: string

  /**
   * Value resolved by the column.
   */
  value: unknown
}

/**
 * Table row with its cells and optional resource.
 */
export interface DataTableRowValue<ResourceValue = unknown> {
  /**
   * Key identifying the row within the table.
   */
  key: string | number

  /**
   * Cell values in column order.
   */
  cells: DataTableCellValue[]

  /**
   * Resource associated with the row, when provided by the backend.
   */
  resource?: ResourceValue | null
}

/**
 * Table data provided by the backend through Inertia.
 */
export interface DataTableValue<ResourceValue = unknown> {
  /**
   * Column definitions in display order.
   */
  columns: DataTableColumnValue[]

  /**
   * Rows retrieved from the table source.
   */
  rows: DataTableRowValue<ResourceValue>[]
}

/**
 * Columns and rows available to the table layout.
 */
export interface DataTableRootSlotProps<ResourceValue = unknown> {
  /**
   * Columns in their current display order.
   */
  columns: DataTableColumnValue[]

  /**
   * Rows in their current display order.
   */
  rows: DataTableRowValue<ResourceValue>[]
}

/**
 * Row and ordered cells available to the row layout.
 */
export interface DataTableRowSlotProps<ResourceValue = unknown> {
  /**
   * Row and its optional resource received from the backend.
   */
  row: DataTableRowValue<ResourceValue>

  /**
   * Cells in the current column order.
   */
  cells: DataTableCellValue[]
}
