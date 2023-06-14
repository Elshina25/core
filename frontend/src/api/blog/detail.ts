import { IBlog, IPerson, ISeo } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение детальной страницы блога
 * @returns
 */
export default async (slug: string) => {
  return await requestPromise<BlogDetailResponse>({
    url: `blog/detail/${slug}/`,
    method: 'get'
  })
}

/**
 * Структура ответа
 */
export interface BlogDetailResponse {
  id: number
  detailPicture: string
  detailText: string
  activeFrom: string
  name: string
  author: IPerson
  otherArticles: IBlog[]
  seo: ISeo
}
