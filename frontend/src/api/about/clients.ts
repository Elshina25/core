import { IClient } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение списка клиентов
 * @returns
 */
export default async () => {
  try {
    const { data } = await requestPromise<IClient[]>({
      url: 'clients/',
      method: 'get'
    })

    return data
  } catch (err) {
    console.error('Ошибка получения списка клиентов', err)

    return []
  }
}
