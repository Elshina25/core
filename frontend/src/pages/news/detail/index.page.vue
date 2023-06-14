<template>
  <div class="container">
    <b-breadcrumb class="mb-20 2xl:mb-50">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug">
        {{ $ROUTE.HOME.title }}
      </b-breadcrumb-item>
      <b-breadcrumb-item :to="$ROUTE.NEWS.slug">
        {{ $ROUTE.NEWS.title }}
      </b-breadcrumb-item>
      <b-breadcrumb-item>{{ news.name }}</b-breadcrumb-item>
    </b-breadcrumb>

    <news-head
      :share="SOCIALS_SHARE"
      :photo="getFullUrl(news.image)"
      :title="news.name"
      :date="news.date"
    />

    <div class="news__info">
      <b-article class="mobile:order-2" :content="news.detailText" />
      <news-authors class="news__authors" :authors="news.author" />
    </div>
  </div>
  <slider-news :items="newsSlider" />
</template>

<script lang="ts" setup>
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import { PageProps } from './types'
import { getFullUrl } from '@/utils'
import { SOCIALS_SHARE } from '@/config/share.config'
import BArticle from '@/components/base/article/BArticle.vue'
import NewsHead from '@/pages/news/detail/components/NewsHead.vue'
import NewsAuthors from '@/pages/news/detail/components/NewsAuthors.vue'
import SliderNews from '@/components/slider/SliderNews.vue'

defineProps<PageProps>()
</script>

<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>

<style lang="postcss" scoped>
.news {
  &__authors {
    @apply md:min-w-[232px] md:max-w-[232px] xl:min-w-[390px] xl:max-w-[390px] mobile:order-1;
  }

  &__info {
    @apply flex mobile:flex-col gap-40 md:gap-20 xl:gap-30 mt-30 md:mt-40 xl:mt-80;
  }
}
</style>
