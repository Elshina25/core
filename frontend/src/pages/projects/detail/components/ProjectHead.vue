<template>
  <section>
    <b-badge
      v-for="(tag, idx) in tags"
      :key="idx"
      variant="white"
      class="mr-10"
      >{{ tag }}</b-badge
    >
    <div class="flex align-middle flex-wrap">
      <h1 class="title">{{ title }}</h1>

      <div class="share-group">
        <b-share :socials="share" />
        <span v-if="false" class="t-date">{{ dateFormatter(date) }}</span>
      </div>
      <img v-if="photo" :src="photo" class="preview" />
    </div>
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
    date?: string
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
  @apply mt-10 md:mt-12 xl:mt-16;
}

.share-group {
  @apply w-full flex justify-between mobile:items-center mt-28 md:mt-44 xl:mt-[74px];
}

.preview {
  @apply object-cover w-full h-[280px] md:h-[360px] xl:h-[600px] mt-16 md:mt-20 xl:mt-30 rounded;
}
</style>
