import { INews } from '@/types'
import { requestPromise } from '@/utils/request'
import { LIMIT } from '@/config/news.config'
import {
  handlePaginationParams,
  HasPaginationParams
} from '@/utils/request/pagination'

/**
 * Получение списка новостей
 * @returns
 */
export default async (params: NewsListParams) => {
  return await requestPromise<NewsListResponse, NewsListRequest>({
    url: 'news/list/',
    method: 'get',
    params: {
      ...params,
      ...handlePaginationParams(params.page, LIMIT)
    }
  })
}

// Параметры нашего метода
export type NewsListParams = HasPaginationParams<NewsListRequest>

/**
 * Параметры запроса
 */
interface NewsListRequest {
  offset: number
  limit: number
  year: string | number
  exclude?: string
  image: number
}

/**
 * Структура ответа
 */
export interface NewsListResponse {
  items: INews[]
  count: number
  limit: number
  offset: number
}
