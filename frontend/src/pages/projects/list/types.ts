import { ApiData } from '@/api'
import { ServiceListResponse } from '@/api/service/list'
import { ProjectListParams, ProjectListResponse } from '@/api/project/list'
import { ProjectCategoriesResponse } from '@/api/project/categories'
import { IResearch, IServiceGroup } from '@/types'

export interface PageProps {
  list: ApiData<ProjectListResponse>
  categories: ApiData<ProjectCategoriesResponse>
  queryParams: ProjectListParams
  serviceSlider: ServiceListResponse
  service: IServiceGroup
  research: IResearch
}
