<template>
  <div class="service-group">
    <a :href="`${$ROUTE.SERVICES.slug}/${item.code}`" class="nested-link">
      <span class="t-h3 link link-green">{{ item.name }}</span>
    </a>
  </div>
</template>

<script lang="ts" setup>
import type { ITag } from '@/types'

defineProps<{
  item: ITag
}>()
</script>

<style lang="postcss" scoped>
.service-group {
  @apply max-w-[950px] p-30 pb-32 bg-[#E5EBED] rounded;
}
</style>
