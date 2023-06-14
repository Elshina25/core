import { defineStore } from 'pinia'
import { getQueryParams } from '~/renderer/utils'
import type { IFilterQueryParams } from '@/types/filter'

interface IState {
  start: string
  end: string
}

/**
 * Общая площадь
 */
export const useFilterFloorStore = defineStore('filter-floor', {
  state: (): IState => ({
    start: '',
    end: ''
  }),
  getters: {},
  actions: {
    setDefaultParams(params = getQueryParams<IFilterQueryParams>()) {
      const { floor_start = '', floor_end = '' } = params
      this.start = floor_start
      this.end = floor_end
    }
  }
})
