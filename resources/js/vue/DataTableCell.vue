<template>
  <Primitive :ref="forwardRef" :as="as" :as-child="asChild">
    <slot :cell="cell">{{ formatCellValue(cell.value) }}</slot>
  </Primitive>
</template>

<script setup lang="ts">
import { Primitive, useForwardExpose } from 'reka-ui'
import { formatCellValue } from '../shared/text'
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
