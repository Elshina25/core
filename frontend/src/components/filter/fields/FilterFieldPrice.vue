<template>
  <b-filter-range
    v-model:start="store.start"
    v-model:end="store.end"
    start-placeholder="От"
    end-placeholder="До"
    start-class="w-100 2xl:w-[140px]"
    end-class="w-92 2xl:w-[140px]"
    class="collapse-filters__group !mt-20 md:!mt-34"
  >
    <template #label>
      <span class="filter-label cursor-pointer">Стоимость</span>
    </template>
  </b-filter-range>
</template>

<script setup lang="ts">
import BFilterRange from '../base/range/BFilterRange.vue'
import { useFilterPriceStore } from '@/stores/filter/price'

const store = useFilterPriceStore()
</script>

<style scoped></style>
