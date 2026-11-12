<template>
  <DataTableCell v-bind="props" class="px-4 py-3">
    <template v-if="$slots.default" #default="slotProps">
      <slot v-bind="slotProps" />
    </template>
  </DataTableCell>
</template>

<script setup lang="ts">
import { DataTableCell, type DataTableCellProps } from '@merq/datatable/vue'

const props = defineProps<DataTableCellProps>()

defineSlots<{
  default?: (props: Pick<DataTableCellProps, 'cell'>) => unknown
}>()
</script>
