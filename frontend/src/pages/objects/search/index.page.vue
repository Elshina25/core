<template>
  <div class="container">
    <b-breadcrumb class="md:mb-30 2xl:mb-40">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug">{{
        $ROUTE.HOME.title
      }}</b-breadcrumb-item>
      <b-breadcrumb-item :to="$ROUTE.ESTATE.slug + '/office'">{{
        $ROUTE.ESTATE.title
      }}</b-breadcrumb-item>
      <b-breadcrumb-item>{{ titleOffer }}</b-breadcrumb-item>
    </b-breadcrumb>

    <client-only>
      <the-filter />
    </client-only>
  </div>

  <search-map
    v-if="showMap"
    class="mt-16 md:mt-60"
    :coords="listCoords.objects"
  />

  <div v-else class="container">
    <section :id="OBJECT_LIST_BLOCK_ID" class="objects">
      <template v-if="buildings.objects.length">
        <h1 class="mt-40 2xl:mt-60">
          {{ titleOffer }} {{ titleSpace }} {{ titleAdditional }}
        </h1>
        <!-- TODO Vlad: подумать как скрыть не активный тип карточек -->
        <!-- TODO Vlad: временно закомментил -->
        <!-- @update:type="updateListType" -->
        <search-objects-list-head
          v-model:sort="sort.selected"
          v-model:type="listType"
          :sorting-options="SORT_OPTIONS"
          class="mt-32 md:mt-36 2xl:mt-72"
          @update:sort="updateList('sort', $event)"
        />
      </template>

      <list-no-result
        v-else
        class="mt-30 md:mt-40 2xl:mt-60"
        link-label="страницу поисковой выдачи"
        link="/estate/office"
      />

      <div class="relative">
        <b-loader />

        <div class="hidden mt-32" :class="{ 'xl:block': !isTypeTable }">
          <b-card-building-large
            v-for="building in objects.before"
            :key="building.id + 'large'"
            :building="building"
            class="mt-32"
          />
        </div>
        <div class="buildings" :class="{ 'buildings--hidden': !isTypeTable }">
          <b-card-building
            v-for="building in objects.before"
            :key="building.id"
            :building="building"
          />
        </div>
        <form-object-consult
          v-if="buildings.objects.length"
          title="Оптимизация рабочего пространства"
          description="Поможем определить и внедрить решения по организации рабочего пространства, способствующие достижению лучших результатов."
          class="my-16 md:my-60 2xl:my-80"
        />
        <div class="buildings" :class="{ 'buildings--hidden': !isTypeTable }">
          <b-card-building
            v-for="building in objects.after"
            :key="building.id"
            :building="building"
          />
        </div>
        <div class="hidden" :class="{ 'xl:block': !isTypeTable }">
          <b-card-building-large
            v-for="building in objects.after"
            :key="building.id + 'large'"
            :building="building"
            class="mt-32"
          />
        </div>
      </div>

      <b-pagination
        v-if="buildings.objects.length"
        v-model="page"
        :total="buildings.count"
        :limit="LIMIT"
        scroll-to=".objects"
        @update:model-value="updateList('page', $event)"
      />
    </section>
    <seo-text />
  </div>
  <objects-tabs class="mt-40 md:mt-72 2xl:mt-80" />
</template>

<script lang="ts" setup>
import BCardBuilding from '@/components/base/card/BCardBuilding.vue'
import BCardBuildingLarge from '@/components/base/card/BCardBuildingLarge.vue'
import TheFilter from '@/components/filter/TheFilter.vue'
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import SearchObjectsListHead from './components/SearchObjectsListHead.vue'
import SearchMap from './components/SearchMap.vue'
import FormObjectConsult from '@/components/form/object/FormObjectConsult.vue'
import BPagination from '@/components/base/pagination/BPagination.vue'
import ListNoResult from '@/components/table/ListNoResult.vue'
import BLoader from '@/components/base/loader/BLoader.vue'
import ObjectsTabs from '../list/components/ObjectsTabs.vue'
import ClientOnly from '@/components/base/ClientOnly.vue'
import SeoText from '@/pages/objects/components/SeoText.vue'

import type { PageProps } from './types'
import type { IFilterSpace } from '@/types/filter'

import { SORT_OPTIONS } from '@/config/sort.config'
import { LIMIT, OBJECT_LIST_BLOCK_ID } from '@/config/objects.config'
import { FILTER_SPACES_DECLINATIONS } from '@/config/filter/filter.config'

import { useSortStore } from '@/stores/sort'
import { useFilterLoaderStore } from '@/stores/filter/loader'
import { useBuildingsStore } from '@/stores/buildings'

