<template>
  <a :href="$ROUTE.NEWS.slug + `/${item.slug}`" class="card-news nested-link">
    <div class="title">
      <span class="link">{{ item.name }}</span>
    </div>
    <div class="date">{{ dateFormatter(item.date) }}</div>
  </a>
</template>

<script setup lang="ts">
import { INews } from '@/types'
import { dateFormatter } from '@/utils'

defineProps<{
  item: INews
}>()
</script>

<style src="./styles/card-news.css" scoped />
<style lang="postcss" scoped>
.card-news {
  .title {
    @apply t-h4 mb-32 overflow-hidden;
    -webkit-line-clamp: 6;
    display: -webkit-box;
    -webkit-box-orient: vertical;

    @screen lg {
      @apply t-h3 mb-18;
      -webkit-line-clamp: 5;
    }
  }

  .date {
    @apply mt-auto t-date text-black/60;
  }
}
</style>
