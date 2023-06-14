import api from '@/api'
import { PageProps } from '@/pages/search/types'
import { SearchListParams } from '@/api/search/list'

/**
 * Получение результата поиска
 * @returns
 */
export const getInfo = async (
  params: SearchListParams
): Promise<PageProps['info']> => {
  try {
    const { data } = await api.search.list<PageProps['info']>({
      ...params,
      type: 'info'
    })

    return data
  } catch (err) {
    console.error('Ошибка получения результата поиска', err)

    return {
      items: [],
      count: 0,
      limit: 0,
      offset: 0
    }
  }
}

/**
 * Получение списка объектов
 * @returns
 */
export const getObjects = async (
  params: SearchListParams
): Promise<PageProps['objects']> => {
  try {
    const { data } = await api.search.list<PageProps['objects']>({
      ...params,
      type: 'object'
    })

    return data
  } catch (err) {
    console.error('Ошибка получения результата поиска', err)

    return {
      items: [],
      count: 0,
      limit: 0,
      offset: 0
    }
  }
}
