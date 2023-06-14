<template>
  <div class="container">
    <h2 class="t-h1">Предложения</h2>
    <client-only>
      <the-filter class="mt-40 2xl:mt-60" :show-quick="false" />
    </client-only>
    <div class="card-list">
      <b-card-building
        v-for="item in items"
        :key="item.id"
        :building="item"
        class="card-list__item"
      />
    </div>
  </div>
  <div class="pl-16 mt-20 md:hidden">
    <swiper class="slider" slides-per-view="auto">
      <swiper-slide v-for="item in items" :key="item.id" class="slider__slide">
        <b-card-building class="slider__item" :building="item" />
      </swiper-slide>
    </swiper>
  </div>
  <div class="container mt-20 md:mt-30">
    <the-filter-quick />
  </div>
</template>

<script lang="ts" setup>
import type { ObjectListResponse } from '@/api/object/list'
import TheFilter from '@/components/filter/TheFilter.vue'
import TheFilterQuick from '@/components/filter/TheFilterQuick.vue'
import BCardBuilding from '@/components/base/card/BCardBuilding.vue'
import ClientOnly from '@/components/base/ClientOnly.vue'
import { Swiper, SwiperSlide } from 'swiper/vue'

defineProps<{
  items: ObjectListResponse[]
}>()
</script>

<style lang="postcss" scoped>
.card-list {
  @apply hidden md:grid md:grid-cols-2 xl:grid-cols-3 
    md:gap-20 xl:gap-30;

  &__item {
    @apply mobile:h-full mobile:min-h-[395px] md-only:min-h-[450px];
  }
}

.slider {
  &__slide {
    @apply w-fit h-auto mr-16;
  }

  &__item {
    @apply w-[272px];

    :deep(.card-body__offers) {
      @apply block mt-34;
    }
  }
}
</style>
