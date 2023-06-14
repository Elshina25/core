import { requestPromise } from '@/utils/request'
import { ISelectTag } from '@/types/ui'

/**
 * Получение списка тем для подписки в форме "Подписаться на отчеты"
 * @returns
 */
export default async () => {
  return await requestPromise<ISelectTag[]>({
    url: 'forms/subscribe_sections/',
    method: 'get'
  })
}
