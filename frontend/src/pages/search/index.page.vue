<template>
  <div class="container relative">
    <b-breadcrumb class="mb-30 md:mb-50 2xl:mb-70">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug">
        {{ $ROUTE.HOME.title }}
      </b-breadcrumb-item>
      <b-breadcrumb-item>
        {{ $ROUTE.SEARCH.title }}
      </b-breadcrumb-item>
    </b-breadcrumb>

    <h1 class="mb-30 2xl:mb-80">Результаты поиска</h1>

    <div class="mb-80 2xl:mb-[150px]">
      <h2 class="objects-title mb-40">Объекты ({{ objects.count }})</h2>
      <div v-if="objects.items.length" class="objects-list">
        <b-card-building
          v-for="building in objects.items"
          :key="building.code"
          :building="building"
        />
      </div>
      <list-no-result v-else />

      <b-pagination
        v-model="params.pageObjects"
        :total="objects.count"
        :limit="LIMITS.object"
        scroll-to=".objects-title"
        @update:model-value="
          updateList(getObjects, params.pageObjects, objects)
        "
      />
    </div>

    <b-loader />

    <h2 class="info-title mb-40">Результаты ({{ info.count }})</h2>
    <template v-if="info.items.length">
      <search-info-item
        v-for="item in info.items"
        :key="item.code"
        :info="item"
        class="mb-30"
      />
    </template>
    <list-no-result v-else />

    <b-pagination
      v-model="params.pageInfo"
      :total="info.count"
      :limit="LIMITS.info"
      scroll-to=".info-title"
      @update:model-value="updateList(getInfo, params.pageInfo, info)"
    />
  </div>
</template>

<script lang="ts" setup>
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import BPagination from '@/components/base/pagination/BPagination.vue'
import BCardBuilding from '@/components/base/card/BCardBuilding.vue'
import SearchInfoItem from './components/SearchInfoItem.vue'
import BLoader from '@/components/base/loader/BLoader.vue'
import ListNoResult from '@/components/table/ListNoResult.vue'

import { LIMITS } from '@/config/search.config'
import type { PageProps } from './types'
import type {
  SearchListParams,
  ISearchInfoResponse,
  ISearchObjectsResponse
} from '@/api/search/list'
import { setQueryParams } from '@/utils'
import { useLoader } from '@/composables/useLoader'
import debounce from 'lodash-es/debounce'
import { getInfo, getObjects } from './requests'
import { reactive, watch } from 'vue'

const props = defineProps<PageProps>()

const objects = reactive<ISearchObjectsResponse>(props.objects)
const info = reactive<ISearchInfoResponse>(props.info)
const params = reactive(props.params)

watch(
  () => props.params,
  () => {
    Object.assign(objects, props.objects)
    Object.assign(info, props.info)
    Object.assign(params, props.params)
  }
)

const loading = useLoader()

const updateList = (
  callback: (params: SearchListParams) => void,
  page: number,
  collection: ISearchInfoResponse | ISearchObjectsResponse
) => {
  loading.start()

  debounce(async () => {
    Object.assign(collection, await callback({ query: params.query, page }))
    setQueryParams(params)

    loading.stop()
  }, 500)()
}
</script>

<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>

<style lang="postcss" scoped>
.objects-list {
  @apply grid grid-cols-1 gap-y-16
    md:grid-cols-2 md:gap-20
    2xl:grid-cols-3 2xl:gap-30
    mt-20 md:mt-40;
}
</style>
