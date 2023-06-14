import api from '@/api'
import { PageProps } from './types'
import { ServiceListParams } from '@/api/service/list'

/**
 * Получение списка категорий
 * @returns
 */
export const getCategories = async (): Promise<PageProps['categories']> => {
  try {
    const { data } = await api.service.categories()

    return data
  } catch (err) {
    console.error('Ошибка получения списка категорий', err)

    return []
  }
}

/**
 * Получение списка типов
 * @returns
 */
export const getTypes = async (): Promise<PageProps['types']> => {
  try {
    const { data } = await api.service.types()

    return data
  } catch (err) {
    console.error('Ошибка получения списка типов', err)

    return []
  }
}

/**
 * Получение списка проектов
 * @returns
 */
export const getProjects = async (): Promise<PageProps['projects']> => {
  try {
    const { data } = await api.service.projects()

    return data
  } catch (err) {
    console.error('Ошибка получения списка категорий', err)

    return []
  }
}

/**
 * Получение списка услуг
 * @returns
 */
export const getList = async (
  params?: ServiceListParams
): Promise<PageProps['list']> => {
  try {
    const { data } = await api.service.list(params)

    return data.map((a) => {
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
