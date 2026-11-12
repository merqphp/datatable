<template>
  <main class="mx-auto max-w-5xl space-y-6 p-6 text-gray-950">
    <Head title="Users — Vue" />
    <h1 class="text-2xl font-semibold">DataTable Playground</h1>
    <nav aria-label="Frontend" class="flex gap-4 text-sm">
      <a href="/react/users" class="text-gray-600 hover:text-gray-950">React</a>
      <a href="/vue/users" aria-current="page" class="font-semibold">Vue</a>
    </nav>

    <div class="flex items-center justify-between gap-4">
      <h2 class="text-lg font-semibold">Users</h2>
      <button
        type="button"
        class="rounded-md border border-gray-300 px-3 py-2 text-sm font-medium hover:bg-gray-50 focus-visible:outline-2 focus-visible:outline-offset-2"
        @click="router.reload({ only: ['users'] })"
      >
        Reload users
      </button>
    </div>

    <DataTable :table="users" aria-label="Users" />

    <p v-if="users.rows.length === 0" class="text-sm text-gray-500">No users yet.</p>
  </main>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import type { DataTableValue } from '@merq/datatable/vue'
import type { User } from '../../types'
import DataTable from '../components/datatable/DataTable.vue'

defineProps<{
  users: DataTableValue<User>
}>()
</script>
