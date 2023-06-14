import api, { ApiData } from '@/api'
import { IClient } from '@/types'
import { defineStore } from 'pinia'

export const useClientStore = defineStore('clients', {
  state: () => ({
    items: [] as ApiData<IClient[]>
  }),
  getters: {},
  actions: {
    async fetchList() {
      if (this.items.length) return

      this.items = await api.about.clients()
    }
  }
})
