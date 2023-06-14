<template>
  <b-filter-range
    v-model:start="store.start"
    v-model:end="store.end"
    start-placeholder="Площадь от"
    end-placeholder="до"
    caption="м²"
  />
</template>

<script setup lang="ts">
import BFilterRange from '../base/range/BFilterRange.vue'
import { useFilterSquareStore } from '@/stores/filter/square'

const store = useFilterSquareStore()
</script>

<style scoped></style>
