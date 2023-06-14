import type { IBeforeRenderReturn } from '@/types/renderer'
import type { PageContext } from '~/renderer/types'
import type { PageProps } from '@/pages/projects/list/types'
import { getCategories, getList } from '@/pages/projects/list/requests'
import { getList as getServicesList } from '@/pages/services/list/requests'
import {
  getList as getResearchList,
  getCategories as getResearchCategories
} from '@/pages/research/list/requests'
import { ProjectListParams } from '@/api/project/list'

export async function onBeforeRender(
  context: PageContext
): Promise<IBeforeRenderReturn<PageProps>> {
  const categories = await getCategories()

  const { queryParams } = getParams(context, categories)

  // Список категорий для отображение в листинге (если что-то найдено)
  const list = await getList(queryParams)

  const serviceSlider = await getServicesList()
  const service = serviceSlider[0]

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
        service,
        research,
        categories,
        queryParams,
        serviceSlider,
        showMorePage: 'researches'
      },
      documentProps: {
        title:
          'Реализованные проекты | CORE.XP — Лидирующая консалтинговая компания в области недвижимости',
        description:
          'Реализованные проекты международной консалтинговой компании CORE.XP | Продажа офисных и складских помещений'
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
  queryParams: ProjectListParams
  defaultParams: ProjectListParams
} => {
  const defaultParams: ProjectListParams = {
    page: 1,
    section: categories.sections[0].code,
    type: categories.types[0].code
  }

  // Получаем значения из урла,
  // и устанавливаем дефолтные (если в get нет значений)
  const {
    page = defaultParams.page,
    section = defaultParams.section,
    type = defaultParams.type
  } = context.urlParsed.search

  return {
    queryParams: {
      section,
      type,
      page: +page
    },
    defaultParams
  }
}
