<template>
  <swiper v-if="items.length" slides-per-view="auto" class="my-80 2xl:my-150">
    <template #container-start>
      <b-slider-head class="2xl:!mb-80" :title="title" />
    </template>
    <swiper-slide v-for="(item, idx) in items" :key="idx">
      <b-card-building class="slider-item" :building="item" />
    </swiper-slide>
  </swiper>
</template>

<script lang="ts" setup>
import { Swiper, SwiperSlide } from 'swiper/vue'
import type { ObjectListResponse } from '@/api/object/list'
import BSliderHead from '@/components/base/slider/BSliderHead.vue'
import BCardBuilding from '@/components/base/card/BCardBuilding.vue'
import 'swiper/css'

withDefaults(
  defineProps<{
    title?: string
    items: ObjectListResponse[]
  }>(),
  {
    title: 'Похожие предложения'
  }
)
</script>
<style lang="postcss" scoped>
.slider-item {
  @apply mobile:!w-[272px] md:!w-[358px] xl:!w-[420px];
}
</style>
<style src="@/components/base/slider/slider.css" scoped />
