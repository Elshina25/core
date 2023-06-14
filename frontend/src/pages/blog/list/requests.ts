import api from '@/api'
import { PageProps } from './types'
import { BlogListParams } from '@/api/blog/list'

/**
 * Получение списка категорий
 * @returns
 */
//TODO: заменить ответ промиса на PageProps['categories']
export const getCategories = async (): Promise<unknown> => {
  try {
    const { data: immovables } = await api.blog.categoryEstate()
    const { data: offers } = await api.blog.categoryOffers()

    return {
      immovables,
      offers
    }
  } catch (err) {
    console.error('Ошибка получения списка категорий', err)

    return {
      immovables: [],
      offers: []
    }
  }
}

/**
 * Получение списка блогов
 * @returns
 */
export const getList = async (
  params: BlogListParams
): Promise<PageProps['list']> => {
  try {
    const { data } = await api.blog.list(params)

    return data
  } catch (err) {
    console.error('Ошибка получения списка блогов', err)

    return {
      items: [],
      count: 0,
      limit: 0,
      offset: 0
    }
  }
}
