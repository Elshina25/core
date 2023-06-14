import type { IFilterSpace } from '@/types/filter'
import type { ObjectClassesResponse } from '@/api/object/collection/classes'
import type { ObjectCountiesResponse } from '@/api/object/collection/counties'
import type { ObjectMetrosResponse } from '@/api/object/collection/metros'

import {
  FILTER_OFFER_TABS,
  FILTER_SPACES,
  MOSCOW_CITY_METRO_LIST
} from '@/config/filter/filter.config'
import { ObjectDistrictsResponse } from '@/api/object/collection/districts'

type RouteSlugData<T> =
  | {
      list: T
      pageTitle: string
    }
  | undefined

/**
 * Получение space по переданным параметрам
 * @param val
 * @param field
 * @returns
 */
export const getSpace = (
  val: string,
  field: keyof IFilterSpace
): IFilterSpace | undefined => {
  return FILTER_SPACES.find((space: IFilterSpace) => space[field] === val)
}

/**
 * Получение типа предложения
 * @param type
 * @returns
 */
export const getOffer = (type: string) => {
  return FILTER_OFFER_TABS.find((a) => a.type === type)
}

/**
 * Получение slug-a без prefix-a
 * @param slug
 * @param prefix
 * @returns
 */
export const getSlugWithoutPrefix = (
  slug: string,
  prefix: 'class' | 'metro' | 'district'
) => {
  // Проверка есть ли slug
  if (!slug) return

  // Проходит ли условие slug, либо получили что-то другое
  const regex = new RegExp(`${prefix}-(.*)`, 'i')
  return slug.match(regex)?.[1] ?? null
}

/**
 * Обрабатываем slug с классом
 * @param slug
 * @param classList
 * @returns
 */
export const routeSlugWithClass = (
  slug: string,
  classList: ObjectClassesResponse[]
): RouteSlugData<ObjectClassesResponse[]> => {
  // Проходит ли условие slug, либо получили что-то другое
  const classType = getSlugWithoutPrefix(slug, 'class')
  if (!classType) return

  // Проверяем существование этого класса в перечне классов
  const found = classList?.find((a) => a.type === classType)

  if (!found) return

  // Все хорошо, отдаем класс
  return {
    list: [found],
    pageTitle: `класса ${found.name.toUpperCase()} в Москве`
  }
}

/**
 * Обрабатываем slug с метро
 * @param slug
 * @param metroList
 * @returns
 */
export const routeSlugWithMetro = (
  slug: string,
  metroList: ObjectMetrosResponse
): RouteSlugData<ObjectMetrosResponse> => {
  // Если в урле есть moscow-city, то возвращаем три ранее обозначенных метро
  if (slug?.includes('moscow-city')) {
    return {
      list: MOSCOW_CITY_METRO_LIST.map((a) => ({ id: '', name: '', slug: a })),
      pageTitle: 'в Москва-Сити'
    }
  }

  // Проходит ли условие slug, либо получили что-то другое
  const metroType = getSlugWithoutPrefix(slug, 'metro')
  if (!metroType) return

  // Проверяем существование этого метро в списке
  const found = metroList.find((a) => a.slug === metroType)
  if (!found) return

  // Все хорошо, отдаем метро
  return {
    list: [found],
    pageTitle: `у метро ${found.name}`
  }
}

/**
 * Обрабатываем slug с районом
 * @param slug
 * @param list
 * @returns
 */
export const routeSlugWithDistrict = (
  slug: string,
  list: ObjectDistrictsResponse
): RouteSlugData<ObjectDistrictsResponse> => {
  // Проходит ли условие slug, либо получили что-то другое
  const type = getSlugWithoutPrefix(slug, 'district')
  if (!type) return

  // Проверяем существование этого метро в списке
  const found = list.find((a) => a.areaSlug === type)
  if (!found) return

  // Все хорошо, отдаем район
  return {
    list: [found],
    pageTitle: `в районе ${found.areaNameRu}`
  }
}

/**
 * Обрабатываем slug с округом
 * @param slug
 * @param countieList
 * @returns
 */
export const routeSlugWithCounties = (
  slug: string,
  countieList: ObjectCountiesResponse
): RouteSlugData<ObjectCountiesResponse> => {
  // Проходит ли условие slug, либо получили что-то другое
  const isMetroOrClass =
    getSlugWithoutPrefix(slug, 'metro') ||
    getSlugWithoutPrefix(slug, 'district') ||
    getSlugWithoutPrefix(slug, 'class')
  if (isMetroOrClass) return

  // Проверяем существование этого округа в списке
  const found = countieList.find((a) => a.slug === slug)

  if (!found) return

  // Все хорошо, отдаем округ
  return {
    list: [found],
    pageTitle: `в ${found.nameRu}`
  }
}

/**
 * Обрабатываем slug с площадью
 * @param slug
 * @returns
 */
export const routeSlugWithSquare = (
  slug: string
): RouteSlugData<{
  start: string
  end: string
}> => {
  if (slug?.includes('1000-m2')) {
    return {
      list: {
        start: '1000',
        end: ''
      },
      pageTitle: '1000 кв.м.'
    }
  }

  return
}
