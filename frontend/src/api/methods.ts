// Исследования
import researchList from './research/list'
import researchDetail from './research/detail'
import researchCategories from './research/categories'
import researchReportCategories from './research/reportCategories'

//Завершенные проекты
import projectList from './project/list'
import projectCategories from './project/categories'
import projectDetail from './projects/detail'
import projectSlides from './project/slides'

//Блог
import blogList from './blog/list'
import blogCategoryEstate from './blog/categoryEstate'
import blogCategoryOffers from './blog/categoryOffers'
import blogDetail from './blog/detail'

// Услуги
import serviceList from './service/list'
import serviceTypes from './service/types'
import serviceCategories from './service/categories'
import projectsList from './service/projects'
import serviceDetail from './service/detail'

// Объекты
import objectList from './object/list'
import objectDetailPreview from './object/detail-preview'
import objectListCoords from './object/list-coords'
import objectCollectionSpaces from './object/collection/spaces'
import objectCollectionCities from './object/collection/cities'
import objectCollectionCounties from './object/collection/counties'
import objectCollectionMetros from './object/collection/metros'
import objectCollectionDistricts from './object/collection/districts'
import objectCollectionClasses from './object/collection/classes'
import objectCollectionDirections from './object/collection/directions'
import objectDetail from './object/detail'
import objectFavorites from './object/favorites'
import objectSearch from './object/search'

// Страница "О нас"
import aboutTeam from './about/team'
import aboutAwards from './about/awards'
import aboutClients from './about/clients'
import aboutFactoids from './about/factoids'

// Формы
import formOrderConsult from './form/order/consult'
import formOrderResearch from './form/order/research'
import formOrderReview from './form/order/review'
import formRequestPrice from './form/request/price'
import formRequestResearch from './form/request/research'
import formFeedbackSimple from './form/feedback/feedbackSimple'
import formFeedbackMain from './form/feedback/feedbackMain'
import formFeedbackTopics from './form/feedback/topics'
import formSubscribeResearch from './form/subscribe/research'

// Новости
import newsList from './news/list'
import newsCategories from './news/categories'
import newsDetail from './news/detail'

//Вакансии
import vacancyOffers from './vacancy/offers'
import vacancyList from './vacancy/list'

//Истории сотрудников
import storyList from './vacancy/story/list'
import storyCategories from './vacancy/story/categories'

//Поиск
import searchList from './search/list'

// Поиск адреса
import searchAddress from './address/search'

// Поиск SEO-текста
import searchSeoText from './seo/text'

/**
 * Методы API.
 *
 * Используем тольно через api. Дергать метод напрямую запрещено!
 * @example
 * ```ts
 * // BAD
 * import categories from '@/api/research/categories'
 * await categories()
 *
 * // GOOD
 * import api from '@/api'
 * await api.research.categories()
 * ```
 */
export default {
  research: {
    list: researchList,
    detail: researchDetail,
    categories: researchCategories,
    reportCategories: researchReportCategories
  },
  project: {
    detail: projectDetail,
    list: projectList,
    categories: projectCategories,
    slides: projectSlides
  },
  service: {
    list: serviceList,
    projects: projectsList,
    types: serviceTypes,
    categories: serviceCategories,
    detail: serviceDetail
  },
  blog: {
    list: blogList,
    categoryEstate: blogCategoryEstate,
    categoryOffers: blogCategoryOffers,
    detail: blogDetail
  },
  object: {
    listCoords: objectListCoords,
    detailPreview: objectDetailPreview,
    list: objectList,
    detail: objectDetail,
    favorites: objectFavorites,
    search: objectSearch,
    collection: {
      spaces: objectCollectionSpaces,
      cities: objectCollectionCities,
      counties: objectCollectionCounties,
      metros: objectCollectionMetros,
      districts: objectCollectionDistricts,
      classes: objectCollectionClasses,
      directions: objectCollectionDirections
    }
  },
  news: {
    list: newsList,
    detail: newsDetail,
    categories: newsCategories
  },
  vacancy: {
    story: {
      list: storyList,
      categories: storyCategories
    },
    offers: vacancyOffers,
    list: vacancyList
  },
  about: {
    team: aboutTeam,
    awards: aboutAwards,
    clients: aboutClients,
    factoids: aboutFactoids
  },
  form: {
    feedback: {
      main: formFeedbackMain,
      topics: formFeedbackTopics,
      simple: formFeedbackSimple
    },
    order: {
      consult: formOrderConsult,
      research: formOrderResearch,
      review: formOrderReview
    },
    request: {
      price: formRequestPrice,
      research: formRequestResearch
    },
    subscribe: {
      research: formSubscribeResearch
    }
  },
  search: {
    list: searchList
  },
  address: {
    search: searchAddress
  },
  seo: {
    text: searchSeoText
  },
}
