import { ApiData } from '@/api'
import { ITag } from '@/types'
import { StoryListParams, StoryListResponse } from '@/api/vacancy/story/list'
import { OfferListResponse } from '@/api/vacancy/offers'
import { VacancyListResponse } from '@/api/vacancy/list'

export interface PageProps {
  storyList: ApiData<StoryListResponse>
  storyCategories: ITag<string>[]
  offerList: ApiData<OfferListResponse>
  queryParams: StoryListParams
  list: VacancyListResponse
}
