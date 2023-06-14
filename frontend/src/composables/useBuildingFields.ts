import type { ObjectListResponse } from '@/api/object/list'
import type { IStation } from '@/types/objects'
import { IFilterSpace } from '@/types/filter'
import { computed } from 'vue'
import { getSpace } from '@/utils/filter'

export const useBuildingFields = (building: ObjectListResponse) => {
  // Тип объекта
  const space = computed<IFilterSpace | undefined>(() =>
    getSpace(building.propertyType, 'id')
  )

  // Тип оффера
  const offer = computed<string>(() => {
    const rent = +building.propertyLease ? 'Аренда' : ''
    const sale = +building.propertySale ? 'Продажа' : ''

    return [rent, sale].filter((a) => a.length).join('/')
  })

  // Метро
  const metro = computed<IStation | null>(() => {
    return building.metroStation
      ? {
          code: building.metroStationCode,
          name: building.metroStation,
          distance: +building.distanceFromMetro,
          color: building.metroColor
        }
      : null
  })

  return { space, offer, metro }
}
