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
   * Name of the component registered by the consumer.
   */
  component: string

  /**
   * Props passed to the registered component.
   */
  props: Record<string, unknown>
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
 * Server pagination state and navigation for a table.
 */
export interface DataTablePaginationValue {
  /**
   * Current page, starting at one.
   */
  currentPage: number

  /**
   * Last available page, including an empty first page.
   */
  lastPage: number

  /**
   * Current number of rows per page.
   */
  perPage: number

  /**
   * Allowed page sizes, or an empty list for a fixed size.
   */
  perPageOptions: number[]

  /**
   * Total number of rows in the source.
   */
  total: number

  /**
   * First displayed row number, or null for an empty source.
   */
  from: number | null

  /**
   * Last displayed row number, or null for an empty source.
   */
  to: number | null

  /**
   * Query parameter identifying the current page.
   */
  pageName: string

  /**
   * Query parameter selecting the page size.
   */
  perPageName: string

  /**
   * URL of the first page when it precedes the current page.
   */
  firstPageUrl: string | null

  /**
   * URL of the previous page when available.
   */
  prevPageUrl: string | null

  /**
   * URL of the next page when available.
   */
  nextPageUrl: string | null

  /**
   * URL of the last page when it follows the current page.
   */
  lastPageUrl: string | null
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

  /**
   * Server pagination, or null when pagination is disabled or unavailable.
   */
  pagination: DataTablePaginationValue | null
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

/**
 * Server state available to the pagination layout.
 */
export interface DataTablePaginationSlotProps {
  /**
   * Pagination received from the latest Inertia response.
   */
  pagination: DataTablePaginationValue
}

/**
 * Page size and actions available to a consumer's control.
 */
export interface DataTablePerPageSlotProps {
  /**
   * Current number of rows per page.
   */
  modelValue: number

  /**
   * Page sizes allowed by the backend.
   */
  options: number[]

  /**
   * Apply a page size through Inertia and reset the page.
   */
  update: (value: number) => void
}

/**
 * Availability and destination of a pagination control.
 */
export interface DataTablePaginationButtonSlotProps extends DataTablePaginationSlotProps {
  /**
   * Server-provided destination, or null at the pagination boundary.
   */
  href: string | null

  /**
   * Whether the destination is unavailable.
   */
  disabled: boolean
}
