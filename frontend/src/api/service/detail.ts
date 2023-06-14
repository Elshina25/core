import { IProject, ISeo, IPerson, ITag, IServiceGroup } from '@/types'
import { requestPromise } from '@/utils/request'

/**
 * Получение страницы исследования
 * @returns
 */
export default async (slug: string) => {
  return await requestPromise<ServiceDetailResponse>({
    url: `service/detail/${slug}/`,
    method: 'get'
  })
}

/**
 * Структура ответа
 */
export interface ServiceDetailResponse {
  id: number
  name: string
  section?: {
    code: string
    id: string
  }
  forWhom: ITag<string[] | boolean>
  type: ITag<string[] | boolean>
  utp: UtpItem[]
  preview_picture: string
  preview_text: string
  detailText: string
  workers: IPerson[]
  doneProjects: IProject[]
  otherService: IServiceGroup[]
  seo: ISeo
}

interface UtpItem {
  title: string
  desc: string
}
