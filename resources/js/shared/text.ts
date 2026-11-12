/**
 * Format a JSON cell value as plain text for either frontend.
 */
export function formatCellValue(value: unknown): string {
  if (value == null) {
    return ''
  }

  return typeof value === 'object' ? (JSON.stringify(value, null, 2) ?? '') : String(value)
}
