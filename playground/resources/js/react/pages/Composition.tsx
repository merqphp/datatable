import { DataTableCell, DataTableHeading, DataTableRoot, DataTableRow } from '@merq/datatable/react'
import { useRef, useState, type ComponentProps } from 'react'
import { compositionTable } from '../../composition'

/**
 * Forward native attributes and refs from the headless cell.
 */
function CustomCell(props: ComponentProps<'td'>) {
  return <td {...props} data-composed="cell" />
}

/**
 * Exercise element overrides, event composition and forwarded refs.
 */
export default function Composition() {
  /**
   * Refs supplied to the headless component and its rendered child.
   */
  const cellRef = useRef<HTMLTableCellElement>(null)
  const childRef = useRef<HTMLTableCellElement>(null)

  /**
   * Observable results of composed handlers and ref forwarding.
   */
  const [parentClicks, setParentClicks] = useState(0)
  const [childClicks, setChildClicks] = useState(0)
  const [refsMatch, setRefsMatch] = useState(false)

  /**
   * Focus the composed cell through the public component ref.
   */
  function focusCell() {
    setRefsMatch(cellRef.current !== null && cellRef.current === childRef.current)
    cellRef.current?.focus()
  }

  return (
    <main className="mx-auto max-w-5xl space-y-6 p-6 text-gray-950">
      <h1 className="text-2xl font-semibold">Component composition</h1>
      <DataTableRoot table={compositionTable}>
        {({ columns, rows }) => (
          <table aria-label="Composition" className="w-full border-collapse text-left text-sm">
            <thead>
              <tr>
                <DataTableHeading
                  column={columns[0]!}
                  className="border-b-2 border-gray-300 px-4 py-3"
                  render={<th data-composed="heading" />}
                />
              </tr>
            </thead>
            <tbody>
              <DataTableRow
                row={rows[0]!}
                className="border-b border-gray-200"
                render={(props) => <tr {...props} data-composed="row" />}
              >
                {({ cells }) => (
                  <DataTableCell
                    cell={cells[0]!}
                    ref={cellRef}
                    className="cursor-pointer px-4 py-3"
                    tabIndex={0}
                    onClick={() => setParentClicks((count) => count + 1)}
                    render={
                      <CustomCell
                        ref={childRef}
                        className="font-medium"
                        onClick={() => setChildClicks((count) => count + 1)}
                      />
                    }
                  >
                    {({ cell }) => <strong>{String(cell.value)}</strong>}
                  </DataTableCell>
                )}
              </DataTableRow>
            </tbody>
          </table>
        )}
      </DataTableRoot>
      <button
        type="button"
        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
        onClick={focusCell}
      >
        Focus cell
      </button>
      <p>
        Parent clicks: {parentClicks}; child clicks: {childClicks}
      </p>
      <p>{refsMatch ? 'Refs point to cell' : 'Refs unchecked'}</p>
    </main>
  )
}
