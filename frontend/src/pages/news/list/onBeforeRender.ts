import type { IBeforeRenderReturn } from '@/types/renderer'
import type { PageContext } from '~/renderer/types'
import type { PageProps } from '@/pages/news/list/types'
import { getCategories, getList } from './requests'
import { NewsListParams } from '@/api/news/list'
import { getList as getServicesList } from '@/pages/services/list/requests'
import {
  getCategories as getResearchCategories,
  getList as getResearchList
} from '@/pages/research/list/requests'

export async function onBeforeRender(
  context: PageContext
): Promise<IBeforeRenderReturn<PageProps>> {
  const categories = await getCategories()

  const { queryParams } = getParams(context, categories)

  // Список категорий для отображение в листинге (если что-то найдено)
  const list = await getList(queryParams)

  const serviceList = await getServicesList()
  const service = serviceList[0]

  const researchCategories = await getResearchCategories()
  //TODO: возможно стоит вынести получение дефолтных параметров в отдельную функцию
  const { items: researchList } = await getResearchList({
    page: 1,
    section: researchCategories.sections[0].code,
    type: researchCategories.types[0].code,
    year: researchCategories.year[0].code
  })
  const research = researchList[0]

  return {
    pageContext: {
      pageProps: {
        list,
        research,
        service,
        categories,
        queryParams,
        showMorePage: 'blog'
      },
      documentProps: {
        title:
          'Новости | CORE.XP — Лидирующая консалтинговая компания в области недвижимости',
        description:
          'Новости международной консалтинговой компании CORE.XP. Продажа офисных и складских помещений'
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
  queryParams: NewsListParams
  defaultParams: NewsListParams
} => {
  const defaultParams: NewsListParams = {
    page: 1,
    image: 1,
    year: categories.year[0].code
  }

  // Получаем значения из урла,
  // и устанавливаем дефолтные (если в get нет значений)
  const {
    page = defaultParams.page,
    image = defaultParams.image,
    year = defaultParams.year
  } = context.urlParsed.search

  return {
    queryParams: {
      page: +page,
      image: +image,
      year
    },
    defaultParams
  }
}
