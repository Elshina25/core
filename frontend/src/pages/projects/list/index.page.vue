<template>
  <div class="container">
    <b-breadcrumb class="mb-30 2xl:mb-80">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug"
        >{{ $ROUTE.HOME.title }}
      </b-breadcrumb-item>
      <b-breadcrumb-item>{{ $ROUTE.PROJECTS.title }}</b-breadcrumb-item>
    </b-breadcrumb>
    <projects-first-screen />
  </div>

  <projects-filter />

  <div class="container">
    <template v-if="list.count">
      <div class="relative">
        <b-loader />
        <projects-card-list
          :service="service"
          :research="research"
          :items="list.items"
          :current-page="params.page"
        />
      </div>
      <b-pagination
        v-model="params.page"
        :total="list.count"
        :limit="LIMIT"
        scroll-to=".ttl"
      />
    </template>
    <template v-else>
      <list-no-result
        class="mt-30 md:mt-40 2xl:mt-60"
        link-label="страницу завершенных проектов"
        link="/projects"
      />
    </template>
  </div>
  <slider-services :items="serviceSlider" class="mt-80 md:mt-100 2xl:mt-150" />
</template>

<script lang="ts" setup>
import { PageProps } from '@/pages/projects/list/types'
import { provide, reactive, watch } from 'vue'
import debounce from 'lodash-es/debounce'
import { getList } from '@/pages/projects/list/requests'
import { setQueryParams } from '@/utils'
import { KEY_CATEGORIES, KEY_PARAMS } from '@/pages/projects/list/injectionKeys'
import { useLoader } from '@/composables/useLoader'
import BLoader from '@/components/base/loader/BLoader.vue'
import SliderServices from '@/components/slider/SliderServices.vue'
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import ProjectsFirstScreen from '@/pages/projects/list/components/ProjectsFirstScreen.vue'
import ProjectsFilter from '@/pages/projects/list/components/ProjectsFilter.vue'
import BPagination from '@/components/base/pagination/BPagination.vue'
import { LIMIT } from '@/config/project.config'
import ProjectsCardList from '@/pages/projects/list/components/ProjectsCardList.vue'
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

<style scoped></style>
