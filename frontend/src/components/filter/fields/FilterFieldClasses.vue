<template>
  <b-filter-select
    v-model="store.selected"
    :options="store.convertedOptions"
    placeholder="Класс"
    allow-empty
    multiple
    :disabled="isDisabled"
  />
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useFilterClassStore } from '@/stores/filter/classes'
import { useFilterSpaceStore } from '@/stores/filter/spaces'
import BFilterSelect from '../base/select/BFilterSelect.vue'

const store = useFilterClassStore()
const space = useFilterSpaceStore()

await store.fetchOptions()

// TODO: нужно вынести в конфиг
const isDisabled = computed(() => ['2'].includes(space.selected))
</script>
