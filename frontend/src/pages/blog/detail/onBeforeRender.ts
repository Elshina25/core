import api from '@/api'
import { IBeforeRenderReturn } from '@/types/renderer'
import { PageContext } from '~/renderer/types'
import { PageProps } from './types'
import { getCategories as getNewsCategories } from '@/pages/news/list/requests'

export async function onBeforeRender(
  context: PageContext
): Promise<IBeforeRenderReturn<PageProps>> {
  const slug = context.routeParams.slug
  const blog = await getItem(slug)

  const newsCategories = await getNewsCategories()

  const { data: newsSlider } = await api.news.list({
    page: 1,
    image: 0,
    year: newsCategories.year[0].code
  })
  // 404
  if (!blog) {
    console.error('404') // FIXME: Найти решение для отрисовки 404 страницы
    throw new Error()
  }

  return {
    pageContext: {
      documentProps: {
        title: blog.seo.elementMetaTitle,
        description: blog.seo.elementMetaDescription,
        image: blog.detailPicture
      },
      pageProps: {
        blog,
        newsSlider,
        showMorePage: 'news'
      }
    }
  }
}

/**
 * Получение детальной страницы блога
 * @param slug
 * @returns
 */
const getItem = async (slug: string): Promise<PageProps['blog'] | null> => {
  try {
    const { data } = await api.blog.detail(slug)
    return data
  } catch (err) {
    console.error(err)
    return null
  }
}
