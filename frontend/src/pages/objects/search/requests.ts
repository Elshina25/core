import api from '@/api'
import type {} from '@/api/object/list'
import type { ObjectPreviewDetailResponse } from '@/api/object/detail-preview'
import type { ObjectsCoordsResponse } from '@/api/object/list-coords'
import { ObjectListParams, ObjectsResponse } from '@/api/object/list'
/**
 * Получение списка объектов
 * @returns
 */
export const getList = async (
  params: ObjectListParams,
  limit?: number
): Promise<ObjectsResponse> => {
  try {
    const { data } = await api.object.list(params, limit)

    return data
  } catch (err) {
    console.error('Ошибка получения списка объектов', err)

    return {
      objects: [],
      count: 0,
      offset: '0',
      limit: '0'
    }
  }
}

/**
 * Получение списка координат
 * @returns
 */
export const getListCoords = async (
  params: ObjectListParams,
  limit?: number
): Promise<ObjectsCoordsResponse> => {
  try {
    const { data } = await api.object.listCoords(params, limit)

    return data
  } catch (err) {
    console.error('Ошибка получения списка координат', err)

    return {
      objects: [],
      count: 0,
      offset: '0',
      limit: '0'
    }
  }
}

/**
 * Получение превью объекта
 */
export const getDetailPreview = async (
  id: number
): Promise<ObjectPreviewDetailResponse | null> => {
  try {
    const { data } = await api.object.detailPreview(id)

    return data
  } catch (err) {
    console.error('Ошибка получения информации об объекте', err)

    return null
  }
}
