import { defineStore } from 'pinia'

export const useMenuStore = defineStore('menu', {
  state: () => ({
    isOpen: false
  }),
  getters: {
    status(state) {
      return state.isOpen
    }
  },
  actions: {
    open() {
      this.isOpen = true
    },
    close() {
      this.isOpen = false
    }
  }
})
