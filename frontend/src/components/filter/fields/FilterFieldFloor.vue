<template>
  <b-filter-range
    v-model:start="store.start"
    v-model:end="store.end"
    start-placeholder="C"
    end-placeholder="По"
    start-class="w-60"
    end-class="w-52 md:w-60"
    class="collapse-filters__group mobile:block"
  >
    <template #label>
      <span class="filter-label cursor-pointer">Этаж</span>
    </template>
  </b-filter-range>
</template>

<script setup lang="ts">
import BFilterRange from '../base/range/BFilterRange.vue'
import { useFilterFloorStore } from '@/stores/filter/floor'

const store = useFilterFloorStore()
</script>

<style scoped></style>
