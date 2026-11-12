<template>
  <DataTableHeading v-bind="props" class="border-b-2 border-gray-300 px-4 py-3 font-semibold">
    <template v-if="$slots.default" #default="slotProps">
      <slot v-bind="slotProps" />
    </template>
  </DataTableHeading>
</template>

<script setup lang="ts">
import { DataTableHeading, type DataTableHeadingProps } from '@merq/datatable/vue'

const props = defineProps<DataTableHeadingProps>()

defineSlots<{
  default?: (props: Pick<DataTableHeadingProps, 'column'>) => unknown
}>()
</script>
