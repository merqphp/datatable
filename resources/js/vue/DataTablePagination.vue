<template>
  <Primitive v-if="pagination" :ref="forwardRef" :as="as" :as-child="asChild">
    <slot :pagination="pagination" />
  </Primitive>
</template>

<script setup lang="ts">
import { Primitive, useForwardExpose } from 'reka-ui'
import type { DataTablePaginationSlotProps } from '../shared'
import { useDataTableContext } from './context'
import type { DataTablePaginationProps } from './types'

withDefaults(defineProps<DataTablePaginationProps>(), { as: 'nav' })

defineSlots<{
  default: (props: DataTablePaginationSlotProps) => unknown
}>()

/**
 * Server pagination supplied by the nearest table root.
 */
const { pagination } = useDataTableContext()

/**
 * Forward the rendered element through the component ref.
 */
const { forwardRef } = useForwardExpose()
</script>
