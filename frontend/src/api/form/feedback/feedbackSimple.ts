import { requestPromise } from '@/utils/request'
import { FormResponse } from '@/types/api'

/**
 * Отправка формы упрощенной обратной связи
 * @returns
 */
export default async (data: IFormFeedbackSimpleRequest) => {
  return await requestPromise<FormResponse, IFormFeedbackSimpleRequest>({
    url: 'forms/callback/',
    method: 'post',
    data
  })
}

/**
 * Поля запроса формы упрощенной обратной связи
 */
export interface IFormFeedbackSimpleRequest {
  name: string
  phone: string
  email: string
  questions: string

  //скрытые поля
  page: string
}
