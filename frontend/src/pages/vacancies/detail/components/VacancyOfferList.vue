<template>
  <h1 class="mb-30 md:mb-40 2xl:mb-60">Что мы предлагаем</h1>
  <div class="offers">
    <div v-for="(item, idx) in items" :key="idx">
      <h2 class="text-green mb-8 2xl:mb-10">{{ item.name }}</h2>
      <div class="t-p2 md:t-p1">{{ item.previewText }}</div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { IOffer } from '@/types'

defineProps<{
  items: IOffer[]
}>()
</script>

<style lang="postcss" scoped>
.offers {
  @apply bg-white rounded p-16 md:p-30 2xl:p-40 grid min-w-full;

  @screen mobile {
    @apply flex flex-col gap-20;
  }

  @screen md {
    @apply gap-30;
    grid-template-columns: repeat(2, 323px);
  }

  @screen 2xl {
    @apply gap-x-30 gap-y-40;
    grid-template-columns: 380px 420px 380px;
  }
}
</style>
