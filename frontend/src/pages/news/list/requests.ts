import api from '@/api'
import { PageProps } from '@/pages/news/list/types'
import { NewsListParams } from '@/api/news/list'

/**
 * Получение списка категорий
 * @returns
 */
export const getCategories = async (): Promise<PageProps['categories']> => {
  try {
    const { data } = await api.news.categories()

    return data
  } catch (err) {
    console.error('Ошибка получения списка категорий новостей', err)

    return {
      topics: [],
      year: []
    }
  }
}

/**
 * Получение списка новостей
 * @returns
 */
export const getList = async (
  params: NewsListParams
): Promise<PageProps['list']> => {
  try {
    const { data } = await api.news.list(params)

    return data
  } catch (err) {
    console.error('Ошибка получения списка новостей', err)

    return {
      items: [],
      count: 0,
      limit: 0,
      offset: 0
    }
  }
}
