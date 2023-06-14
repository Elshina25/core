import { requestPromise } from '@/utils/request'
import { ObjectListParams } from './list'
import { LIMIT } from '@/config/objects.config'
import { handlePaginationParams } from '@/utils/request/pagination'

/**
 * Получение списка объектов
 * @returns
 */
export default async (params: ObjectListParams, limit: number = LIMIT) => {
  return await requestPromise<ObjectsCoordsResponse, ObjectListParams>({
    url: 'objects/coords/',
    method: 'get',
    params: {
      ...params,
      ...handlePaginationParams(params.page, limit)
    }
  })
}

/**
 * Структура ответа запроса
 */

export interface ObjectsCoordsResponse {
  count: number
  limit: string
  offset: string
  objects: ObjectListResponse[]
}

/**
 * Структура ответа списка объектов
 */
export interface ObjectListResponse {
  id: number
  longitude: number
  latitude: number
}
