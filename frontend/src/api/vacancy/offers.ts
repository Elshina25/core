import { IOffer } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение списка элементов для блока "Мы предлагаем"
 * @returns
 */
export default async (params: OfferListParams) => {
  return await requestPromise<OfferListResponse, OfferListParams>({
    url: 'vacancy/whatsweoffer/list/',
    method: 'get',
    params
  })
}

/**
 * Параметры запроса
 */
export interface OfferListParams {
  limit: number
}

/**
 * Структура ответа
 */
export interface OfferListResponse {
  items: IOffer[]
  count: number
  limit: number
  offset: number
}
