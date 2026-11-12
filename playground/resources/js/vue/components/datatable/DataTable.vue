<template>
  <DataTableRoot :table="table" v-slot="{ columns, rows }">
    <table class="w-full border-collapse text-left text-sm" v-bind="$attrs">
      <thead>
        <tr>
          <DataTableHeading v-for="column in columns" :key="column.id" :column="column" />
        </tr>
      </thead>
      <tbody>
        <DataTableRow v-for="row in rows" :key="row.key" :row="row" v-slot="{ cells }">
          <DataTableCell v-for="cell in cells" :key="cell.column" :cell="cell" />
        </DataTableRow>
      </tbody>
    </table>
  </DataTableRoot>
</template>

<script setup lang="ts" generic="ResourceValue = unknown">
import { DataTableRoot, type DataTableRootProps } from '@merq/datatable/vue'
import DataTableCell from './DataTableCell.vue'
import DataTableHeading from './DataTableHeading.vue'
import DataTableRow from './DataTableRow.vue'

defineProps<DataTableRootProps<ResourceValue>>()

defineOptions({ inheritAttrs: false })
</script>
