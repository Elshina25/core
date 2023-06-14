import { defineStore } from 'pinia'
import { getQueryParams } from '~/renderer/utils'
import type { IFilterQueryParams } from '@/types/filter'

interface IState {
  name: string
  address: string
}

/**
 * Поле поиска
 */
export const useFilterSearchStore = defineStore('filter-search', {
  state: (): IState => ({
    name: '',
    address: ''
  }),
  getters: {},
  actions: {
    setDefaultParams(params = getQueryParams<IFilterQueryParams>()) {
      const { propertyAddressRus = '', name = '' } = params
      this.name = name
      this.address = propertyAddressRus
    }
  }
})
