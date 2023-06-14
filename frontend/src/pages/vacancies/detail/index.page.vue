<template>
  <div class="container">
    <b-breadcrumb class="mb-30 2xl:mb-80">
      <b-breadcrumb-item :to="$ROUTE.HOME.slug">{{
        $ROUTE.HOME.title
      }}</b-breadcrumb-item>
      <b-breadcrumb-item>{{ $ROUTE.VACANCY.title }}</b-breadcrumb-item>
    </b-breadcrumb>

    <div class="flex">
      <h1 class="ttl mb-30 md:mb-40 2xl:mb-80 uppercase">Вакансии</h1>
      <b-badge variant="green" class="vacancy-badge">Мы нанимаем</b-badge>
    </div>

    <vacancy-offer-list :items="offerList.items" />
    <vacancy-list :items="list.items" />
  </div>

  <slider-stories
    :list="storyList"
    :categories="storyCategories"
    :query-params="queryParams"
  />
</template>

<script lang="ts" setup>
import { PageProps } from './types'
import BBadge from '@/components/base/badge/BBadge.vue'
import BBreadcrumb from '@/components/base/breadcrumb/BBreadcrumb.vue'
import BBreadcrumbItem from '@/components/base/breadcrumb/BBreadcrumbItem.vue'
import SliderStories from '@/components/slider/SliderStories.vue'
import VacancyList from './components/VacancyList.vue'
import VacancyOfferList from './components/VacancyOfferList.vue'

defineProps<PageProps>()
</script>

<script lang="ts">
export { onBeforeRender } from './onBeforeRender'
</script>

<style scoped lang="postcss">
.vacancy-badge {
  @apply mobile:-mt-8 ml-8 md:ml-16 2xl:mt-14 h-fit md:t-p2;
}
</style>
