import { defineStore } from 'pinia'
const FAVORITES_LOCALSTORAGE_KEY = 'favorites-list'

interface IState {
  list: string[]
}

export const useFavoritesStore = defineStore('favorites', {
  state: (): IState => ({
    list: []
  }),
  getters: {
    getList(state) {
      return state.list
    },
    isActive: (state) => (id: string) => {
      return state.list.includes(id)
    }
  },
  actions: {
    /**
     * Добавление/удаление в избранное
     * @param id
     */
    toggle(id: string) {
      const foundId = this.list.find((favoriteId: string) => favoriteId === id)

      if (foundId) {
        // remove
        const listWithoutCurrentId = this.list.filter((a) => a !== id)
        this.save(listWithoutCurrentId)
      } else {
        // add
        this.save([...this.list, id])
      }
    },

    /**
     * Сохраняем избранные в наше хранилище
     * @param ids
     */
    save(ids: string[]) {
      localStorage.setItem(FAVORITES_LOCALSTORAGE_KEY, JSON.stringify(ids))
      this.list = ids
    },

    /**
     * Получаем хранилище избранных
     */
    loadData() {
      const data = localStorage?.getItem(FAVORITES_LOCALSTORAGE_KEY) || ''
      this.list = data ? JSON.parse(data) : []
    }
  }
})
