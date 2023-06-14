<template>
  <b-filter-select
    v-model="store.selected"
    :options="store.convertedOptions"
    :close-on-select="true"
    placeholder="Офис"
    radio
  />
</template>

<script setup lang="ts">
import { watch } from 'vue'
import { useFilterClassStore } from '@/stores/filter/classes'
import { useFilterSpaceStore } from '@/stores/filter/spaces'
import BFilterSelect from '../base/select/BFilterSelect.vue'

const store = useFilterSpaceStore()
const classes = useFilterClassStore()

await store.fetchOptions()

watch(
  () => store.selected,
  (id) => {
    if (id === '2') {
      classes.selected = []
    }
  }
)
</script>

<style scoped></style>
