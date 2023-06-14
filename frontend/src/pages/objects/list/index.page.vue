<template>
  <div class="container">
    <b-breadcrumb class="md:mb-30 2xl:mb-40">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug">{{
        $ROUTE.HOME.title
      }}</b-breadcrumb-item>
      <b-breadcrumb-item>{{ $ROUTE.ESTATE.title }}</b-breadcrumb-item>
    </b-breadcrumb>

    <client-only>
      <the-filter />
    </client-only>

    <section>
      <section :id="OBJECT_LIST_BLOCK_ID" class="objects">
        <template v-if="buildings.objects.length">
          <h1 class="mt-40 2xl:mt-60">Избранные предложения</h1>
        </template>
      </section>

      <object-list :list="buildings.objects" />
    </section>

    <seo-text />
  </div>

  <objects-tabs class="mt-40 md:mt-72 2xl:mt-80" />
</template>

<script lang="ts" setup>
import TheFilter from '@/components/filter/TheFilter.vue'
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import ObjectsTabs from '../list/components/ObjectsTabs.vue'
import ClientOnly from '@/components/base/ClientOnly.vue'
import ObjectList from './components/ObjectList.vue'
import SeoText from '@/pages/objects/components/SeoText.vue'

import { useSortStore } from '@/stores/sort'
import { useFilterLoaderStore } from '@/stores/filter/loader'
import { useBuildingsStore } from '@/stores/buildings'

import { useFilterUpdateStore } from '@/composables/filter/useFilterUpdateStore'
import { useObjectsList } from '@/composables/useObjectsList'
import { usePageContext } from '~/renderer/utils'
import { OBJECT_LIST_BLOCK_ID } from '@/config/objects.config'

import debounce from 'lodash-es/debounce'
import { onMounted, onUnmounted, watch, onServerPrefetch } from 'vue'
import { storeToRefs } from 'pinia'
import { getConvertedQueryParams } from '@/utils'

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

onUnmounted(() => document.documentElement.classList.remove('scroll-smooth'))

const buildingStore = useBuildingsStore()

// Список объектов
const { buildings } = storeToRefs(buildingStore)

// Стор сортировки
const sort = useSortStore()
// Задаем дефолтное значение сортировки
sort.setDefaultParams()

// Стор с лоадером для кнопок "Показать предложения"
const loaderStore = useFilterLoaderStore()

// Обновления меток на карте
watch(
  () => pageContext.urlParsed.search,
  () => {
    // Включаем лоадер
    loaderStore.start()
    debounce(async () => {
      // Обновляем список объектов
      await updateBuildingsList(
        pageContext,
        pageContext.urlParsed.searchOriginal,
        true
      )
      // Отключаем лоадер
      loaderStore.stop()
    }, 300)()
  }
)
</script>

<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>
