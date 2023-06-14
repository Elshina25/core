import { requestPromise } from '@/utils/request'
import { IAward } from '@/types'

/**
 * Получение списка наград
 * @returns
 */
export default async () => {
  try {
    const { data } = await requestPromise<IAward[]>({
      url: 'awards/',
      method: 'get'
    })

    return data
  } catch (err) {
    console.error('Ошибка получения списка наград', err)

    return []
  }
}
