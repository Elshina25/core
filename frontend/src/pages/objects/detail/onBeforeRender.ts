import api from '@/api'
import { IBeforeRenderReturn } from '@/types/renderer'
import { PageContext } from '~/renderer/types'
import { PageProps } from '@/pages/objects/detail/types'
import { getSpace } from '@/utils/filter'

export async function onBeforeRender(
  context: PageContext
): Promise<IBeforeRenderReturn<PageProps>> {
  const space = getSpace(context.routeParams.space, 'type')
  const slug = context.routeParams.slug

  const object = await getItem(slug)

  // 404
  if (!object || object?.propertyType?.id !== space?.id) {
    console.error('404') // FIXME: Найти решение для отрисовки 404 страницы
    throw new Error()
  }

  const others = await getOthers(context, object.city.id)

  return {
    pageContext: {
      documentProps: {
        title: object.seo?.elementMetaTitle,
        description: object.seo?.elementMetaDescription,
        image: object.images?.[0]
      },
      pageProps: {
        object,
        others,
        showMorePage: 'services'
      }
    }
  }
}

/**
 * Получение детальной страницы объекта
 * @param slug
 * @returns
 */
const getItem = async (slug: string): Promise<PageProps['object'] | null> => {
  try {
    const { data } = await api.object.detail(slug)
    return data
  } catch (err) {
    console.error(err)
    return null
  }
}

/**
 * Получение похожих объектов
 * @param context
 * @returns
 */
const getOthers = async (
  context: PageContext,
  cityId: string
): Promise<PageProps['others']> => {
  try {
    const { data } = await api.object.list({
      page: 1,
      propertyType: getSpace(context.routeParams.space, 'type')?.id,
      excludeCode: context.routeParams.slug,
      orderBy: {
        rand: 'asc'
      },
      city: +cityId
    })

    return data.objects
  } catch (err) {
    console.error(err)
    return []
  }
}
