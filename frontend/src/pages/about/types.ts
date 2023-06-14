import { ApiData } from '@/api'
import { IAward } from '@/types'
import { NewsListResponse } from '@/api/news/list'

export interface PageProps {
  awards: ApiData<IAward[]>
  news: NewsListResponse
}
