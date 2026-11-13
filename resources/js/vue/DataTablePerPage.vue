<template>
  <slot
    v-if="pagination && pagination.perPageOptions.length > 1"
    :model-value="pagination.perPage"
    :options="pagination.perPageOptions"
    :update="setPerPage"
  />
</template>

<script setup lang="ts">
import type { DataTablePerPageSlotProps } from '../shared'
import { useDataTableContext } from './context'

defineOptions({ inheritAttrs: false })

defineSlots<{
  default: (props: DataTablePerPageSlotProps) => unknown
}>()

/**
 * Server page size and its Inertia update action.
 */
const { pagination, setPerPage } = useDataTableContext()
</script>
