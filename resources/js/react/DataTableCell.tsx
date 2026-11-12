import { mergeProps } from '@base-ui/react/merge-props'
import { useRender } from '@base-ui/react/use-render'
import { DataTableCellContent } from './DataTableCellContent'
import type { DataTableCellProps } from './types'

/**
 * Render a cell with its registered component or custom content.
 */
export function DataTableCell({ cell, children, render, ...props }: DataTableCellProps) {
  return useRender({
    defaultTagName: 'td',
    render,
    props: mergeProps<'td'>(
      {
        children:
          typeof children === 'function' ? (
            children({ cell })
          ) : children === undefined ? (
            <DataTableCellContent cell={cell} />
          ) : (
            children
          ),
      },
      props,
    ),
  })
}
