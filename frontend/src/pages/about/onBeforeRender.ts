import type { IBeforeRenderReturn } from '@/types/renderer'
import { PageProps } from './types'
import api from '@/api'
import { getCategories as getNewsCategories } from '@/pages/news/list/requests'

export async function onBeforeRender(): Promise<
  IBeforeRenderReturn<PageProps>
> {
  const awards = await api.about.awards()
  const newsCategories = await getNewsCategories()

  const { data: news } = await api.news.list({
    page: 1,
    image: 0,
    year: newsCategories.year[0].code
  })

  return {
    pageContext: {
      pageProps: {
        awards,
        news,
        showMorePage: 'services'
      },
      documentProps: {
        title:
          'О компании | CORE.XP - Лидирующая консалтинговая компания в области недвижимости',
        description: 'О компании'
      }
    }
  }
}
