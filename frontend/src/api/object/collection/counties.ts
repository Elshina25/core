import { FILTER_CITY_DEFAULT_ID } from '@/config/filter/filter.config'
import { requestPromise } from '@/utils/request'

/**
 * Получение списка округов
 * @returns
 */
export default async (params: ObjectCountiesRequest) => {
  const { cityId = FILTER_CITY_DEFAULT_ID } = params ?? {}

  return await requestPromise<ObjectCountiesResponse, ObjectCountiesRequest>({
    url: 'objects/сounty/',
    method: 'get',
    params: {
      cityId
    }
  })
}

export interface ObjectCountiesRequest {
  cityId?: string
}

export type ObjectCountiesResponse = {
  id: string
  nameRu: string
  slug: string
}[]
