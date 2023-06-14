import { IBlog } from '@/types'
import { requestPromise } from '@/utils/request'
import { LIMIT } from '@/config/project.config'
import {
  handlePaginationParams,
  HasPaginationParams
} from '@/utils/request/pagination'

/**
 * Получение списка блогов
 * @returns
 */
export default async (params: BlogListParams) => {
  return await requestPromise<BlogListResponse, BlogListRequest>({
    url: 'blog/list/',
    method: 'get',
    params: {
      ...params,
      ...handlePaginationParams(params.page, LIMIT)
    }
  })
}

// Параметры нашего метода
export type BlogListParams = HasPaginationParams<BlogListRequest>

/**
 * Параметры запроса
 */
interface BlogListRequest {
  offset: number
  limit: number
  immovables?: string
  offers?: string
  exclude?: string
}

/**
 * Структура ответа
 */
export interface BlogListResponse {
  items: IBlog[]
  count: number
  limit: number
  offset: number
}
