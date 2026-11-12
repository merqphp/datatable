import { createContext } from 'react'
import type { DataTableComponents } from './types'

/**
 * Component registry scoped to the nearest provider.
 */
export const DataTableComponentsContext = createContext<DataTableComponents>({})
