import { requestPromise } from '@/utils/request'
import { ServiceCategoriesResponse } from './categories'
import { ServiceTypesResponse } from './types'
import { IServiceItem } from '@/types'

/**
 * Получение списка услуг
 * @returns
 */
export default async (params?: ServiceListParams) => {
  return await requestPromise<ServiceListResponse, ServiceListParams>({
    url: 'service/list/',
    method: 'get',
    params
  })
}

/**
 * Параметры запроса
 */
export interface ServiceListParams {
  forWhom: ServiceCategoriesResponse[0]['code']
  type: ServiceTypesResponse[0]['code']
}

/**
 * Структура ответа
 */
export type ServiceListResponse = {
  id: number
  code: string
  name: string
  picture: string | null
  description: string
  items?: IServiceItem[]
}[]
