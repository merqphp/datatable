import { mergeProps } from '@base-ui/react/merge-props'
import { useRender } from '@base-ui/react/use-render'
import { useDataTableContext } from './context'
import type { DataTablePaginationButtonProps } from './types'

/**
 * Follow a server pagination URL through the table's Inertia navigation.
 */
function DataTablePaginationButton({
  destination,
  children,
  render,
  disabled: disabledProp,
  ...props
}: DataTablePaginationButtonProps & { destination: 'prevPageUrl' | 'nextPageUrl' }) {
  const { pagination, visit } = useDataTableContext()
  const href = pagination?.[destination] ?? null
  const disabled = disabledProp || !href

  return useRender({
    defaultTagName: 'button',
    enabled: pagination !== null,
    state: { disabled },
    render,
    props: mergeProps<'button'>(
      {
        type: 'button',
        disabled,
        'aria-disabled': disabled,
        onClick: (event) => {
          if (event.defaultPrevented) return

          event.preventDefault()

          if (!disabled && href) visit(href)
        },
        children:
          pagination &&
          (typeof children === 'function' ? children({ pagination, href, disabled }) : children),
      },
      props,
    ),
  })
}

/**
 * Render a control for the preceding page.
 */
export function DataTablePaginationPrevious(props: DataTablePaginationButtonProps) {
  return <DataTablePaginationButton {...props} destination="prevPageUrl" />
}

/**
 * Render a control for the following page.
 */
export function DataTablePaginationNext(props: DataTablePaginationButtonProps) {
  return <DataTablePaginationButton {...props} destination="nextPageUrl" />
}
