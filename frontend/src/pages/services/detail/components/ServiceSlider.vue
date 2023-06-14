<template>
  <div v-if="items.length">
    <swiper slides-per-view="auto">
      <template #container-start>
        <b-slider-head title="Реализованные проекты" />
      </template>
      <swiper-slide v-for="(item, idx) in items" :key="idx">
        <b-card-project class="project-card" :project="item" />
      </swiper-slide>
    </swiper>

    <div class="container mt-20 md:mt-30 2xl:mt-60">
      <a class="link" href="/projects/">Все проекты</a>
    </div>
  </div>
</template>

<script lang="ts" setup>
import BCardProject from '@/components/base/card/BCardProject.vue'
import BSliderHead from '@/components/base/slider/BSliderHead.vue'
import { IProject } from '@/types'
import { Swiper, SwiperSlide } from 'swiper/vue'
import 'swiper/css'

defineProps<{
  items: IProject[]
}>()
</script>

<style lang="postcss" scoped>
.project-card {
  @apply xl:w-[545px] xl:h-[450px] 2xl:w-[645px] 2xl:h-[550px];
}
</style>

<style src="@/components/base/slider/slider.css" scoped />
