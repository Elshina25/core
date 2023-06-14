import { IPersonTeam } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение списка работников для блока "Наша команда"
 * @returns
 */
export default async () => {
  try {
    const { data } = await requestPromise<IPersonTeam[]>({
      url: 'team/',
      method: 'get'
    })

    return data
  } catch (err) {
    console.error('Ошибка получения списка работников', err)

    return []
  }
}
