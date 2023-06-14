<template>
  <div class="factoid">
    <div class="factoid-title">{{ factoid.title }}</div>
    <h3 class="mb-0 lg:mb-4">{{ factoid.value }}</h3>
    <div class="t-p3">{{ factoid?.subtitle }}</div>
  </div>
</template>

<script setup lang="ts">
import { IObjectFactoid } from '@/types/objects'

defineProps<{
  factoid: IObjectFactoid
}>()
</script>

<style lang="postcss" scoped>
.factoid {
  @apply py-6 px-8 md:py-8 md:px-16 lg:py-10 lg:px-20;
  @apply flex flex-col items-center bg-white rounded;

  &-title {
    @apply t-date whitespace-nowrap mb-4 md:mb-6 lg:mb-12;
  }
}
</style>
