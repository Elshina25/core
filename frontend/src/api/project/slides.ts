import { IProjectSlide } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение списка завершенных проектов для главной страницы
 * @returns
 */
export default async () => {
  return await requestPromise<ProjectSlidesResponse>({
    url: 'projects/list_fact/',
    method: 'get'
  })
}

/**
 * Структура ответа
 */
export interface ProjectSlidesResponse {
  items: IProjectSlide[]
  count: number
}
