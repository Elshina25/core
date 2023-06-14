<template>
  <swiper slides-per-view="auto" class="mb-150">
    <template #container-start>
      <b-slider-head :title="title" />
    </template>
    <swiper-slide v-for="(item, idx) in items" :key="idx">
      <b-card-news :item="item" />
    </swiper-slide>
    <swiper-slide>
      <a :href="$ROUTE.NEWS.slug" class="card-news nested-link">
        <div class="t-h1 lg:t-h2">
          <span class="link">Читать <br />все новости</span>
        </div>
      </a>
    </swiper-slide>
  </swiper>
</template>

<script setup lang="ts">
import BSliderHead from '@/components/base/slider/BSliderHead.vue'
import BCardNews from '@/components/base/card/BCardNews.vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { INews } from '@/types'
import 'swiper/css'

withDefaults(
  defineProps<{
    title?: string
    items: INews[]
  }>(),
  {
    title: 'Наши новости'
  }
)
</script>

<style src="@/components/base/slider/slider.css" scoped />
<style src="@/components/base/card/styles/card-news.css" scoped />
