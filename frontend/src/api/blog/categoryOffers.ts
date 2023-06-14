import { ITag } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение списка категорий "предложения"
 * @returns
 */
export default async () => {
  return await requestPromise<ITag<string>[]>({
    url: 'blog/offers/',
    method: 'get'
  })
}
