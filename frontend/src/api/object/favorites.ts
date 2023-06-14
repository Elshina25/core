import { requestPromise } from '@/utils/request'
import { ObjectListResponse } from '@/api/object/list'

/**
 * Получение списка избранных объектов
 * @returns
 */
export default async (params: ObjectFavoriteListParams) => {
  return await requestPromise<ObjectListResponse[], ObjectFavoriteListParams>({
    url: 'objects/object_favorite/',
    method: 'get',
    params
  })
}

// Параметры нашего метода
export type ObjectFavoriteListParams = {
  'id[]': ObjectListResponse['id'][]
}
