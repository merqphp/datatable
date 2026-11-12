import { mergeProps } from '@base-ui/react/merge-props'
import { useRender } from '@base-ui/react/use-render'
import type { DataTableHeadingProps } from './types'

/**
 * Render a column heading with optional custom content.
 */
export function DataTableHeading({ column, children, render, ...props }: DataTableHeadingProps) {
  return useRender({
    defaultTagName: 'th',
    render,
    props: mergeProps<'th'>(
      {
        scope: 'col',
        children:
          typeof children === 'function'
            ? children({ column })
            : children === undefined
              ? column.title
              : children,
      },
      props,
    ),
  })
}
