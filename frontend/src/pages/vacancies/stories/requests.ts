import api from '@/api'
import { PageProps } from './types'
import { StoryListParams } from '@/api/vacancy/story/list'

/**
 * Получение списка категорий
 * @returns
 */
export const getCategories = async (): Promise<PageProps['categories']> => {
  try {
    const { data } = await api.vacancy.story.categories()

    return data
  } catch (err) {
    console.error('Ошибка получения списка категорий', err)

    return []
  }
}

/**
 * Получение списка историй сотрудников
 * @returns
 */
export const getList = async (
  params: StoryListParams
): Promise<PageProps['list']> => {
  try {
    const { data } = await api.vacancy.story.list(params)

    return data
  } catch (err) {
    console.error('Ошибка получения списка историй сотрудников', err)

    return {
      items: [],
      count: 0,
      limit: 0,
      offset: 0
    }
  }
}
