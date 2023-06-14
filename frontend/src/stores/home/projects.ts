import api, { ApiData } from '@/api'
import { ProjectSlidesResponse } from '@/api/project/slides'
import { defineStore } from 'pinia'

export const useHomeProjectStore = defineStore('homeProjects', {
  state: () => ({
    items: [] as ApiData<ProjectSlidesResponse['items']>
  }),
  getters: {},
  actions: {
    async fetchList() {
      if (this.items.length) return

      try {
        const { data } = await api.project.slides()

        this.items = data.items
      } catch (err) {
        console.error(
          'Ошибка получения списка завершенных проектов для главной страницы',
          err
        )

        return {
          items: [],
          count: 0
        }
      }
    }
  }
})
