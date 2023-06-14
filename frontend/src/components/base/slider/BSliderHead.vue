<template>
  <div class="swiper-head mobile:mb-30 md:mb-40 2xl:mb-60 container">
    <div class="t-h1">{{ title }}</div>
    <b-slider-navigator />
  </div>
</template>

<script setup lang="ts">
import BSliderNavigator from './BSliderNavigator.vue'

defineProps<{
  title: string
}>()
</script>

<style lang="postcss" scoped>
.swiper-head {
  @apply mobile:block;
  @apply md:flex md:justify-between md:items-center;

  .t-h1 {
    @apply text-left m-0;
  }
}
</style>
