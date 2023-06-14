import api from '@/api'
import { defineStore } from 'pinia'
import type { IFilterQueryParams } from '@/types/filter'
import {
  ObjectDistrictsParams,
  ObjectDistrictsResponse
} from '@/api/object/collection/districts'
import { getQueryParams } from '~/renderer/utils'
import { routeSlugWithDistrict } from '@/utils/filter'

interface IState {
  options: ObjectDistrictsResponse
  selected: string[]
}

/**
 * Районы
 */
export const useFilterDistrictStore = defineStore('filter-districts', {
  state: (): IState => ({
    options: [],
    selected: []
  }),
  getters: {
    convertedOptions: (state) =>
      state.options
        .map((el) => ({
          id: el.areaSlug ?? '',
          name: el.areaNameRu ?? ''
        }))
        .filter((el) => !!(el.name && el.id))
  },
  actions: {
    setDefaultParams(params = getQueryParams<IFilterQueryParams>()) {
      const { district, slug = '' } = params
      const currentDistrict = routeSlugWithDistrict(slug, this.options)

      this.selected = currentDistrict
        ? currentDistrict.list.map((a) => a.areaSlug)
        : district ?? []
    },

    async fetchOptions(params: ObjectDistrictsParams) {
      if (this.options.length) return

      try {
        const { data } = await api.object.collection.districts(params)
        this.options = data.sort((a, b) =>
          a.areaNameRu.localeCompare(b.areaNameRu)
        )
      } catch (err) {
        console.error(err)
      }
    }
  }
})
