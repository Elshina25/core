<template>
  <div class="select-group">
    <Multiselect
      v-if="options.length"
      v-bind="settings"
      v-model="selected"
      :options="options"
    >
      <template #caret>
        <b-icon class="select-group__caret" name="arrow-down" />
      </template>
    </Multiselect>
  </div>
</template>

<script setup lang="ts">
// @ts-ignore
import Multiselect from 'vue-multiselect/dist/vue-multiselect.ssr'
import BIcon from '@/components/base/icon/BIcon.vue'

import { ISelectOption, ISelectValue } from '@/types/ui'
import { computed } from 'vue'

const emit = defineEmits<{
  (e: 'update:modelValue', event: ISelectValue): void
}>()

const props = withDefaults(
  defineProps<{
    options: ISelectOption[]
    modelValue: ISelectValue | null
    settings?: Record<string, unknown>
  }>(),
  {
    settings: () => ({
      searchable: false,
      multiselect: false,
      allowEmpty: false,
      selectLabel: '',
      deselectLabel: '',
      selectedLabel: '',
      openDirection: 'bottom',
      trackBy: 'id',
      label: 'name'
    })
  }
)

const selected = computed({
  get() {
    return props.options.find((el) => el.id === props.modelValue) ?? null
  },

  set(val) {
    emit('update:modelValue', val?.id ?? '')
  }
})
</script>

<style src="./select.css" scoped></style>
