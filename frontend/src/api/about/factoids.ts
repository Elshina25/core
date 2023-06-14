import { requestPromise } from '@/utils/request'
import { IFactoid } from '@/types'

/**
 * Получение списка фактоидов
 * @returns
 */
export default async () => {
  try {
    const { data } = await requestPromise<IFactoid[]>({
      url: 'factoids/',
      method: 'get'
    })

    return data
  } catch (err) {
    console.error('Ошибка получения списка фактоидов', err)

    return []
  }
}
