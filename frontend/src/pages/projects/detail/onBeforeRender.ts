import api from '@/api'
import { IBeforeRenderReturn } from '@/types/renderer'
import { PageContext } from '~/renderer/types'
import { PageProps } from '@/pages/projects/detail/types'
import { getList as getServicesList } from '@/pages/services/list/requests'
import { getList } from '@/pages/projects/list/requests'

export async function onBeforeRender(
  context: PageContext
): Promise<IBeforeRenderReturn<PageProps>> {
  const slug = context.routeParams.slug
  const project = await getItem(slug)

  const params = {
    page: 1,
    section: project?.section?.code ?? '',
    type: project?.type?.code ?? '',
    exclude: slug
  }
  const { items: projectSlider } = await getList(params)

  // 404
  if (!project) {
    console.error('404') // FIXME: Найти решение для отрисовки 404 страницы
    throw new Error()
  }

  const serviceSlider = await getServicesList()

  return {
    pageContext: {
      documentProps: {
        title: project.metaTitle,
        description: project.metaDescription,
        image: project.image
      },
      pageProps: {
        project,
        projectSlider,
        serviceSlider,
        showMorePage: 'researches'
      }
    }
  }
}

/**
 * Получение детальной страницы исследования
 * @param slug
 * @returns
 */
const getItem = async (slug: string): Promise<PageProps['project'] | null> => {
  try {
    const { data } = await api.project.detail(slug)
    return data
  } catch (err) {
    console.error(err)
    return null
  }
}
