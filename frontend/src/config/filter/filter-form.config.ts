import type { IFilterFieldsStore, IFilterQueryParams } from '@/types/filter'
import { defineAsyncComponent } from 'vue'

/** IComponentActivity
 * @property {store} - ключ IFilterFieldsStore
 * @property {field} - название поля в store, в котором хранятся активные значения
 * @property {values} - массив значений, при активности которых необходимо скрыть компонент
 */
export interface IComponentActivity {
  store: keyof IFilterFieldsStore
  field: string
  values: string[]
}

/** IFilterFormField
 * @property {id} - id
 * @property {component} - компонент
 * @property {isAsync} - обернуть компонент в suspence
 * @property {queryParamKeys} - массив ключей query параметров, с которыми связано это поле
 * @property {children} - массив дочерних элементов, которые нужно прокинуть в slot-ы
 * @property {label} - label поля
 * @property {className} - класс компонента
 * @property {params} - параметры компонента
 * @property {slotName} - наименование слота
 * @property {activeFor} - объект, с помощью которого определяется активность компонента
 * @property {hiddenFor} - объект, с помощью которого определяется активность компонента
 */
export interface IFilterFormField {
  id: string
  component: ReturnType<typeof defineAsyncComponent>
  isAsync: boolean
  queryParamKeys: (keyof IFilterQueryParams)[]
  children?: IFilterFormField[]
  className?: string
  params?: Record<string, unknown>
  hiddenFor?: IComponentActivity[]
  activeFor?: IComponentActivity[]
  slotName?: string
}

/**
 * Метод проверяет активность компонента
 * @param hiddenFor
 * @param activeFor
 * @param storeFilters
 * @returns {boolean}
 */
export const isFieldActive = (
  hiddenFor: IComponentActivity[] | undefined,
  activeFor: IComponentActivity[] | undefined,
  storeFilters: IFilterFieldsStore
) => {
  /**
   * Метод проверяет наличие совпадений значений
   * @param list
   * @returns {boolean}
   * @description Возвращает true если найдены совпадения, иначе false
   */
  const checkMatches = (
    list: IComponentActivity[],
    storeFilters: IFilterFieldsStore
  ) =>
    list.some(({ store, field, values }: IComponentActivity): boolean => {
      // Активные значения поля
      const selected = storeFilters[store][field]
      // Если поле с выбором множества значений
      if (Array.isArray(selected)) {
        // Проходим по каждому значению и проверяем его наличие в списке
        return selected.some((el: string): boolean => values.includes(el))
      }
      // Есть ли активное значение в списке
      return values.includes(selected)
    })

  const isHidden: boolean = hiddenFor
    ? checkMatches(hiddenFor, storeFilters)
    : false
  const isActive: boolean = activeFor
    ? checkMatches(activeFor, storeFilters)
    : true

  return !isHidden && isActive
}

// Конфигурация фильтров
export const FILTER_FORM_MAIN: IFilterFormField[] = [
  {
    id: 'city',
    component: defineAsyncComponent(
      () => import('@/components/filter/fields/FilterFieldCities.vue')
    ),
    className: 'filters__select filters__select--city',
    isAsync: true,
    queryParamKeys: ['city']
  },
  {
    id: 'spaces',
    component: defineAsyncComponent(
      () => import('@/components/filter/fields/FilterFieldSpaces.vue')
    ),
    className: 'filters__select filters__select--space',
    isAsync: true,
    queryParamKeys: ['space']
  },
  {
    id: 'square',
    component: defineAsyncComponent(
      () => import('@/components/filter/fields/FilterFieldSquare.vue')
    ),
    className: 'filters__range',
    params: {
      'start-class': 'flex-1 min-w-[100px] md:w-96 2xl:w-[140px]',
      'end-class': 'mobile:flex-1 w-72 md:w-68 2xl:w-80'
    },
    isAsync: false,
    queryParamKeys: ['square_start', 'square_end']
  },
  {
    id: 'classes',
    component: defineAsyncComponent(
      () => import('@/components/filter/fields/FilterFieldClasses.vue')
    ),
    className: 'filters__select filters__select--class',
    isAsync: true,
    queryParamKeys: ['building_class']
  },
  {
    id: 'search',
    component: defineAsyncComponent(
      () => import('@/components/filter/fields/search/FilterFieldSearch.vue')
    ),
    className: 'filters__search',
    isAsync: true,
    queryParamKeys: ['propertyAddressRus', 'name'],
    children: [
      {
        id: 'district-and-metro',
        component: defineAsyncComponent(
          () =>
            import('@/components/filter/fields/search/FilterSearchBtnGroup.vue')
        ),
        slotName: 'default',
        isAsync: true,
        queryParamKeys: ['district', 'metro'],
        hiddenFor: [
          {
            store: 'space',
            field: 'selected',
            values: ['1']
          }
        ],
        activeFor: [
          {
            store: 'city',
            field: 'selected',
            values: ['6']
          }
        ]
      }
    ]
  }
]

export const FILTER_FORM_COLLAPSE: IFilterFormField[] = [
  {
    id: 'direction',
    component: defineAsyncComponent(
      () => import('@/components/filter/fields/FilterFieldDirections.vue')
    ),
    isAsync: true,
    queryParamKeys: ['direction'],
    activeFor: [
      {
        store: 'space',
        field: 'selected',
        values: ['1']
      }
    ]
  },
  {
    id: 'price',
    component: defineAsyncComponent(
      () => import('@/components/filter/fields/FilterFieldPrice.vue')
    ),
    isAsync: false,
    queryParamKeys: ['price_start', 'price_end']
  },
  {
    id: 'floor',
    component: defineAsyncComponent(
      () => import('@/components/filter/fields/FilterFieldFloor.vue')
    ),
    isAsync: false,
    queryParamKeys: ['floor_start', 'floor_end'],
    hiddenFor: [
      {
        store: 'space',
        field: 'selected',
        values: ['1', '2', '4', '7']
      }
    ]
  },
  {
    id: 'distance',
    component: defineAsyncComponent(
      () => import('@/components/filter/fields/FilterFieldDistance.vue')
    ),
    isAsync: false,
    queryParamKeys: ['distance'],
    hiddenFor: [
      {
        store: 'space',
        field: 'selected',
        values: ['1', '7', '4']
      }
    ],
    activeFor: [
      {
        store: 'city',
        field: 'selected',
        values: ['6']
      }
    ]
  },
  {
    id: 'distanceFromCity',
    component: defineAsyncComponent(
      () => import('@/components/filter/fields/FilterFieldDistanceFromCity.vue')
    ),
    isAsync: false,
    queryParamKeys: ['distance_from_city'],
    activeFor: [
      {
        store: 'space',
        field: 'selected',
        values: ['1']
      }
    ]
  },
  {
    id: 'counties',
    component: defineAsyncComponent(
      () => import('@/components/filter/fields/FilterFieldCounties.vue')
    ),
    isAsync: true,
    queryParamKeys: ['county'],
    hiddenFor: [
      {
        store: 'space',
        field: 'selected',
        values: ['1', '4']
      }
    ]
  },
  {
    id: 'parking',
    component: defineAsyncComponent(
      () => import('@/components/filter/fields/FilterFieldParking.vue')
    ),
    isAsync: false,
    queryParamKeys: ['parking'],
    hiddenFor: [
      {
        store: 'space',
        field: 'selected',
        values: ['1', '2', '4']
      }
    ]
  }
]
