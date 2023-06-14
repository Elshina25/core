<template>
  <template v-for="field in activeFields" :key="field.id">
    <suspense v-if="field.isAsync">
      <b-filter-form-item :field="field" />
    </suspense>

    <b-filter-form-item v-else :field="field" />
  </template>
</template>

<script lang="ts" setup>
import BFilterFormItem from './BFilterFormItem.vue'

import type { IFilterFormField } from '@/config/filter/filter-form.config'

import { isFieldActive } from '@/config/filter/filter-form.config'
import { useFilterStore } from '@/composables/filter/useFilterStore'
import { computed } from 'vue'

const props = defineProps<{
  fields: IFilterFormField[]
}>()

const { storeFilters } = useFilterStore()

const activeFields = computed<IFilterFormField[]>(() =>
  props.fields.filter((field) =>
    isFieldActive(field.hiddenFor, field.activeFor, storeFilters)
  )
)
</script>
