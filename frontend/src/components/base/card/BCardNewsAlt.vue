<template>
  <a class="card-news nested-link" :href="`/news/${news.slug}`">
    <img :src="image" :title="news.name" alt="" />

    <div class="info">
      <span class="relative">
        <span class="info-title link device:link-white">
          {{ truncate(news.name, 70) }}
        </span>
      </span>

      <div class="info-description">{{ news.preview }}</div>
      <div class="info-date">{{ dateFormatter(news.date) }}</div>
    </div>
  </a>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { INews } from '@/types'
import { DEFAULT_OG_IMAGE } from '@/config/meta.config'
import { dateFormatter, getFullUrl, truncate } from '@/utils'

const props = defineProps<{
  news: INews
}>()

const image = computed(() => {
  if (!props.news.image) {
    return DEFAULT_OG_IMAGE
  }

  return getFullUrl(props.news.image)
})
</script>

<style lang="postcss" scoped>
.card-news {
  @apply relative
    flex flex-col justify-end
    w-full h-[288px] p-16
    md:h-[358px] md:w-[calc(50%-10px)]
    bg-cover bg-center
    rounded overflow-hidden cursor-pointer;

  background: linear-gradient(
    180deg,
    rgba(0, 0, 0, 0) 0%,
    rgba(0, 0, 0, 0.7) 100%
  );

  @screen xl {
    @apply bg-none h-auto w-[calc(50%-15px)] p-0 block;
  }

  img {
    @screen mobile {
      @apply absolute inset-0 w-full h-full object-cover object-center -z-10;
    }

    @screen md-only {
      @apply absolute inset-0 w-full h-full object-cover object-center -z-10;
    }

    @screen lg-only {
      @apply absolute inset-0 w-full h-full object-cover object-center -z-10;
    }

    @screen xl {
      @apply block inset-0 w-[645px] h-[390px] object-cover object-center mb-30 rounded;
    }
  }

  .info {
    @apply flex flex-col md-only:mr-[20%] lg-only:mr-[20%];

    &-title {
      @apply t-h3 2xl:t-h2 relative;
      word-break: break-word; /* TODO: попробовать установить глобально */
    }

    &-description {
      @apply hidden text-white t-p1 md:max-h-[90px] xl:max-h-[180px] text-ellipsis;
    }

    &-date {
      @apply mt-16 xl:mt-20 opacity-60 text-white xl:text-black t-date whitespace-nowrap;
    }
  }
}
</style>
