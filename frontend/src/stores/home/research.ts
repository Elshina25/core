import api, { ApiData } from '@/api'
import { ResearchListResponse } from '@/api/research/list'
import { defineStore } from 'pinia'

export const useHomeResearchStore = defineStore('homeResearch', {
  state: () => ({
    research: [] as ApiData<ResearchListResponse['items']>
  }),
  getters: {},
  actions: {
    async fetchList() {
      if (this.research.length) return

      try {
        const { data } = await api.research.list({ page: 1 })

        this.research = data.items
      } catch (err) {
        console.error(
          'Ошибка получения списка исследований главной страницы',
          err
        )
      }
    }
  }
})
