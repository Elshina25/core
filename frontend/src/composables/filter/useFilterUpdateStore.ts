import type { IFilterQueryParams } from '@/types/filter'

import { FILTER_CITY_DEFAULT_ID } from '@/config/filter/filter.config'
import { useFilterStore } from './useFilterStore'

/**
 * Обновление данных store-а фильтров
 */
export const useFilterUpdateStore = () => {
  const { storeFilters } = useFilterStore()

  // Метод запрашивает option-ы select-ов фильтра
  const fetchOptions = async () => {
    try {
      const promises = [
        storeFilters.city.fetchOptions(),
        storeFilters.directions.fetchOptions(),
        storeFilters.buildingClass.fetchOptions(),
        storeFilters.space.fetchOptions(),
        storeFilters.metro.fetchOptions(),
        storeFilters.district.fetchOptions({
          cities: [FILTER_CITY_DEFAULT_ID]
        }),
        storeFilters.county.fetchOptions({ cityId: FILTER_CITY_DEFAULT_ID })
      ]
      await Promise.all(promises)
    } catch (err) {
      console.error('Ошибка получения списка опций', err)
      return []
    }
  }

  // Метод заполняет поля значениями по умолчанию
  const setDefaultParams = (params: IFilterQueryParams) => {
    Object.values(storeFilters).forEach((store) => {
      store.setDefaultParams(params)
    })
  }

  return { setDefaultParams, fetchOptions }
}
