import type { IBeforeRenderReturn } from '@/types/renderer'
import type { PageContext } from '~/renderer/types'
import type { PageProps, IParams } from './types'
import { useLoader } from '@/composables/useLoader'
import { getInfo, getObjects } from './requests'

export async function onBeforeRender(
  context: PageContext
): Promise<IBeforeRenderReturn<PageProps>> {
  const loader = useLoader()
  loader.start()

  const query = context.urlParsed.search.query
  const pageInfo = +context.urlParsed.search.pageInfo || 1
  const pageObjects = +context.urlParsed.search.pageObjects || 1
  const params: IParams = { query, pageObjects, pageInfo }

  const objectsList = await getObjects({ query, page: pageObjects })
  const infoList = await getInfo({ query, page: pageInfo })

  loader.stop()

  return {
    pageContext: {
      documentProps: {
        title: '',
        description: '',
        image: ''
      },
      pageProps: {
        objects: objectsList,
        info: infoList,
        params,
        showMorePage: 'about'
      }
    }
  }
}
