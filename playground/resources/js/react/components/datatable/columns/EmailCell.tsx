/**
 * Display an email link using consumer-defined component props.
 */
export default function EmailCell({ label, href }: { label: string; href: string }) {
  return (
    <a href={href} className="underline underline-offset-4">
      {label}
    </a>
  )
}
