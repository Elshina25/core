<template>
  <swiper slides-per-view="auto" class="mb-80 md:mb-76 2xl:mb-150">
    <template #container-start>
      <b-slider-head :title="title" />
    </template>

    <swiper-slide v-for="(item, idx) in items" :key="idx">
      <img :src="getFullUrl(item)" class="slide" />
    </swiper-slide>
  </swiper>
</template>

<script setup lang="ts">
import BSliderHead from '@/components/base/slider/BSliderHead.vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'
import { getFullUrl } from '@/utils'

defineProps<{
  items: string[]
}>()

const title = 'Фото проекта'
</script>
<style lang="postcss" scoped>
.slide {
  @apply rounded h-[180px] md:h-[460px] 2xl:h-[572px];
}
</style>
<style src="@/components/base/slider/slider.css" scoped />
