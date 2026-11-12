<template>
  <component :is="component" v-bind="cell.props" />
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { resolveCellComponent } from '../shared/components'
import { useDataTableComponents } from './components'
import type { DataTableCellContentProps } from './types'

defineOptions({ inheritAttrs: false })

const props = defineProps<DataTableCellContentProps>()

/**
 * Cell components registered by the consumer application.
 */
const components = useDataTableComponents()

/**
 * Consumer component matching the current cell definition.
 */
const component = computed(() => resolveCellComponent(components, props.cell.component))
</script>
