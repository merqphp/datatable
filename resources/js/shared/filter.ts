import type { CancelToken, FormDataConvertible, Router } from '@inertiajs/core'

/**
 * Current page and query keys managed by a filter.
 */
export interface FilterOptions {
  /**
   * Inertia URL retaining unrelated query parameters.
   */
  url: string

  /**
   * Inertia properties to reload, or all properties when omitted.
   */
  only?: string[]

  /**
   * Query keys cleared whenever the filter changes.
   */
  resetQuery: string[]
}

/**
 * Share filter navigation between the framework adapters.
 */
export function createFilter(router: Router, getOptions: () => FilterOptions) {
  /**
   * Pending filter update and active Inertia request.
   */
  let timeout: ReturnType<typeof setTimeout> | undefined
  let cancelToken: CancelToken | undefined

  /**
   * Discard an update that has not been submitted yet.
   */
  function clearPending() {
    clearTimeout(timeout)
    timeout = undefined
  }

  /**
   * Visit a page while preserving the consumer's state and scroll.
   */
  function visit(url: string, data: Record<string, FormDataConvertible> = {}) {
    clearPending()
    cancelToken?.cancel()

    router.get(url, data, {
      only: getOptions().only ?? [],
      preserveState: true,
      preserveScroll: true,
      onCancelToken: (token) => {
        cancelToken = token
      },
      onFinish: () => {
        cancelToken = undefined
      },
    })
  }

  /**
   * Submit filter data through Inertia after clearing dependent query keys.
   */
  function update(data: Record<string, FormDataConvertible>) {
    clearPending()
    cancelToken?.cancel()

    timeout = setTimeout(() => {
      const options = getOptions()
      const reset = Object.fromEntries(options.resetQuery.map((key) => [key, undefined]))

      visit(options.url, { ...data, ...reset })
    }, 50)
  }

  /**
   * Clear pending work on navigation and dispose it when the table unmounts.
   */
  function listen() {
    const unsubscribe = router.on('navigate', clearPending)
    const unsubscribeBefore = router.on('before', clearPending)

    return () => {
      unsubscribe()
      unsubscribeBefore()
      clearPending()
      cancelToken?.cancel()
    }
  }

  return { update, visit, listen }
}
