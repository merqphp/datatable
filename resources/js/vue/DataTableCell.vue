<template>
  <Primitive :ref="forwardRef" :as="as" :as-child="asChild">
    <slot :cell="cell">
      <DataTableCellContent :cell="cell" />
    </slot>
  </Primitive>
</template>

<script setup lang="ts">
import { Primitive, useForwardExpose } from 'reka-ui'
import DataTableCellContent from './DataTableCellContent.vue'
import type { DataTableCellProps } from './types'

withDefaults(defineProps<DataTableCellProps>(), {
  as: 'td',
})

defineSlots<{
  default?: (props: Pick<DataTableCellProps, 'cell'>) => unknown
}>()

/**
 * Forward the rendered element through the component ref.
 */
const { forwardRef } = useForwardExpose()
</script>
