<template>
  <DataTablePagination
    v-slot="{ pagination }"
    aria-label="Table pagination"
    class="mt-4 flex flex-wrap items-center justify-between gap-4 text-sm"
  >
    <p>{{ pagination.from ?? 0 }}–{{ pagination.to ?? 0 }} of {{ pagination.total }} rows</p>
    <div class="flex items-center gap-4">
      <DataTablePerPage v-slot="{ modelValue, options, update }">
        <label class="flex items-center gap-2">
          Rows per page
          <select
            aria-label="Rows per page"
            class="rounded-md border border-gray-300 px-2 py-1.5"
            :value="modelValue"
            @change="update(Number(($event.target as HTMLSelectElement).value))"
          >
            <option v-for="option in options" :key="option" :value="option">
              {{ option }}
            </option>
          </select>
        </label>
      </DataTablePerPage>
      <span>Page {{ pagination.currentPage }} of {{ pagination.lastPage }}</span>
      <DataTablePaginationPrevious as-child>
        <button class="rounded-md border border-gray-300 px-3 py-1.5 disabled:opacity-40">
          Previous
        </button>
      </DataTablePaginationPrevious>
      <DataTablePaginationNext as-child>
        <button class="rounded-md border border-gray-300 px-3 py-1.5 disabled:opacity-40">
          Next
        </button>
      </DataTablePaginationNext>
    </div>
  </DataTablePagination>
</template>

<script setup lang="ts">
import {
  DataTablePagination,
  DataTablePaginationPrevious,
  DataTablePaginationNext,
  DataTablePerPage,
} from '@merq/datatable/vue'
</script>
