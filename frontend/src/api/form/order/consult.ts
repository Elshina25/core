import { requestPromise } from '@/utils/request'
import { FormResponse } from '@/types/api'

/**
 * Отправка формы заказа помощи в подборе
 * @returns
 */
export default async (data: IFormOrderConsultRequest) => {
  return await requestPromise<FormResponse, IFormOrderConsultRequest>({
    url: 'forms/help_in_selection/',
    method: 'post',
    data
  })
}

/**
 * Поля запроса формы заказа помощи в подборе
 */
export interface IFormOrderConsultRequest {
  phone: string

  // скрытые поля
  page: string
  crmId: string | null
}
