import api, { ApiData } from '@/api'
import { IFactoid } from '@/types'
import { defineStore } from 'pinia'

export const useFactoidStore = defineStore('factoids', {
  state: () => ({
    factoids: [] as ApiData<IFactoid[]>
  }),
  getters: {},
  actions: {
    async fetchList() {
      if (this.factoids.length) return

      this.factoids = await api.about.factoids()
    }
  }
})
