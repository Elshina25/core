import { Api } from '@/api'
import { IFilterClassName, IFilterClassType } from '@/types/filter'

/**
 * Получение списка классов
 * @returns
 */
export default async () => {
  // FIXME: когда-то должно приходить с бека. Нужно будет заменить на реальный запрос!
  // TODO: поменял b_plus на b+ для запроса, нужно придумать где держать b_plus для slug
  return await Promise.resolve<Api<ObjectClassesResponse[]>>({
    data: [
      { id: 1, type: 'a', name: 'A' },
      { id: 2, type: 'b', name: 'B' },
      { id: 3, type: 'b+', name: 'B+' },
      { id: 4, type: 'c', name: 'C' }
    ],
    status: 'success',
    errors: []
  })
}

export interface ObjectClassesResponse {
  id: number
  type: IFilterClassType
  name: IFilterClassName
}
