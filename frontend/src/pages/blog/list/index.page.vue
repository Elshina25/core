<template>
  <div class="container">
    <b-breadcrumb class="mb-30 2xl:mb-80">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug"
        >{{ $ROUTE.HOME.title }}
      </b-breadcrumb-item>
      <b-breadcrumb-item>{{ $ROUTE.BLOG.title }}</b-breadcrumb-item>
    </b-breadcrumb>

    <blog-first-screen />
    <div class="relative">
      <b-loader />
      <blog-card-list
        :items="list.items"
        :research="research"
        :service="service"
        :current-page="params.page"
      />
    </div>
    <b-pagination
      v-if="list.count"
      v-model="params.page"
      :total="list.count"
      :limit="LIMIT"
      scroll-to=".ttl"
    />
    <list-no-result
      v-else
      link-label="страницу блога"
      :link="$ROUTE.BLOG.slug"
      class="mt-30 md:mt-40 2xl:mt-60 mb-40 2xl:mb-80"
    />
  </div>
  <slider-services :items="serviceSlider" class="mt-80 2xl:mt-150" />
</template>

<script lang="ts" setup>
import { provide, reactive, watch } from 'vue'
import { LIMIT } from '@/config/blog.config'
import { getList } from './requests'
import { PageProps } from './types'
import { setQueryParams } from '@/utils'
import { KEY_PARAMS } from './injectionKeys'
import debounce from 'lodash-es/debounce'
import { useLoader } from '@/composables/useLoader'
import BLoader from '@/components/base/loader/BLoader.vue'
import BPagination from '@/components/base/pagination/BPagination.vue'
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import SliderServices from '@/components/slider/SliderServices.vue'
import ListNoResult from '@/components/table/ListNoResult.vue'
import BlogFirstScreen from '@/pages/blog/list/components/BlogFirstScreen.vue'
import BlogCardList from '@/pages/blog/list/components/BlogCardList.vue'

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
  }, 500)
})

provide(KEY_PARAMS, params)
</script>

<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>
