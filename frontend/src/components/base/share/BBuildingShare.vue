<template>
  <div class="building-share">
    <b-share
      :socials="SOCIALS_SHARE"
      :url="BASE_URL + url"
      :show-label="false"
    />
    <b-favorite :id="id" variant="collapsed" />
  </div>
</template>

<script lang="ts" setup>
import BShare from '@/components/base/share/BShare.vue'
import BFavorite from '@/components/base/favorite/BFavorite.vue'
import { SOCIALS_SHARE } from '@/config/share.config'

defineProps<{
  id: string
  url: string
}>()

const BASE_URL = import.meta.env.VITE_BASE_URL
</script>

<style lang="postcss" scoped>
.building-share {
  @apply absolute top-16 right-16 md:top-20 md:right-20
  flex bg-auxiliary-6/50 rounded-[4px] gap-8 md:gap-10 mobile:py-4 px-6 md:px-4;

  :deep(.share-trigger) {
    @apply text-white;

    &:hover {
      svg {
        @apply fill-white;
      }
    }
  }
}
</style>
