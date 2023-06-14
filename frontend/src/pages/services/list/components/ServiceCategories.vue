<template>
  <b-tags v-model="params.forWhom" :items="categoriesList" />
  <b-tags v-model="params.type" :items="typesList" />
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { injectStrict } from '@/utils'
import { KEY_PARAMS, KEY_CATEGORIES, KEY_TYPES } from '../injectionKeys'
import BTags from '@/components/base/tags/BTags.vue'

const params = injectStrict(KEY_PARAMS)
const categories = injectStrict(KEY_CATEGORIES)
const types = injectStrict(KEY_TYPES)

const categoriesList = computed(() => categories)
const typesList = computed(() => types)
</script>

<style scoped></style>
