<template>
  <div v-if="swiper" class="swiper-navigator">
    <button
      :class="{ disabled: swiper.progress === 0 }"
      @click="swiper.slidePrev()"
    >
      <b-icon name="arrow-prev" />
    </button>

    <button
      :class="{ disabled: swiper.progress === 1 }"
      @click="swiper.slideNext()"
    >
      <b-icon name="arrow-next" />
    </button>
  </div>
</template>

<script setup lang="ts">
import { useSwiper } from 'swiper/vue'
import BIcon from '../icon/BIcon.vue'

const swiper = useSwiper()
</script>

<style lang="postcss" scoped>
.swiper-navigator {
  @apply flex gap-16 mobile:hidden;

  button {
    @apply text-black hover:text-black transition;

    &.disabled {
      @apply text-black/50 hover:text-black/50 cursor-default;
    }
  }
}
</style>
