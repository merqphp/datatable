import { useDataTableContext } from './context'
import type { DataTablePerPageProps } from './types'

/**
 * Provide the page size and update action to a consumer's control.
 */
export function DataTablePerPage({ children }: DataTablePerPageProps) {
  const { pagination, setPerPage } = useDataTableContext()

  if (!pagination || pagination.perPageOptions.length < 2) return null

  return children({
    modelValue: pagination.perPage,
    options: pagination.perPageOptions,
    update: setPerPage,
  })
}
