import { requestPromise } from '@/utils/request'
import { ObjectCountiesResponse } from './counties'
import { ObjectMetrosResponse } from './metros'

/**
 * Получение списка районов
 * @returns
 */
export default async (params: ObjectDistrictsParams) => {
  const { counties: slugs = [], cities = [] } = params

  return await requestPromise<ObjectDistrictsResponse, ObjectDistrictsRequest>({
    url: 'objects/county/raions/',
    method: 'get',
    params: {
      slugs,
      cities
    }
  })
}

export interface ObjectDistrictsParams {
  counties?: string[]
  cities?: string[]
}

export interface ObjectDistrictsRequest {
  slugs?: string[]
  cities?: string[]
}

export type ObjectDistrictsResponse = {
  cityId: number
  areaId: string
  areaNameRu: string
  areaSlug: string
  countySlug: ObjectCountiesResponse[0]['slug']
  metroSlugs: ObjectMetrosResponse[0]['slug'][]
}[]
