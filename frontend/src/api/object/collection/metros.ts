import { requestPromise } from '@/utils/request'

/**
 * Получение списка метро
 * @returns
 */
export default async () => {
  return await requestPromise<ObjectMetrosResponse>({
    url: 'objects/metros/',
    method: 'get'
  })
}

export type ObjectMetrosResponse = {
  id: string
  name: string
  slug: string
}[]
