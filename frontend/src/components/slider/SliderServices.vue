<template>
  <swiper slides-per-view="auto" class="mb-80 md:mb-76 2xl:mb-160">
    <template #container-start>
      <b-slider-head :title="title" />
    </template>

    <swiper-slide v-for="(item, idx) in items" :key="idx">
      <b-card-service :item="item" />
    </swiper-slide>
  </swiper>
</template>

<script setup lang="ts">
import BSliderHead from '@/components/base/slider/BSliderHead.vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'
import BCardService from '@/components/base/card/BCardService.vue'
import { IServiceGroup } from '@/types'

defineProps<{
  items: IServiceGroup[]
}>()

const title = 'Наши услуги'
</script>

<style src="@/components/base/slider/slider.css" scoped />
