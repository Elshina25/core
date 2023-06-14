import { defineStore } from 'pinia'

type Variant = 'list' | 'map'

interface IState {
  status: boolean
  variant: Variant
}

/**
 * Лоадер
 */
export const useFilterLoaderStore = defineStore('filter-loader', {
  state: (): IState => ({
    status: false,
    variant: 'list'
  }),
  getters: {
    isMap(state) {
      return state.variant === 'map'
    },
    isList(state) {
      return state.variant === 'list'
    }
  },
  actions: {
    setVariant(variant: Variant) {
      this.variant = variant
    },
    start() {
      this.status = true
    },
    stop() {
      this.status = false
    }
  }
})
