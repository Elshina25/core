import type { IBeforeRenderReturn } from '@/types/renderer'
import type { PageContext } from '~/renderer/types'
import type { PageProps } from '@/pages/blog/list/types'
import { getList } from '@/pages/blog/list/requests'
import { getList as getServicesList } from '@/pages/services/list/requests'
import {
  getList as getResearchList,
  getCategories as getResearchCategories
} from '@/pages/research/list/requests'
import { BlogListParams } from '@/api/blog/list'

export async function onBeforeRender(
  context: PageContext
): Promise<IBeforeRenderReturn<PageProps>> {
  const { queryParams } = getParams(context)

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
        queryParams,
        serviceSlider,
        showMorePage: 'news'
      },
      documentProps: {
        title:
          'Блог | CORE.XP — Лидирующая консалтинговая компания в области недвижимости',
        description:
          'Интересная и полезная информация по рынку коммерческой недвижимости: офисная, торговая, складская, инвестиции и пр.'
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
  context: PageContext
): {
  queryParams: BlogListParams
  defaultParams: BlogListParams
} => {
  const defaultParams: BlogListParams = {
    page: 1
  }

  // Получаем значения из урла,
  // и устанавливаем дефолтные (если в get нет значений)
  const { page = defaultParams.page } = context.urlParsed.search

  return {
    queryParams: {
      page: +page
    },
    defaultParams
  }
}
