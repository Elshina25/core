import { ESTATE_ROUTES, ROUTE, SERVICE_ROUTES } from '../routes'

/**
 * Услуги (первая колонка)
 */
const MENU_SERVICES = SERVICE_ROUTES

/**
 * Объекты (вторая колонка)
 */
const MENU_ESTATES = ESTATE_ROUTES

/**
 * Разделы (третья колонка)
 */
const MENU_OTHERS = [
  ROUTE.ABOUT,
  ROUTE.RESEARCHES,
  ROUTE.PROJECTS,
  ROUTE.NEWS,
  ROUTE.BLOG,
  ROUTE.VACANCY,
  ROUTE.CONTACTS
]

/**
 * Группировка колонок для отображения всех ссылок
 */
export const MENU_COLUMNS = [
  {
    type: 'services',
    sections: [
      {
        ...ROUTE.SERVICES,
        items: MENU_SERVICES
      }
    ]
  },
  {
    type: 'objects',
    sections: [
      {
        title: ROUTE.ESTATE.title, // TODO: улучшить
        slug: ROUTE.ESTATE.slug + '/office',
        items: MENU_ESTATES
      }
    ]
  },
  {
    type: 'others',
    sections: MENU_OTHERS.map((a) => ({ ...a, items: [] }))
  }
]
