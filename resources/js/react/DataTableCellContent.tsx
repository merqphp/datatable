import { useContext } from 'react'
import { resolveCellComponent } from '../shared/components'
import { DataTableComponentsContext } from './components'
import type { DataTableCellContentProps } from './types'

/**
 * Render the consumer component with the props supplied by its PHP column.
 */
export function DataTableCellContent({ cell }: DataTableCellContentProps) {
  /**
   * Cell components registered by the nearest provider.
   */
  const components = useContext(DataTableComponentsContext)

  /**
   * Consumer component matching the current cell definition.
   */
  const Component = resolveCellComponent(components, cell.component)

  return <Component {...cell.props} />
}
