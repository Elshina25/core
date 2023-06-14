import api, { ApiData } from '@/api'
import { IPersonTeam } from '@/types'
import { defineStore } from 'pinia'

export const useTeamStore = defineStore('teams', {
  state: () => ({
    items: [] as ApiData<IPersonTeam[]>
  }),
  getters: {},
  actions: {
    async fetchList() {
      if (this.items.length) return

      this.items = await api.about.team()
    }
  }
})
