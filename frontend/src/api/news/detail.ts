import { requestPromise } from '@/utils/request'
import { IPerson, ITag, ISeo } from '@/types'

/**
 * Получение данных для детальной страницы новости
 * @returns
 */
export default async (slug: string) => {
  return await requestPromise<NewsDetailResponse>({
    url: `news/detail/${slug}/`,
    method: 'get'
  })
}

/**
 * Структура ответа
 */
export interface NewsDetailResponse {
  id: number
  name: string
  slug: string
  image: string
  date: string
  topic: ITag<string>
  detailText: string
  author: IPerson[]
  seo: ISeo
}
