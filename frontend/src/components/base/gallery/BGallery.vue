<template>
  <swiper
    class="gallery"
    slides-per-view="auto"
    :breakpoints="breakpoints"
    slide-to-clicked-slide
    @swiper="onSwiper"
  >
    <template #container-start>
      <div class="relative w-full">
        <a v-if="url" :href="url">
          <img class="gallery-active" :src="getFullUrl(activeItem)" />
        </a>
        <img v-else class="gallery-active" :src="getFullUrl(activeItem)" />

        <div v-if="pagination" class="pagination">
          <div
            v-for="i in items.length"
            :key="`pagination${i}`"
            class="pagination__item"
            :class="{ 'pagination__item--active': activeIndex === i - 1 }"
            @click="changeActiveIndex(i - 1)"
          ></div>
        </div>
      </div>
    </template>
    <swiper-slide
      v-for="(item, idx) in items"
      :key="idx"
      :class="{ active: idx === activeIndex }"
      @click="changeActiveIndex(idx)"
    >
      <img :src="getFullUrl(item)" />
    </swiper-slide>
  </swiper>
</template>

<script setup lang="ts">
import { computed, Ref, ref } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Swiper as ISwiper } from 'swiper/types'
import 'swiper/css'
import { getFullUrl } from '@/utils'

const props = withDefaults(
  defineProps<{
    items: string[]
    pagination?: boolean
    url: string
  }>(),
  {
    pagination: false,
    url: ''
  }
)

const breakpoints = {
  320: {
    direction: 'horizontal'
  },
  768: {
    direction: 'vertical'
  },
  1024: {
    direction: 'horizontal'
  }
}

const $swiper = ref() as Ref<ISwiper>
const onSwiper = (swiper: ISwiper) => {
  $swiper.value = swiper
}

const activeIndex = computed(() => $swiper.value?.activeIndex ?? 0)

//обработчик клика слайда - присваивает индекс активного слайда
const changeActiveIndex = (idx: number) => {
  $swiper.value.activeIndex = idx
}

// Активная картинка
const activeItem = computed(() => props.items[activeIndex.value])
</script>

<style lang="postcss" scoped>
.gallery {
  @apply w-full flex flex-col md-only:flex-row md-only:h-[338px];

  &-active {
    @apply min-w-full h-[160px] md:h-[338px] lg:h-[480px];
    @apply mobile:mb-[9px] md-only:mr-10 lg:mb-20 object-cover rounded bg-black/50;
  }
}

.pagination {
  @apply w-fit absolute left-0 right-0 m-auto bottom-30
    flex flex-wrap items-center;

  &__item {
    @apply w-8 h-8 mr-8 rounded-full bg-white opacity-50;

    &--active {
      @apply opacity-100;
    }
  }
}

:deep(.swiper-wrapper) {
  @apply max-h-[var(--size-img)] md:max-h-[var(--size-img-md)]
  md-only:max-w-[var(--size-img-md)] lg:max-h-[var(--size-img-lg)] md-only:ml-10;

  --size-img: 90px;
  --size-img-md: 100px;
  --size-img-lg: 80px;

  .swiper-slide {
    @apply cursor-pointer w-auto h-auto;
    @apply mobile:mr-[9px] md:mb-10 lg:mr-20 last:mr-0;

    img {
      @apply w-[var(--size-img)] h-[var(--size-img)] rounded;

      @screen md {
        @apply w-[var(--size-img-md)] h-[var(--size-img-md)];
      }

      @screen lg {
        @apply w-[var(--size-img-lg)] h-[var(--size-img-lg)];
      }
    }

    &.active {
      img {
        @apply border-2 border-green;
      }
    }

    &:hover {
      img {
        @apply border-2 border-green;
      }
    }
  }
}
</style>
