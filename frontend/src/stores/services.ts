import api, { ApiData } from '@/api'
import { ServiceListResponse } from '@/api/service/list'
import { defineStore } from 'pinia'

export const useServiceStore = defineStore('services', {
  state: () => ({
    services: [] as ApiData<ServiceListResponse>
  }),
  getters: {},
  actions: {
    async fetchList() {
      if (this.services.length) return

      try {
        const { data } = await api.service.list()

        this.services = data.map((a) => {
          if (a.code === 'consalting') {
            a.code = 'consulting'
          }

          return a
        })
      } catch (err) {
        console.error('Ошибка получения списка услуг', err)

        return []
      }
    }
  }
})
