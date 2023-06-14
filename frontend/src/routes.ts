import {
  FILTER_SPACES,
  FILTER_CITY_DEFAULT_ID
} from './config/filter/filter.config'

export type RouteKey =
  | 'home'
  | 'about'
  | 'researches'
  | 'estate'
  | 'search'
  | 'services'
  | 'projects'
  | 'contacts'
  | 'vacancy'
  | 'blog'
  | 'news'
  | 'favorites'

export interface RouteData {
  title: string
  slug: string
}

export const ROUTE: Record<Uppercase<RouteKey>, RouteData> = {
  HOME: {
    title: 'Главная',
    slug: '/'
  },
  ABOUT: {
    title: 'О компании',
    slug: '/about'
  },
  RESEARCHES: {
    title: 'Исследования',
    slug: '/analytics'
  },
  ESTATE: {
    title: 'Объекты',
    slug: '/estate'
  },
  SEARCH: {
    title: 'Поиск',
    slug: '/search'
  },
  SERVICES: {
    title: 'Услуги',
    slug: '/services'
  },
  VACANCY: {
    title: 'Вакансии',
    slug: '/vacancy'
  },
  CONTACTS: {
    title: 'Контакты',
    slug: '/contacts'
  },
  PROJECTS: {
    title: 'Реализованные проекты',
    slug: '/projects'
  },
  BLOG: {
    title: 'Блог',
    slug: '/blog'
  },
  NEWS: {
    title: 'Новости',
    slug: '/news'
  },
  FAVORITES: {
    title: 'Избранное',
    slug: '/favorites'
  }
}

/**
 * Ссылки на самые популярные услуги (меню, футер)
 */
// TODO: slugs через API
export const SERVICE_ROUTES: RouteData[] = [
  {
    title: 'Консалтинг',
    slug: '/services/#consulting'
  },
  {
    title: 'Сопровождение сделок',
    slug: '/services/#accompaniment'
  },
  {
    title: 'Эксплуатация и управление объектами',
    slug: '/services/#exploitation'
  },
  {
    title: 'Управление проектами строительства и внутренней отделкой',
    slug: '/services/#control'
  },
  {
    title: 'Подбор объектов недвижимости',
    slug: '/services/#selection'
  }
]

/**
 * Ссылки на самые популярные типы объектов
 */
// TODO: формирование ссылки через единую функцию
export const ESTATE_ROUTES: RouteData[] = FILTER_SPACES.map((space) => {
  return {
    title: space.name,
    slug: `${ROUTE.ESTATE.slug}/${space.type}/rent/?page=1&city=${FILTER_CITY_DEFAULT_ID}`
  }
})
