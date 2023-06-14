import { ApiData } from '@/api'
import { BlogListParams, BlogListResponse } from '@/api/blog/list'
import { IResearch, IServiceGroup } from '@/types'
import { ServiceListResponse } from '@/api/service/list'

export interface PageProps {
  list: ApiData<BlogListResponse>
  queryParams: BlogListParams
  service: IServiceGroup
  research: IResearch
  serviceSlider: ServiceListResponse
}
