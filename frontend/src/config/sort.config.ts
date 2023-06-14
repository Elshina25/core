import type { ISelectOption } from '@/types/ui'

export const DEFAULT_SORT_VALUE = 'newest'

export const SORT_OPTIONS: ISelectOption[] = [
  { id: 'newest', name: 'По новизне' },
  { id: 'minPrice', name: 'По цене' },
  { id: 'buildingClass', name: 'По классу здания' },
  { id: 'grossArea', name: 'По свободной площади' }
]
