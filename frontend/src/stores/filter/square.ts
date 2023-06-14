import { defineStore } from 'pinia'
import { getQueryParams } from '~/renderer/utils'
import type { IFilterQueryParams } from '@/types/filter'
import { routeSlugWithSquare } from '@/utils/filter'

interface IState {
  start: string
  end: string
}

/**
 * Общая площадь
 */
export const useFilterSquareStore = defineStore('filter-square', {
  state: (): IState => ({
    start: '',
    end: ''
  }),
  getters: {},
  actions: {
    setDefaultParams(params = getQueryParams<IFilterQueryParams>()) {
      const { square_start = '', square_end = '', slug = '' } = params

      const currentSquare = routeSlugWithSquare(slug)
      this.start = currentSquare ? currentSquare.list.start : square_start
      this.end = currentSquare ? currentSquare.list.end : square_end
    }
  }
})
