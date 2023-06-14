import type { IBeforeRenderReturn } from '@/types/renderer'
import type { IFilterOfferType, IFilterQueryParams } from '@/types/filter'
import {
  FILTER_OFFER_DEFAULT,
  FILTER_SPACE_DEFAULT_ID
} from '@/config/filter/filter.config'
import { PageContext } from '~/renderer/types'
import { getSpace } from '@/utils/filter'
import { getConvertedQueryParams } from '@/utils'
import { getPageSeo } from '@/utils/estateTitle'
import { getSeoText} from '../components/requests';

export async function onBeforeRender(
  context: PageContext
): Promise<IBeforeRenderReturn> {
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

  const seoText = await getSeoText(context);

  return {
    pageContext: {
      pageProps: {
        showMorePage: 'services',
        seoText
      },
      documentProps: getPageSeo(
        offer,
        space,
        convertedQueryParams.building_class?.[0]
      )
    }
  }
}
