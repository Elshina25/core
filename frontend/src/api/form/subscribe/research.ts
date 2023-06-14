import { requestPromise } from '@/utils/request'
import { FormResponse } from '@/types/api'
import { ITag } from '@/types'

/**
 * Отправка формы подписки на исследвания
 * @returns
 */
export default async (data: IFormSubscribeResearchRequest) => {
  return await requestPromise<FormResponse, IFormSubscribeResearchRequest>({
    url: 'forms/subscribe_for_report/',
    method: 'post',
    data
  })
}

export interface IFormSubscribeResearchRequest {
  email: string
  check: string
  'sections[]': ITag['code'][]
}
