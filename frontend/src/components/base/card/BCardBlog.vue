<template>
  <a class="card-blog nested-link" :href="`${$ROUTE.BLOG.slug}/${blog.code}`">
    <img :src="getFullUrl(blog.picture)" :title="blog.name" alt="" />

    <div class="info">
      <span class="relative">
        <span class="info-title link link-white">
          {{ truncate(blog.name, 50) }}
        </span>
      </span>
      <div class="info-description" v-html="blog.previewText"></div>
      <div class="info-date">
        {{ dateFormatter(blog.activeFrom) }}
      </div>
    </div>
  </a>
</template>

<script lang="ts" setup>
import { IBlog } from '@/types'
import { dateFormatter, getFullUrl, truncate } from '@/utils'

defineProps<{
  blog: IBlog
}>()
</script>

<style lang="postcss" scoped>
.card-blog {
  @apply relative
    flex flex-col justify-end
    w-full h-[288px] p-16
    md:h-[358px] md:w-[calc(50%-10px)]
    xl:h-[550px] xl:w-[calc(50%-15px)] xl:p-30
    bg-cover bg-center
    rounded overflow-hidden cursor-pointer;

  background: linear-gradient(
    180deg,
    rgba(0, 0, 0, 0) 0%,
    rgba(0, 0, 0, 0.7) 100%
  );

  img {
    @apply absolute inset-0 w-full h-full object-cover object-center -z-10;
  }

  .info {
    @apply flex flex-col md-only:mr-[20%];
    &-title {
      @apply t-h3 xl:t-h2  relative;
      word-break: break-word; /* TODO: попробовать установить глобально */
    }

    &-description {
      @apply hidden text-white t-p1 md:max-h-[90px] xl:max-h-[180px] text-ellipsis;
    }

    &-date {
      @apply mt-16 xl:mt-20 opacity-60 text-white t-date whitespace-nowrap;
    }
  }
}
</style>
