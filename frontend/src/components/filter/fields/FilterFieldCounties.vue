<template>
  <div v-if="show" class="collapse-filters__group">
    <span class="filter-label">Округ</span>
    <div class="flex flex-wrap md-only:max-w-[600px]">
      <b-filter-checkbox
        v-for="item in countie.options"
        :key="item.id"
        v-model="countie.selected"
        :label="item.nameRu"
        :value="item.slug"
        variant="button"
        class="mr-8 md:mr-10 mb-8 md:mb-10"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import BFilterCheckbox from '../base/checkbox/BFilterCheckbox.vue'
import { useFilterCityStore } from '@/stores/filter/cities'
import { useFilterCountieStore } from '@/stores/filter/counties'
import { computed } from 'vue'
import { FILTER_CITY_DEFAULT_ID } from '@/config/filter/filter.config'

const city = useFilterCityStore()
const countie = useFilterCountieStore()

await countie.fetchOptions({ cityId: FILTER_CITY_DEFAULT_ID })

const show = computed(() => city.isMoscow && countie.options.length)
</script>

<style scoped></style>
