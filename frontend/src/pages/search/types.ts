import { ApiData } from '@/api'
import type {
  ISearchInfoResponse,
  ISearchObjectsResponse
} from '@/api/search/list'

export interface IParams {
  pageInfo: number
  pageObjects: number
  query: string
}

export interface PageProps {
  objects: ApiData<ISearchObjectsResponse>
  info: ApiData<ISearchInfoResponse>
  params: IParams
}
