<template>
  <swiper
    :slide-to-clicked-slide="true"
    slides-per-view="auto"
    :loop="true"
    :looped-slides="items.length"
    :centered-slides="true"
    class="mt-80 md:mt-100 2xl:mt-150"
    @slide-change="onSlideChange"
    @swiper="onSwiper"
  >
    <template #container-start>
      <div class="container mb-30 md:mb-40 2xl:mb-60 flex justify-between">
        <div class="t-h1">{{ title }}</div>
        <b-slider-navigator />
      </div>
    </template>
    <swiper-slide v-for="(item, idx) in items" :key="idx" :virtual-index="idx">
      <b-tag :active="isTagActive(idx)">{{ item.name }}</b-tag>
    </swiper-slide>
    <template #container-end>
      <slider-projects-detail ref="slide" :project="activeItem" />
    </template>
  </swiper>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Swiper as ISwiper } from 'swiper/types'
import 'swiper/css'
import BTag from '@/components/base/tags/BTag.vue'
import SliderProjectsDetail from '@/components/slider/SliderProjectsDetail.vue'
import BSliderNavigator from '@/components/base/slider/BSliderNavigator.vue'
import { useHomeProjectStore } from '@/stores/home/projects'
import { storeToRefs } from 'pinia'
import { useSwipe } from '@vueuse/core'
import { watch } from 'vue'

withDefaults(
  defineProps<{
    title?: string
  }>(),
  {
    title: 'Реализованные проекты'
  }
)

const store = useHomeProjectStore()
const { items } = storeToRefs(store)
await store.fetchList()

const slider = ref(null as ISwiper | null)
const slide = ref(null)
const { isSwiping, direction } = useSwipe(slide)

// Активная карточка проекта
const activeItem = ref(items.value[0])
const currentIndex = ref(0)

const isTagActive = (idx: number) => {
  return idx === currentIndex.value
}

const onSwiper = (swiper: ISwiper) => {
  slider.value = swiper
}

const onSlideChange = (swiper: ISwiper) => {
  currentIndex.value = swiper.realIndex ?? 0
  activeItem.value = items.value[currentIndex.value]
}

watch(isSwiping, () => {
  if (!isSwiping.value || !slider.value) return

  if (direction.value === 'RIGHT') {
    slider.value.slidePrev()
  }

  if (direction.value === 'LEFT') {
    slider.value.slideNext()
  }
})
</script>

<style lang="postcss" scoped>
:deep(.swiper-wrapper) {
  .swiper-slide {
    @apply relative h-auto w-auto cursor-pointer
    mr-12 md:mr-24 xl:mr-30 last:mr-0
    mb-30 xl:mb-40;
  }
}
</style>
