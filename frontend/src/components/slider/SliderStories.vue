<template>
  <div class="mt-80 md:mt-96 2xl:mt-150">
    <swiper slides-per-view="auto" class="mb-20 md:mb-30 2xl:mb-40">
      <template #container-start>
        <b-slider-head class="2xl:mb-80" :title="title" />
        <b-tags
          v-model="params.topic"
          :items="categories"
          class="mb-30 2xl:mb-40"
        />
      </template>

      <swiper-slide v-for="(item, idx) in list.items" :key="idx">
        <div>
          <b-loader />
          <b-card-story variant="slider" :item="item" />
        </div>
      </swiper-slide>
    </swiper>

    <div class="container">
      <a class="link t-p2" :href="`${$ROUTE.VACANCY.slug}/employee-stories`"
        >Читать все истории</a
      >
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ITag } from '@/types'
import { Swiper, SwiperSlide } from 'swiper/vue'
import BSliderHead from '@/components/base/slider/BSliderHead.vue'
import 'swiper/css'
import BCardStory from '@/components/base/card/BCardStory.vue'
import BLoader from '@/components/base/loader/BLoader.vue'
import { reactive, watch } from 'vue'
import { useLoader } from '@/composables/useLoader'
import debounce from 'lodash-es/debounce'
import { getList } from '@/pages/vacancies/stories/requests'
import { StoryListParams, StoryListResponse } from '@/api/vacancy/story/list'
import BTags from '@/components/base/tags/BTags.vue'

const props = withDefaults(
  defineProps<{
    title?: string
    categories: ITag<string>[]
    list: StoryListResponse
    queryParams: StoryListParams
  }>(),
  {
    title: 'Истории наших сотрудников'
  }
)

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

<style scoped lang="postcss">
:deep(.swiper-slide) {
  @apply mobile:!mr-0 md-only:!mr-0 mobile:min-w-[288px] md-only:min-w-[733.8px];
}
</style>
<style src="@/components/base/slider/slider.css" scoped />
