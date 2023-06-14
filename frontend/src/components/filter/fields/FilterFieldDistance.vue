<template>
  <div class="2xl:flex 2xl:items-center">
    <div class="collapse-filters__group">
      <span class="filter-label">{{ title }}</span>
      <div class="collapse-filters__checkbox-group">
        <b-filter-checkbox
          v-for="item in FILTER_METRO_DISTANCE_OPTIONS"
          :key="item.id"
          v-model="showMinutes"
          :label="item.label"
          :value="item.id"
          :allow-empty="false"
          variant="button"
          class="collapse-filters__checkbox"
          @update:model-value="clearDistance"
        />
      </div>
    </div>

    <b-filter-input
      v-show="!!showMinutes"
      v-model="metros.distance"
      class="collapse-filters__group"
    >
      <template #label>
        <span class="filter-label 2xl:!mr-20">Не более</span>
      </template>
      <template #after>
        <span class="filter-label ml-10 !mb-0 !mr-0">минут</span>
      </template>
    </b-filter-input>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import BFilterInput from '../base/input/BFilterInput.vue'
import BFilterCheckbox from '../base/checkbox/BFilterCheckbox.vue'
import { useFilterMetroStore } from '@/stores/filter/metros'
import { useFilterSpaceStore } from '@/stores/filter/spaces'
import { FILTER_METRO_DISTANCE_OPTIONS } from '@/config/filter/filter.config'

const metros = useFilterMetroStore()
const space = useFilterSpaceStore()

const showMinutes = ref(+!!metros.distance || 0)

const title = computed(() => {
  if (space.selected === '1') {
    return 'Расстояние от МКАД'
  }

  return 'Расстояние от метро'
})

const clearDistance = () => {
  if (showMinutes.value) return
  metros.distance = ''
}
</script>

<style lang="postcss" scoped>
:deep(.filter-element) {
  input {
    @apply h-50 md:h-52 xl:h-[62px] w-60 md:w-80;
  }
}
</style>
