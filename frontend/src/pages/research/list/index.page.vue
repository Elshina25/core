<template>
  <div class="container">
    <b-breadcrumb class="mb-30 2xl:mb-80">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug"
        >{{ $ROUTE.HOME.title }}
      </b-breadcrumb-item>
      <b-breadcrumb-item>{{ $ROUTE.RESEARCHES.title }}</b-breadcrumb-item>
    </b-breadcrumb>

    <researches-first-screen />
  </div>

  <researches-filter />

  <div class="container">
    <div class="relative">
      <b-loader />
      <template v-if="list.count">
        <researches-card-list :items="list.items" :current-page="params.page" />
        <b-pagination
          v-model="params.page"
          :total="list.count"
          :limit="LIMIT"
          scroll-to=".ttl"
        />

        <form-order-research
          id="personal-form"
          show-only-on="mobile"
          class="mt-80"
        />
      </template>
      <list-no-result
        v-else
        link-label="страницу исследований"
        link="/analytics"
        class="mt-30 md:mt-40 2xl:mt-60 mb-40 2xl:mb-80"
      />
    </div>
  </div>

  <template v-if="!list.count">
    <slider-research
      class="mt-0"
      title="Читайте на сайте"
      :items="alsoList.items"
    />

    <div class="container">
      <form-order-research
        id="personal-form"
        show-only-on="mobile"
        class="mt-80"
      />
    </div>
  </template>

  <slider-services :items="serviceSlider" class="mt-80 2xl:mt-150" />
</template>

<script lang="ts" setup>
import { provide, reactive, watch } from 'vue'
import { LIMIT } from '@/config/research.config'
import { getList } from './requests'
import { PageProps } from './types'
import { setQueryParams } from '@/utils'
import { KEY_PARAMS, KEY_CATEGORIES } from './injectionKeys'
import debounce from 'lodash-es/debounce'
import { useLoader } from '@/composables/useLoader'
import BLoader from '@/components/base/loader/BLoader.vue'
import ResearchesFirstScreen from './components/ResearchesFirstScreen.vue'
import ResearchesFilter from './components/ResearchesFilter.vue'
import ResearchesCardList from './components/ResearchesCardList.vue'
import BPagination from '@/components/base/pagination/BPagination.vue'
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import SliderServices from '@/components/slider/SliderServices.vue'
import FormOrderResearch from '@/components/form/research/FormOrderResearch.vue'
import SliderResearch from '@/components/slider/SliderResearch.vue'
import ListNoResult from '@/components/table/ListNoResult.vue'

const props = defineProps<PageProps>()

const params = reactive(props.queryParams)
const list = reactive(props.list)

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
</script>

<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>
