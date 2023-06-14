<template>
  <swiper slides-per-view="auto">
    <swiper-slide v-for="(item, idx) in items" :key="idx">
      <b-card-project :project="item" />
    </swiper-slide>
  </swiper>
</template>

<script lang="ts" setup>
import { IProject } from '@/types'
import { Swiper, SwiperSlide } from 'swiper/vue'
import BCardProject from '@/components/base/card/BCardProject.vue'
import 'swiper/css'

defineProps<{
  items: IProject[]
}>()
</script>

<style src="@/components/base/slider/slider.css" scoped />
