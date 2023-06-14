import { requestPromise } from '@/utils/request'
import { FormResponse } from '@/types/api'

/**
 * Отправка формы обратной связи
 * @returns
 */
export default async (data: IFormFeedbackMainRequest) => {
  return await requestPromise<FormResponse, IFormFeedbackMainRequest>({
    url: 'forms/discuss_task/',
    method: 'post',
    data
  })
}

/**
 * Поля запроса формы обратной связи
 */
export interface IFormFeedbackMainRequest {
  interest?: string
  type?: string
  name: string
  phone: string
  companyName: string
  email: string
  comment: string // необязательное поле

  //скрытые поля
  page: string
}
