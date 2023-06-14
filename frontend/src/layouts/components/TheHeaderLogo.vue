<template>
  <component
    :is="getTagByActiveLink('/')"
    href="/"
    class="header-logo"
    title="На главную"
  >
    <div class="logo"></div>
    <div class="slogan" v-html="SLOGAN.header"></div>
  </component>
</template>

<script setup lang="ts">
import { SLOGAN } from '@/config/main.config'
import { getTagByActiveLink } from '@/utils/show'
</script>

<style lang="postcss" scoped>
.header-logo {
  @apply flex mobile:flex-col md:items-center gap-4 md:gap-18;

  .logo {
    @apply w-[86px] h-[15px] md:w-120 md:h-20;
    @apply block bg-[url('/images/logo-grey.png')] bg-contain bg-no-repeat;
  }

  .slogan {
    @apply device:w-[86px] xl:w-[161px]
      t-caption mobile:text-[8px] xl:text-14;
  }
}
</style>
