import type { IBeforeRenderReturn } from '@/types/renderer'
import type { PageProps } from './types'
import type { PageContext } from '~/renderer/types'
import type { IFilterOfferType, IFilterQueryParams } from '@/types/filter'
import {
  getSpace,
  routeSlugWithClass,
  routeSlugWithCounties,
  routeSlugWithDistrict,
  routeSlugWithMetro,
  routeSlugWithSquare
} from '@/utils/filter'
import { getConvertedQueryParams } from '@/utils'
import {
  FILTER_OFFER_DEFAULT,
  FILTER_SPACE_DEFAULT_ID
} from '@/config/filter/filter.config'
import { getPageSeo } from '@/utils/estateTitle'
import { useFilterClassStore } from '@/stores/filter/classes'
import { useFilterMetroStore } from '@/stores/filter/metros'
import { useFilterDistrictStore } from '@/stores/filter/districts'
import { useFilterCountieStore } from '@/stores/filter/counties'
import { initStore } from '@/utils/initStore'
import { getSeoText} from '../components/requests';

export async function onBeforeRender(
  context: PageContext
): Promise<IBeforeRenderReturn<PageProps>> {
  initStore(context)

  // Конвертированные query параметры
  const convertedQueryParams: IFilterQueryParams = getConvertedQueryParams(
    context.urlParsed.searchOriginal || ''
  )

  // Тип сделки
  const offer: IFilterOfferType = (context.routeParams.offer ??
    FILTER_OFFER_DEFAULT) as IFilterOfferType

  // Тип недвижимости
  const space: string =
    getSpace(context.routeParams.space, 'type')?.id ?? FILTER_SPACE_DEFAULT_ID

  // Флаг для отображения карты с объектами
  const isMapActive = !!+context.urlParsed.search.show_map

  // Заголовок для страницы поиска
  const heading = generateHeading(context)

  const seoText = await getSeoText(context);

  return {
    pageContext: {
      pageProps: {
        showMorePage: 'services',
        showMap: isMapActive,
        heading,
        seoText
      },
      documentProps: {
        ...getPageSeo(
          offer,
          space,
          convertedQueryParams.building_class?.[0],
          heading
        )
      }
    }
  }
}

/**
 * Генератор заголовков
 * @param context
 * @returns
 */
const generateHeading = (context: PageContext) => {
  const slug = context.routeParams.slug

  const classStore = useFilterClassStore()
  const metroStore = useFilterMetroStore()
  const districtStore = useFilterDistrictStore()
  const countieStore = useFilterCountieStore()

  // Класс
  const currentClass = routeSlugWithClass(slug, classStore.options)
  if (currentClass) {
    return currentClass.pageTitle
  }

  // Метро
  const currentMetro = routeSlugWithMetro(slug, metroStore.options)
  if (currentMetro) {
    return currentMetro.pageTitle
  }

  // Район
  const currentDistrict = routeSlugWithDistrict(slug, districtStore.options)
  if (currentDistrict) {
    return currentDistrict.pageTitle
  }

  // Площадь
  const currentSquare = routeSlugWithSquare(slug)
  if (currentSquare) {
    return currentSquare.pageTitle
  }

  // Округ
  const currentCounty = routeSlugWithCounties(slug, countieStore.options)
  if (currentCounty) {
    return currentCounty.pageTitle
  }

  return 'в Москве'
}
