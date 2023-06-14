import { ROUTE } from '@/routes'
import { resolveRoute } from 'vite-plugin-ssr/routing'
import { PageContext } from '~/renderer/types'
import { getOffer, getSpace } from '@/utils/filter'

export default (pageContext: PageContext) => {
  {
    const result = resolveRoute(
      ROUTE.ESTATE.slug + '/@space/@offer',
      pageContext.urlPathname
    )

    if (result.match && pageContext.urlParsed.searchOriginal) {
      const space = !!getSpace(result.routeParams.space, 'type')
      const offer = !!getOffer(result.routeParams.offer)

      if (space && offer) return result
    }
  }

  const result = resolveRoute(
    ROUTE.ESTATE.slug + '/@space/@offer/@slug',
    pageContext.urlPathname
  )

  if (result.match) {
    const space = !!getSpace(result.routeParams.space, 'type')
    const offer = !!getOffer(result.routeParams.offer)

    if (space && offer) return result
  }

  return result
}
