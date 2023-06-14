import { ITag } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение списка категорий "типы недвижимости"
 * @returns
 */
export default async () => {
  return await requestPromise<ITag<string>[]>({
    url: 'blog/immovables/',
    method: 'get'
  })
}
