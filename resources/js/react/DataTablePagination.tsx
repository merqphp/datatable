import { mergeProps } from '@base-ui/react/merge-props'
import { useRender } from '@base-ui/react/use-render'
import { useDataTableContext } from './context'
import type { DataTablePaginationProps } from './types'

/**
 * Render a pagination layout when the table provides pagination.
 */
export function DataTablePagination({ children, render, ...props }: DataTablePaginationProps) {
  const { pagination } = useDataTableContext()

  return useRender({
    defaultTagName: 'nav',
    enabled: pagination !== null,
    render,
    props: mergeProps<'nav'>(
      {
        children:
          pagination && (typeof children === 'function' ? children({ pagination }) : children),
      },
      props,
    ),
  })
}
