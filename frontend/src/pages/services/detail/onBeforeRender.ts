import api from '@/api'
import { IBeforeRenderReturn } from '@/types/renderer'
import { PageContext } from '~/renderer/types'
import { PageProps } from './types'

export async function onBeforeRender(
  context: PageContext
): Promise<IBeforeRenderReturn<PageProps>> {
  const slug = context.routeParams.slug
  const service = await getService(slug)

  // 404
  if (!service) {
    console.error('404') // FIXME: Найти решение для отрисовки 404 страницы
    throw new Error()
  }

  return {
    pageContext: {
      documentProps: {
        title: service.seo.elementMetaTitle,
        description: service.seo.elementMetaDescription
        // keywords: service.seo.element_meta_keywords
      },
      pageProps: {
        service,
        showMorePage: 'researches'
      }
    }
  }
}

/**
 * Получение детальной страницы услуги
 * @param slug
 * @returns
 */
const getService = async (
  slug: string
): Promise<PageProps['service'] | null> => {
  try {
    const { data } = await api.service.detail(slug)
    if (data.section?.code === 'consalting') {
      data.section.code = 'consulting'
    }

    return data
  } catch (err) {
    console.error(err)
    return null
  }
}
