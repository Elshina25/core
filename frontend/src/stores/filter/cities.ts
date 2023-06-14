import api from '@/api'
import { defineStore } from 'pinia'
import { ObjectCitiesResponse } from '@/api/object/collection/cities'
import {
  FILTER_CITY_DEFAULT_ID,
  FILTER_CITY_DEFAULT_NAME
} from '@/config/filter/filter.config'
import type { ISelectValue } from '@/types/ui'
import type { IFilterQueryParams } from '@/types/filter'
import { getQueryParams } from '~/renderer/utils'

interface IState {
  options: ObjectCitiesResponse
  selected: ISelectValue
}

/**
 * Города
 */
export const useFilterCityStore = defineStore('filter-cities', {
  state: (): IState => ({
    options: [],
    selected: FILTER_CITY_DEFAULT_ID
  }),
  getters: {
    isMoscow: (state): boolean => state.selected === FILTER_CITY_DEFAULT_ID,
    optionsWithDefault: (state) => {
      if (!state.options.length) {
        return [{ id: FILTER_CITY_DEFAULT_ID, name: FILTER_CITY_DEFAULT_NAME }]
      }
      return state.options
    }
  },
  actions: {
    setDefaultParams(params = getQueryParams<IFilterQueryParams>()) {
      const { city = FILTER_CITY_DEFAULT_ID } = params
      this.selected = city
    },

    async fetchOptions() {
      // FIXME: оставляем только Москву
      return
      // if (this.options.length) return

      try {
        const { data } = await api.object.collection.cities()
        const priorityCityIds = ['6', '7'] // id приоритетных городов, которые должны отображаться выше других
        this.options = data
          .sort((a, b) => a.name.localeCompare(b.name))
          .sort((a, b) => {
            const isCityAPriority: boolean = priorityCityIds.includes(a.id)
            const isCityBPriority: boolean = priorityCityIds.includes(b.id)
            if (isCityAPriority && isCityBPriority) {
              // Сортировка городов по позициям в массиве priorityCityIds
              return (
                priorityCityIds.indexOf(a.id) - priorityCityIds.indexOf(b.id)
              )
            }
            // Сортировка городов по приоритету (если id города есть в массиве priorityCityIds, то поднимаем его вверх)
            return +!isCityAPriority - +!isCityBPriority
          })
      } catch (err) {
        console.error(err)
      }
    }
  }
})
