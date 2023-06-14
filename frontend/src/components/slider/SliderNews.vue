<template>
  <div class="mt-80 md:mt-96 xl:mt-150">
    <swiper slides-per-view="auto" class="news-slider">
      <template #container-start>
        <b-slider-head class="xl:mb-80" :title="title" />
      </template>
      <swiper-slide v-for="(item, idx) in items" :key="idx">
        <b-card-news-alt class="news-slider__item" :news="item" />
      </swiper-slide>
    </swiper>
  </div>
</template>

<script lang="ts" setup>
import { INews } from '@/types'
import { Swiper, SwiperSlide } from 'swiper/vue'
import BSliderHead from '@/components/base/slider/BSliderHead.vue'
import 'swiper/css'
import BCardNewsAlt from '@/components/base/card/BCardNewsAlt.vue'

withDefaults(
  defineProps<{
    title?: string
    items: INews[]
  }>(),
  {
    title: 'Другие новости'
  }
)
</script>

<style src="@/components/base/slider/slider.css" scoped />
<style lang="postcss" scoped>
.news-slider {
  @apply mb-20 md:mb-30 xl:mb-40;

  &__item {
    @apply !w-[270px] h-[270px] md:!w-[340px] md:h-[340px] xl:!w-[420px] xl:h-fit;

    :deep(img) {
      @screen xl {
        @apply !max-w-[420px] !max-h-[280px] mb-20;
      }
    }
    :deep(.info-title) {
      @apply xl:t-h3;
    }
  }
}
</style>
