import {
  DataTablePagination as Pagination,
  DataTablePaginationPrevious,
  DataTablePaginationNext,
  DataTablePerPage,
} from '@merq/datatable/react'

/**
 * Compose the headless pagination controls with Tailwind styling.
 */
export default function DataTablePagination() {
  return (
    <Pagination
      aria-label="Table pagination"
      className="mt-4 flex flex-wrap items-center justify-between gap-4 text-sm"
    >
      {({ pagination }) => (
        <>
          <p>
            {pagination.from ?? 0}–{pagination.to ?? 0} of {pagination.total} rows
          </p>
          <div className="flex items-center gap-4">
            <DataTablePerPage>
              {({ modelValue, options, update }) => (
                <label className="flex items-center gap-2">
                  Rows per page
                  <select
                    aria-label="Rows per page"
                    className="rounded-md border border-gray-300 px-2 py-1.5"
                    value={modelValue}
                    onChange={(event) => update(Number(event.target.value))}
                  >
                    {options.map((option) => (
                      <option key={option} value={option}>
                        {option}
                      </option>
                    ))}
                  </select>
                </label>
              )}
            </DataTablePerPage>
            <span>
              Page {pagination.currentPage} of {pagination.lastPage}
            </span>
            <DataTablePaginationPrevious
              render={<button />}
              className="rounded-md border border-gray-300 px-3 py-1.5 disabled:opacity-40"
            >
              Previous
            </DataTablePaginationPrevious>
            <DataTablePaginationNext
              render={<button />}
              className="rounded-md border border-gray-300 px-3 py-1.5 disabled:opacity-40"
            >
              Next
            </DataTablePaginationNext>
          </div>
        </>
      )}
    </Pagination>
  )
}
