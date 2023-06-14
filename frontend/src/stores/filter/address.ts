import { defineStore } from 'pinia'
import { getQueryParams } from '~/renderer/utils'
import type { IFilterQueryParams } from '@/types/filter'

interface IState {
  selected: string
}

/**
 * Адрес
 */
export const useFilterAddressStore = defineStore('filter-address', {
  state: (): IState => ({
    selected: ''
  }),
  getters: {},
  actions: {
    setDefaultParams(params = getQueryParams<IFilterQueryParams>()) {
      const { address = '' } = params
      this.selected = address
    }
  }
})
