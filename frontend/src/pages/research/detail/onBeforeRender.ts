import api from '@/api'
import { IBeforeRenderReturn } from '@/types/renderer'
import { PageContext } from '~/renderer/types'
import { PageProps } from './types'
import { getList } from '../list/requests'
import { convertStringToDate } from '@/utils'

export async function onBeforeRender(
  context: PageContext
): Promise<IBeforeRenderReturn<PageProps>> {
  const slug = context.routeParams.slug
  const research = await getItem(slug)
  const params = {
    page: 1,
    section: research?.section?.code ?? '',
    type: research?.type?.code ?? '',
    year: research?.date
      ? convertStringToDate(research.date).getFullYear()
      : '',
    exclude: slug
  }
  const { items } = await getList(params)
  // 404
  if (!research) {
    console.error('404') // FIXME: Найти решение для отрисовки 404 страницы
    throw new Error()
  }

  return {
    pageContext: {
      documentProps: {
        title: research.metaTitle,
        description: research.metaDescription,
      },
      pageProps: {
        research,
        researchList: items,
        showMorePage: 'services'
      }
    }
  }
}

/**
 * Получение детальной страницы исследования
 * @param slug
 * @returns
 */
const getItem = async (slug: string): Promise<PageProps['research'] | null> => {
  try {
    const { data } = await api.research.detail(slug)
    return data
  } catch (err) {
    console.error(err)
    return null
  }
}
