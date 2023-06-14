import api from '@/api'
import { defineStore } from 'pinia'
import { ObjectSpacesResponse } from '@/api/object/collection/spaces'
import {
  FILTER_SPACE_DEFAULT_ID,
  FILTER_SPACE_DEFAULT_NAME,
  FILTER_SPACES,
  FILTER_SPACE_DEFAULT_TYPE
} from '@/config/filter/filter.config'
import { IFilterSpace, IFilterSpaceType } from '@/types/filter'
import { getSpace } from '@/utils/filter'

interface IState {
  options: ObjectSpacesResponse
  selected: string
}

/**
 * Тип помещения
 */
export const useFilterSpaceStore = defineStore('filter-spaces', {
  state: (): IState => ({
    options: [],
    selected: FILTER_SPACE_DEFAULT_ID
  }),
  getters: {
    convertedOptions: (state): IFilterSpace[] => {
      if (!state.options.length) {
        return [
          {
            id: FILTER_SPACE_DEFAULT_ID,
            name: FILTER_SPACE_DEFAULT_NAME,
            type: FILTER_SPACE_DEFAULT_TYPE
          }
        ]
      }
      return FILTER_SPACES.filter(
        (el) => !!state.options.find((option) => option.id === el.id)
      )
    },
    spaceType: (state): IFilterSpaceType =>
      getSpace(state.selected, 'id')?.type || FILTER_SPACE_DEFAULT_TYPE
  },
  actions: {
    setDefaultParams(params: Record<string, string>) {
      const { space = FILTER_SPACE_DEFAULT_TYPE } = params
      this.selected = getSpace(space, 'type')?.id || FILTER_SPACE_DEFAULT_ID
    },

    async fetchOptions() {
      if (this.options.length) return

      try {
        const { data } = await api.object.collection.spaces()
        this.options = data
      } catch (err) {
        console.error(err)
      }
    }
  }
})
