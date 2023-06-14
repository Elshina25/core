import { requestPromise } from '@/utils/request'
import { IObjectFactoid, IObjectTableItem, IStation } from '@/types/objects'
import { IPerson, ITag, ISeo } from '@/types'
import { IFilterSpace } from '@/types/filter'

/**
 * Получение данных для детальной страницы объектов
 * @returns
 */
export default async (slug: string) => {
  return await requestPromise<ObjectDetailResponse>({
    url: `objects/detail/${slug}`,
    method: 'get'
  })
}

/**
 * Структура ответа
 */
export interface ObjectDetailResponse {
  id: string
  title: string
  for: string
  city: {
    id: string
    name: string
  }
  address: string
  type: string
  operationHeight?: string
  columnGridMin?: string
  columnGridMax?: string
  temperatureFrom?: string
  temperatureTo?: string
  distanceFromCity?: string
  floorType?: string
  floorLoad?: string
  fireSecurity?: string
  propertyType: {
    id: IFilterSpace['id']
    name: IFilterSpace['name']
  }
  metro?: IStation
  class: string
  buildingTotal?: string
  leaseTotal?: string
  leaseSquare?: string
  leasePrice?: number | string
  salePrice?: number | string
  parkingType?: string
  contacts: IPerson[]
  images: string[]
  miniImages: string[]
  xCoordinate: number
  yCoordinate: number
  description: string
  commercialDescription?: string
  crmId: string
  rooms: {
    rent: IObjectTableItem[]
    sale: IObjectTableItem[]
  }
  district: ITag
  factoids?: IObjectFactoid[] // с бэка не приходит
  seo: ISeo
}
