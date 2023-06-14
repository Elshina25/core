import { requestPromise } from '@/utils/request'

/**
 * Получение списка типов помещений
 * @returns
 */
export default async () => {
  return await requestPromise<ObjectSpacesResponse>({
    url: 'objects/types/',
    method: 'get'
  })
}

export type ObjectSpacesResponse = {
  id: string
  name: string
}[]
