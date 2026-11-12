<template>
  <Primitive :ref="forwardRef" :as="as" :as-child="asChild">
    <slot :row="row" :cells="cells" />
  </Primitive>
</template>

<script setup lang="ts" generic="ResourceValue = unknown">
import { Primitive, useForwardExpose } from 'reka-ui'
import { computed } from 'vue'
import { useDataTableContext } from './context'
import type { DataTableRowProps, DataTableRowSlotProps } from './types'

const props = withDefaults(defineProps<DataTableRowProps<ResourceValue>>(), {
  as: 'tr',
})

defineSlots<{
  default: (props: DataTableRowSlotProps<ResourceValue>) => unknown
}>()

/**
 * Forward the rendered element through the component ref.
 */
const { forwardRef } = useForwardExpose()

/**
 * Operations provided by the enclosing table root.
 */
const table = useDataTableContext()

/**
 * Cells resolved from the current row and table definition.
 */
const cells = computed(() => table.getCells(props.row.key))
</script>
