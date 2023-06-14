import api from '@/api'
import { defineStore } from 'pinia'
import type { IFilterQueryParams } from '@/types/filter'
import {
  ObjectCountiesRequest,
  ObjectCountiesResponse
} from '@/api/object/collection/counties'
import { getQueryParams } from '~/renderer/utils'
import { routeSlugWithCounties } from '@/utils/filter'

interface IState {
  options: ObjectCountiesResponse
  selected: string[]
}

/**
 * Округа
 */
export const useFilterCountieStore = defineStore('filter-counties', {
  state: (): IState => ({
    options: [],
    selected: []
  }),
  getters: {},
  actions: {
    setDefaultParams(params = getQueryParams<IFilterQueryParams>()) {
      const { county, slug = '' } = params
      const currentCounty = routeSlugWithCounties(slug, this.options)

      this.selected = currentCounty
        ? currentCounty.list.map((a) => a.slug)
        : county ?? []
    },

    async fetchOptions(params: ObjectCountiesRequest) {
      if (this.options.length) return

      try {
        const { data } = await api.object.collection.counties(params)
        this.options = data
      } catch (err) {
        console.error(err)
      }
    }
  }
})
