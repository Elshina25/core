<template>
  <section>
    <b-badge
      v-for="(tag, idx) in tags.filter(Boolean)"
      :key="idx"
      variant="white"
      class="mr-10 mb-6"
    >
      {{ tag }}
    </b-badge>
    <h1 class="title">{{ title }}</h1>
    <div class="share-group">
      <b-share :socials="share" />
      <span class="t-date">{{ dateFormatter(date) }}</span>
    </div>
    <img v-if="photo" :src="photo" class="preview" />
  </section>
</template>

<script lang="ts" setup>
import BBadge from '@/components/base/badge/BBadge.vue'
import BShare from '@/components/base/share/BShare.vue'
import { dateFormatter } from '@/utils'

withDefaults(
  defineProps<{
    tags?: string[]
    title: string
    date: string
    photo: string
    share: string[]
  }>(),
  {
    tags: () => [],
    title: '',
    date: '',
    photo: ''
  }
)
</script>

<style lang="postcss" scoped>
.title {
  @apply mt-4 md:mt-6 xl:mt-10;
}

.share-group {
  @apply w-full flex justify-between mobile:items-center mt-28 md:mt-44 xl:mt-[74px];
}

.preview {
  @apply object-cover w-full h-[280px] md:h-[360px] xl:h-[600px] mt-16 md:mt-10 xl:mt-30 rounded;
}
</style>
