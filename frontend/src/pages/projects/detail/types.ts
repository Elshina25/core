import { ApiData } from '@/api'
import { ServiceListResponse } from '@/api/service/list'
import { ProjectDetailResponse } from '@/api/projects/detail'
import { ProjectListResponse } from '@/api/project/list'

export interface PageProps {
  project: ApiData<ProjectDetailResponse>
  projectSlider: ApiData<ProjectListResponse['items']>
  serviceSlider: ServiceListResponse
}
