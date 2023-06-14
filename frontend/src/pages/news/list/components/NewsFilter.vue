<template>
  <b-tags
    v-model="params.year"
    :items="years"
    @update:model-value="params.page = 1"
  />
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { injectStrict } from '@/utils'
import { KEY_PARAMS, KEY_CATEGORIES } from '../injectionKeys'
import BTags from '@/components/base/tags/BTags.vue'

const params = injectStrict(KEY_PARAMS)
const categories = injectStrict(KEY_CATEGORIES)

const years = computed(() => categories.year)
</script>

<style scoped></style>
