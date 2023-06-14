import { EMAIL, PHONE } from './main.config'
import type { IMap } from '@/types'

interface ContactsRequisite {
  label: string
  value: string
}

/**
 * Номер телефона
 */
export const CONTACTS_PHONE = PHONE

/**
 * Адрес электронной почты
 */
export const CONTACTS_EMAIL = EMAIL

/**
 * Адрес
 * @important Использовать через v-html
 */
export const CONTACTS_ADDRESS = `Россия, 123112&nbsp;Москва, БЦ&nbsp;ОКО, ММДЦ Москва-Сити 1-й Красногвардейский проезд, дом&nbsp;21, строение 1, 29&nbsp;этаж`

/**
 * Часы работы
 * @important Использовать через v-html
 */
export const CONTACTS_WORK_TIME =
  'Часы работы: по&nbsp;будням 08:00&ndash;20:00'

/**
 * Карта
 */
export const CONTACTS_MAP: IMap = {
  coords: [
    {
      latitude: 55.749765,
      longitude: 37.534325
    }
  ],
  zoom: 15
}

/**
 * Реквизиты
 */
export const CONTACTS_REQUISITES: ContactsRequisite[] = [
  {
    label: 'Получатель',
    value: 'ООО «КОР ЭКС ПИ» (CORE.XP, LLC)'
  },
  {
    label: 'Р/с',
    value: '40702810300001404919 в АО «Райффайзенбанк»'
  },
  {
    label: 'Адрес банка',
    value: 'г. Москва'
  },
  {
    label: 'К/счет',
    value: '30101810200000000700'
  },
  {
    label: 'ИНН',
    value: '7714624720'
  },
  {
    label: 'КПП',
    value: '770301001'
  },
  {
    label: 'БИК',
    value: '044525700'
  },
  {
    label: 'ОГРН',
    value: '1057748959454'
  }
]
