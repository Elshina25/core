<template>
  <h1 class="title mt-30 2xl:mt-60">{{ object.title }}</h1>

  <div class="share-group">
    <b-share :socials="share" />
    <b-favorite :id="object.id" />
  </div>
</template>

<script setup lang="ts">
import { injectStrict } from '@/utils'
import { KEY_OBJECT, KEY_SHARE } from '../injectionKeys'
import BShare from '@/components/base/share/BShare.vue'
import BFavorite from '@/components/base/favorite/BFavorite.vue'

const share = injectStrict(KEY_SHARE)
const object = injectStrict(KEY_OBJECT)
</script>

<style scoped>
.share-group {
  @apply w-full flex justify-between mobile:items-center;
  @apply mt-20 md:mt-30 2xl:mt-40 mb-12 md:mb-18 2xl:mb-24;
}
</style>
