import { emailLinkFormatter, phoneFormatter, phoneLinkFormatter } from '@/utils'
import { ADDRESS, EMAIL, PHONE, SOCIALS } from './main.config'
import { ROUTE, SERVICE_ROUTES } from '../routes'
import { AGREEMENT_LINK } from './form.config'

interface IFooterItem {
  icon?: string
  title: string
  slug?: string
  notice?: {
    color: 'green'
    text: string
  }
}

/**
 * Контакты (первая колонка)
 */
const FOOTER_CONTACTS: IFooterItem[] = [
  {
    icon: 'phone',
    title: phoneFormatter(PHONE),
    slug: phoneLinkFormatter(PHONE)
  },
  {
    icon: 'mail',
    title: EMAIL,
    slug: emailLinkFormatter(EMAIL)
  },
  {
    icon: 'map',
    title: ADDRESS
  }
]

/**
 * Услуги (вторая колонка)
 */
const FOOTER_SERVICES: IFooterItem[] = SERVICE_ROUTES

/**
 * Разделы (третья колонка)
 */
const FOOTER_SECTIONS: IFooterItem[] = [
  ROUTE.ABOUT,
  {
    ...ROUTE.VACANCY,
    notice: {
      color: 'green',
      text: 'Мы нанимаем'
    }
  },
  ROUTE.PROJECTS,
  ROUTE.BLOG,
  ROUTE.NEWS,
  ROUTE.CONTACTS
]

/**
 * Группировка колонок для отображения всех ссылок
 */
export const FOOTER_COLUMNS = [
  {
    type: 'contacts',
    title: 'Наши контакты',
    items: FOOTER_CONTACTS,
    socials: SOCIALS
  },
  {
    type: 'services',
    title: ROUTE.SERVICES.title,
    slug: ROUTE.SERVICES.slug,
    items: FOOTER_SERVICES
  },
  {
    type: 'sections',
    title: 'Компания',
    items: FOOTER_SECTIONS
  }
]

export const FOOTER_PRIVACY_POLICY = {
  title: 'Политика конфиденциальности',
  slug: AGREEMENT_LINK
}
