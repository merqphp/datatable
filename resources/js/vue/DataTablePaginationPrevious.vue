<template>
  <DataTablePaginationButton :ref="forwardRef" v-bind="props" destination="prevPageUrl">
    <template #default="state"><slot v-bind="state" /></template>
  </DataTablePaginationButton>
</template>

<script setup lang="ts">
import { useForwardExpose } from 'reka-ui'
import type { DataTablePaginationButtonSlotProps } from '../shared'
import DataTablePaginationButton from './DataTablePaginationButton.vue'
import type { DataTablePaginationButtonProps } from './types'

const props = defineProps<DataTablePaginationButtonProps>()

defineSlots<{
  default?: (props: DataTablePaginationButtonSlotProps) => unknown
}>()

/**
 * Forward the rendered button through the component ref.
 */
const { forwardRef } = useForwardExpose()
</script>
