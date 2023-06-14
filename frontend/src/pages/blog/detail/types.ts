import { ApiData } from '@/api'
import { BlogDetailResponse } from '@/api/blog/detail'
import { NewsListResponse } from '@/api/news/list'

export interface PageProps {
  blog: ApiData<BlogDetailResponse>
  newsSlider: NewsListResponse
}
