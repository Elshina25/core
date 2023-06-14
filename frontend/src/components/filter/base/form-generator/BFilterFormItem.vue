<template>
  <component
    :is="field.component"
    v-bind="field.params"
    :class="field.className"
  >
    <template
      v-for="children in activeChildren"
      :key="children.id"
      #[children.slotName]
    >
      <component
        :is="children.component"
        v-bind="field.params"
        :class="field.className"
      ></component>
    </template>
  </component>
</template>

<script lang="ts" setup>
import type { IFilterFormField } from '@/config/filter/filter-form.config'

import { isFieldActive } from '@/config/filter/filter-form.config'
import { useFilterStore } from '@/composables/filter/useFilterStore'
import { computed } from 'vue'

const props = defineProps<{
  field: IFilterFormField
}>()

const { storeFilters } = useFilterStore()

const activeChildren = computed<IFilterFormField[]>(
  () =>
    props.field.children?.filter((field) =>
      isFieldActive(field.hiddenFor, field.activeFor, storeFilters)
    ) ?? []
)
</script>
