import { mergeProps } from '@base-ui/react/merge-props'
import { useRender } from '@base-ui/react/use-render'
import { useDataTableContext } from './context'
import type { DataTableRowProps } from './types'

/**
 * Render a row and provide its ordered cells to the layout.
 */
export function DataTableRow<ResourceValue = unknown>({
  row,
  children,
  render,
  ...props
}: DataTableRowProps<ResourceValue>) {
  /**
   * Operations provided by the enclosing table root.
   */
  const table = useDataTableContext()

  return useRender({
    defaultTagName: 'tr',
    render,
    props: mergeProps<'tr'>(
      {
        children:
          typeof children === 'function'
            ? children({ row, cells: table.getCells(row.key) })
            : children,
      },
      props,
    ),
  })
}
