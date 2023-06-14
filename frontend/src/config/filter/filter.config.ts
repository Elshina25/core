import {
  IFilterOfferTab,
  IFilterSpace,
  IFilterSpaceType,
  IFilterQueryParams
} from '@/types/filter'

/**
 * Главный город
 * (только у него есть округи, районы, метро)
 */
export const FILTER_CITY_DEFAULT_ID = '6'
export const FILTER_CITY_DEFAULT_NAME = 'Москва'
/**
 * Дефолтное пространство
 */
export const FILTER_SPACE_DEFAULT_ID = '3'
export const FILTER_SPACE_DEFAULT_TYPE = 'office'
export const FILTER_SPACE_DEFAULT_NAME = 'Офисы'

export const FILTER_OFFER_DEFAULT = 'rent'
export const FILTER_OFFER_RENT_NAME = 'Арендовать'
export const FILTER_OFFER_SALE_NAME = 'Купить'

export const FILTER_TOPINCLUDE_DEFAULT = 0
export const FILTER_SORT_DEFAULT = 'newest'

export const FILTER_FAVORITES: IFilterQueryParams = {
  page: '1',
  city: FILTER_CITY_DEFAULT_ID,
  space: FILTER_SPACE_DEFAULT_ID,
  offer: FILTER_OFFER_DEFAULT,
  topInclude: '1'
}

export const FILTER_OFFER_TABS: IFilterOfferTab[] = [
  { type: 'rent', name: FILTER_OFFER_RENT_NAME },
  { type: 'sale', name: FILTER_OFFER_SALE_NAME }
]

export const FILTER_METRO_DISTANCE_OPTIONS = [
  { id: 0, label: 'Неважно' },
  { id: 1, label: 'Пешком' }
]

export const FILTER_SPACES_DECLINATIONS: Record<IFilterSpaceType, string[]> = {
  office: ['офисное помещение', 'офисных помещения', 'офисных помещений'],
  industrial_logistics: [
    'складское помещение',
    'складских помещения',
    'складских помещений'
  ],
  retail: ['торговое помещение', 'торговых помещения', 'торговых помещений'],
  land: ['земельный участок', 'земельных участка', 'земельных участков'],
  hotel: ['гостиница', 'гостиницы', 'гостиниц']
}

export const FILTER_SPACES: IFilterSpace[] = [
  {
    id: FILTER_SPACE_DEFAULT_ID,
    type: FILTER_SPACE_DEFAULT_TYPE,
    name: FILTER_SPACE_DEFAULT_NAME
  },
  {
    id: '1',
    type: 'industrial_logistics',
    name: 'Склады'
  },
  {
    id: '2',
    type: 'retail',
    name: 'Торговые помещения'
  }
  // {
  //   id: '4',
  //   type: 'land',
  //   name: 'Земля'
  // },
  // {
  //   id: '7',
  //   type: 'hotel',
  //   name: 'Гостиницы'
  // }
]

export const MOSCOW_CITY_METRO_LIST = [
  'delovoy_tsentr',
  'vystavochnaya_6',
  'mezhdunarodnaya_6'
]
