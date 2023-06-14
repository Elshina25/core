<template>
  <div v-if="activeType" class="info">
    <b-badge v-if="activeType?.title" variant="grey" class="info__tag">{{
      activeType.title
    }}</b-badge>
    <h3 class="info__title">{{ info.name }}</h3>
    <p class="info__preview" v-html="info.preview"></p>
    <a class="link t-p2" :href="url">Подробнее</a>
  </div>
</template>

<script lang="ts" setup>
import type { ISearchInfoItem, InfoItemType } from '@/api/search/list'
import BBadge from '@/components/base/badge/BBadge.vue'
import { ROUTE, RouteData } from '@/routes'
import { computed } from 'vue'

const props = defineProps<{
  info: ISearchInfoItem
}>()

const types: Record<InfoItemType, RouteData> = {
  projects: { title: 'Проект', slug: ROUTE.PROJECTS.slug },
  news: { title: 'Новость', slug: ROUTE.NEWS.slug },
  research: { title: 'Исследование', slug: ROUTE.RESEARCHES.slug },
  article: { title: 'Блог', slug: ROUTE.BLOG.slug },
  object: { title: 'Объект', slug: ROUTE.ESTATE.slug },
  services: { title: 'Услуга', slug: ROUTE.SERVICES.slug }
}

const activeType = computed<RouteData>(() => types[props.info.type])
const url = computed<string>(
  () => `${activeType.value.slug}/${props.info.code}`
)
</script>

<style lang="postcss" scoped>
.info {
  @apply w-full p-16 md:p-26 2xl:py-30 2xl:px-40 bg-white rounded;

  &__preview,
  &__title {
    :deep(b) {
      @apply text-green;
    }
  }

  &__preview {
    @apply t-p2 mb-12 2xl:mb-20;
  }

  &__tag {
    @apply mb-12 2xl:mb-20;
  }

  &__title {
    @apply mb-12 2xl:mb-20;
  }
}
</style>
