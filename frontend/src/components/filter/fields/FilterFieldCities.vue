<template>
  <b-filter-select
    v-model="store.selected"
    :options="store.optionsWithDefault"
    :close-on-select="true"
    placeholder="Город"
    radio
  />
</template>

<script setup lang="ts">
import BFilterSelect from '../base/select/BFilterSelect.vue'
import { useFilterCityStore } from '@/stores/filter/cities'

const store = useFilterCityStore()
await store.fetchOptions()
</script>

<style scoped></style>
