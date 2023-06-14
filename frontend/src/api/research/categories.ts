import { ITag } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение списка категорий
 * @returns
 */
export default async () => {
  return await requestPromise<ResearchCategoriesResponse>({
    url: 'research/sections/',
    method: 'get'
  })
}

export interface ResearchCategoriesResponse {
  sections: ITag<string>[]
  types: ITag<string>[]
  year: ITag[]
}
