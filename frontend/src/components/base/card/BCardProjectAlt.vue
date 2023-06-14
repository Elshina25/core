<template>
  <a class="card-project nested-link" :href="`/projects/${project.slug}`">
    <img :src="getFullUrl(project.image)" :title="project.name" alt="" />

    <div class="badges">
      <b-badge v-if="project.section">{{ project.section }}</b-badge>
      <b-badge v-if="project.type">{{ project.type }}</b-badge>
    </div>

    <div class="info">
      <span class="relative">
        <span class="info-title link link-white">
          {{ truncate(project.preview, 50) }}
        </span>
      </span>

      <div class="info-date">{{ dateFormatter(project.date) }}</div>
    </div>
  </a>
</template>

<script lang="ts" setup>
import { IProject } from '@/types'
import { dateFormatter, getFullUrl, truncate } from '@/utils'
import BBadge from '@/components/base/badge/BBadge.vue'

defineProps<{
  project: IProject
}>()
</script>
<style lang="postcss" scoped>
.card-project {
  @apply relative
    flex flex-col justify-between
    w-full h-[288px] p-16
    md:h-[358px] md:w-[calc(50%-10px)]
    xl:h-[550px] xl:w-[calc(50%-30px/2)] xl:p-30
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

  .badges {
    @apply flex flex-wrap gap-6 xl:gap-10;
  }

  .info {
    @apply flex flex-col md-only:mr-[20%];
    &-title {
      @apply t-h3 xl:t-h2  relative;
      word-break: break-word; /* TODO: попробовать установить глобально */
    }

    &-date {
      @apply mt-16 xl:mt-20 opacity-60 text-white t-date whitespace-nowrap;
    }
  }

  &:hover {
    .info-lock {
      @apply text-white;
    }
  }
}
</style>
