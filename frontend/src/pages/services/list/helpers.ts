import { ServiceListParams } from '@/api/service/list'
import { PageContext } from '~/renderer/types'
import { PageProps } from './types'

/**
 * Получение активных параметров запроса getList
 * @param context
 * @returns
 */
export const getParams = (payload: Payload): Response => {
  const { context, categories, types } = payload
  const { forWhom, type } = context.urlParsed.search

  /**
   * Значения по умолчанию
   */
  const defaultParams = {
    forWhom: categories[0]?.code,
    type: types[0]?.code
  }

  /**
   * Значения из урла
   * или установка дефолтных (если в get нет значений)
   */
  const queryParams = {
    forWhom: forWhom ?? defaultParams.forWhom,
    type: type ?? defaultParams.type
  }

  return {
    queryParams,
    defaultParams
  }
}

interface Payload {
  context: PageContext
  categories: PageProps['categories']
  types: PageProps['types']
}

interface Response {
  queryParams: ServiceListParams
  defaultParams: ServiceListParams
}
