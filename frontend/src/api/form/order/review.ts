import { requestPromise } from '@/utils/request'
import { FormResponse } from '@/types/api'

/**
 * Отправка формы заявки на просмотр
 * @returns
 */
export default async (data: IFormOrderReviewRequest) => {
  return await requestPromise<FormResponse, IFormOrderReviewRequest>({
    url: 'forms/request_for_view/',
    method: 'post',
    data
  })
}

/**
 * Поля запроса формы заявки на просмотр
 */
export interface IFormOrderReviewRequest {
  name: string
  phone: string
  email: string
  square: string
  message: string // необязательное поле

  //скрытые поля
  page: string
  crmId: string
}
