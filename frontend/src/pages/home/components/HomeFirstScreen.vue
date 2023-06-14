<template>
  <section class="first-screen">
    <div class="container">
      <h1 class="ttl uppercase m-0">
        Лучшие решения <br />в&nbsp;коммерческой<br />
        и&nbsp;жилой недвижимости
      </h1>
    </div>
    <div>
      <slider-factoids
        variant="home"
        :items="factoids"
        :sec-per-factoid="4"
        :gap="80"
      />
    </div>
  </section>
</template>

<script setup lang="ts">
import SliderFactoids from '@/components/slider/SliderFactoids.vue'
import { IFactoid } from '@/types'

defineProps<{
  factoids: IFactoid[]
}>()
</script>

<style lang="postcss" scoped>
.first-screen {
  @apply h-[530px] md:h-[460px] xl:h-[790px] pb-26 xl:pb-40;
  @apply pt-[var(--h-header)] md:pt-[var(--h-header-md)] xl:pt-[var(--h-header-xl)];
  @apply mb-40 xl:mb-100;
  @apply flex flex-col justify-between;
  @apply bg-cover bg-center
    mobile:bg-[url('/images/home/bg-mobile.jpg')]
    md:bg-[url('/images/home/bg-tablet.jpg')]
    xl:bg-[url('/images/home/bg.jpg')];

  @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 2dppx) {
    @apply mobile:bg-[url('/images/home/bg-mobile@2x.jpg')]
      md:bg-[url('/images/home/bg-tablet@2x.jpg')]
      xl:bg-[url('/images/home/bg@2x.jpg')];
  }

  .ttl {
    @apply pt-44 md:pt-40 xl:pt-80;
    @apply text-white md:text-40 xl:text-60;
  }
}
</style>
