<template>
  <b-article class="text-block">
    <div v-html="text"></div>
  </b-article>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { usePageContext } from '~/renderer/utils'
import BArticle from '@/components/base/article/BArticle.vue'

const pageContext = usePageContext()

const text = computed<string>(() => {
  return pageContext.pageProps?.seoText ?? ''
})
</script>

<style lang="postcss" scoped>
.text-block {
  @apply mt-60 md:mt-100;

  :deep(h1) {
    @apply max-w-full;
  }

  :deep(h2) {
    @apply xl:mb-30 mt-40 xl:mt-60;
  }
}
</style>
