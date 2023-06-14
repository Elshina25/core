<template>
  <div class="mt-80 md:mt-100 2xl:mt-140">
    <swiper slides-per-view="auto" class="project-slider">
      <template #container-start>
        <b-slider-head :title="title" />
      </template>
      <swiper-slide v-for="(item, idx) in items" :key="idx">
        <b-card-project-alt class="project-slider__item" :project="item" />
      </swiper-slide>
    </swiper>

    <div class="container">
      <a class="link t-p2" :href="$ROUTE.PROJECTS.slug">Все проекты</a>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { IProject } from '@/types'
import { Swiper, SwiperSlide } from 'swiper/vue'
import BSliderHead from '@/components/base/slider/BSliderHead.vue'
import BCardProjectAlt from '@/components/base/card/BCardProjectAlt.vue'
import 'swiper/css'

withDefaults(
  defineProps<{
    title?: string
    items: IProject[]
  }>(),
  {
    title: 'Похожие проекты'
  }
)
</script>

<style src="@/components/base/slider/slider.css" scoped />
<style lang="postcss" scoped>
.project-slider {
  @apply mb-20 md:mb-30 xl:mb-60;

  &__item {
    @apply !w-[270px] h-[270px] md:!w-[340px] md:h-[340px] xl:!w-[645px] xl:h-[550px] md-only:mr-6;

    :deep(.card__labels) {
      @apply flex-col items-start;
    }
  }
}
</style>
