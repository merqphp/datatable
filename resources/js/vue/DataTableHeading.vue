<template>
  <Primitive :ref="forwardRef" :as="as" :as-child="asChild" scope="col">
    <slot :column="column">{{ column.title }}</slot>
  </Primitive>
</template>

<script setup lang="ts">
import { Primitive, useForwardExpose } from 'reka-ui'
import type { DataTableHeadingProps } from './types'

withDefaults(defineProps<DataTableHeadingProps>(), {
  as: 'th',
})

defineSlots<{
  default?: (props: Pick<DataTableHeadingProps, 'column'>) => unknown
}>()

/**
 * Forward the rendered element through the component ref.
 */
const { forwardRef } = useForwardExpose()
</script>
