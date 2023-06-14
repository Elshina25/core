import { IFeedbackTopic } from '@/types'

export const GREEN_THEME = 'green'

export const BLUE_THEME = 'blue'

export const PHONE_MASKA = '+7 ### ###-##-##'

// Темы в форме "Обсудить задачу", у которых есть дополнительный подмассив элементов
export const FEEDBACK_TOPICS_WITH_MORE: IFeedbackTopic['code'][] = [
  'rent',
  'sell',
  'buy',
  'object_valuation',
  'invest'
]

// Ссылка на условия обработки личных данных
// TODO: запросить файл/страницу
export const AGREEMENT_LINK = '/privacy-policy'
