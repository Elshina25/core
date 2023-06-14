import type { ObjectListParams } from '@/api/object/list'
import type { IFilterQueryParams } from '@/types/filter'
import type { IFilterFormField } from '@/config/filter/filter-form.config'

import {
  FILTER_CITY_DEFAULT_ID,
  FILTER_SORT_DEFAULT
} from '@/config/filter/filter.config'
import {
  FILTER_FORM_MAIN,
  FILTER_FORM_COLLAPSE,
  isFieldActive
} from '@/config/filter/filter-form.config'
import { useFilterStore } from './useFilterStore'

import { computed } from 'vue'

export const useFilterStoreValues = () => {
  const { storeFilters } = useFilterStore()

  /**
   * getActiveFilterValues
   * @param params - query параметры
   * @returns IFilterQueryParams
   * @description метод фильтрует и возвращает значения активных полей
   */
  const getActiveFilterValues = (
    params: IFilterQueryParams
  ): IFilterQueryParams => {
    // Cписок дефолтно активных параметров
    const filteredParams: IFilterQueryParams = {
      page: params.page,
      offer: params.offer,
      sort: params.sort,
      city: params.city,
      space: params.space
    }
    const stack = [...FILTER_FORM_MAIN, ...FILTER_FORM_COLLAPSE]

    // Проходим по списку полей
    while (stack.length > 0) {
      // Получаем поле
      const node: IFilterFormField | undefined = stack.pop()

      if (!node) continue

      // Если есть дочерние поля, записываем в стек
      if (node.children?.length) {
        stack.push(...node.children)
      }

      /**
       * Если поле активное, проходимся по ключам query параметров, которые связаны с этим полем
       * и записываем их в filteredParams
       */
      if (isFieldActive(node.hiddenFor, node.activeFor, storeFilters)) {
        node.queryParamKeys.forEach((key: keyof IFilterQueryParams) => {
          if (!params[key]) return
          // TODO Vlad: Разобраться с типами
          //@ts-ignore
          filteredParams[key] = params[key]
        })
      }
    }

    return filteredParams
  }

  // Значения фильтров из store-a
  const filterValues = computed<IFilterQueryParams>(() => {
    const values: IFilterQueryParams = {
      page: '1',
      propertyAddressRus: storeFilters.search.address,
      name: storeFilters.search.name,
      city: storeFilters.city.selected,
      building_class: storeFilters.buildingClass.selected,
      floor_start: storeFilters.floor.start,
      floor_end: storeFilters.floor.end,
      offer: storeFilters.offer.selected,
      parking: storeFilters.parking.selected,
      price_start: storeFilters.price.start,
      price_end: storeFilters.price.end,
      space: storeFilters.space.selected,
      square_start: storeFilters.square.start,
      square_end: storeFilters.square.end,
      sort: storeFilters.sort.selected,
      county: storeFilters.county.selected,
      district: storeFilters.district.selected,
      direction: storeFilters.directions.selected,
      metro: storeFilters.metro.selected,
      distance: storeFilters.metro.distance,
      distance_from_city: storeFilters.distanceFromCity.selected
    }

    // Отфильтрованный список полей
    return getActiveFilterValues(values)
  })

  const getFilterParams = (
    params: IFilterQueryParams = { ...filterValues.value, page: '1' }
  ): ObjectListParams => {
    // Отфильтрованный список полей
    const filteredParams = getActiveFilterValues(params)

    return {
      page: filteredParams.page ? +filteredParams.page : 1,
      byTab: {
        type: filteredParams.offer,
        grossAreaMin: filteredParams.square_start,
        grossAreaMax: filteredParams.square_end,
        minPrice: filteredParams.price_start,
        maxPrice: filteredParams.price_end,
        topInclude: filteredParams.topInclude
          ? (+filteredParams.topInclude as 0 | 1)
          : undefined
      },

      // TODO: ругаемся а потом удаляем
      propertyLease: filteredParams.offer === 'rent' ? 1 : 0,
      propertySale: filteredParams.offer === 'sale' ? 1 : 0,

      name: filteredParams.name || undefined,
      propertyAddressRus: filteredParams.propertyAddressRus || undefined,
      buildingClass: filteredParams.building_class,
      city: filteredParams.city
        ? +filteredParams.city
        : +FILTER_CITY_DEFAULT_ID,
      propertyType: filteredParams.space,
      minUnitFloorNumber: filteredParams.floor_start
        ? +filteredParams.floor_start
        : undefined,
      maxUnitFloorNumber: filteredParams.floor_end
        ? +filteredParams.floor_end
        : undefined,
      hasParking: filteredParams.parking
        ? (+filteredParams.parking as 1 | 0)
        : undefined,
      cityCounty: filteredParams.county,
      cityArea: filteredParams.district,
      metroStation: filteredParams.metro,
      distanceFromMetro: filteredParams.distance
        ? +filteredParams.distance
        : undefined,
      distanceFromCity: filteredParams.distance_from_city
        ? +filteredParams.distance_from_city
        : undefined,
      orderBy: { [filteredParams.sort ?? FILTER_SORT_DEFAULT]: 'asc' },
      direction: filteredParams.direction
    }
  }

  return { filterValues, getFilterParams }
}
