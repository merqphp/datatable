import { router, usePage } from '@inertiajs/vue3'
import { onMounted, onScopeDispose } from 'vue'
import { createFilter, type FilterOptions } from '../shared/filter'

/**
 * Connect filter navigation to the current Inertia page and Vue lifecycle.
 */
export function useFilter(getOptions: () => Omit<FilterOptions, 'url'>) {
  const page = usePage()
  const filter = createFilter(router, () => ({ ...getOptions(), url: page.url }))
  let dispose: (() => void) | undefined

  onMounted(() => {
    dispose = filter.listen()
  })
  onScopeDispose(() => dispose?.())

  return filter
}
