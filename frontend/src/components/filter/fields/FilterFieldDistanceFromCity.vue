<template>
  <b-filter-input v-model="distance.selected" class="collapse-filters__group">
    <template #label>
      <span class="filter-label">Расстояние от города</span>
    </template>
    <template #after>
      <span class="filter-label filter-label--caption">км.</span>
    </template>
  </b-filter-input>
</template>

<script lang="ts" setup>
import BFilterInput from '../base/input/BFilterInput.vue'
import { useFilterDistanceFromCity } from '@/stores/filter/distanceFromCity'

const distance = useFilterDistanceFromCity()
</script>

<style lang="postcss" scoped>
:deep(.filter-element) {
  input {
    @apply h-50 md:h-52 xl:h-[62px] w-60 md:w-80;
  }
}

.collapse-filters {
  .filter-label--caption {
    @apply mobile:mb-0 ml-10;
  }
}
</style>
