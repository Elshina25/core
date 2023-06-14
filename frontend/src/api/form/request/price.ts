import { requestPromise } from '@/utils/request'
import { FormResponse } from '@/types/api'

/**
 * Отправка формы запроса цены площади
 * @returns
 */
export default async (data: IFormRequestPriceRequest) => {
  return await requestPromise<FormResponse, IFormRequestPriceRequest>({
    url: 'forms/request_price/',
    method: 'post',
    data
  })
}

/**
 * Поля запроса формы заявки на просмотр
 */
export interface IFormRequestPriceRequest {
  name: string
  phone: string
  email: string
  companyName: string // необязательное поле
  comment: string // необязательное поле

  //скрытые поля
  page: string
  crmId: string
}
