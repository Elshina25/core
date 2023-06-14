import type { IBeforeRenderReturn } from '@/types/renderer'
import type { PageContext } from '~/renderer/types'
import type { PageProps } from './types'
import { getCategories, getList, getTypes, getProjects } from './requests'
import { getParams } from './helpers'

export async function onBeforeRender(
  context: PageContext
): Promise<IBeforeRenderReturn<PageProps>> {
  const categories = await getCategories()
  const types = await getTypes()

  const { queryParams } = getParams({ context, categories, types })

  const list = await getList(queryParams)
  const projects = await getProjects()

  return {
    pageContext: {
      pageProps: {
        list,
        projects,
        categories,
        types,
        queryParams,
        showMorePage: 'contacts'
      },
      documentProps: {
        title:
          'Услуги CORE.XP. Лидирующая консалтинговая компания в области недвижимости',
        description:
          'Услуги компании CORE.XP | CORE.XP — лидирующая консалтинговая компания в области недвижимости'
      }
    }
  }
}
