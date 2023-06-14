<template>
  <div class="container">
    <b-breadcrumb class="mb-30 2xl:mb-80">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug"
        >{{ $ROUTE.HOME.title }}
      </b-breadcrumb-item>
      <b-breadcrumb-item>{{ $ROUTE.NEWS.title }}</b-breadcrumb-item>
    </b-breadcrumb>

    <news-first-screen />
  </div>

  <news-filter />
  <div class="container">
    <template v-if="list.count">
      <div class="relative">
        <b-loader />
        <news-card-list
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
        link-label="страницу новостей"
        link="/news"
      />
    </template>
  </div>
</template>

<script lang="ts" setup>
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import NewsFirstScreen from '@/pages/news/list/components/NewsFirstScreen.vue'
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BLoader from '@/components/base/loader/BLoader.vue'
import { useLoader } from '@/composables/useLoader'
import { PageProps } from '@/pages/news/list/types'
import { provide, reactive, watch } from 'vue'
import debounce from 'lodash-es/debounce'
import { getList } from '@/pages/news/list/requests'
import { setQueryParams } from '@/utils'
import { KEY_PARAMS, KEY_CATEGORIES } from '@/pages/news/list/injectionKeys'
import ListNoResult from '@/components/table/ListNoResult.vue'
import BPagination from '@/components/base/pagination/BPagination.vue'
import { LIMIT } from '@/config/news.config'
import NewsCardList from '@/pages/news/list/components/NewsCardList.vue'
import NewsFilter from '@/pages/news/list/components/NewsFilter.vue'

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
