import api from '@/api'
import { PageProps } from '@/pages/projects/list/types'
import { ProjectListParams } from '@/api/project/list'

/**
 * Получение списка категорий для завершенных исследований
 * @returns
 */
export const getCategories = async (): Promise<PageProps['categories']> => {
  try {
    const { data } = await api.project.categories()

    return data
  } catch (err) {
    console.error('Ошибка получения списка категорий', err)

    return {
      sections: [],
      types: []
    }
  }
}

/**
 * Получение списка завершенных проектов
 * @returns
 */
export const getList = async (
  params: ProjectListParams
): Promise<PageProps['list']> => {
  try {
    const { data } = await api.project.list(params)

    return data
  } catch (err) {
    console.error('Ошибка получения списка завершенных проектов', err)

    return {
      items: [],
      count: 0,
      limit: 0,
      offset: 0
    }
  }
}
