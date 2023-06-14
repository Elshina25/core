import type { ObjectListParams, ObjectsResponse } from '@/api/object/list'
import type { PageContext } from '~/renderer/types'
import type {
  IFilterOfferType,
  IFilterQueryParams,
  IFilterClassType
} from '@/types/filter'

import {
  FILTER_OFFER_DEFAULT,
  FILTER_SPACE_DEFAULT_ID,
  FILTER_SPACE_DEFAULT_TYPE
} from '@/config/filter/filter.config'

import {
  routeSlugWithClass,
  routeSlugWithMetro,
  routeSlugWithCounties,
  getSpace,
  routeSlugWithDistrict,
  routeSlugWithSquare
} from '@/utils/filter'
import { getConvertedQueryParams } from '@/utils'

import { useFilterStoreValues } from '@/composables/filter/useFilterStoreValues'

import { useFilterClassStore } from '@/stores/filter/classes'
import { useFilterMetroStore } from '@/stores/filter/metros'
import { useFilterCountieStore } from '@/stores/filter/counties'
import { useBuildingsStore } from '@/stores/buildings'
import { useFilterUpdateStore } from './filter/useFilterUpdateStore'
import { useFilterDistrictStore } from '@/stores/filter/districts'

export const useObjectsList = () => {
  const classStore = useFilterClassStore()
  const metroStore = useFilterMetroStore()
  const districtStore = useFilterDistrictStore()
  const countieStore = useFilterCountieStore()
  const buildingsStore = useBuildingsStore()

  const updateBuildingsList = async (
    context: PageContext,
    queryParams: string | null,
    isWatched = false
  ) => {
    // Флаг для отображения карты с объектами
    const isMapActive = !!+context.urlParsed.search.show_map

    const list: ObjectsResponse = {
      count: 0,
      limit: '',
      offset: '',
      objects: []
    }

    // Если карта активна, прерываем выполнение ф-ии и возвращаем дефолтное значение списка
    if (isMapActive) return list

    // Конвертированные query параметры
    const convertedQueryParams: IFilterQueryParams = getConvertedQueryParams(
      queryParams || ''
    )

    // Класс
    const currentClass = routeSlugWithClass(
      context.routeParams.slug,
      classStore.options
    )
    const classes = currentClass
      ? currentClass.list.map((a) => a.type)
      : (convertedQueryParams.building_class as IFilterClassType[])

    // Метро
    const currentMetro = routeSlugWithMetro(
      context.routeParams.slug,
      metroStore.options
    )
    const metro = currentMetro
      ? currentMetro.list.map((a) => a.slug)
      : convertedQueryParams.metro

    // Район
    const currentDistrict = routeSlugWithDistrict(
      context.routeParams.slug,
      districtStore.options
    )
    const district = currentDistrict
      ? currentDistrict.list.map((a) => a.areaSlug)
      : convertedQueryParams.district

    // Площадь
    const currentSquare = routeSlugWithSquare(context.routeParams.slug)
    const square = currentSquare
      ? currentSquare.list
      : {
          start: convertedQueryParams.square_start,
          end: convertedQueryParams.square_end
        }

    // Округ
    const currentCounty = routeSlugWithCounties(
      context.routeParams.slug,
      countieStore.options
    )
    const counties = currentCounty
      ? currentCounty.list.map((a) => a.slug)
      : convertedQueryParams.county

    // Тип сделки
    const offer: IFilterOfferType = (context.routeParams.offer ??
      FILTER_OFFER_DEFAULT) as IFilterOfferType

    // Тип недвижимости
    const space: string =
      getSpace(context.routeParams.space, 'type')?.id ?? FILTER_SPACE_DEFAULT_ID

    // Собираем query параметры
    const baseParams: IFilterQueryParams = {
      ...convertedQueryParams,
      building_class: classes,
      county: counties,
      square_start: square.start,
      square_end: square.end,
      metro,
      district,
      space,
      offer
    }

    if (isWatched) {
      const stories = useFilterUpdateStore()
      stories.setDefaultParams({
        ...baseParams,
        space:
          getSpace(context.routeParams.space, 'type')?.type ??
          FILTER_SPACE_DEFAULT_TYPE
      })
    }

    const { getFilterParams } = useFilterStoreValues()

    // Конвертированный список параметров для запроса
    const convertedParams: ObjectListParams = getFilterParams(baseParams)

    await buildingsStore.getBuildings(convertedParams)
  }

  return { updateBuildingsList }
}
