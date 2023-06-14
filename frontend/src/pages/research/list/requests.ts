import api from '@/api'
import { PageProps } from './types'
import { ResearchListParams } from '@/api/research/list'
import { ISelectTag } from '@/types/ui'

/**
 * Получение списка категорий
 * @returns
 */
export const getCategories = async (): Promise<PageProps['categories']> => {
  try {
    const { data } = await api.research.categories()

    return data
  } catch (err) {
    console.error('Ошибка получения списка категорий исследований', err)

    return {
      sections: [],
      types: [],
      year: []
    }
  }
}

/**
 * Получение списка исследований
 * @returns
 */
export const getList = async (
  params: ResearchListParams
): Promise<PageProps['list']> => {
  try {
    const { data } = await api.research.list(params)

    return data
  } catch (err) {
    console.error('Ошибка получения списка исследований', err)

    return {
      items: [],
      count: 0,
      limit: 0,
      offset: 0
    }
  }
}

/**
 * Получение списка категорий
 * @returns
 */
export const getReportCategories = async (): Promise<ISelectTag[]> => {
  try {
    const { data } = await api.research.reportCategories()

    return data
      .map((item) => {
        // TODO: Нормализовать сортировку
        switch (item.code) {
          case 'all':
            item.sort = 1000
            break
          default:
            item.sort = 10
        }

        return item
      })
      .sort((a, b) => b.sort - a.sort)
  } catch (err) {
    console.error('Ошибка получения категорий отчетов для подписки', err)

    return []
  }
}
