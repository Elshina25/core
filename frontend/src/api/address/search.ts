import { requestPromise } from '@/utils/request'

/**
 * Получение списка адресов
 * @returns
 */
export default async (params: AddressSearchRequest) => {
  return await requestPromise<SearchResponse, AddressSearchRequest>({
    url: `objects/address_search/`,
    method: 'get',
    params
  })
}

/**
 * Структура запроса
 */
export interface AddressSearchRequest {
  address: string
  type?: 'db' | 'kladr'
}

interface SearchResponse {
  items: IAddressSearchItem[]
}

export interface IAddressSearchItem {
  address: string
}
