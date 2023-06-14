import { IPerson, IResearchFactoid, ITag } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение страницы исследования
 * @returns
 */
export default async (slug: string) => {
  return await requestPromise<ResearchDetailResponse>({
    url: `research/detail/${slug}/`,
    method: 'get'
  })
}

/**
 * Структура ответа
 */
export interface ResearchDetailResponse {
  id: number
  name: string
  image: string
  section: ITag<string>
  type: ITag<string>
  closed: number
  short: number
  date: string
  detailText: string
  newBuilding: string
  dealValue: string
  freePlace: string
  averagePrice: string
  authors: IPerson[]
  facts: IResearchFactoid[]
}
