import api from '@/api'
import { defineStore } from 'pinia'
import type { IFilterQueryParams } from '@/types/filter'
import { ObjectDirectionItem } from '@/api/object/collection/directions'
import { getQueryParams } from '~/renderer/utils'

interface IState {
  options: ObjectDirectionItem
  selected: string
}
/**
 * Классы объекта
 */
export const useFilterDirectionsStore = defineStore('filter-directions', {
  state: (): IState => ({
    options: [],
    selected: ''
  }),
  actions: {
    setDefaultParams(params = getQueryParams<IFilterQueryParams>()) {
      const { direction = '' } = params
      this.selected = direction
    },

    async fetchOptions() {
      if (this.options.length) return

      try {
        const { data } = await api.object.collection.directions()
        this.options = data.items ?? []
      } catch (err) {
        console.error(err)
      }
    }
  }
})
