<template>
  <div class="mb-60 md:mb-80 2xl:mb-150 quote">
    <div class="quote__line"></div>
    <div class="xl:w-3/4">
      <div class="t-p1 mb-20 md:mb-32 xl:mb-40">{{ quote }}</div>
      <h3 class="xl:t-h4 mb-8 xl:mb-10">{{ author }}</h3>
      <div class="t-p2">{{ jobTitle }}</div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  quote: string
  author: string
  jobTitle: string
}>()
</script>

<style scoped lang="postcss">
.quote {
  @apply flex gap-16 md:gap-24 xl:gap-42 items-stretch;

  &__line {
    @apply min-h-full bg-green w-4 md:w-6 xl:w-8 border-green shrink-0;
  }
}
</style>
