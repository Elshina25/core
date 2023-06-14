<template>
  <div class="container">
    <b-breadcrumb class="mb-30 2xl:mb-80">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug">{{
        $ROUTE.HOME.title
      }}</b-breadcrumb-item>
      <b-breadcrumb-item>{{ $ROUTE.SERVICES.title }}</b-breadcrumb-item>
    </b-breadcrumb>
    <h1 class="title">Услуги</h1>
  </div>

  <div class="mt-30 md:mt-40 2xl:mt-60">
    <service-categories />
  </div>

  <div class="container">
    <div class="relative">
      <b-loader />
      <service-group
        v-for="(item, idx) in servicesList.beforeSlider"
        :key="item.id"
        :group="item"
        :class="{
          'mt-30 md:mt-40 2xl:mt-60': idx === 0,
          'mt-40 md:mt-60 2xl:mt-80': idx !== 0
        }"
      >
        <client-only v-if="item.code === SERVICE_WITH_FILTER_CODE">
          <the-filter :show-quick="false" class="mt-10 2xl:mt-20 mb-100" />
        </client-only>
      </service-group>

      <service-slider
        v-if="projects.length"
        class="mt-40 md:mt-60 2xl:mt-80"
        :items="projects"
      />

      <service-group
        v-for="item in servicesList.afterSlider"
        :key="item.id"
        :group="item"
        class="mt-40 md:mt-60 2xl:mt-80"
      >
        <client-only v-if="item.code === SERVICE_WITH_FILTER_CODE">
          <the-filter :show-quick="false" class="mt-10 2xl:mt-20 mb-100" />
        </client-only>
      </service-group>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { provide, reactive, watch, computed } from 'vue'
import { KEY_PARAMS, KEY_CATEGORIES, KEY_TYPES } from './injectionKeys'
import { SERVICE_WITH_FILTER_CODE } from '@/config/service.config'
import { setQueryParams } from '@/utils'
import { getList } from './requests'
import type { PageProps } from './types'
import debounce from 'lodash-es/debounce'
import { useLoader } from '@/composables/useLoader'
import BLoader from '@/components/base/loader/BLoader.vue'
import TheFilter from '@/components/filter/TheFilter.vue'
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import ServiceGroup from './components/ServiceGroup.vue'
import ServiceCategories from './components/ServiceCategories.vue'
import ServiceSlider from './components/ServiceSlider.vue'
import ClientOnly from '@/components/base/ClientOnly.vue'

const props = defineProps<PageProps>()

const params = reactive(props.queryParams)
const list = reactive(props.list)

/**
 * Разбиваем список услуг на 2 массива для вывода слайдера между ними
 */
const servicesList = computed(() => {
  const filteredList = list.filter((el) => el?.items?.length)

  return {
    beforeSlider: filteredList.slice(0, 3),
    afterSlider: filteredList.slice(3)
  }
})

const loading = useLoader()

watch(params, () => {
  loading.start()

  debounce(async () => {
    Object.assign(list, await getList(params))
    setQueryParams(params)
    loading.stop()
  }, 500)()
})

provide(KEY_PARAMS, params)
provide(KEY_CATEGORIES, props.categories)
provide(KEY_TYPES, props.types)
</script>

<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>

<style lang="postcss" scoped>
.title {
  @apply text-30 md:text-60 xl:text-[70px] uppercase;
}
</style>
