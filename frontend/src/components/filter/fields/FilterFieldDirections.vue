<template>
  <div class="collapse-filters__group">
    <div class="filter-label">Направление</div>
    <b-filter-select
      v-model="store.selected"
      :options="store.options"
      placeholder="Направление"
      class="max-w-[250px]"
      radio
    />
  </div>
</template>

<script setup lang="ts">
import { useFilterDirectionsStore } from '@/stores/filter/directions'
import BFilterSelect from '../base/select/BFilterSelect.vue'

const store = useFilterDirectionsStore()

await store.fetchOptions()
</script>
