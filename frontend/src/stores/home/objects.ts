import { ApiData } from '@/api'
import { ObjectsResponse } from '@/api/object/list'
import { getList as getObjectList } from '@/pages/objects/search/requests'
import { defineStore } from 'pinia'

export const useHomeObjectStore = defineStore('homeObjects', {
  state: () => ({
    objects: [] as ApiData<ObjectsResponse['objects']>
  }),
  getters: {},
  actions: {
    async fetchList() {
      if (this.objects.length) return

      const res = await getObjectList(
        {
          page: 1,
          city: 6,
          // @ts-ignore
          byTab: { topInclude: 1 }
        },
        6
      )

      this.objects = res.objects
    }
  }
})
