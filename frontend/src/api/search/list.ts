import type { SearchTypes } from '@/config/search.config'
import type { HasPaginationParams } from '@/utils/request/pagination'
import type { ObjectListResponse } from '@/api/object/list'
import { handlePaginationParams } from '@/utils/request/pagination'
import { LIMITS, LIMIT_DEFAULT } from '@/config/search.config'
import { requestPromise } from '@/utils/request'

/**
 * Получение результата поиска
 * @returns
 */
export default async <T>(params: SearchListParams) => {
  return await requestPromise<T, ISearchListRequest>({
    url: 'search/',
    method: 'get',
    params: {
      ...params,
      ...handlePaginationParams(
        params.page,
        params.type ? LIMITS[params.type] : LIMIT_DEFAULT
      )
    }
  })
}

export type SearchListParams = HasPaginationParams<ISearchListRequest>

/**
 * Параметры запроса
 */
interface ISearchListRequest {
  query: string
  type?: SearchTypes
  offset: number
  limit: number
}

/**
 * Структура ответа
 */
interface ISearchResponse {
  count: number
  limit: number
  offset: number
}

export interface ISearchInfoResponse extends ISearchResponse {
  items: ISearchInfoItem[]
}

export interface ISearchObjectsResponse extends ISearchResponse {
  items: ObjectListResponse[]
}

export type InfoItemType =
  | 'projects'
  | 'news'
  | 'research'
  | 'article'
  | 'object'
  | 'services'

export interface ISearchInfoItem {
  code: string
  name: string
  type: InfoItemType
  preview: string
}
