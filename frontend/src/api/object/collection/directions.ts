import { requestPromise } from '@/utils/request'

/**
 * Получение списка направлений
 * @returns
 */
export default async () => {
  return await requestPromise<ObjectDirectionsResponse>({
    url: 'objects/directions/',
    method: 'get'
  })
}

type ObjectDirectionsResponse = {
  items: ObjectDirectionItem
}

export type ObjectDirectionItem = {
  id: string
  name: string
}[]
