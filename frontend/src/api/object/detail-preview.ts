import type { ObjectListResponse } from '@/api/object/list'
import { requestPromise } from '@/utils/request'

/**
 * Получение данных для превью объекта
 * @returns
 */
export default async (id: number) => {
  return await requestPromise<ObjectPreviewDetailResponse>({
    url: `objects/object_preview/${id}/`,
    method: 'get'
  })
}

/**
 * Структура ответа
 */
export interface ObjectPreviewDetailResponse extends ObjectListResponse {
  picture: string
  propertyTypeId: string
}
