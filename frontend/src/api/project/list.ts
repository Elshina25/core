import { IProject } from '@/types'
import { requestPromise } from '@/utils/request'
import { LIMIT } from '@/config/project.config'
import {
  handlePaginationParams,
  HasPaginationParams
} from '@/utils/request/pagination'

/**
 * Получение списка завершенных проектов
 * @returns
 */
export default async (params: ProjectListParams) => {
  return await requestPromise<ProjectListResponse, ProjectListRequest>({
    url: 'projects/list/',
    method: 'get',
    params: {
      ...params,
      ...handlePaginationParams(params.page, LIMIT)
    }
  })
}

// Параметры нашего метода
export type ProjectListParams = HasPaginationParams<ProjectListRequest>

/**
 * Параметры запроса
 */
interface ProjectListRequest {
  offset: number
  limit: number
  section: string
  type: string
  exclude?: string
}

/**
 * Структура ответа
 */
export interface ProjectListResponse {
  items: IProject[]
  count: number
  limit: number
  offset: number
}
