import { IVacancy } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение списка вакансий
 * @returns
 */
export default async () => {
  return await requestPromise<VacancyListResponse>({
    url: 'vacancy/list/',
    method: 'get'
  })
}

/**
 * Структура ответа
 */
export interface VacancyListResponse {
  items: IVacancy[]
  count: number
  limit: number
  offset: number
}
