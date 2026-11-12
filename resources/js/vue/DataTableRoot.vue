<template>
  <slot :columns="columns" :rows="rows" />
</template>

<script setup lang="ts" generic="ResourceValue = unknown">
import { tableFeatures, useTable } from '@tanstack/vue-table'
import { computed } from 'vue'
import type { DataTableCellValue, DataTableRowValue } from '../shared'
import { provideDataTableContext } from './context'
import type { DataTableRootProps, DataTableRootSlotProps } from './types'

defineOptions({ inheritAttrs: false })

const props = defineProps<DataTableRootProps<ResourceValue>>()

defineSlots<{
  default: (props: DataTableRootSlotProps<ResourceValue>) => unknown
}>()

/**
 * Core table capabilities used for rendering.
 */
const features = tableFeatures({})

/**
 * Core table model built from the reactive backend payload.
 */
const table = useTable<typeof features, DataTableRowValue<ResourceValue>>({
  features,
  columns: computed(() =>
    props.table.columns.map((column) => ({
      id: column.id,
      accessorFn: (row: DataTableRowValue<ResourceValue>) =>
        row.cells.find((cell) => cell.column === column.id),
    })),
  ),
  data: computed(() => props.table.rows),
  getRowId: (row) => String(row.key),
})

/**
 * Backend definitions indexed by their column identifiers.
 */
const definitions = computed(
  () => new Map(props.table.columns.map((column) => [column.id, column])),
)

/**
 * Column definitions in the order resolved by the table model.
 */
const columns = computed(() =>
  table.getAllLeafColumns().map((column) => definitions.value.get(column.id)!),
)

/**
 * Backend rows in the order resolved by the table model.
 */
const rows = computed(() => table.getRowModel().rows.map((row) => row.original))

provideDataTableContext({
  getCells: (key) =>
    table
      .getRow(String(key))
      .getAllCells()
      .map((cell) => cell.getValue<DataTableCellValue>()),
})
</script>
