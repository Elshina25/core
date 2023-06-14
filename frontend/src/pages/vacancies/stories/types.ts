import { ApiData } from '@/api'
import { ITag } from '@/types'
import { StoryListParams, StoryListResponse } from '@/api/vacancy/story/list'

export interface PageProps {
  list: ApiData<StoryListResponse>
  categories: ITag<string>[]
  queryParams: StoryListParams
}
