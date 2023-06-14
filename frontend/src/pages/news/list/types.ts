import { ApiData } from '@/api'
import { NewsListParams, NewsListResponse } from '@/api/news/list'
import { IResearch, IServiceGroup } from '@/types'
import { NewsCategoriesResponse } from '@/api/news/categories'

export interface PageProps {
  list: ApiData<NewsListResponse>
  queryParams: NewsListParams
  service: IServiceGroup
  research: IResearch
  categories: ApiData<NewsCategoriesResponse>
}
