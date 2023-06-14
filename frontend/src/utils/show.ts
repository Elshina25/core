import { ROUTE } from '@/routes'
import { IPerson, IServiceItem } from '@/types'
import { IDropdownItem } from '@/types/ui'
import {
  emailLinkFormatter,
  phoneFormatter,
  phoneLinkFormatter
} from '@/utils/index'
import { isActiveLink } from '~/renderer/utils'

/**
 * Отображение квадратного метра м2 -> м²
 * @param str
 * @returns
 */
export const showStringWithSquare = (str: string) => {
  return str.replace('м2', 'м²')
}

/**
 * Если мы находимся активной на странице, то убираем ссылку на нее (делаем текстом)
 * Это нужно для seo, чтобы не было зацикленных ссылок
 * @param slug /about
 * @return a or div
 */
export const getTagByActiveLink = (slug?: string) => {
  return slug && !isActiveLink(slug) ? 'a' : 'div'
}

/**
 * Преобразование контактов в нужный формат
 * @param person
 * @returns
 */
export const getPersonContacts = (person: IPerson): IDropdownItem[] => {
  const phone = {
    icon: 'phone-reverse',
    label: phoneFormatter(person.phone ?? ''),
    value: person.phone ? phoneLinkFormatter(person.phone) : '',
    isTextLink: true
  }

  const whatsapp = {
    icon: 'whatsapp',
    collection: 'socials',
    label: 'WhatsApp',
    value: setValueWithSocialUrl('https://wa.me/', person.whatsapp)
  }

  const telegram = {
    icon: 'tg-rounded',
    collection: 'socials',
    label: 'Telegram',
    value: setValueWithSocialUrl('https://t.me/', person.telegram)
  }

  const viber = {
    icon: 'viber-rounded',
    collection: 'socials',
    label: 'Viber',
    value: setValueWithSocialUrl('viber://chat?number=', person.telegram)
  }

  const email = {
    icon: 'mail-fill',
    label: person.email ?? '',
    value: person.email ? emailLinkFormatter(person.email) : '',
    isTextLink: true
  }

  // Установить значение с прямой ссылкой на соц сеть
  function setValueWithSocialUrl(socialUrl: string, value: string | undefined) {
    if (!value) return ''

    // Если это телефон, то приводим к нужному формату
    if (value.match(/[\W]/g)) {
      return socialUrl + phoneLinkFormatter(value, '')
    }

    // Если это юзернейм, то отображаем как заполнено
    return socialUrl + value
  }

  return [phone, whatsapp, telegram, viber, email].filter((a) => a.value)
}

/**
 * Получение цены объекта
 * @param price
 * @returns
 */
export const getObjectPrice = (price: unknown) => {
  if (!price || price === '-2') {
    return {
      status: null,
      text: 'Нет'
    }
  }

  if (price === '-1') {
    return {
      status: false,
      text: 'По запросу'
    }
  }

  return {
    status: true,
    text: price + ' Р/м²'
  }
}

/**
 * Получение ссылки на детальную страницу услуги
 * @param code
 * @param fastLink
 * @returns
 */
export const getServiceSlug = (
  code: IServiceItem['code'],
  fastLink: IServiceItem['fastLink']
) => {
  return fastLink ? code : `${ROUTE.SERVICES.slug}/${code}`
}
