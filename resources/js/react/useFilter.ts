import { router, usePage } from '@inertiajs/react'
import { useEffect, useMemo, useRef } from 'react'
import { createFilter, type FilterOptions } from '../shared/filter'

/**
 * Connect filter navigation to the current Inertia page and React lifecycle.
 */
export function useFilter(options: Omit<FilterOptions, 'url'>) {
  const page = usePage()
  const current = useRef<FilterOptions>({ ...options, url: page.url })
  const filter = useMemo(() => createFilter(router, () => current.current), [])

  useEffect(() => {
    current.current = { ...options, url: page.url }
  })

  useEffect(() => filter.listen(), [filter])

  return filter
}