import { getConvertedQueryParams, pluralize, setQueryParams } from '@/utils'
import { useLoader } from '@/composables/useLoader'
import { useFilterUpdateStore } from '@/composables/filter/useFilterUpdateStore'
import { useFilterStoreValues } from '@/composables/filter/useFilterStoreValues'
import { useObjectsCoords } from '@/composables/useObjectsCoords'
import { useObjectsList } from '@/composables/useObjectsList'
import { usePageContext } from '~/renderer/utils'

import debounce from 'lodash-es/debounce'
import {
  computed,
  onMounted,
  onUnmounted,
  ref,
  watch,
  onServerPrefetch
} from 'vue'
import { storeToRefs } from 'pinia'

const props = defineProps<PageProps>()
const pageContext = usePageContext()

const { setDefaultParams, fetchOptions } = useFilterUpdateStore()
const { updateBuildingsList } = useObjectsList()

const initStore = async () => {
  // Запрашиваем option-ы select-ов
  await fetchOptions()

  // Инициализируем store
  setDefaultParams({
    ...pageContext.routeParams,
    ...getConvertedQueryParams(pageContext.urlParsed.searchOriginal || '')
  })

  // Запрашиваем список объектов
  await updateBuildingsList(pageContext, pageContext.urlParsed.searchOriginal)
}

onServerPrefetch(initStore)

onMounted(async () => {
  document.documentElement.classList.add('scroll-smooth')

  await initStore()
})

// Список координат и метод для обновления списка координат
const { listCoords, updateListCoords } = useObjectsCoords()

// Метод для получения параметров фильтра
const { getFilterParams } = useFilterStoreValues()

onMounted(() => {
  if (!props.showMap) return
  // Если карта активна, запрашиваем координаты
  updateListCoords(getFilterParams())
})

onUnmounted(() => {
  document.documentElement.classList.remove('scroll-smooth')
})

const buildingStore = useBuildingsStore()
// Список объектов
const { buildings } = storeToRefs(buildingStore)
// Разделенный список объектов для вывода до и после формы
const objects = computed(() => ({
  before: buildings.value.objects?.slice(0, 6) || [],
  after: buildings.value.objects?.slice(6, 12) || []
}))

// Стор сортировки
const sort = useSortStore()
// Задаем дефолтное значение сортировки
sort.setDefaultParams()

// Тип списка (Таблицей, Списком)
const listType = ref<string>('list')
// Флаг табличного вида списка объектов
const isTypeTable = computed(() => listType.value === 'table')

// Текущая страница
const page = ref(+pageContext.urlParsed.search.page || 1)
// Стор с лоадером для кнопок "Показать предложения"
const loaderStore = useFilterLoaderStore()

// Обновления меток на карте
watch(
  () => pageContext.urlParsed.search,
  () => {
    // Если меняется параметр в фильтре, обновляем значения
    page.value = 1
    // Включаем лоадер
    loaderStore.start()
    debounce(async () => {
      if (props.showMap) {
        // При активности карты, обновляем список координат
        await updateListCoords(getFilterParams())
      } else {
        // Обновляем список объектов
        await updateBuildingsList(
          pageContext,
          pageContext.urlParsed.searchOriginal,
          true
        )
      }
      // Отключаем лоадер
      loaderStore.stop()
    }, 300)()
  }
)

const declinations = computed<string[]>(() => {
  const spaceType = pageContext.routeParams.space as IFilterSpace['type']
  const defaultDeclinations = ['объект', 'объекта', 'объектов']

  return FILTER_SPACES_DECLINATIONS?.[spaceType] ?? defaultDeclinations
})

const titleOffer = computed(() =>
  pageContext.routeParams.offer === 'rent' ? 'Аренда' : 'Продажа'
)

const titleSpace = computed(() => pluralize(5, declinations.value, false))
const titleAdditional = computed(() => props.heading ?? '')
const loading = useLoader()

const updateList = (param: string, val: string) => {
  // Запускаем лоадер
  loading.start()

  debounce(async () => {
    // Добавляем в гет параметры переданное значение
    setQueryParams({ [param]: val }, window.location.search)
    // Запрашиваем новый список объектов
    await updateBuildingsList(pageContext, window.location.search)
    // Останавливаем лоадер
    loading.stop()
  }, 500)()
}

// TODO Vlad: временно закомментил
// onMounted(() => {
//   listType.value = localStorage.getItem('listType') ?? 'list'
// })

// const updateListType = (val: string) => {
//   localStorage.setItem('listType', val)
// }
</script>

<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>

<style lang="postcss" scoped>
.buildings {
  @apply grid grid-cols-1 gap-y-16
    md:grid-cols-2 md:gap-20
    xl:grid-cols-3 xl:gap-30
    mt-20 md:mt-40;

  &--hidden {
    @apply xl:hidden;
  }
}
</style>
