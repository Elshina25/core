import api from '@/api'
import { ObjectListResponse } from '@/api/object/list'
import { ObjectFavoriteListParams } from '@/api/object/favorites'

/**
 * Получение списка избранных объектов
 * @returns
 */
export const getList = async (
  params: ObjectFavoriteListParams
): Promise<ObjectListResponse[]> => {
  try {
    const { data } = await api.object.favorites(params)

    return data
  } catch (err) {
    console.error('Ошибка получения списка завершенных проектов', err)

    return []
  }
}
