import type { ObjectListParams } from '@/api/object/list'
import type { ObjectsCoordsResponse } from '@/api/object/list-coords'
import { COORDS_LIST_LIMIT } from '@/config/objects.config'
import { getListCoords } from '@/pages/objects/search/requests'
import { reactive } from 'vue'

export const useObjectsCoords = () => {
  // Объект с списком координат
  const listCoords = reactive<ObjectsCoordsResponse>({
    count: 0,
    limit: '',
    offset: '',
    objects: []
  })

  // Метод обновления списка координат
  const updateListCoords = async (params: ObjectListParams) => {
    // Запрашиваем и сохраняем первую страницу списка координат
    Object.assign(listCoords, await getListCoords(params, COORDS_LIST_LIMIT))
    // Кол-во страниц
    const pagesCount = Math.ceil(listCoords.count / COORDS_LIST_LIMIT)
    // Запрашиваем оставшиеся координаты
    for (let i = 1; i < pagesCount; i++) {
      getListCoords({ ...params, page: i + 1 }, COORDS_LIST_LIMIT).then(
        (res) => {
          listCoords.objects.push(...res.objects)
        }
      )
    }
  }

  return { listCoords, updateListCoords }
}
