import { requestPromise } from '@/utils/request'

/**
 * Получение списка городов
 * @returns
 */
export default async () => {
  return await requestPromise<ObjectCitiesResponse>({
    url: 'objects/cities/',
    method: 'get'
  })
}

export type ObjectCitiesResponse = {
  id: string
  name: string
}[]
