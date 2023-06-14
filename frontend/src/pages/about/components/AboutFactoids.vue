<template>
  <section class="factoids">
    <slider-factoids
      :items="factoids"
      :gap="40"
      :sec-per-factoid="5"
      variant="about"
    />
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
.factoids {
  @apply mb-80 md:mb-100 2xl:mb-150;
}
</style>
