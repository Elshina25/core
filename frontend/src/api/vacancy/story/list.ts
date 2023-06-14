import { IStory } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение списка историй сотрудников
 * @returns
 */
export default async (params: StoryListParams) => {
  return await requestPromise<StoryListResponse, StoryListParams>({
    url: 'vacancy/workhistory/list/',
    method: 'get',
    params
  })
}

/**
 * Параметры запроса
 */
export interface StoryListParams {
  topic: string
}

/**
 * Структура ответа
 */
export interface StoryListResponse {
  items: IStory[]
  count: number
  limit: number
  offset: number
}
