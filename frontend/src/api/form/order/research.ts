import { requestPromise } from '@/utils/request'
import { FormResponse } from '@/types/api'

/**
 * Отправка формы заказа исследования
 * @returns
 */
export default async (data: IFormOrderResearchRequest) => {
  return await requestPromise<FormResponse, IFormOrderResearchRequest>({
    url: 'forms/research/',
    method: 'post',
    data
  })
}

/**
 * Поля запроса формы заказа исследования
 */
export interface IFormOrderResearchRequest {
  name: string
  phone: string
  email: string
  questions: string
  companyName: string // необязательное поле
}
