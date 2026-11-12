<template>
  <DataTableRow v-bind="props" class="border-b border-gray-200" v-slot="slotProps">
    <slot v-bind="slotProps" />
  </DataTableRow>
</template>

<script setup lang="ts" generic="ResourceValue = unknown">
import {
  DataTableRow,
  type DataTableRowProps,
  type DataTableRowSlotProps,
} from '@merq/datatable/vue'

const props = defineProps<DataTableRowProps<ResourceValue>>()

defineSlots<{
  default: (props: DataTableRowSlotProps<ResourceValue>) => unknown
}>()
</script>
