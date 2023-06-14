import { ITag } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение списка типов
 * @returns
 */
export default async () => {
  return await requestPromise<ServiceTypesResponse>({
    url: 'service/types/',
    method: 'get'
  })
}

export type ServiceTypesResponse = ITag[]
