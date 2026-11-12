/**
 * Shared table used to exercise custom elements and component composition.
 */
export const compositionTable = {
  columns: [{ id: 'name', title: 'Name', type: 'text' }],
  rows: [{ key: 'ada', cells: [{ column: 'name', value: 'Ada Lovelace' }] }],
}
