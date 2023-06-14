import api from '@/api'
import { IBeforeRenderReturn } from '@/types/renderer'
import { PageContext } from '~/renderer/types'
import { PageProps } from '@/pages/news/detail/types'
import { getList } from '@/pages/news/list/requests'
import { convertStringToDate } from '@/utils'

export async function onBeforeRender(
  context: PageContext
): Promise<IBeforeRenderReturn<PageProps>> {
  const slug = context.routeParams.slug
  const news = await getItem(slug)

  const params = {
    page: 1,
    exclude: slug,
    image: 1,
    year: news?.date ? convertStringToDate(news.date).getFullYear() : ''
  }
  const { items: newsSlider } = await getList(params)

  // 404
  if (!news) {
    console.error('404') // FIXME: Найти решение для отрисовки 404 страницы
    throw new Error()
  }

  return {
    pageContext: {
      documentProps: {
        title: news.seo?.elementMetaTitle,
        description: news.seo?.elementMetaDescription,
        image: news.image
      },
      pageProps: {
        news,
        newsSlider,
        showMorePage: 'blog'
      }
    }
  }
}

/**
 * Получение детальной страницы новостей
 * @param slug
 * @returns
 */
const getItem = async (slug: string): Promise<PageProps['news'] | null> => {
  try {
    const { data } = await api.news.detail(slug)
    return data
  } catch (err) {
    console.error(err)
    return null
  }
}
