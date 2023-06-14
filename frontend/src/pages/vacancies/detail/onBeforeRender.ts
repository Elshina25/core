import type { IBeforeRenderReturn } from '@/types/renderer'
import type { PageProps } from './types'
import {
  getCategories as getStoryCategories,
  getList as getStoryList
} from '@/pages/vacancies/stories/requests'
import { StoryListParams } from '@/api/vacancy/story/list'
import { getList, getOffers } from '@/pages/vacancies/detail/requests'
import { OFFER_LIMIT } from '@/config/vacancy.config'
import { OfferListParams } from '@/api/vacancy/offers'

export async function onBeforeRender(): Promise<
  IBeforeRenderReturn<PageProps>
> {
  const storyCategories = await getStoryCategories()

  const storyQueryParams: StoryListParams = {
    topic: storyCategories[0]?.code
  }
  const storyList = await getStoryList(storyQueryParams)

  const offerQueryParams: OfferListParams = {
    limit: OFFER_LIMIT
  }
  const offerList = await getOffers(offerQueryParams)

  const list = await getList()

  return {
    pageContext: {
      pageProps: {
        storyCategories,
        storyList,
        offerList,
        list,
        queryParams: storyQueryParams,
        showMorePage: 'about'
      },
      documentProps: {
        title:
          'Вакансии CORE.XP. Лидирующая консалтинговая компания в области недвижимости',
        description:
          'Вакансии CORE.XP | CORE.XP — лидирующая консалтинговая компания в области недвижимости'
      }
    }
  }
}
