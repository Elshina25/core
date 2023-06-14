import { ApiData } from '@/api'
import { NewsDetailResponse } from '@/api/news/detail'
import { NewsListResponse } from '@/api/news/list'

export interface PageProps {
  news: ApiData<NewsDetailResponse>
  newsSlider: ApiData<NewsListResponse['items']>
}
