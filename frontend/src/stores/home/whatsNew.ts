import { ApiData } from '@/api'
import { defineStore } from 'pinia'
import {
  getCategories,
  getList as getResearchList
} from '@/pages/research/list/requests'
import { getCategories as getNewsCategories } from '@/pages/news/list/requests'
import { getList as getNewsList } from '@/pages/news/list/requests'
import { getList as getBlogList } from '@/pages/blog/list/requests'
import { IBlog, INews, IResearch, IServiceItem, IWhatNewCard } from '@/types'
import { convertStringToDate } from '@/utils'
import { ROUTE } from '@/routes'
import { ServiceListResponse } from '@/api/service/list'
import { getServiceSlug } from '@/utils/show'
import orderBy from 'lodash-es/orderBy'
import { useServiceStore } from '../services'

export const useHomeWhatsNewStore = defineStore('homeWhatsNew', {
  state: () => ({
    whatsNew: [] as ApiData<IWhatNewCard[]>
  }),
  getters: {},
  actions: {
    async fetchList() {
      // TODO: можно сделать отдельные сторы для категорий
      const categories = await getCategories()
      const newsCategories = await getNewsCategories()

      const specialResearches = await getResearchList({
        page: 1,
        section: categories.sections[0].code,
        type: categories.types[2].code,
        year: categories.year[0].code
      })

      const { items: news } = await getNewsList({
        page: 1,
        image: 1,
        year: newsCategories.year[0].code
      })

      const { items: blogs } = await getBlogList({
        page: 1
      })

      const serviceStore = useServiceStore()

      this.whatsNew = getWhatNewList(
        news,
        blogs,
        specialResearches.items,
        getWhatNewListServices(serviceStore.services)
      )
    }
  }
})

/**
 * Формирование массива для слайдера Что нового
 * @param news
 * @param blogs
 * @param researches
 * @param services
 * @returns
 */
const getWhatNewList = (
  news: INews[],
  blogs: IBlog[],
  researches: IResearch[],
  services: IServiceItem[]
): IWhatNewCard[] => {
  const mappedNews: IWhatNewCard[] = news
    .map(
      (el): IWhatNewCard => ({
        date: el.date ? convertStringToDate(el.date) : new Date(),
        image: el.image,
        name: el.name,
        slug: `${ROUTE.NEWS.slug}/${el.slug}`,
        type: 'news'
      })
    )
    .slice(0, 7)

  const mappedBlogs: IWhatNewCard[] = blogs
    .map(
      (el): IWhatNewCard => ({
        date: el.activeFrom ? convertStringToDate(el.activeFrom) : new Date(),
        image: el.picture,
        name: el.name,
        slug: `${ROUTE.BLOG.slug}/${el.code}`,
        type: 'blog'
      })
    )
    .slice(0, 7)

  const mappedResearches: IWhatNewCard[] = researches
    .map(
      (el): IWhatNewCard => ({
        date: el.date ? convertStringToDate(el.date) : new Date(),
        image: el.image ? el.image : undefined,
        name: el.name,
        slug: `${ROUTE.RESEARCHES.slug}/${el.slug}`,
        type: 'research'
      })
    )
    .slice(0, 3)

  const mappedServices: IWhatNewCard[] = services
    .map(
      (el): IWhatNewCard => ({
        date: el.date ? convertStringToDate(el.date) : new Date(),
        name: el.name,
        slug: getServiceSlug(el.code, el.fastLink),
        type: 'service'
      })
    )
    .slice(0, 3)

  return orderBy(
    [...mappedNews, ...mappedBlogs, ...mappedResearches, ...mappedServices],
    [(el) => el.date],
    ['desc']
  )
}

/**
 * Получаем элементы услуг для массива слайдера Что нового
 * @param services
 * @returns
 */
const getWhatNewListServices = (
  services: ServiceListResponse
): IServiceItem[] => {
  return services.reduce(
    (acc: IServiceItem[], item) => (item.items ? [...acc, ...item.items] : acc),
    []
  )
}
