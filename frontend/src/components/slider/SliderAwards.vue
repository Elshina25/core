<template>
  <swiper slides-per-view="auto" class="mb-80 md:mb-76 2xl:mb-160">
    <template #container-start>
      <b-slider-head :title="title" />
    </template>
    <swiper-slide v-for="(item, idx) in items" :key="idx">
      <b-card-award :item="item" />
    </swiper-slide>
  </swiper>
</template>

<script setup lang="ts">
import BSliderHead from '@/components/base/slider/BSliderHead.vue'
import BCardAward from '@/components/base/card/BCardAward.vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { IAward } from '@/types'
import 'swiper/css'

withDefaults(
  defineProps<{
    title?: string
    items: IAward[]
  }>(),
  {
    title: 'Наши награды'
  }
)
</script>

<style src="@/components/base/slider/slider.css" scoped />
