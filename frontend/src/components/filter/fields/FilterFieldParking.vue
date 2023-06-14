<template>
  <div class="collapse-filters__group !items-center !mt-20 2xl:!mt-38">
    <span class="filter-label">Парковка</span>
    <div class="flex flex-wrap">
      <b-filter-checkbox
        v-model="store.selected"
        label="Есть парковка"
        :value="1"
        :false-value="0"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { useFilterParkingStore } from '@/stores/filter/parking'
import BFilterCheckbox from '../base/checkbox/BFilterCheckbox.vue'

const store = useFilterParkingStore()
</script>

<style scoped></style>
