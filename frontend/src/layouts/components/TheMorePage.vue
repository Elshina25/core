<template>
  <section class="container mt-80 md:mt-100 2xl:mt-150">
    <a v-if="content" :href="content.slug" class="nested-link">
      <div class="more-page">
        <h4 class="uppercase text-center t-p1 2xl:t-h4">Далее</h4>
        <div class="t-h1 link w-fit block mt-10 m-auto mobile:text-[30px]">
          {{ content.title }}
        </div>
        <div class="more-page__text t-p2" v-html="content.text"></div>
      </div>
    </a>
  </section>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { MORE_PAGE_CONTENT } from '@/config/morePage.config'
import { usePageContext } from '~/renderer/utils'

const pageContext = usePageContext()

// Получаем ключ след страницы
const code = computed(() => pageContext.pageProps?.showMorePage ?? '')

// Получаем содержимое след страницы
const content = computed(() => {
  if (!code.value) return null

  return MORE_PAGE_CONTENT[code.value]
})
</script>

<style lang="postcss" scoped>
.more-page {
  @apply bg-white rounded-[15px]
    px-[11px] pt-34 pb-14 md:px-84 md:py-60 2xl:py-40 2xl:px-240 overflow-hidden;

  &__text {
    @apply mt-20 max-h-[114px] md:max-h-[94px] 2xl:max-h-[116px] text-center 2xl:text-left;
    background: linear-gradient(180deg, #505050 0%, rgba(80, 80, 80, 0) 91.38%);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }
}
</style>
