import { pluralize } from '@/utils'
import { getSpace } from './filter'
import { useFilterClassStore } from '@/stores/filter/classes'
import { FILTER_SPACES_DECLINATIONS } from '@/config/filter/filter.config'
import { PHONE } from '@/config/main.config'

/**
 * Получение seo для страницы с списком объектов
 * @param offer - тип сделки
 * @param spaceId - тип помещения
 * @param classType - класс помещения
 * @returns object
 */
export const getPageSeo = (
  offer = 'rent',
  spaceId: string,
  classType?: string,
  heading?: string
): Record<string, string> => {
  // Тип сделки
  const offerLabel = offer === 'rent' ? 'Аренда' : 'Продажа'

  // Тип объекта
  const spaceType = getSpace(spaceId, 'id')?.type

  // label типа объекта
  const typeLabel = spaceType
    ? pluralize(5, FILTER_SPACES_DECLINATIONS[spaceType], false)
    : ''

  // Label класса
  const classLabel = getClassLabel()

  /**
   * Получение label класса
   * @returns
   */
  function getClassLabel() {
    if (classType) {
      const store = useFilterClassStore()
      // Название класса
      const className = store.convertedOptions.find(
        (el) => el.id === classType
      )?.name

      return className ? ` класса ${className}` : ''
    }

    return ''
  }

  /**
   * Формируем title
   * @returns
   */
  const generateTitle = () => {
    return `${offerLabel} ${typeLabel}${classLabel} ${heading ?? ''} - CORE.XP`
  }

  /**
   * Формируем description
   * @returns
   */
  const generateDescription = () => {
    // Тип сделки для description
    const descriptionOfferLabel = offer === 'rent' ? 'аренде' : 'продаже'

    return `${offerLabel} ${typeLabel}${classLabel}. Подберем оптимальный для Вас вариант по ${descriptionOfferLabel} ${typeLabel}${classLabel}. Обращайтесь по телефону ${PHONE}`
  }

  return {
    title: generateTitle(),
    description: generateDescription()
  }
}
