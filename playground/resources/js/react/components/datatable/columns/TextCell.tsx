/**
 * Display a text value using the playground's formatting.
 */
export default function TextCell({ value }: { value: string | number | null }) {
  return <>{value ?? '—'}</>
}
