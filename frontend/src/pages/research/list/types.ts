import { ApiData } from '@/api'
import { ResearchCategoriesResponse } from '@/api/research/categories'
import { ResearchListParams, ResearchListResponse } from '@/api/research/list'
import { ServiceListResponse } from '@/api/service/list'

export interface PageProps {
  list: ApiData<ResearchListResponse>
  alsoList: ApiData<ResearchListResponse>
  categories: ApiData<ResearchCategoriesResponse>
  queryParams: ResearchListParams
  serviceSlider: ServiceListResponse
}
