<template>
  <div class="banner">
    <div class="t-h1 md:t-h2 text-white">Обзоры рынка недвижимости</div>
    <span>
      <a :href="$ROUTE.RESEARCHES.slug">
        <b-button class="banner__btn" variant="white-blue"
          >Перейти к аналитике</b-button
        >
      </a>
    </span>
  </div>
</template>

<script lang="ts" setup>
import BButton from '@/components/base/button/BButton.vue'
</script>

<style scoped lang="postcss">
.banner {
  @apply p-16 md:p-26 lg:p-40 my-40 md:my-60 lg:my-80
  flex flex-col gap-60 lg:gap-40 rounded lg:max-w-[870px] lg:w-auto bg-cover;
  @apply bg-[url(/images/service/detail/banner/research-banner-mobile.png)]
  md:bg-[url(/images/service/detail/banner/research-banner-tablet.png)] lg:bg-[url(/images/service/detail/banner/research-banner.png)];

  &__btn {
    @apply w-[256px] md:w-[250px] lg:w-[300px];
  }
}
</style>
