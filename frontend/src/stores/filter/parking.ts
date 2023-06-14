import { defineStore } from 'pinia'
import { getQueryParams } from '~/renderer/utils'
import type { IFilterQueryParams } from '@/types/filter'

interface IState {
  selected: number
}

/**
 * Общая площадь
 */
export const useFilterParkingStore = defineStore('filter-parking', {
  state: (): IState => ({
    selected: 0
  }),
  getters: {},
  actions: {
    setDefaultParams(params = getQueryParams<IFilterQueryParams>()) {
      const { parking = 0 } = params
      this.selected = +parking
    }
  }
})
