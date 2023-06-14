import { defineStore } from 'pinia'
import type { ISelectValue } from '@/types/ui'
import type { IFilterQueryParams } from '@/types/filter'
import { DEFAULT_SORT_VALUE } from '@/config/sort.config'
import { getQueryParams } from '~/renderer/utils'

interface IState {
  selected: ISelectValue
}

/**
 * Тип предложения
 */
export const useSortStore = defineStore('sort', {
  state: (): IState => ({
    selected: ''
  }),
  getters: {},
  actions: {
    setDefaultParams(params = getQueryParams<IFilterQueryParams>()) {
      const { sort = DEFAULT_SORT_VALUE } = params
      this.selected = sort
    }
  }
})
