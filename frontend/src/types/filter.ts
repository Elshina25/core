import type { StateTree } from 'pinia'

/**
 * Сортировка
 */
export type IFilterOrderDir = 'asc' | 'desc'

/**
 * Класс объекта
 */
export type IFilterClassType = 'a' | 'b' | 'b+' | 'c'
export type IFilterClassName = 'A' | 'B' | 'B+' | 'C'

/**
 * Тип предложения
 */
export type IFilterOfferType = 'rent' | 'sale'
export interface IFilterOfferTab {
  type: IFilterOfferType
  name: string
}

/**
 * Тип пространства
 */
export interface IFilterSpace {
  id: string
  type: IFilterSpaceType
  name: string
  declination?: string[]
}

export type IFilterSpaceType =
  | 'office'
  | 'industrial_logistics'
  | 'retail'
  | 'hotel'
  | 'land'

/**
 * Быстрый поиск
 */
export interface IFilterQuickData {
  title: string
  offer: IFilterOfferType
  slug: string
}

export type IFilterQuick = Partial<Record<IFilterSpaceType, IFilterQuickData[]>>

export interface IFilterQueryParams {
  name?: string
  page: string
  city: string
  offer: IFilterOfferType
  space: string
  slug?: string
  distance_from_city?: string
  address?: string
  propertyAddressRus?: string
  building_class?: IFilterClassType[]
  floor_start?: string
  floor_end?: string
  parking?: '1' | '0'
  price_start?: string
  price_end?: string
  square_start?: string
  square_end?: string
  sort?: string
  county?: string[]
  district?: string[]
  metro?: string[]
  distance?: string
  topInclude?: string
  direction?: string
}

/**
 * Store полей фильтра
 */
export interface IFilterFieldsStore {
  address: StateTree
  city: StateTree
  buildingClass: StateTree
  county: StateTree
  district: StateTree
  floor: StateTree
  metro: StateTree
  offer: StateTree
  parking: StateTree
  price: StateTree
  space: StateTree
  square: StateTree
  sort: StateTree
  search: StateTree
  distanceFromCity: StateTree
  directions: StateTree
}
