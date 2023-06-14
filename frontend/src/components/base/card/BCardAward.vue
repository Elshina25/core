<template>
  <div class="card-award">
    <div class="year">{{ item.year || '' }}</div>
    <div class="title">{{ item.name }}</div>
    <div class="text" v-html="item.previewText"></div>
    <div class="tags">
      <b-badge
        v-for="(tag, idx) in item.tags"
        :key="idx"
        variant="outline-gray"
        >{{ tag }}</b-badge
      >
    </div>
  </div>
</template>

<script setup lang="ts">
import { IAward } from '@/types'
import BBadge from '../badge/BBadge.vue'

defineProps<{
  item: IAward
}>()
</script>

<style lang="postcss" scoped>
.card-award {
  @apply flex flex-col w-[253px] md:w-[320px] lg:w-[420px] h-full p-14 md:p-30 bg-white rounded;

  .year {
    @apply flex justify-center items-center
      bg-[url('/images/about/palm-branch.png')] bg-cover
      w-46 h-44
      md:w-[71px] md:h-[68px]
      lg:w-[92px] lg:h-[88px]
      text-[8px] md:text-[13px] lg:text-[17px] leading-150 font-semibold;
  }

  .title {
    @apply t-h2 md:t-h3
      mt-14 md:mt-20 lg:mt-30
      mb-4 md:mb-14;
  }

  .text {
    @apply t-p3 md:t-p2
      mb-12 md:mb-30;
  }

  .tags {
    @apply mt-auto flex gap-y-4 gap-x-8 md:gap-8 lg:gap-10 flex-wrap;
  }
}
</style>
