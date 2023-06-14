<template>
  <b-tags
    v-model="params.section"
    :items="sections"
    @update:model-value="params.page = 1"
  />
  <b-tags
    v-model="params.type"
    :items="types"
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

const sections = computed(() => categories.sections)
const types = computed(() => categories.types)
</script>

<style scoped></style>
