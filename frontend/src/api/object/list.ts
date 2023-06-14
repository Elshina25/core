import { requestPromise } from '@/utils/request'
import {
  IFilterClassType,
  IFilterOfferType,
  IFilterOrderDir
} from '@/types/filter'
import { LIMIT } from '@/config/objects.config'
import {
  handlePaginationParams,
  HasPaginationParams
} from '@/utils/request/pagination'

/**
 * Получение списка объектов
 * @returns
 */
export default async (params: ObjectListParams, limit: number = LIMIT) => {
  return await requestPromise<ObjectsResponse, ObjectListParams>({
    url: 'objects/filter/',
    method: 'get',
    params: {
      ...params,
      ...handlePaginationParams(params.page, limit)
    }
  })
}

export type ObjectListParams = HasPaginationParams<ObjectListRequest>

/**
 * Параметры запроса
 */
interface ObjectListRequest {
  limit?: number
  offset?: number
  byTab?: {
    type: IFilterOfferType // TODO: наругать бек
    grossAreaMin?: string
    grossAreaMax?: string
    minPrice?: string
    maxPrice?: string
    topInclude?: 1 | 0
  }

  // TODO: наругать бек и удалить
  propertyLease?: 1 | 0
  propertySale?: 1 | 0

  // Общие
  name?: string
  propertyAddressRus?: string // Адрес
  buildingClass?: IFilterClassType[] // Класс объекта - массив (A/B/B+/C). Может быть пустым
  city?: number // id города
  propertyType?: string // Тип объекта
  minUnitFloorNumber?: number // Минимальный этаж
  maxUnitFloorNumber?: number // Максимальный этаж
  hasParking?: 1 | 0 // Наличие парковки
  cityCounty?: string[] // Округ
  cityArea?: string[] // Район
  metroStation?: string[] // Метро
  distanceFromMetro?: number // Расстояние до метро
  distanceFromCity?: number // Расстояние от города
  direction?: string // Направление

  // Сортировка
  orderBy?: {
    newest?: IFilterOrderDir
    minPrice?: IFilterOrderDir
    buildingClass?: IFilterOrderDir
    grossArea?: IFilterOrderDir
    rand?: IFilterOrderDir
  }

  excludeCode?: ObjectListResponse['code']
}

/**
 * Структура ответа запроса
 */

export interface ObjectsResponse {
  count: number
  limit: string
  offset: string
  objects: ObjectListResponse[]
}

/**
 * Структура ответа списка объектов
 */
export interface ObjectListResponse {
  id: string
  county: string | null
  minRentPrice: string
  maxRentPrice: string
  minSalePrice: string
  maxSalePrice: string
  xCoordinate: string
  yCoordinate: string
  propertyAddressRus: string
  propertyDescriptionRus: string
  locationDescriptionRus: string
  propertyNameRus: string
  propertyLease: string
  propertySale: string
  propertyType: string
  code: string
  metroStation: string
  metroStationCode: string
  distanceFromMetro: string
  locationZone: string
  buildingClass: string
  direction: string
  pictures: string[]
  minUnitSize: number
  roomCount: number
  maxUnitSize: number
  rooms?: Room[]
  metroColor: string
}

export interface Room {
  unitOfferedRent: string
  unitFloorNumber: string
  unitSize: string
}
