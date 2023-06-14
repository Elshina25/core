import { requestPromise } from '@/utils/request'
import { IObjectSearchResponse } from '@/types/objects'

/**
 * Получение данных для превью объекта
 * @returns
 */
export default async (name: string) => {
  return await requestPromise<SearchResponse, ObjectSearchRequest>({
    url: `objects/name_search/`,
    method: 'get',
    params: { name }
  })
}

/**
 * Структура запроса
 */
export interface ObjectSearchRequest {
  name: string
}

interface SearchResponse {
  items: IObjectSearchResponse[]
}
