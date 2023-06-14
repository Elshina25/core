import { ITag } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение списка категорий
 * @returns
 */
export default async () => {
  return await requestPromise<NewsCategoriesResponse>({
    url: 'news/sections/',
    method: 'get'
  })
}

export interface NewsCategoriesResponse {
  topics: ITag<string>[]
  year: ITag[]
}
