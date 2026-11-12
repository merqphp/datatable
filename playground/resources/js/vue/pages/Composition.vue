<template>
  <main class="mx-auto max-w-5xl space-y-6 p-6 text-gray-950">
    <h1 class="text-2xl font-semibold">Component composition</h1>
    <DataTableRoot :table="compositionTable" v-slot="{ columns, rows }">
      <table aria-label="Composition" class="w-full border-collapse text-left text-sm">
        <thead>
          <tr>
            <DataTableHeading
              :column="columns[0]!"
              class="border-b-2 border-gray-300 px-4 py-3"
              as-child
              v-slot="{ column }"
            >
              <th data-composed="heading">{{ column.title }}</th>
            </DataTableHeading>
          </tr>
        </thead>
        <tbody>
          <DataTableRow
            :row="rows[0]!"
            :as="CustomRow"
            class="border-b border-gray-200"
            v-slot="{ cells }"
          >
            <DataTableCell
              ref="cellRef"
              :cell="cells[0]!"
              class="cursor-pointer px-4 py-3"
              tabindex="0"
              as-child
              @click="parentClicks++"
              v-slot="{ cell }"
            >
              <td ref="childRef" data-composed="cell" class="font-medium" @click="childClicks++">
                <strong>{{ cell.value }}</strong>
              </td>
            </DataTableCell>
          </DataTableRow>
        </tbody>
      </table>
    </DataTableRoot>
    <button
      type="button"
      class="rounded-md border border-gray-300 px-3 py-2 text-sm"
      @click="focusCell"
    >
      Focus cell
    </button>
    <p>Parent clicks: {{ parentClicks }}; child clicks: {{ childClicks }}</p>
    <p>{{ refsMatch ? 'Refs point to cell' : 'Refs unchecked' }}</p>
  </main>
</template>

<script setup lang="ts">
import { DataTableCell, DataTableHeading, DataTableRoot, DataTableRow } from '@merq/datatable/vue'
import { h, ref, useTemplateRef, type FunctionalComponent, type HTMLAttributes } from 'vue'
import { compositionTable } from '../../composition'

/**
 * Forward native attributes and slots from the headless row.
 */
const CustomRow: FunctionalComponent<HTMLAttributes> = (props, { slots }) =>
  h('tr', { ...props, 'data-composed': 'row' }, slots.default?.())

/**
 * Refs supplied to the headless component and its rendered child.
 */
const cellRef = useTemplateRef('cellRef')
const childRef = useTemplateRef('childRef')

/**
 * Observable results of composed handlers and ref forwarding.
 */
const parentClicks = ref(0)
const childClicks = ref(0)
const refsMatch = ref(false)

/**
 * Focus the composed cell through the public component ref.
 */
function focusCell() {
  refsMatch.value = childRef.value !== null && cellRef.value?.$el === childRef.value
  cellRef.value?.$el.focus()
}
</script>
