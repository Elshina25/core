import { computed } from 'vue'
import { ROUTE } from '@/routes'
import {
  OBJECT_LIST_BLOCK_ID,
  OBJECTS_MAP_BLOCK_ID
} from '@/config/objects.config'
import { FILTER_CITY_DEFAULT_ID } from '@/config/filter/filter.config'
import { useFilterStoreValues } from './useFilterStoreValues'
import { convertObjectToURLSearchParams } from '@/utils'
import { getSpace } from '@/utils/filter'
import type { IFilterQueryParams, IFilterClassType } from '@/types/filter'

/**
 * Генерация ссылок для кнопок
 */
export const useFilterStoreGenerator = () => {
  const { filterValues } = useFilterStoreValues()

  // Генерируем базовую ссылку с гет параметрами
  const generatedUrlWithParams = computed<string>(() => {
    const { space, offer, ...params } = filterValues.value
    const spaceType = getSpace(space, 'id')?.type

    //Список параметров route-a
    const baseUrlParamsList = [ROUTE.ESTATE.slug, spaceType, offer]

    // Если выбрана Москва, генерируем дополнительные параметры route-a
    if (params.city === FILTER_CITY_DEFAULT_ID) {
      // Объект с дополнительными параметрами route-a
      const urlSlugs: Pick<
        IFilterQueryParams,
        'building_class' | 'metro' | 'district' | 'county'
      > = {
        building_class: params.building_class?.map(
          (el) => `class-${el}` as IFilterClassType
        ),
        metro: params.metro?.map((el) => `metro-${el}`),
        district: params.district?.map((el) => `district-${el}`),
        county: params.county
      }

      // Ключ urlSlugs
      let property: keyof typeof urlSlugs

      // Если у какого то из параметров объекта urlSlugs выбрано 1 значение, то добавляем это значение в параметры route-a
      for (property in urlSlugs) {
        const val = urlSlugs[property]
        if (val?.length !== 1) continue

        // Добавляем значение в параметры route-a
        baseUrlParamsList.push(val[0])
        // Удаляем значение с params, что бы оно не дублировалось в query параметрах
        delete params[property]

        break
      }
    }

    const baseUrl = baseUrlParamsList.join('/')
    const queryParams = convertObjectToURLSearchParams(params).toString()

    return `${baseUrl}/?${queryParams}`
  })

  // Ссылка для кнопки "Показать предложения"
  const showOfferListLink = computed<string>(
    () => `${generatedUrlWithParams.value}#${OBJECT_LIST_BLOCK_ID}`
  )

  // Ссылка для кнопки "Показать на карте"
  const showOnMapLink = computed<string>(
    () => `${generatedUrlWithParams.value}&show_map=1#${OBJECTS_MAP_BLOCK_ID}`
  )

  return { showOfferListLink, showOnMapLink }
}
