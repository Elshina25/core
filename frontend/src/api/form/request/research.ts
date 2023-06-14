import { requestPromise } from '@/utils/request'
import { FormResponse } from '@/types/api'

/**
 * Отправка формы запроса доступа к исследованию
 * @returns
 */
export default async (data: IFormRequestResearchRequest) => {
  return await requestPromise<FormResponse, IFormRequestResearchRequest>({
    url: 'forms/request_for_research/',
    method: 'post',
    data
  })
}

export interface IFormRequestResearchRequest {
  name: string
  phone: string
  email: string
  companyName: string // необязательное поле
  comment: string // необязательное поле

  // скрытые поля
  id: number
  page: string
  title: string
}
