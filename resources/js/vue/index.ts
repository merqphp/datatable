export * from '../shared'

export { default as DataTableRoot } from './DataTableRoot.vue'
export { default as DataTableHeading } from './DataTableHeading.vue'
export { default as DataTableRow } from './DataTableRow.vue'
export { default as DataTableCell } from './DataTableCell.vue'
export { default as DataTableCellContent } from './DataTableCellContent.vue'
export { DataTablePlugin } from './components'
export { default as DataTablePagination } from './DataTablePagination.vue'
export { default as DataTablePaginationPrevious } from './DataTablePaginationPrevious.vue'
export { default as DataTablePaginationNext } from './DataTablePaginationNext.vue'
export { default as DataTablePerPage } from './DataTablePerPage.vue'

export type {
  DataTableRootProps,
  DataTableRootSlotProps,
  DataTableHeadingProps,
  DataTableRowProps,
  DataTableRowSlotProps,
  DataTableCellProps,
  DataTableCellContentProps,
  DataTableComponents,
  DataTablePluginOptions,
  DataTablePaginationProps,
  DataTablePaginationButtonProps,
} from './types'
