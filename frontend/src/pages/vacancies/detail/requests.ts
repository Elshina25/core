import { PageProps } from '@/pages/vacancies/detail/types'
import api from '@/api'
import { OfferListParams } from '@/api/vacancy/offers'

/**
 * Получение списка предложений для раздела "Мы предлагаем"
 * @returns
 */
export const getOffers = async (
  params: OfferListParams
): Promise<PageProps['offerList']> => {
  try {
    const { data } = await api.vacancy.offers(params)

    return data
  } catch (err) {
    console.error('Ошибка получения списка предложений', err)

    return {
      items: [],
      count: 0,
      limit: 0,
      offset: 0
    }
  }
}

/**
 * Получение списка вакансий
 * @returns
 */
export const getList = async (): Promise<PageProps['list']> => {
  try {
    const { data } = await api.vacancy.list()

    return data
  } catch (err) {
    console.error('Ошибка получения списка вакансия', err)

    return {
      items: [],
      count: 0,
      limit: 0,
      offset: 0
    }
  }
}
