import api from '@/api'
import type { ObjectsResponse, ObjectListParams } from '@/api/object/list'
import { defineStore } from 'pinia'

interface IState {
  buildings: ObjectsResponse
}
/**
 * Классы объекта
 */
export const useBuildingsStore = defineStore('buildings', {
  state: (): IState => ({
    buildings: {
      count: 0,
      limit: '',
      offset: '',
      objects: []
    }
  }),
  actions: {
    async getBuildings(params: ObjectListParams) {
      try {
        const { data } = await api.object.list(params)
        this.buildings = data
      } catch (err) {
        console.error(err)
      }
    }
  }
})
