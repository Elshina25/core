<template>
  <div class="mt-80 md:mt-100 xl:mt-140">
    <swiper slides-per-view="auto" class="blog-slider">
      <template #container-start>
        <b-slider-head :title="title" />
      </template>
      <swiper-slide v-for="(item, idx) in items" :key="idx">
        <b-card-blog class="blog-slider__item" :blog="item" />
      </swiper-slide>
    </swiper>
  </div>
</template>

<script lang="ts" setup>
import { IBlog } from '@/types'
import { Swiper, SwiperSlide } from 'swiper/vue'
import BSliderHead from '@/components/base/slider/BSliderHead.vue'
import 'swiper/css'
import BCardBlog from '@/components/base/card/BCardBlog.vue'

withDefaults(
  defineProps<{
    title?: string
    items: IBlog[]
  }>(),
  {
    title: 'Больше статей из блога'
  }
)
</script>

<style src="@/components/base/slider/slider.css" scoped />
<style lang="postcss" scoped>
.blog-slider {
  &__item {
    @apply !w-[270px] h-[270px] md:!w-[340px] md:h-[340px] xl:!w-[645px] xl:h-[550px] md-only:mr-6;

    :deep(.card__labels) {
      @apply flex-col items-start;
    }
  }
}
</style>
