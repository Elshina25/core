import { ITag } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение списка категорий для разводящей проектов
 * @returns
 */
export default async () => {
  return await requestPromise<ProjectCategoriesResponse>({
    url: 'projects/sections/',
    method: 'get'
  })
}

export interface ProjectCategoriesResponse {
  sections: ITag<string>[]
  types: ITag<string>[]
}
