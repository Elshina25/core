<template>
  <div class="container">
    <b-breadcrumb class="mb-30 md:mb-50 2xl:mb-70">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug">
        {{ $ROUTE.HOME.title }}
      </b-breadcrumb-item>
      <b-breadcrumb-item :to="$ROUTE.BLOG.slug">
        {{ $ROUTE.BLOG.title }}
      </b-breadcrumb-item>
      <b-breadcrumb-item>{{ blog.name }}</b-breadcrumb-item>
    </b-breadcrumb>

    <blog-head
      :date="blog.activeFrom"
      :share="SOCIALS_SHARE"
      :photo="getFullUrl(blog.detailPicture)"
      :title="blog.name"
    />

    <div class="blog-info">
      <b-article class="mobile:order-2" :content="blog.detailText" />
      <person-item
        :person="blog.author"
        variant="project"
        class="md:min-w-[232px] md:max-w-[232px] xl:min-w-[390px] xl:max-w-[390px] mobile:order-1"
      />
    </div>
    <form-object-consult
      v-if="false"
      title="Стратегический консалтинг"
      subtitle="Финальным результатом работы является детальная разработка концепции и финансово-экономическое обоснование проекта."
      crm-id=""
      class="mb-[70px] md:mb-60 xl:mb-100"
    />
    <form-subscribe-project :crm-id="blog.id" />
  </div>
  <slider-blog
    :items="blog.otherArticles"
    class="mt-80 md:mt-100 xl:mt-[137px]"
  />
  <slider-about-news
    :items="newsSlider.items"
    class="mt-80 md:mt-100 xl:mt-150"
  />
</template>

<script lang="ts" setup>
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import { PageProps } from './types'
import { getFullUrl } from '@/utils'
import { SOCIALS_SHARE } from '@/config/share.config'
import FormObjectConsult from '@/components/form/object/FormObjectConsult.vue'
import FormSubscribeProject from '@/components/form/project/FormSubscribeProject.vue'
import BArticle from '@/components/base/article/BArticle.vue'
import BlogHead from '@/pages/blog/detail/components/BlogHead.vue'
import PersonItem from '@/components/person/PersonItem.vue'
import SliderBlog from '@/components/slider/SliderBlog.vue'
import SliderAboutNews from '@/components/slider/SliderAboutNews.vue'

defineProps<PageProps>()
</script>

<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>

<style lang="postcss" scoped>
.blog-info {
  @apply mt-30 md:mt-40 2xl:mt-80 mb-80 md:mb-100 2xl:mb-150 flex mobile:flex-col gap-40 md:gap-20 2xl:gap-30;
}
</style>
