import { ROUTE } from '@/routes'
import { FILTER_OFFER_TABS } from '@/config/filter/filter.config'
import { resolveRoute } from 'vite-plugin-ssr/routing'
import { PageContext } from '~/renderer/types'
import { getSpace } from '@/utils/filter'

export default (pageContext: PageContext) => {
  const result = resolveRoute(
    ROUTE.ESTATE.slug + '/@space/@slug',
    pageContext.urlPathname
  )

  if (result.match) {
    const space = !!getSpace(result.routeParams.space, 'type')
    const offer = checkOfferType(result.routeParams.slug)

    if (space && !offer) return result
  }

  return false
}

const checkOfferType = (type: string) => {
  return !!FILTER_OFFER_TABS.find((a) => a.type === type)
}
