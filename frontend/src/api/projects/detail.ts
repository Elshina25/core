import { IFormLabels, IPerson, ITag } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение детальной страницы проекта
 * @returns
 */
export default async (slug: string) => {
  return await requestPromise<ProjectDetailResponse>({
    url: `projects/detail/${slug}/`,
    method: 'get'
  })
}

/**
 * Структура ответа
 */
export interface ProjectDetailResponse {
  id: number
  name: string
  image: string
  linkedServices: ITag<string>[]
  section: ITag<string>
  type: ITag<string>
  detailText: string
  quoteText: string
  quoteAuthor: string
  photos: string[]
  persons: IPerson[]
  date: string
  form: IFormLabels
  metaDescription: string
  metaKeywords: string
  metaTitle: string
}
