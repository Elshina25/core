<template>
  <section class="first-screen">
    <video :autoplay="true" preload="auto" playsinline muted>
      <source :src="getFullUrl('/upload/video/Final.mp4')" type="video/mp4" />
    </video>
  </section>
</template>

<script setup lang="ts">
import { getFullUrl } from '@/utils'
</script>

<style lang="postcss" scoped>
.first-screen {
  @apply h-[250px] md:h-[365px] xl:h-[684px]
    bg-cover bg-center
    mobile:bg-[url('/images/about/bg-mobile.png')]
    md:bg-[url('/images/about/bg-tablet.png')]
    xl:bg-[url('/images/about/bg.jpg')];

  @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 2dppx) {
    @apply xl:bg-[url('/images/about/bg@2x.jpg')];
  }

  video {
    @apply w-full h-full object-cover;
  }
}
</style>
