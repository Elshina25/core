<template>
  <div class="mt-80 md:mt-96 2xl:mt-150">
    <swiper slides-per-view="auto" class="mb-20 md:mb-30 2xl:mb-40">
      <template #container-start>
        <b-slider-head :title="title" />
      </template>
      <swiper-slide v-for="(item, idx) in items" :key="idx">
        <b-card-what-new :item="item" />
      </swiper-slide>
    </swiper>
  </div>
</template>

<script lang="ts" setup>
import { IWhatNewCard } from '@/types'
import { Swiper, SwiperSlide } from 'swiper/vue'
import BSliderHead from '@/components/base/slider/BSliderHead.vue'
import 'swiper/css'
import BCardWhatNew from '@/components/base/card/BCardWhatNew.vue'
withDefaults(
  defineProps<{
    title?: string
    items: IWhatNewCard[]
  }>(),
  {
    title: 'Что нового'
  }
)
</script>

<style src="@/components/base/slider/slider.css" scoped />
