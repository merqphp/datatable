<template>
  <Primitive
    v-if="pagination"
    :ref="forwardRef"
    v-bind="$attrs"
    :as="as"
    :as-child="asChild"
    type="button"
    :disabled="isDisabled"
    :aria-disabled="isDisabled"
    :data-disabled="isDisabled ? '' : undefined"
    @click="navigate"
  >
    <slot :pagination="pagination" :href="href" :disabled="isDisabled" />
  </Primitive>
</template>

<script setup lang="ts">
import { Primitive, useForwardExpose } from 'reka-ui'
import { computed } from 'vue'
import type { DataTablePaginationButtonSlotProps } from '../shared'
import { useDataTableContext } from './context'
import type { DataTablePaginationButtonProps } from './types'

defineOptions({ inheritAttrs: false })

const props = withDefaults(
  defineProps<
    DataTablePaginationButtonProps & {
      /**
       * Server URL selected by the navigation component.
       */
      destination: 'prevPageUrl' | 'nextPageUrl'
    }
  >(),
  { as: 'button' },
)

defineSlots<{
  default?: (props: DataTablePaginationButtonSlotProps) => unknown
}>()

/**
 * Destination and availability derived from the current server pagination.
 */
const { pagination, visit } = useDataTableContext()
const href = computed(() => pagination.value?.[props.destination] ?? null)
const isDisabled = computed(() => props.disabled || !href.value)

/**
 * Forward the rendered element through the component ref.
 */
const { forwardRef } = useForwardExpose()

/**
 * Follow an available page through Inertia unless the consumer cancels it.
 */
function navigate(event: MouseEvent) {
  if (event.defaultPrevented) return

  event.preventDefault()

  if (!isDisabled.value && href.value) visit(href.value)
}
</script>
