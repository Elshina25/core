import { ApiData } from '@/api'
import { ServiceDetailResponse } from '@/api/service/detail'

export interface PageProps {
  service: ApiData<ServiceDetailResponse>
}
