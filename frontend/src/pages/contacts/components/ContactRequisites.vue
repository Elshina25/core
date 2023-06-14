<template>
  <h1 class="mb-20 md:mb-30 2xl:mb-60">Банковские реквизиты</h1>
  <div class="requisites">
    <div
      v-for="({ label, value }, idx) in CONTACTS_REQUISITES"
      :key="idx"
      class="requisites__row"
    >
      <div class="requisites__row-label">{{ label }}</div>
      <div class="requisites__row-value">{{ value }}</div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { CONTACTS_REQUISITES } from '@/config/contacts.config'
</script>

<style scoped lang="postcss">
.requisites {
  @apply p-16 md:p-26 lg:p-60 rounded bg-white mb-80 md:mb-100 lg:mb-150;
  @apply flex flex-col flex-nowrap md:flex-wrap overflow-hidden;
  @apply md:max-h-[172px] lg:max-h-[350px] gap-[13px] md:gap-8 lg:gap-30 md:gap-x-36 lg:gap-x-32;

  &__row {
    @apply flex mobile:gap-16 md:justify-between md-only:items-center;
    @apply md:min-w-[calc(50%-18px)] lg:min-w-[calc(50%-20px)];

    &-label {
      @apply t-caption lg:t-p3 text-black/60;
      @apply flex-none w-[90px] md:w-150 lg:mt-4;
    }

    &-value {
      @apply t-p3 md:t-caption lg:t-p2;
      @apply mobile:min-w-[150px] md:w-[156px] lg:w-[360px];
    }
  }
}
</style>
