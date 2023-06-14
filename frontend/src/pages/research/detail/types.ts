import { ApiData } from '@/api'
import { ResearchDetailResponse } from '@/api/research/detail'
import { ResearchListResponse } from '@/api/research/list'

export interface PageProps {
  research: ApiData<ResearchDetailResponse>
  researchList: ApiData<ResearchListResponse['items']>
}
