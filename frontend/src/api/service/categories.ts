import { ITag } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение списка категорий
 * @returns
 */
export default async () => {
  return await requestPromise<ServiceCategoriesResponse>({
    url: 'service/forwhom/',
    method: 'get'
  })
}

export type ServiceCategoriesResponse = ITag[]
