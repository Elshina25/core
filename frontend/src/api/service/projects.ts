import { IProject } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение списка типов
 * @returns
 */
export default async () => {
  return await requestPromise<ServiceProjectsResponse>({
    url: 'service/projectsdone/',
    method: 'get'
  })
}

/**
 * Структура ответа
 */
export type ServiceProjectsResponse = IProject[]
