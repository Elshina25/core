import api from '@/api'
import { defineStore } from 'pinia'
import type { IFilterQueryParams } from '@/types/filter'
import { ObjectMetrosResponse } from '@/api/object/collection/metros'
import { getQueryParams } from '~/renderer/utils'
import { routeSlugWithMetro } from '@/utils/filter'
import orderBy from 'lodash-es/orderBy'

interface IState {
  options: ObjectMetrosResponse
  selected: string[]
  distance: string
}

/**
 * Метро
 */
export const useFilterMetroStore = defineStore('filter-metros', {
  state: (): IState => ({
    options: [],
    selected: [],
    distance: ''
  }),
  getters: {},
  actions: {
    setDefaultParams(params = getQueryParams<IFilterQueryParams>()) {
      const { metro, distance = '', slug = '' } = params
      const currentMetro = routeSlugWithMetro(slug, this.options)

      this.selected = currentMetro
        ? currentMetro.list.map((a) => a.slug)
        : metro ?? []

      this.distance = distance
    },

    async fetchOptions() {
      if (this.options.length) return

      try {
        const { data } = await api.object.collection.metros()
        this.options = orderBy(data, [(el) => el.name.toLowerCase()], ['asc'])
      } catch (err) {
        console.error(err)
      }
    }
  }
})
