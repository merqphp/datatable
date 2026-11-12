/**
 * Shared table used to exercise custom elements and component composition.
 */
export const compositionTable = {
  columns: [{ id: 'name', title: 'Name' }],
  rows: [
    {
      key: 'ada',
      cells: [
        {
          column: 'name',
          component: 'DataTable::Columns/Text',
          props: { value: 'Ada Lovelace' },
        },
      ],
    },
  ],
}
