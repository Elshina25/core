import api from '@/api'
import { defineStore } from 'pinia'
import type { IFilterQueryParams } from '@/types/filter'
import { ObjectClassesResponse } from '@/api/object/collection/classes'
import { IFilterClassType } from '@/types/filter'
import { getQueryParams } from '~/renderer/utils'
import { routeSlugWithClass } from '@/utils/filter'

interface IState {
  options: ObjectClassesResponse[]
  selected: IFilterClassType[]
}
/**
 * Классы объекта
 */
export const useFilterClassStore = defineStore('filter-classes', {
  state: (): IState => ({
    options: [],
    selected: []
  }),
  getters: {
    convertedOptions: (state) =>
      state.options.map((option) => ({ id: option.type, name: option.name }))
  },
  actions: {
    setDefaultParams(params = getQueryParams<IFilterQueryParams>()) {
      const { building_class, slug = '' } = params
      const currentClass = routeSlugWithClass(slug, this.options)

      this.selected = (
        currentClass
          ? currentClass.list.map((a) => a.type)
          : building_class ?? []
      ) as IFilterClassType[]
    },

    async fetchOptions() {
      if (this.options.length) return

      try {
        const { data } = await api.object.collection.classes()
        this.options = data
      } catch (err) {
        console.error(err)
      }
    }
  }
})
