import { defineStore } from 'pinia'
import type { IFilterOfferType } from '@/types/filter'
import { FILTER_OFFER_DEFAULT } from '@/config/filter/filter.config'

interface IState {
  selected: IFilterOfferType
}

/**
 * Тип предложения
 */
export const useFilterOfferStore = defineStore('filter-offers', {
  state: (): IState => ({
    selected: FILTER_OFFER_DEFAULT
  }),
  getters: {},
  actions: {
    setDefaultParams(params: Record<string, string>) {
      const { offer = FILTER_OFFER_DEFAULT } = params
      this.selected = offer as IFilterOfferType
    },

    change(type: IFilterOfferType) {
      this.selected = type
    }
  }
})
