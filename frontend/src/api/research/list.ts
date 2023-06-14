import { IResearch } from '@/types'
import { requestPromise } from '@/utils/request'
import { LIMIT } from '@/config/research.config'
import {
  handlePaginationParams,
  HasPaginationParams
} from '@/utils/request/pagination'

/**
 * Получение списка исследований
 * @returns
 */
export default async (params: ResearchListParams) => {
  return await requestPromise<ResearchListResponse, ResearchListRequest>({
    url: 'research/list/',
    method: 'get',
    params: {
      ...params,
      ...handlePaginationParams(params.page, LIMIT)
    }
  })
}

// Параметры нашего метода
export type ResearchListParams = HasPaginationParams<ResearchListRequest>

/**
 * Параметры запроса
 */
interface ResearchListRequest {
  offset: number
  limit: number
  section?: string
  type?: string
  year?: string | number
  exclude?: string
}

/**
 * Структура ответа
 */
export interface ResearchListResponse {
  items: IResearch[]
  count: number
  limit: number
  offset: number
}
