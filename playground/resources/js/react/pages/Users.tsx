import { Head, router } from '@inertiajs/react'
import type { DataTableValue } from '@merq/datatable/react'
import type { User } from '../../types'
import DataTable from '../components/datatable/DataTable'

export default function Users({ users }: { users: DataTableValue<User> }) {
  return (
    <main className="mx-auto max-w-5xl space-y-6 p-6 text-gray-950">
      <Head title="Users — React" />
      <h1 className="text-2xl font-semibold">DataTable Playground</h1>
      <nav aria-label="Frontend" className="flex gap-4 text-sm">
        <a href="/react/users" aria-current="page" className="font-semibold">
          React
        </a>
        <a href="/vue/users" className="text-gray-600 hover:text-gray-950">
          Vue
        </a>
      </nav>

      <div className="flex items-center justify-between gap-4">
        <h2 className="text-lg font-semibold">Users</h2>
        <button
          type="button"
          className="rounded-md border border-gray-300 px-3 py-2 text-sm font-medium hover:bg-gray-50 focus-visible:outline-2 focus-visible:outline-offset-2"
          onClick={() => router.reload({ only: ['users'] })}
        >
          Reload users
        </button>
      </div>

      <DataTable table={users} aria-label="Users" />

      {users.rows.length === 0 && <p className="text-sm text-gray-500">No users yet.</p>}
    </main>
  )
}
