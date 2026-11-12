import { DataTableComponentsContext } from './components'
import type { DataTableProviderProps } from './types'

/**
 * Provide consumer cell components to descendant tables.
 */
export function DataTableProvider({ components, children }: DataTableProviderProps) {
  return <DataTableComponentsContext value={components}>{children}</DataTableComponentsContext>
}
