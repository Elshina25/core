import { ApiData } from '@/api'
import { ServiceCategoriesResponse } from '@/api/service/categories'
import { ServiceListParams, ServiceListResponse } from '@/api/service/list'
import { ServiceProjectsResponse } from '@/api/service/projects'
import { ServiceTypesResponse } from '@/api/service/types'

export interface PageProps {
  list: ApiData<ServiceListResponse>
  projects: ApiData<ServiceProjectsResponse>
  categories: ApiData<ServiceCategoriesResponse>
  types: ApiData<ServiceTypesResponse>
  queryParams: ServiceListParams
}
