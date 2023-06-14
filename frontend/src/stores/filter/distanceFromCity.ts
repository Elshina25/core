import { defineStore } from 'pinia'
import type { IFilterQueryParams } from '@/types/filter'
import { getQueryParams } from '~/renderer/utils'

interface IState {
  selected: string
}

/**
 * Метро
 */
export const useFilterDistanceFromCity = defineStore('filter-distance', {
  state: (): IState => ({
    selected: ''
  }),
  actions: {
    setDefaultParams(params = getQueryParams<IFilterQueryParams>()) {
      const { distance_from_city = '' } = params
      this.selected = distance_from_city
    }
  }
})
