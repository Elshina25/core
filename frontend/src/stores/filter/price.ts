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
export const useFilterPriceStore = defineStore('filter-price', {
  state: (): IState => ({
    start: '',
    end: ''
  }),
  getters: {},
  actions: {
    setDefaultParams(params = getQueryParams<IFilterQueryParams>()) {
      const { price_start = '', price_end = '' } = params
      this.start = price_start
      this.end = price_end
    }
  }
})
