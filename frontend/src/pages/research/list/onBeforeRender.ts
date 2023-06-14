import type { ResearchListParams } from '@/api/research/list'
import type { IBeforeRenderReturn } from '@/types/renderer'
import type { PageContext } from '~/renderer/types'
import type { PageProps } from './types'
import { getCategories, getList } from './requests'
import isEqual from 'lodash-es/isEqual'
import { getList as getServicesList } from '@/pages/services/list/requests'

export async function onBeforeRender(
  context: PageContext
): Promise<IBeforeRenderReturn<PageProps>> {
  const categories = await getCategories()

  const { queryParams, defaultParams } = getParams(context, categories)

  // Список исследований для отображение в листинге (если что-то найдено)
  const list = await getList(queryParams)

  // Список исследований для отображения в слайдере (если ничего не найдено)
  const alsoList = !isEqual(queryParams, defaultParams)
    ? await getList(defaultParams)
    : { ...list }

  const serviceSlider = await getServicesList()

  return {
    pageContext: {
      pageProps: {
        list,
        alsoList,
        categories,
        queryParams,
        serviceSlider,
        showMorePage: 'blog'
      },
      documentProps: {
        title:
          'Аналитика | CORE.XP — Лидирующая консалтинговая компания в области недвижимости',
        description:
          'Аналитика от международной консалтинговой компании CORE.XP | Продажа офисных и складских помещений'
      }
    }
  }
}

/**
 * Получение параметров запроса getList
 * @param context
 * @returns
 */
const getParams = (
  context: PageContext,
  categories: PageProps['categories']
): {
  queryParams: ResearchListParams
  defaultParams: ResearchListParams
} => {
  const defaultParams: ResearchListParams = {
    page: 1,
    section: categories.sections[0].code,
    type: categories.types[0].code,
    year: categories.year[0].code
  }

  // Получаем значения из урла,
  // и устанавливаем дефолтные (если в get нет значений)
  const {
    page = defaultParams.page,
    section = defaultParams.section,
    type = defaultParams.type,
    year = defaultParams.year
  } = context.urlParsed.search

  return {
    queryParams: {
      section,
      type,
      year,
      page: +page
    },
    defaultParams
  }
}
