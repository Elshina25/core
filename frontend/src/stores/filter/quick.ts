import { IFilterQuick } from '@/types/filter'
import { defineStore } from 'pinia'

export const useFilterQuickStore = defineStore('filter-quick', {
  state: (): IFilterQuick => ({
    office: [
      // TODO: проставить ссылки
      {
        title: 'Аренда в Москва-Сити',
        offer: 'rent',
        slug: '/moscow-city'
      },
      {
        title: 'Аренда офиса класса А',
        offer: 'rent',
        slug: '/class-a'
      },
      {
        title: 'Продажа офисов класса А',
        offer: 'sale',
        slug: '/class-a'
      },
      {
        title: 'Продажа офисов в ЦАО',
        offer: 'sale',
        slug: '/tsao'
      },
      {
        title: 'Продажа офисов в Москва-Сити',
        offer: 'sale',
        slug: '/moscow-city'
      },
      {
        title: 'Аренда офисов 1000 кв. метров',
        offer: 'rent',
        slug: '/1000-m2'
      }
    ],
    industrial_logistics: [
      {
        title: 'Аренда складского комплекса',
        offer: 'rent',
        slug: ''
      }
    ],
    retail: [
      {
        title: 'Аренда торговых помещений',
        offer: 'rent',
        slug: ''
      }
    ]
  }),
  getters: {},
  actions: {}
})
