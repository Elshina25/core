<template>
  <a
    class="card-project nested-link"
    :href="$ROUTE.PROJECTS.slug + `/${project.slug}`"
    :class="{ 'card-project--gradient': !backgroundImage }"
    :style="cardStyle"
  >
    <div class="badges">
      <b-badge v-if="project.section">{{ project.section }}</b-badge>
      <b-badge v-if="project.type">{{ project.type }}</b-badge>
    </div>

    <div class="info">
      <span>
        <span class="info-title link link-white">
          {{ project.name }}
        </span>
      </span>

      <div class="info-date">{{ project.date }}</div>
    </div>
  </a>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { IProject } from '@/types'
import { getFullUrl } from '@/utils'
import BBadge from '@/components/base/badge/BBadge.vue'

const props = defineProps<{
  project: IProject
}>()

const backgroundImage = computed(() => getFullUrl(props.project.image))

const cardStyle = computed(() => {
  return backgroundImage.value
    ? {
        'background-image': `linear-gradient(180deg,rgba(0,0,0,0),rgba(0,0,0,.7)), url(${backgroundImage.value})`
      }
    : ''
})
</script>

<style lang="postcss" scoped>
.card-project {
  @apply relative flex flex-col justify-between p-16 xl:p-30
    w-[270px] h-[270px] md:w-[340px] md:h-[340px]
    xl:w-[420px] xl:h-[420px] bg-cover bg-center
    rounded overflow-hidden cursor-pointer;

  &--gradient {
    @apply bg-[url('/images/research/card-bg-mobile.png')]
      md:bg-[url('/images/research/card-bg-tablet.png')]
      xl:bg-[url('/images/research/card-bg.png')];
  }

  &:not(.card-project--gradient) {
    background-image: linear-gradient(
      180deg,
      rgba(0, 0, 0, 0) 0%,
      rgba(0, 0, 0, 0.7) 100%
    );
  }

  .badges {
    @apply flex flex-wrap gap-6 xl:gap-10;

    :deep(.badge) {
      @apply flex-none;
    }
  }

  .info {
    @apply flex flex-col;

    &-title {
      @apply w-fit t-h4 xl:order-1 xl:mt-20;
    }

    &-date {
      @apply mt-16 xl:mt-0 opacity-60 text-white t-date
        whitespace-nowrap;
    }
  }

  &:hover {
    .info-lock {
      @apply text-white;
    }
  }
}
</style>
