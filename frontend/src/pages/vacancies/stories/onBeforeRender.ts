import type { IBeforeRenderReturn } from '@/types/renderer'
import type { PageProps } from './types'
import { getCategories, getList } from './requests'
import { StoryListParams } from '@/api/vacancy/story/list'

export async function onBeforeRender(): Promise<
  IBeforeRenderReturn<PageProps>
> {
  const categories = await getCategories()

  const queryParams: StoryListParams = {
    topic: categories[0]?.code
  }

  const list = await getList(queryParams)

  return {
    pageContext: {
      pageProps: {
        list,
        categories,
        queryParams,
        showMorePage: 'about'
      },
      documentProps: {
        title:
          'Истории сотрудников CORE.XP. Лидирующая консалтинговая компания в области недвижимости',
        description:
          'Истории сотрудников CORE.XP | CORE.XP — лидирующая консалтинговая компания в области недвижимости'
      }
    }
  }
}
