<template>
  <div
    class="mt-80 md:mt-100 2xl:mt-[163px] -mb-120 md:-mb-100 2xl:-mb-50 overflow-x-hidden"
  >
    <swiper
      :slide-to-clicked-slide="true"
      slides-per-view="auto"
      :loop="true"
      :looped-slides="items.length"
      :centered-slides="true"
      @slide-change="onSlideChange"
      @swiper="onSwiper"
    >
      <template #container-start>
        <div class="container t-h1 mb-30 md:mb-40 2xl:mb-60">{{ title }}</div>
      </template>
      <swiper-slide
        v-for="(item, idx) in items"
        :key="idx"
        :virtual-index="idx"
      >
        <img class="object-cover object-top" :src="getFullUrl(item.image)" />
      </swiper-slide>
      <template #container-end>
        <slider-team-detail ref="slide" :person="activeItem" />
      </template>
    </swiper>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { getFullUrl } from '@/utils'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Swiper as ISwiper } from 'swiper/types'
import SliderTeamDetail from './SliderTeamDetail.vue'
import 'swiper/css'
import { useTeamStore } from '@/stores/teams'
import { storeToRefs } from 'pinia'
import { useSwipe } from '@vueuse/core'
import { watch } from 'vue'

withDefaults(
  defineProps<{
    title?: string
  }>(),
  {
    title: 'Наша команда'
  }
)

const store = useTeamStore()
const { items } = storeToRefs(store)
await store.fetchList()

const slider = ref(null as ISwiper | null)
const slide = ref(null)
const { isSwiping, direction } = useSwipe(slide)

const onSwiper = (swiper: ISwiper) => {
  slider.value = swiper
}

// Активная карточка сотрудника
const activeItem = ref(items.value[0])

const onSlideChange = (swiper: ISwiper) => {
  activeItem.value = items.value[swiper.realIndex ?? 0]
}

watch(isSwiping, () => {
  if (!isSwiping.value || !slider.value) return

  if (direction.value === 'RIGHT') {
    slider.value.slidePrev()
  }

  if (direction.value === 'LEFT') {
    slider.value.slideNext()
  }
})
</script>

<style lang="postcss" scoped>
:deep(.swiper-wrapper) {
  .swiper-slide {
    @apply relative h-auto w-auto cursor-pointer
      mr-12 md:mr-24 xl:mr-30 last:mr-0
      mb-20 md:mb-30 xl:mb-80;

    --size-img: 68px;
    --size-img-md: 80px;
    --size-img-xl: 110px;

    img {
      @apply w-[var(--size-img)] h-[var(--size-img)]  rounded;

      @screen md {
        @apply w-[var(--size-img-md)] h-[var(--size-img-md)];
      }

      @screen xl {
        @apply w-[var(--size-img-xl)] h-[var(--size-img-xl)];
      }
    }

    &-active {
      &::after {
        @apply content-[''] block absolute top-4 left-4 bg-green rounded -z-10;
        @apply w-[var(--size-img)] h-[var(--size-img)];

        @screen md {
          @apply w-[var(--size-img-md)] h-[var(--size-img-md)];
        }

        @screen xl {
          @apply w-[var(--size-img-xl)] h-[var(--size-img-xl)];
        }
      }
    }
  }
}
</style>
