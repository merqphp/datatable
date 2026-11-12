export * from '../shared'

export { default as DataTableRoot } from './DataTableRoot.vue'
export { default as DataTableHeading } from './DataTableHeading.vue'
export { default as DataTableRow } from './DataTableRow.vue'
export { default as DataTableCell } from './DataTableCell.vue'
export { default as DataTableCellContent } from './DataTableCellContent.vue'
export { DataTablePlugin } from './components'

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
} from './types'
