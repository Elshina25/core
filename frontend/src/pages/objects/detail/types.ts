import { ObjectDetailResponse } from '@/api/object/detail'
import { ObjectsResponse } from '@/api/object/list'
import { ApiData } from '@/api'

export interface PageProps {
  object: ObjectDetailResponse
  others: ApiData<ObjectsResponse['objects']>
}
