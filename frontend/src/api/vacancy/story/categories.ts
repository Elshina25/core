import { ITag } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение списка тем для историй сотрудников
 * @returns
 */
export default async () => {
  return await requestPromise<ITag<string>[]>({
    url: 'vacancy/workhistory/topics/',
    method: 'get'
  })
}
