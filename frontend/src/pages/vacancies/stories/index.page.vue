<template>
  <div class="container">
    <b-breadcrumb class="mb-30 2xl:mb-80">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug">{{
        $ROUTE.HOME.title
      }}</b-breadcrumb-item>
      <b-breadcrumb-item :to="$ROUTE.VACANCY.slug">{{
        $ROUTE.VACANCY.title
      }}</b-breadcrumb-item>
      <b-breadcrumb-item>Истории сотрудников</b-breadcrumb-item>
    </b-breadcrumb>

    <h1 class="ttl mb-30 md:mb-40 2xl:mb-60 uppercase">Истории сотрудников</h1>
  </div>

  <b-tags v-model="params.topic" :items="categories" class="mb-30 2xl:mb-40" />

  <div class="container">
    <div class="relative">
      <b-loader />
      <div v-if="list.items.length" class="story-list">
        <b-card-story
          v-for="(item, idx) in list.items"
          :key="idx"
          :item="item"
        />
      </div>
      <list-no-result
        v-else
        link-label="страницу вакансий"
        :link="$ROUTE.VACANCY.slug"
        class="mt-30 md:mt-40 2xl:mt-60 mb-40 2xl:mb-80"
      />
    </div>
  </div>
</template>

<script lang="ts" setup>
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import { reactive, watch } from 'vue'
import { PageProps } from './types'
import debounce from 'lodash-es/debounce'
import { getList } from './requests'
import { useLoader } from '@/composables/useLoader'
import BLoader from '@/components/base/loader/BLoader.vue'
import BTags from '@/components/base/tags/BTags.vue'
import BCardStory from '@/components/base/card/BCardStory.vue'
import ListNoResult from '@/components/table/ListNoResult.vue'

const props = defineProps<PageProps>()

const params = reactive(props.queryParams)
const list = reactive(props.list)

const loading = useLoader()

watch(params, () => {
  loading.start()

  debounce(async () => {
    Object.assign(list, await getList(params))
    loading.stop()
  }, 500)()
})
</script>

<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>

<style scoped lang="postcss">
.story-list {
  @apply flex flex-col gap-20 2xl:gap-60;
}
</style>
